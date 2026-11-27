<?php

namespace App\Filament\Resources\Warehouses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WarehousesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Warehouse Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('location')
                    ->label('Location')
                    ->default('–—')
                    ->searchable(),

                TextColumn::make('stocks_count')
                    ->label('Stock Entries')
                    ->counts('stocks')
                    ->badge()
                    ->color('info'),

                // TextColumn::make('purchase_count')
                //     ->label('Purchases')
                //     ->counts('purchases')
                //     ->badge()
                //     ->color('warning'),

                TextColumn::make('sales_count')
                    ->label('Sales')
                    ->counts('sales')
                    ->badge()
                    ->color('success'),

                ToggleColumn::make('status')
                    ->label('status')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '2' => 'Inactive',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
