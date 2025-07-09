<?php

namespace App\Filament\Resources\TotalResource\Pages;

use App\Filament\Resources\TotalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTotals extends ListRecords
{
    protected static string $resource = TotalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('generate_positional_totals')
                ->label('Generate Positional Totals')
                ->icon('heroicon-o-calculator')
                ->action(function () {
                    $totals = \App\Models\Total::generatePositionalTotals();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Positional Totals Generated')
                        ->body("Generated " . count($totals) . " positional total records!")
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalDescription('This will clear existing positional totals and create new ones')
                ->color('success'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TotalResource\Widgets\TotalOverview::class,
        ];
    }
}
