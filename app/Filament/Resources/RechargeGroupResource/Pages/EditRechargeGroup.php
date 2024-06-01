<?php

namespace App\Filament\Resources\RechargeGroupResource\Pages;

use App\Filament\Resources\RechargeGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRechargeGroup extends EditRecord
{
    protected static string $resource = RechargeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
