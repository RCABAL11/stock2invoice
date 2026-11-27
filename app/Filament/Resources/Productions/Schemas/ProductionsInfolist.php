<?php

namespace App\Filament\Resources\Productions\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductionsInfoList
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Production Information')
                    ->schema([
                        TextEntry::make('reference_no')
                            ->label('Reference No.')
                            ->weight('bold'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Completed' => 'success',
                                'In Progress' => 'warning',
                                'Cancelled' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('warehouse.name')
                            ->label('Warehouse'),

                        TextEntry::make('production_date')
                            ->label('Production Date')
                            ->date('M d, Y'),

                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Production Items')
                    ->schema([
                        RepeatableEntry::make('entries')
                            ->label('Items')
                            ->table([
                                TableColumn::make('Product'),
                                TableColumn::make('Quantity'),
                            ])
                            ->schema([
                                TextEntry::make('product.name'),
                                TextEntry::make('quantity')
                                    ->numeric()
                                    ->badge(),
                            ])
                            ->contained(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }
}
