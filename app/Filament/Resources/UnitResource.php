<?php

namespace App\Filament\Resources;

use App\Filament\Imports\UnitImporter;
use App\Filament\Resources\UnitResource\Pages;
use App\Models\RechargeGroup;
use App\Models\Unit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?int $navigationSort = -1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->required(),
                TextInput::make('serial')
                    ->required(),
                TextInput::make('batch')
                    ->required(),
                // TextInput::make('registered_at'),
                Select::make('recharge_group_id')
                    ->relationship(name: 'rechargeGroup', titleAttribute: 'name'),
                TextInput::make('warranty_months')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('product.name')
                    ->searchable(),
                TextColumn::make('serial')
                    ->searchable(),
                TextColumn::make('batch')
                    ->searchable(),
                TextColumn::make('rechargeGroup.name')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('registered_at')
                    ->searchable(),
                TextColumn::make('warranty_months')
                    ->searchable(),
            ])
            ->filters([
                Filter::make('empty_recharge_group')
                    ->query(fn (Builder $query): Builder => $query->whereNull('recharge_group_id')),
                SelectFilter::make('rechargeGroup')
                    ->relationship('rechargeGroup', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
                BulkAction::make('addRechargeGroup')
                    ->label('Change Recharge Group')
                    ->modalWidth('xl')
                    ->modalHeading('Add Recharge Group to Selected Units')
                    ->modalSubmitActionLabel('Add Recharge Groups')
                    ->form([
                        Select::make('rechargeGroupId')
                            ->label('Recharge Group')
                            ->options(RechargeGroup::pluck('name', 'id')->toArray())
                            ->required(),
                    ])
                    ->action(function ($records, $data) {
                        $selectedRecords = $records->pluck('id')->toArray();
                        $rechargeGroup = RechargeGroup::findOrFail($data['rechargeGroupId']);
                        Unit::whereIn('id', $selectedRecords)->each(function ($unit) use ($rechargeGroup) {
                            $unit->update([
                                'recharge_group_id' => $rechargeGroup->id,
                            ]);
                        });
                    })

                    ->deselectRecordsAfterCompletion(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(UnitImporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
