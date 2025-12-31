<?php

namespace App\Filament\Resources\IncomingStockResource\Pages;

use App\Filament\Resources\IncomingStockResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIncomingStock extends CreateRecord
{
    protected static string $resource = IncomingStockResource::class;
}
