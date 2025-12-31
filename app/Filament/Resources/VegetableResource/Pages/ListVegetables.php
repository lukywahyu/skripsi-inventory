<?php

namespace App\Filament\Resources\VegetableResource\Pages;

use App\Filament\Resources\VegetableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVegetables extends ListRecords
{
    protected static string $resource = VegetableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
