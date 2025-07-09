<?php

namespace App\Filament\Widgets;

use App\Models\Kantor;
use App\Models\Sufix;
use App\Models\Total;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalKantor = Kantor::count();
        $totalSufix = Sufix::count();
        $totalTotal = Total::count();

        return [
            Stat::make('Total Kantor', $totalKantor)
                ->description('Jumlah kantor yang terdaftar')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success'),
                
            Stat::make('Total Sufix', $totalSufix)
                ->description('Jumlah sufix yang terdaftar')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('info'),
            Stat::make('Total Total', $totalTotal)
                ->description('Jumlah total yang terdaftar')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),
        ];
    }
}
