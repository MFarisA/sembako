<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    protected $table = 'total';
    
    protected $fillable = [
        'jumlah_alokasi_bnba',
        'jumlah_alokasi_biaya',
        'jumlah_realisasi',
        'jumlah_realisasi_biaya',
        'persentase',
        'sufix_id',
    ];

    public function sufix()
    {
        return $this->belongsTo(Sufix::class);
    }

    public static function generatePositionalTotals()
    {
        self::whereNull('sufix_id')->delete();
        
        $sufixes = \App\Models\Sufix::with('subSufixes')->get();
        
        $maxSubSufixes = $sufixes->max(function ($sufix) {
            return $sufix->subSufixes->count();
        });
        
        $totalsCreated = [];
        
        for ($position = 0; $position < $maxSubSufixes; $position++) {
            $totalData = [
                'jumlah_alokasi_bnba' => 0,
                'jumlah_alokasi_biaya' => 0,
                'jumlah_realisasi' => 0,
                'jumlah_realisasi_biaya' => 0,
                'sufix_id' => null, 
            ];
            
            $hasData = false;
            
            foreach ($sufixes as $sufix) {
                $subSufixAtPosition = $sufix->subSufixes->skip($position)->first();
                
                if ($subSufixAtPosition) {
                    $totalData['jumlah_alokasi_bnba'] += $subSufixAtPosition->alokasi ?? 0;
                    $totalData['jumlah_alokasi_biaya'] += $subSufixAtPosition->alokasi_biaya ?? 0;
                    $totalData['jumlah_realisasi'] += $subSufixAtPosition->realisasi ?? 0;
                    $totalData['jumlah_realisasi_biaya'] += $subSufixAtPosition->realisasi_biaya ?? 0;
                    $hasData = true;
                }
            }
            
            if ($hasData) {
                $totalData['persentase'] = $totalData['jumlah_alokasi_bnba'] > 0 ? 
                    ($totalData['jumlah_realisasi'] / $totalData['jumlah_alokasi_bnba']) * 100 : 0;
                
                $total = self::create($totalData);
                $totalsCreated[] = [
                    'position' => $position + 1,
                    'total' => $total
                ];
            }
        }
        
        return $totalsCreated;
    }
}
