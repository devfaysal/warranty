<?php

namespace App\Livewire;

use App\Enums\ConnectionType;
use App\Enums\MobileOperator;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
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
        dd($this->form->getState());
    }

    public function render()
    {
        return view('livewire.register-product');
    }
}
