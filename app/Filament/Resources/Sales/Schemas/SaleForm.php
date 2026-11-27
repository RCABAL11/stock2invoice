<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class SaleForm
{
    public static function updateTotals(Get $get, Set $set): void
    {
        $items = $get('../../items') ?? $get('items') ?? [];

        $total = collect($items)->sum(function ($item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            return $quantity * $unitPrice;
        });

        if ($get('../../items') !== null) {
            $set('../../total_amount', number_format($total, 2, '.', ''));
        } else {
            $set('total_amount', number_format($total, 2, '.', ''));
        }
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sales Information')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(Customer::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('warehouse_id')
                            ->label('Warehouse')
                            ->options(Warehouse::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('invoice_no')
                            ->label('Invoice Number')
                            ->placeholder('e.g. INV-0001')
                            ->unique(Sale::class, 'invoice_no', ignoreRecord: true)
                            ->required()
                            ->maxLength(255),

                        DatePicker::make('sale_date')
                            ->label('Sales Date')
                            ->required()
                            ->default(now()),

                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('₱')
                            ->default(0)
                            ->readOnly()
                            ->required(),

                        TextInput::make('paid_amount')
                            ->label('Paid Amount')
                            ->numeric()
                            ->prefix('₱')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $total = (float) $get('total_amount');
                                $paid = (float) $get('paid_amount');
                                $set('due_amount', number_format(max(0, $total - $paid), 2, '.', ''));
                            })
                            ->required(),

                        TextInput::make('due_amount')
                            ->label('Due Amount')
                            ->numeric()
                            ->prefix('₱')
                            ->default(0)
                            ->readOnly()
                            ->required(),


                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                            ])
                            ->default('pending')
                            ->required(),

                    ])
                    ->columns(2),

                Section::make('Sales Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->label('Products')
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            })
                            ->deleteAction(
                                fn($action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)),
                            )
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $sellingPrice = (float) (Product::find($state)?->selling_price ?? 0);
                                        $quantity = (float) ($get('quantity') ?: 1);

                                        $set('unit_price', number_format($sellingPrice, 2, '.', ''));
                                        $set('subtotal', number_format($quantity * $sellingPrice, 2, '.', ''));

                                        self::updateTotals($get, $set);
                                    })
                                    ->columnSpan(2),

                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->required()
                                    ->default(1)
                                    ->numeric()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $quantity = (float) $get('quantity');
                                        $unitPrice = (float) $get('unit_price');
                                        $set('subtotal', number_format($quantity * $unitPrice, 2, '.', ''));

                                        self::updateTotals($get, $set);
                                    }),

                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->prefix('₱')
                                    ->default(0)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $quantity = (float) $get('quantity');
                                        $unitPrice = (float) $get('unit_price');
                                        $set('subtotal', number_format($quantity * $unitPrice, 2, '.', ''));

                                        self::updateTotals($get, $set);
                                    }),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('₱')
                                    ->default(0)
                                    ->readOnly(),
                            ])
                            ->columns(5)
                            ->defaultItems(1)
                            ->addActionLabel('Add Product')
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }
}
