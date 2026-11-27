<?php

namespace App\Filament\Resources\Productions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductionsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Production Information')
                    ->schema([
                        TextInput::make('reference_no')
                            ->label('Reference No.')
                            ->placeholder('e.g. PROD-0001')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('warehouse_id')
                            ->label('Warehouse')
                            ->relationship('warehouse', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('production_date')
                            ->label('Production Date')
                            ->default(now())
                            ->native(false)
                            ->displayFormat('M d, Y')
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Draft' => 'Draft',
                                'In Progress' => 'In Progress',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->default('Draft')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Enter additional production details...')
                            ->rows(4)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Production Items')
                    ->schema([
                        Repeater::make('entries')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(9),

                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required()
                                    ->columnSpan(3),
                            ])
                            ->columns(12)
                            ->addActionLabel('Add Product')
                            ->reorderable(false)
                            ->collapsible()
                            ->required()
                            ->minItems(1)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
