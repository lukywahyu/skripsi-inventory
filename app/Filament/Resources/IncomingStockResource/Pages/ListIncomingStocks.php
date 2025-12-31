<?php

namespace App\Filament\Resources\IncomingStockResource\Pages;

use App\Filament\Resources\IncomingStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncomingStocks extends ListRecords
{
    protected static string $resource = IncomingStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
