<?php

namespace App\Filament\Resources\TotalResource\Widgets;

use App\Models\Total;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalJmlAlokasiBNbA = Total::sum('jumlah_alokasi_bnba');
        $totalJmlAlokasiBiaya = Total::sum('jumlah_alokasi_biaya');
        $totalRealisasi = Total::sum('jumlah_realisasi');
        $totalRealisasiBiaya = Total::sum('jumlah_realisasi_biaya');
        $totalProsentase = Total::sum('persentase');
        
        return [
            Stat::make('Total Alokasi BNbA', 'Rp ' . number_format($totalJmlAlokasiBNbA))
                ->description('Total alokasi BNbA dari semua total')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
            Stat::make('Total Alokasi Biaya', 'Rp ' . number_format($totalJmlAlokasiBiaya))
                ->description('Total alokasi biaya dari semua total')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
            Stat::make('Total Realisasi', 'Rp ' . number_format($totalRealisasi))
                ->description('Total realisasi dari semua total')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),
            Stat::make('Total Realisasi Biaya', 'Rp ' . number_format($totalRealisasiBiaya))
                ->description('Total realisasi biaya dari semua total')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),
            Stat::make('Total Persentase', number_format($totalProsentase, 2) . '%')
                ->description('Total persentase dari semua total')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('secondary'),
        ];
    }
}
