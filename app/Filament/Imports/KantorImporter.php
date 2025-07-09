<?php

namespace App\Filament\Imports;

use App\Models\Kantor;
use App\Models\Sufix;
use App\Models\SubSufix;
use App\Models\Total;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class KantorImporter extends Importer
{
    protected static ?string $model = Kantor::class;

    public static function getColumns(): array
    {
        return [
            // Kantor columns
            ImportColumn::make('kantor')
                ->label('Kantor'),
            ImportColumn::make('nopen')
                ->label('Nopen'),
            ImportColumn::make('kab_kota')
                ->label('Kabupaten/Kota'),
            ImportColumn::make('alokasi_kpm')
                ->label('Alokasi KPM'),
            ImportColumn::make('alokasi_jml_uang')
                ->label('Alokasi Jumlah Uang'),

            
            ImportColumn::make('nama_sufix')
                ->label('Nama Sufix')
                ->relationship()
                ->requiredMapping()
                ->rules(['nullable', 'string', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Kantor
    {
       // return Sufix::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Kantor();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your kantor import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
