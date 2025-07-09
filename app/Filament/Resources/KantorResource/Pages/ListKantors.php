<?php

namespace App\Filament\Resources\KantorResource\Pages;

use App\Filament\Exports\KantorExporter;
use App\Filament\Imports\KantorImporter;
use App\Filament\Resources\KantorResource;
use App\Filament\Resources\KantorResource\Widgets\KantorOverview;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListKantor extends ListRecords
{
    protected static string $resource = KantorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->exporter(KantorExporter::class),
            ImportAction::make('import')
                    ->label('Import Kantor')
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->color('primary')
                    ->importer(KantorImporter::class)
                    ->successNotificationTitle('Kantor imported successfully'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KantorOverview::class,
        ];
    }
}
