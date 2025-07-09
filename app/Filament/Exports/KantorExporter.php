<?php

namespace App\Filament\Exports;

use App\Models\Kantor;
use App\Models\Sufix;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class KantorExporter extends Exporter
{
    protected static ?string $model = Kantor::class;

    public static function getColumns(): array
    {
        $columns = [
            // Kantor columns
            ExportColumn::make('kantor')
                ->label('Kantor'),
            ExportColumn::make('nopen')
                ->label('Nopen'),
            ExportColumn::make('kab_kota')
                ->label('Kabupaten/Kota'),
            ExportColumn::make('alokasi_kpm')
                ->label('Alokasi KPM'),
            ExportColumn::make('alokasi_jml_uang')
                ->label('Alokasi Jumlah Uang'),
            
        ];

        // Get all unique sufixes to create dynamic columns
        $sufixes = Sufix::distinct('nama_sufix')->pluck('nama_sufix');
        
        // Loop through each sufix and create a column
        foreach ($sufixes as $index => $sufixName) {
            $columns[] = ExportColumn::make("sufixes.{$index}.nama_sufix")
                ->label("{$sufixName}")
                ->state(function (Kantor $record) use ($index) {
                    return $record->sufixes->get($index)?->nama_sufix ?? '';
                });
        }
        

        return $columns;
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['sufixes', 'subSufixes', 'totals']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your kantor export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
