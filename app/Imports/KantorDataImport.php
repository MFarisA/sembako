<?php

namespace App\Imports;

use App\Models\Kantor;
use App\Models\Sufix;
use App\Models\SubSufix;
use App\Models\Total;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\Log;

class KantorDataImport implements ToCollection, WithHeadingRow, WithBatchInserts
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $rowIndex => $row) {
            try {
                // Find or create Kantor
                $kantor = Kantor::updateOrCreate(
                    ['nopen' => $row['nopen']],
                    [
                        'kantor' => $row['kantor'],
                        'kab_kota' => $row['kab_kota'],
                        'alokasi_kpm' => $row['alokasi_kpm'],
                        'alokasi_jml_uang' => $row['alokasi_jml_uang'],
                    ]
                );

                // Create Sufix if nama_sufix is provided
                if (!empty($row['nama_sufix'])) {
                    $sufix = Sufix::firstOrCreate([
                        'nama_sufix' => $row['nama_sufix'],
                        'kantor_id' => $kantor->id,
                    ]);

                    // Create SubSufix if sub data is provided
                    if ($this->hasSubSufixData($row)) {
                        // Create new SubSufix for each row with data
                        // This allows multiple SubSufix records per Sufix
                        SubSufix::create([
                            'sufix_id' => $sufix->id,
                            'alokasi' => $row['alokasi'] ?? 0,
                            'alokasi_biaya' => $row['alokasi_biaya'] ?? 0,
                            'realisasi' => $row['realisasi'] ?? 0,
                            'realisasi_biaya' => $row['realisasi_biaya'] ?? 0,
                            'gagal_bayar_tolak' => $row['gagal_bayar_tolak'] ?? 0,
                            'sisa_aktif' => $row['sisa_aktif'] ?? 0,
                            'sisa_biaya' => $row['sisa_biaya'] ?? 0,
                        ]);

                        // Regenerate total for this Sufix after adding SubSufix
                        $sufix->generateTotal();
                    }
                }
            } catch (\Exception $e) {
                // Log the error for debugging with row number
                Log::error('Import failed for row ' . ($rowIndex + 1) . ': ' . json_encode($row) . ' Error: ' . $e->getMessage());
            }
        }
    }

    private function hasSubSufixData($row): bool
    {
        return isset($row['alokasi']) ||
               isset($row['alokasi_biaya']) ||
               isset($row['realisasi']) ||
               isset($row['realisasi_biaya']) ||
               isset($row['gagal_bayar_tolak']) ||
               isset($row['sisa_aktif']) ||
               isset($row['sisa_biaya']);
    }

    private function hasTotalData($row): bool
    {
        // Method ini tidak lagi digunakan untuk impor langsung Total,
        // tetapi tetap dipertahankan jika ada kebutuhan lain di masa depan.
        return isset($row['jumlah_alokasi_bnba']) ||
               isset($row['jumlah_alokasi_biaya']) ||
               isset($row['jumlah_realisasi']) ||
               isset($row['jumlah_realisasi_biaya']) ||
               isset($row['persentase']);
    }

    /**
     * Clear existing data before import (optional - call this if you want fresh import)
     */
    public static function clearExistingData()
    {
        // Delete in proper order due to foreign key constraints
        \App\Models\Total::truncate();
        \App\Models\SubSufix::truncate();
        \App\Models\Sufix::truncate();
        \App\Models\Kantor::truncate();
    }

    /**
     * Batch process import with better memory management
     */
    public function batchSize(): int
    {
        return 100; // Process 100 rows at a time
    }
}