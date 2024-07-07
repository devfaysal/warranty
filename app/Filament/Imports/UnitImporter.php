<?php

namespace App\Filament\Imports;

use App\Models\Unit;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class UnitImporter extends Importer
{
    protected static ?string $model = Unit::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('product')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('serial')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('batch')
                ->rules(['max:255']),
            ImportColumn::make('warranty_months')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('rechargeGroup')
                ->relationship(),
        ];
    }

    public function resolveRecord(): ?Unit
    {
        return Unit::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'serial' => $this->data['serial'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your unit import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
