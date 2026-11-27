<?php

namespace App\Filament\Resources\Productions\Pages;

use App\Filament\Resources\Productions\ProductionsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductions extends ListRecords
{
    protected static string $resource = ProductionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New Production'),
        ];
    }
}
