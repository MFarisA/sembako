<?php

namespace App\Filament\Resources\SufixResource\Widgets;

use App\Models\Sufix;
use App\Models\SubSufix;
use App\Models\Total;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SufixOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSufix = Sufix::count();
        
        // Get aggregated data from SubSufix table
        $totalAlokasi = SubSufix::sum('alokasi');
        $totalAlokasiB = SubSufix::sum('alokasi_biaya');
        $totalRealisasi = SubSufix::sum('realisasi');
        $totalRealisasiBiaya = SubSufix::sum('realisasi_biaya');
        $totalGagalBayar = SubSufix::sum('gagal_bayar_tolak');
        $totalSisaAktif = SubSufix::sum('sisa_aktif');
        $totalSisaBiaya = SubSufix::sum('sisa_biaya');
        
        // Get totals count
        $totalRecords = Total::count();

        return [
            Stat::make('Total Sufix', $totalSufix)
                ->description('Jumlah sufix yang terdaftar')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('success'),
                
            Stat::make('Total Records', $totalRecords)
                ->description('Jumlah total records')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
                
            Stat::make('Total Alokasi', number_format($totalAlokasi))
                ->description('Total alokasi dari semua sub sufix')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
                
            Stat::make('Total Alokasi Biaya', 'Rp ' . number_format($totalAlokasiB))
                ->description('Total alokasi biaya')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
                
            Stat::make('Total Realisasi', number_format($totalRealisasi))
                ->description('Total realisasi dari semua sub sufix')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Gagal Bayar/Tolak', number_format($totalGagalBayar))
                ->description('Total gagal bayar atau tolak')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
                
            Stat::make('Sisa Aktif', number_format($totalSisaAktif))
                ->description('Total sisa aktif')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
                
            Stat::make('Sisa Biaya', 'Rp ' . number_format($totalSisaBiaya))
                ->description('Total sisa biaya')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('secondary'),
        ];
    }
}
