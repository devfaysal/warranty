<?php

namespace App\Livewire;

use App\Enums\ConnectionType;
use App\Enums\MobileOperator;
use App\Jobs\RechargeJob;
use App\Models\Customer;
use App\Models\Recharge;
use App\Models\Unit;
use Devfaysal\Muthofun\Facades\Muthofun;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class RegisterProduct extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('আপনার নাম')
                    ->required()
                    ->regex('%[a-zA-Z]%')
                    ->columnSpan(2)
                    ->validationMessages([
                        'regex' => ':attribute ইংরেজী অক্ষরে লিখুন',
                    ]),
                TextInput::make('serial')
                    ->label('সিরিয়াল নম্বর (কোথায় পাবেন)')
                    ->required()
                    ->numeric()
                    ->columnSpan(2),
                TextInput::make('mobile_number')
                    ->label('মোবাইল নম্বর')
                    ->unique()
                    ->required()
                    ->numeric()
                    ->columnSpan(2),
                Select::make('mobile_operator')
                    ->label('অপারেটর')
                    ->placeholder('অপারেটর নির্বাচন করুন')
                    ->options(MobileOperator::class)
                    ->required()
                    ->columnSpan(1),
                Select::make('connection_type')
                    ->label('সিমের ধরণ')
                    ->placeholder('সিমের ধরণ নির্বাচন করুন')
                    ->options(ConnectionType::class)
                    ->default(ConnectionType::Prepaid)
                    ->required()
                    ->columnSpan(1),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function create(): void
    {
        // dd($this->form->getState());
        //Check if the serial is exists in DB
        $serial = $this->data['serial'];
        $unit = Unit::query()
            ->where('serial', $serial)
            ->whereNull('registered_at')
            ->first();
        if (is_null($unit)) {
            Notification::make()
                ->title('Wrong serial number entered')
                ->danger()
                ->send();

            return;
        }

        //Update or Create Customer
        $customer = Customer::updateOrCreate(
            ['mobile_number' => $this->data['mobile_number']],
            [
                'name' => $this->data['name'],
                'mobile_operator' => $this->data['mobile_operator'],
                'connection_type' => $this->data['connection_type'],
            ]
        );

        $unit->update([
            'customer_id' => $customer->id,
            'registered_at' => now(),
        ]);

        $message = 'Dear '.$this->data['name'].', Your Product ('.$this->data['serial'].') Registered Successfully!';
        Muthofun::send($this->data['mobile_number'], $message);

        if ($unit->rechargeGroup) {
            $recharge = Recharge::create([
                'unit_id' => $unit->id,
                'mobile_no' => $customer->mobile_number,
                'mobile_operator' => $customer->mobile_operator,
                'connection_type' => $customer->connection_type,
                'amount' => $unit->rechargeGroup->amount,
            ]);
            RechargeJob::dispatch($recharge);
        }

        $this->data = [];
        Notification::make()
            ->title('Product Registered successfully')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.register-product');
    }
}
