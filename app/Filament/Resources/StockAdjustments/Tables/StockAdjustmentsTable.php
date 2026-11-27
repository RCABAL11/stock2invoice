<?php

namespace App\Filament\Resources\StockAdjustments\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use PhpParser\Node\Stmt\Label;

class StockAdjustmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'add' => 'Added',
                        'subtract' => 'Subtracted',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'add' => 'success',
                        'subtract' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'add' => 'heroicon-o-plus-circle',
                        'subtract' => 'heroicon-o-minus-circle',
                    }),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                TextColumn::make('reason')
                    ->label('Reason')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('adjustedBy.name')
                    ->label('Adjusted By')
                    ->default('—'),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('product_id')
                    ->label('Product')
                    ->options(Product::pluck('name', 'id'))
                    ->placeholder('All Products'),

                SelectFilter::make('warehouse_id')
                    ->label('Warehouse')
                    ->options(Product::pluck('name', 'id'))
                    ->placeholder('All Warehouses'),

                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'add' => 'Add Stock',
                        'subtract' => 'Subtract Stock',
                    ])
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
