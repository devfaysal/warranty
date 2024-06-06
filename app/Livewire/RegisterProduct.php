<?php

namespace App\Livewire;

use App\Enums\ConnectionType;
use App\Enums\MobileOperator;
use App\Models\Customer;
use App\Models\Unit;
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
                    ->label('Your Name')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('serial')
                    ->label('Serial Number')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('mobile_number')
                    ->unique()
                    ->required()
                    ->columnSpan(2),
                Select::make('mobile_operator')
                    ->options(MobileOperator::class)
                    ->required()
                    ->columnSpan(1),
                Select::make('connection_type')
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
