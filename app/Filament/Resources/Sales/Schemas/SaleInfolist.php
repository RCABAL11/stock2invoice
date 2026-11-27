<?php

namespace App\Filament\Resources\Sales\Schemas;

use Dom\Text;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sales information')
                    ->schema([
                        TextEntry::make('invoice_no')
                            ->label('Invoice Number')
                            ->weight('bold'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'warning',
                                'completed' => 'success',
                            }),

                        TextEntry::make('customer.name')
                            ->label('Customer'),

                        TextEntry::make('warehouse.name')
                            ->label('Warehouse'),

                        TextEntry::make('sale_date')
                            ->label('Sales Date')
                            ->date('d M Y'),

                        TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('PHP'),

                        TextEntry::make('paid_amount')
                            ->label('Paid Amount')
                            ->money('PHP'),

                        TextEntry::make('due_amount')
                            ->label('Due Amount')
                            ->money('PHP'),
                    ])
                    ->columns(3),

                Section::make('Sales Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Product')
                                    ->weight('bold')
                                    ->columnSpan(2),

                                TextEntry::make('quantity')
                                    ->label('Quantity'),

                                TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->money('PHP'),

                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('PHP')
                                    ->weight('bold'),
                            ])
                            ->columns(5)
                            ->contained(false),
                    ])
                    ->columns(1),
            ]);
    }
}
