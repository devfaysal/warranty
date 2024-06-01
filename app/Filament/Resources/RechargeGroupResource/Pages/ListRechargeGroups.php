<?php

namespace App\Filament\Resources\RechargeGroupResource\Pages;

use App\Filament\Resources\RechargeGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRechargeGroups extends ListRecords
{
    protected static string $resource = RechargeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
