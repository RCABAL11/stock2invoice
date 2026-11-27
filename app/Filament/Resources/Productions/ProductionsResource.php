<?php

namespace App\Filament\Resources\Productions;

use App\Filament\Resources\Productions\Pages\CreateProductions;
use App\Filament\Resources\Productions\Pages\EditProductions;
use App\Filament\Resources\Productions\Pages\ListProductions;
use App\Filament\Resources\Productions\Pages\ViewProductions;
use App\Filament\Resources\Productions\Schemas\ProductionsForm;
use App\Filament\Resources\Productions\Schemas\ProductionsInfoList;
use App\Filament\Resources\Productions\Tables\ProductionsTable;
use App\Models\Production;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProductionsResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|UnitEnum|null $navigationGroup = 'Transactions';

    protected static ?string $navigationLabel = 'Productions';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'reference_no';

    public static function form(Schema $schema): Schema
    {
        return ProductionsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductionsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductionsInfoList::configure($schema);
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
            'index' => ListProductions::route('/'),
            'create' => CreateProductions::route('/create'),
            'view' => ViewProductions::route('/{record}'),
            'edit' => EditProductions::route('/{record}/edit'),
        ];
    }
}
