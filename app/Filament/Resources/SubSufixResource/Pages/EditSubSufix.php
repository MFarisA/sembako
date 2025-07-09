<?php

namespace App\Filament\Resources\SubSufixResource\Pages;

use App\Filament\Resources\SubSufixResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubSufix extends EditRecord
{
    protected static string $resource = SubSufixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
