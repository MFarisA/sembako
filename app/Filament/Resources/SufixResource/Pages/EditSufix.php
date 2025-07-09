<?php

namespace App\Filament\Resources\SufixResource\Pages;

use App\Filament\Resources\SufixResource;
use App\Filament\Resources\SufixResource\Widgets\SufixOverview;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSufix extends EditRecord
{
    protected static string $resource = SufixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         SufixOverview::class,
    //     ];   
    // }
}
