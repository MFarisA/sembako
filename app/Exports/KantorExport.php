<?php

namespace App\Exports;

use App\Models\Kantor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KantorExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kantorIds;

    public function __construct($kantorIds = null)
    {
        $this->kantorIds = $kantorIds;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Eager load semua relasi yang dibutuhkan
        $query = Kantor::with('sufixes.subSufixes', 'sufixes.total');
        
        // Filter by specific Kantor IDs if provided and not null
        if ($this->kantorIds !== null && !empty($this->kantorIds)) {
            $query->whereIn('id', $this->kantorIds);
        } elseif ($this->kantorIds !== null && empty($this->kantorIds)) {
            // If explicitly passed empty array, return no results
            $query->where('id', '<', 0); // This will return no results
        }
        
        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            // 'Kantor ID',
            'Nama Kantor',
            'Nopen',
            'Kab/Kota',
            'Alokasi KPM Kantor',
            'Alokasi Jumlah Uang Kantor',
            // 'SubSufix ID',
            'SubSufix Alokasi',
            'SubSufix Alokasi Biaya',
            'SubSufix Realisasi',
            'SubSufix Realisasi Biaya',
            'SubSufix Gagal Bayar Tolak',
            'SubSufix Sisa Aktif',
            'SubSufix Sisa Biaya',
            // 'Sufix ID',
            'Nama Sufix',
            'Total Jumlah Alokasi BNBA Sufix',
            'Total Jumlah Alokasi Biaya Sufix',
            'Total Jumlah Realisasi Sufix',
            'Total Jumlah Realisasi Biaya Sufix',
            'Total Persentase Sufix',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($kantor): array
    {
        $rows = [];
        // Loop melalui setiap sufix dari kantor
        foreach ($kantor->sufixes as $sufix) {
            // Jika ada subSufix, loop melalui setiap subSufix
            if ($sufix->subSufixes->isNotEmpty()) {
                foreach ($sufix->subSufixes as $subSufix) {
                    $rows[] = [
                        // $kantor->id,
                        $kantor->kantor,
                        $kantor->nopen,
                        $kantor->kab_kota,
                        $kantor->alokasi_kpm,
                        $kantor->alokasi_jml_uang,
                        // $subSufix->id,
                        $subSufix->alokasi,
                        $subSufix->alokasi_biaya,
                        $subSufix->realisasi,
                        $subSufix->realisasi_biaya,
                        $subSufix->gagal_bayar_tolak,
                        $subSufix->sisa_aktif,
                        $subSufix->sisa_biaya,
                        // $sufix->id,
                        $sufix->nama_sufix,
                        $sufix->total ? $sufix->total->jumlah_alokasi_bnba : null,
                        $sufix->total ? $sufix->total->jumlah_alokasi_biaya : null,
                        $sufix->total ? $sufix->total->jumlah_realisasi : null,
                        $sufix->total ? $sufix->total->jumlah_realisasi_biaya : null,
                        $sufix->total ? $sufix->total->persentase : null,
                    ];
                }
            } else {
                // Jika tidak ada subSufix, tambahkan baris dengan data sufix dan total saja
                $rows[] = [
                    // $kantor->id,
                    $kantor->kantor,
                    $kantor->nopen,
                    $kantor->kab_kota,
                    $kantor->alokasi_kpm,
                    $kantor->alokasi_jml_uang,
                    // Kolom subSufix kosong
                    null, null, null, null, null, null, null,
                    // $sufix->id,
                    $sufix->nama_sufix,
                    $sufix->total ? $sufix->total->jumlah_alokasi_bnba : null,
                    $sufix->total ? $sufix->total->jumlah_alokasi_biaya : null,
                    $sufix->total ? $sufix->total->jumlah_realisasi : null,
                    $sufix->total ? $sufix->total->jumlah_realisasi_biaya : null,
                    $sufix->total ? $sufix->total->persentase : null,
                ];
            }
        }
        
        // Jika tidak ada sufix sama sekali untuk kantor ini
        if ($kantor->sufixes->isEmpty()) {
            $rows[] = [
                // $kantor->id,
                $kantor->kantor,
                $kantor->nopen,
                $kantor->kab_kota,
                $kantor->alokasi_kpm,
                $kantor->alokasi_jml_uang,
                // Kolom subSufix kosong
                null, null, null, null, null, null, null,
                // Kolom sufix dan total kosong
                null, null, null, null, null, null,
            ];
        }

        return $rows;
    }
}