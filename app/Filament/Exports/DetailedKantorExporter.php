<?php

namespace App\Filament\Exports;

use App\Models\Kantor;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

class DetailedKantorExporter extends Exporter
{
    protected static ?string $model = Kantor::class;

    public static function getColumns(): array
    {
        $columns = [
            // Basic Kantor data
            ExportColumn::make('no')
                ->label('NO')
                ->state(function ($record, $livewire) {
                    static $counter = 0;
                    return ++$counter;
                }),
            ExportColumn::make('kantor')
                ->label('KANTOR'),
            ExportColumn::make('nopen')
                ->label('NOPEN'),
            ExportColumn::make('kab_kota')
                ->label('KAB/KOTA')
                ->state(function ($record) {
                    return $record->kab_kota ?? '';
                }),
            ExportColumn::make('alokasi_kpm')
                ->label('ALOKASI KPM')
                ->state(function ($record) {
                    return number_format($record->alokasi_kpm, 0, ',', '.');
                }),
            ExportColumn::make('alokasi_jml_uang')
                ->label('ALOKASI JUMLAH UANG')
                ->state(function ($record) {
                    return number_format($record->alokasi_jml_uang, 0, ',', '.');
                }),
        ];

        // Get all unique Sufix names dynamically
        $sufixNames = \App\Models\Sufix::pluck('nama_sufix')->unique()->sort()->values();
        
        // Add dynamic columns for each Sufix
        foreach ($sufixNames as $index => $sufixName) {
            $sufixLabel = strtoupper($sufixName);
            
            // Alokasi BNBA
            $columns[] = ExportColumn::make("sufix_{$index}_alokasi")
                ->label("{$sufixLabel} ALOKASI")
                ->state(function ($record) use ($sufixName) {
                    $sufix = $record->sufixes->where('nama_sufix', $sufixName)->first();
                    if (!$sufix) return '-';
                    $total = $sufix->subSufixes->sum('alokasi');
                    return $total > 0 ? number_format($total, 0, ',', '.') : '-';
                });
                
            // Alokasi Biaya
            $columns[] = ExportColumn::make("sufix_{$index}_alokasi_biaya")
                ->label("{$sufixLabel} ALOKASI BIAYA")
                ->state(function ($record) use ($sufixName) {
                    $sufix = $record->sufixes->where('nama_sufix', $sufixName)->first();
                    if (!$sufix) return '-';
                    $total = $sufix->subSufixes->sum('alokasi_biaya');
                    return $total > 0 ? number_format($total, 0, ',', '.') : '-';
                });
                
            // Realisasi
            $columns[] = ExportColumn::make("sufix_{$index}_realisasi")
                ->label("{$sufixLabel} REALISASI")
                ->state(function ($record) use ($sufixName) {
                    $sufix = $record->sufixes->where('nama_sufix', $sufixName)->first();
                    if (!$sufix) return '-';
                    $total = $sufix->subSufixes->sum('realisasi');
                    return $total > 0 ? number_format($total, 0, ',', '.') : '-';
                });
                
            // Realisasi Biaya
            $columns[] = ExportColumn::make("sufix_{$index}_realisasi_biaya")
                ->label("{$sufixLabel} REALISASI BIAYA")
                ->state(function ($record) use ($sufixName) {
                    $sufix = $record->sufixes->where('nama_sufix', $sufixName)->first();
                    if (!$sufix) return '-';
                    $total = $sufix->subSufixes->sum('realisasi_biaya');
                    return $total > 0 ? number_format($total, 0, ',', '.') : '-';
                });
                
            // Gagal Bayar/Tolak
            $columns[] = ExportColumn::make("sufix_{$index}_gagal_bayar_tolak")
                ->label("{$sufixLabel} GAGAL BAYAR/TOLAK")
                ->state(function ($record) use ($sufixName) {
                    $sufix = $record->sufixes->where('nama_sufix', $sufixName)->first();
                    if (!$sufix) return '-';
                    $total = $sufix->subSufixes->sum('gagal_bayar_tolak');
                    return $total > 0 ? number_format($total, 0, ',', '.') : '-';
                });
                
            // Sisa Aktif
            $columns[] = ExportColumn::make("sufix_{$index}_sisa_aktif")
                ->label("{$sufixLabel} SISA AKTIF")
                ->state(function ($record) use ($sufixName) {
                    $sufix = $record->sufixes->where('nama_sufix', $sufixName)->first();
                    if (!$sufix) return '-';
                    $total = $sufix->subSufixes->sum('sisa_aktif');
                    return $total > 0 ? number_format($total, 0, ',', '.') : '-';
                });
                
            // Sisa Biaya
            $columns[] = ExportColumn::make("sufix_{$index}_sisa_biaya")
                ->label("{$sufixLabel} SISA BIAYA")
                ->state(function ($record) use ($sufixName) {
                    $sufix = $record->sufixes->where('nama_sufix', $sufixName)->first();
                    if (!$sufix) return '-';
                    $total = $sufix->subSufixes->sum('sisa_biaya');
                    return $total > 0 ? number_format($total, 0, ',', '.') : '-';
                });
        }

        // Add Total columns
        $columns[] = ExportColumn::make('total_jumlah_alokasi_bnba')
            ->label('JUMLAH ALOKASI BNBA')
            ->state(function ($record) {
                $total = $record->totals->sum('jumlah_alokasi_bnba');
                return $total > 0 ? number_format($total, 0, ',', '.') : '-';
            });
            
        $columns[] = ExportColumn::make('total_jumlah_alokasi_biaya')
            ->label('JUMLAH ALOKASI BIAYA')
            ->state(function ($record) {
                $total = $record->totals->sum('jumlah_alokasi_biaya');
                return $total > 0 ? number_format($total, 0, ',', '.') : '-';
            });
            
        $columns[] = ExportColumn::make('total_jumlah_realisasi')
            ->label('JUMLAH REALISASI')
            ->state(function ($record) {
                $total = $record->totals->sum('jumlah_realisasi');
                return $total > 0 ? number_format($total, 0, ',', '.') : '-';
            });
            
        $columns[] = ExportColumn::make('total_jumlah_realisasi_biaya')
            ->label('JUMLAH REALISASI BIAYA')
            ->state(function ($record) {
                $total = $record->totals->sum('jumlah_realisasi_biaya');
                return $total > 0 ? number_format($total, 0, ',', '.') : '-';
            });
            
        $columns[] = ExportColumn::make('prosentase')
            ->label('PROSENTASE')
            ->state(function ($record) {
                $avgPercentage = $record->totals->avg('persentase');
                return $avgPercentage ? number_format($avgPercentage, 2) . '%' : '-';
            });

        return $columns;
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['sufixes.subSufixes', 'totals']);
    }

    /**
     * Generate grouped column structure for complex headers
     */
    public static function getColumnStructure(): array
    {
        $sufixNames = \App\Models\Sufix::pluck('nama_sufix')->unique()->sort()->values();
        
        $structure = [
            'basic' => [
                'label' => 'Basic Information',
                'columns' => ['no', 'kantor', 'nopen', 'kab_kota', 'alokasi_kpm', 'alokasi_jml_uang']
            ]
        ];

        // Add Sufix groups
        foreach ($sufixNames as $index => $sufixName) {
            $sufixLabel = strtoupper($sufixName);
            $structure["sufix_{$index}"] = [
                'label' => $sufixLabel,
                'columns' => [
                    "sufix_{$index}_alokasi",
                    "sufix_{$index}_alokasi_biaya", 
                    "sufix_{$index}_realisasi",
                    "sufix_{$index}_realisasi_biaya",
                    "sufix_{$index}_gagal_bayar_tolak",
                    "sufix_{$index}_sisa_aktif",
                    "sufix_{$index}_sisa_biaya"
                ]
            ];
        }

        // Add totals group
        $structure['totals'] = [
            'label' => 'JUMLAH',
            'columns' => [
                'total_jumlah_alokasi_bnba',
                'total_jumlah_alokasi_biaya',
                'total_jumlah_realisasi',
                'total_jumlah_realisasi_biaya',
                'prosentase'
            ]
        ];

        return $structure;
    }

    /**
     * Get formatted data for export with enhanced formatting
     */
    public static function getFormattedData(): array
    {
        $query = static::modifyQuery(Kantor::query());
        $records = $query->get();
        $sufixNames = \App\Models\Sufix::pluck('nama_sufix')->unique()->sort()->values();
        
        $data = [];
        $counter = 0;
        
        foreach ($records as $record) {
            $counter++;
            $row = [
                'no' => $counter,
                'kantor' => $record->kantor,
                'nopen' => $record->nopen,
                'kab_kota' => $record->kab_kota ?? '',
                'alokasi_kpm' => number_format($record->alokasi_kpm, 0, ',', '.'),
                'alokasi_jml_uang' => number_format($record->alokasi_jml_uang, 0, ',', '.'),
            ];

            // Add Sufix data
            foreach ($sufixNames as $index => $sufixName) {
                $sufix = $record->sufixes->where('nama_sufix', $sufixName)->first();
                
                if ($sufix) {
                    $alokasi = $sufix->subSufixes->sum('alokasi');
                    $alokasibiaya = $sufix->subSufixes->sum('alokasi_biaya');
                    $realisasi = $sufix->subSufixes->sum('realisasi');
                    $realisasibiaya = $sufix->subSufixes->sum('realisasi_biaya');
                    $gagalbayar = $sufix->subSufixes->sum('gagal_bayar_tolak');
                    $sisaaktif = $sufix->subSufixes->sum('sisa_aktif');
                    $sisabiaya = $sufix->subSufixes->sum('sisa_biaya');
                    
                    $row["sufix_{$index}_alokasi"] = $alokasi > 0 ? number_format($alokasi, 0, ',', '.') : '-';
                    $row["sufix_{$index}_alokasi_biaya"] = $alokasibiaya > 0 ? number_format($alokasibiaya, 0, ',', '.') : '-';
                    $row["sufix_{$index}_realisasi"] = $realisasi > 0 ? number_format($realisasi, 0, ',', '.') : '-';
                    $row["sufix_{$index}_realisasi_biaya"] = $realisasibiaya > 0 ? number_format($realisasibiaya, 0, ',', '.') : '-';
                    $row["sufix_{$index}_gagal_bayar_tolak"] = $gagalbayar > 0 ? number_format($gagalbayar, 0, ',', '.') : '-';
                    $row["sufix_{$index}_sisa_aktif"] = $sisaaktif > 0 ? number_format($sisaaktif, 0, ',', '.') : '-';
                    $row["sufix_{$index}_sisa_biaya"] = $sisabiaya > 0 ? number_format($sisabiaya, 0, ',', '.') : '-';
                } else {
                    $row["sufix_{$index}_alokasi"] = '-';
                    $row["sufix_{$index}_alokasi_biaya"] = '-';
                    $row["sufix_{$index}_realisasi"] = '-';
                    $row["sufix_{$index}_realisasi_biaya"] = '-';
                    $row["sufix_{$index}_gagal_bayar_tolak"] = '-';
                    $row["sufix_{$index}_sisa_aktif"] = '-';
                    $row["sufix_{$index}_sisa_biaya"] = '-';
                }
            }

            // Add totals
            $totalAlokasiBnba = $record->totals->sum('jumlah_alokasi_bnba');
            $totalAlokasiBiaya = $record->totals->sum('jumlah_alokasi_biaya');
            $totalRealisasi = $record->totals->sum('jumlah_realisasi');
            $totalRealisasiBiaya = $record->totals->sum('jumlah_realisasi_biaya');
            $avgPercentage = $record->totals->avg('persentase');
            
            $row['total_jumlah_alokasi_bnba'] = $totalAlokasiBnba > 0 ? number_format($totalAlokasiBnba, 0, ',', '.') : '-';
            $row['total_jumlah_alokasi_biaya'] = $totalAlokasiBiaya > 0 ? number_format($totalAlokasiBiaya, 0, ',', '.') : '-';
            $row['total_jumlah_realisasi'] = $totalRealisasi > 0 ? number_format($totalRealisasi, 0, ',', '.') : '-';
            $row['total_jumlah_realisasi_biaya'] = $totalRealisasiBiaya > 0 ? number_format($totalRealisasiBiaya, 0, ',', '.') : '-';
            $row['prosentase'] = $avgPercentage ? number_format($avgPercentage, 2) . '%' : '-';
            
            $data[] = $row;
        }

        return $data;
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your detailed kantor export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
