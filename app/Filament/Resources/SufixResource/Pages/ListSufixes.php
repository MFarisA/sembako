<?php

namespace App\Filament\Resources\SufixResource\Pages;

use App\Filament\Resources\SufixResource;
use App\Filament\Resources\SufixResource\Widgets\SufixOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSufixes extends ListRecords
{
    protected static string $resource = SufixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('generate_totals')
                ->label('Generate All Totals')
                ->icon('heroicon-o-calculator')
                ->action(function () {
                    $sufixes = \App\Models\Sufix::with('subSufixes')->get();
                    
                    foreach ($sufixes as $sufix) {
                        $sufix->generateTotal();
                    }
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Totals Generated')
                        ->body("Generated totals for {$sufixes->count()} sufixes!")
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalDescription('This will regenerate all Total records by aggregating SubSufix data for each Sufix.')
                ->color('success'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SufixOverview::class,
        ];
    }
}
