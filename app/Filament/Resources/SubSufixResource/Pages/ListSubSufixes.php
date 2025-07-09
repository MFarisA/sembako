<?php

namespace App\Filament\Resources\SubSufixResource\Pages;

use App\Filament\Resources\SubSufixResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubSufixes extends ListRecords
{
    protected static string $resource = SubSufixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
