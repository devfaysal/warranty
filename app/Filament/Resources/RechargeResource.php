<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RechargeResource\Pages;
use App\Models\Recharge;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RechargeResource extends Resource
{
    protected static ?string $model = Recharge::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('unit_id')
                    ->searchable(),
                TextColumn::make('unique_id')
                    ->searchable(),
                TextColumn::make('mobile_no')
                    ->searchable(),
                TextColumn::make('mobile_operator')
                    ->searchable(),
                TextColumn::make('connection_type')
                    ->searchable(),
                TextColumn::make('amount')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('status_code')
                    ->searchable(),
                TextColumn::make('status_description')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListRecharges::route('/'),
            'create' => Pages\CreateRecharge::route('/create'),
            'edit' => Pages\EditRecharge::route('/{record}/edit'),
        ];
    }
}
