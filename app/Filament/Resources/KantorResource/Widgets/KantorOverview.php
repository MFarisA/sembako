<?php

namespace App\Filament\Resources\KantorResource\Widgets;

use App\Models\Kantor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KantorOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalKantor = Kantor::count();
        $totalAlokasi = Kantor::sum('alokasi_kpm');
        $totalUang = Kantor::sum('alokasi_jml_uang');
        
        $totalSufixAlokasi = Kantor::with('subSufixes')->get()
            ->flatMap->subSufixes
            ->sum('alokasi');
            
        $totalRealisasi = Kantor::with('subSufixes')->get()
            ->flatMap->subSufixes
            ->sum('realisasi');

        $totalFromTotalTable = Kantor::with('totals')->get()
            ->flatMap->totals
            ->sum('jumlah_alokasi_bnba');
            
        $totalRealisasiFromTotalTable = Kantor::with('totals')->get()
            ->flatMap->totals
            ->sum('jumlah_realisasi');

        return [
            Stat::make('Total Kantor', $totalKantor)
                ->description('Jumlah kantor yang terdaftar')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success'),
            Stat::make('Total Alokasi Uang', 'Rp ' . number_format($totalUang))
                ->description('Total alokasi uang seluruh kantor')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
                
            Stat::make('Total Alokasi KPM', number_format($totalAlokasi))
                ->description('Total alokasi KPM seluruh kantor')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
                
            Stat::make('Total Alokasi Sufix', number_format($totalSufixAlokasi))
                ->description('Total alokasi dari semua sufix')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
                
            Stat::make('Total Realisasi', number_format($totalRealisasi))
                ->description('Total realisasi dari semua sufix')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Total Alokasi (Total Table)', number_format($totalFromTotalTable))
                ->description('Total alokasi dari tabel Total')
                ->descriptionIcon('heroicon-m-chart-bar-square')
                ->color('info'),
                
            // Stat::make('Persentase Realisasi (Sufix)', $totalSufixAlokasi > 0 ? number_format(($totalRealisasi / $totalSufixAlokasi) * 100, 2) . '%' : '0%')
            //     ->description('Persentase dari data Sufix')
            //     ->descriptionIcon('heroicon-m-chart-pie')
            //     ->color($totalSufixAlokasi > 0 && ($totalRealisasi / $totalSufixAlokasi) >= 0.8 ? 'success' : 'danger'),
                
            // Stat::make('Persentase Realisasi (Total)', $totalFromTotalTable > 0 ? number_format(($totalRealisasiFromTotalTable / $totalFromTotalTable) * 100, 2) . '%' : '0%')
            //     ->description('Persentase dari tabel Total')
            //     ->descriptionIcon('heroicon-m-chart-pie')
            //     ->color($totalFromTotalTable > 0 && ($totalRealisasiFromTotalTable / $totalFromTotalTable) >= 0.8 ? 'success' : 'danger'),
        ];
    }
}
