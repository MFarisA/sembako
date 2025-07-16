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
                        'alokasi_kpm' => $this->parseIndonesianNumber($row['alokasi_kpm']),
                        'alokasi_jml_uang' => $this->parseIndonesianNumber($row['alokasi_jml_uang']),
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
                            'alokasi' => $this->parseIndonesianNumber($row['alokasi'] ?? 0),
                            'alokasi_biaya' => $this->parseIndonesianNumber($row['alokasi_biaya'] ?? 0),
                            'realisasi' => $this->parseIndonesianNumber($row['realisasi'] ?? 0),
                            'realisasi_biaya' => $this->parseIndonesianNumber($row['realisasi_biaya'] ?? 0),
                            'gagal_bayar_tolak' => $this->parseIndonesianNumber($row['gagal_bayar_tolak'] ?? 0),
                            'sisa_aktif' => $this->parseIndonesianNumber($row['sisa_aktif'] ?? 0),
                            'sisa_biaya' => $this->parseIndonesianNumber($row['sisa_biaya'] ?? 0),
                        ]);

                        // Don't generate total per Sufix, we'll generate per Kantor later
                    }
                }
            } catch (\Exception $e) {
                // Log the error for debugging with row number
                Log::error('Import failed for row ' . ($rowIndex + 1) . ': ' . json_encode($row) . ' Error: ' . $e->getMessage());
            }
        }
        
        // After all rows are processed, generate one total per Kantor
        $this->generateKantorTotals();
    }

    /**
     * Generate one total per Kantor (aggregate from all Sufixes in that Kantor)
     */
    private function generateKantorTotals()
    {
        // Get all Kantors that were processed
        $kantors = Kantor::with(['sufixes.subSufixes'])->get();
        
        foreach ($kantors as $kantor) {
            if ($kantor->sufixes->isEmpty()) {
                continue; // Skip if no sufixes
            }
            
            // Delete existing totals for this Kantor
            Total::whereHas('sufix', function ($query) use ($kantor) {
                $query->where('kantor_id', $kantor->id);
            })->delete();
            
            // Calculate aggregate totals from all SubSufixes in this Kantor
            $allSubSufixes = collect();
            foreach ($kantor->sufixes as $sufix) {
                $allSubSufixes = $allSubSufixes->merge($sufix->subSufixes);
            }
            
            if ($allSubSufixes->isEmpty()) {
                continue; // Skip if no SubSufixes
            }
            
            // Aggregate calculations
            $totalAlokasi = $allSubSufixes->sum('alokasi');
            $totalAlokasiB = $allSubSufixes->sum('alokasi_biaya');
            $totalRealisasi = $allSubSufixes->sum('realisasi');
            $totalRealisasiB = $allSubSufixes->sum('realisasi_biaya');
            $totalGagalBayar = $allSubSufixes->sum('gagal_bayar_tolak');
            $totalSisaAktif = $allSubSufixes->sum('sisa_aktif');
            $totalSisaBiaya = $allSubSufixes->sum('sisa_biaya');
            
            // Calculate percentage
            $persentase = $totalAlokasi > 0 ? ($totalRealisasi / $totalAlokasi) * 100 : 0;
            
            // Create one Total record for the first Sufix of this Kantor
            // This represents the total for the entire Kantor
            $firstSufix = $kantor->sufixes->first();
            
            Total::create([
                'sufix_id' => $firstSufix->id,
                'jumlah_alokasi_bnba' => $totalAlokasi,
                'jumlah_alokasi_biaya' => $totalAlokasiB,
                'jumlah_realisasi' => $totalRealisasi,
                'jumlah_realisasi_biaya' => $totalRealisasiB,
                'persentase' => $persentase,
                'gagal_bayar_tolak' => $totalGagalBayar,
                'sisa_aktif' => $totalSisaAktif,
                'sisa_biaya' => $totalSisaBiaya,
            ]);
        }
    }

    /**
     * Parse Indonesian number format (dots as thousand separators)
     * Examples: "9.831.300.000" -> 9831300000, "12.657" -> 12657, "-" -> 0
     */
    private function parseIndonesianNumber($value): int
    {
        // Handle null, empty, or "-" values
        if (empty($value) || $value === '-' || $value === null) {
            return 0;
        }

        // Convert to string and clean
        $value = trim(strval($value));
        
        // Handle "-" specifically
        if ($value === '-') {
            return 0;
        }

        // Remove dots (thousand separators) and convert to integer
        $cleanValue = str_replace('.', '', $value);
        
        // Handle decimal values by removing commas if any
        $cleanValue = str_replace(',', '', $cleanValue);
        
        // Convert to integer
        return intval($cleanValue);
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
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Total::truncate();
        \App\Models\SubSufix::truncate();
        \App\Models\Sufix::truncate();
        \App\Models\Kantor::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Batch process import with better memory management
     */
    public function batchSize(): int
    {
        return 100; // Process 100 rows at a time
    }
}