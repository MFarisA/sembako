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
        // Delete existing positional totals (we'll identify them by regenerating)
        self::whereNotNull('sufix_id')->delete();
        
        // Get all Kantors with their Sufixes and SubSufixes
        $kantors = \App\Models\Kantor::with('sufixes.subSufixes')->get();
        
        $totalsCreated = [];
        
        // Process each Kantor separately
        foreach ($kantors as $kantor) {
            $sufixes = $kantor->sufixes;
            
            if ($sufixes->isEmpty()) {
                continue; // Skip Kantor with no Sufixes
            }
            
            $maxSubSufixes = $sufixes->max(function ($sufix) {
                return $sufix->subSufixes->count();
            });
            
            // Generate positional totals for this specific Kantor
            for ($position = 0; $position < $maxSubSufixes; $position++) {
                $totalData = [
                    'jumlah_alokasi_bnba' => 0,
                    'jumlah_alokasi_biaya' => 0,
                    'jumlah_realisasi' => 0,
                    'jumlah_realisasi_biaya' => 0,
                ];
                
                // Get the Sufix at this position within this Kantor to link the total to
                $sufixAtPosition = $sufixes->skip($position)->first();
                if (!$sufixAtPosition) {
                    continue; // Skip if no Sufix available for this position in this Kantor
                }
                
                $totalData['sufix_id'] = $sufixAtPosition->id;
                
                $hasData = false;
                
                // Only calculate from SubSufixes within this Kantor
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
                        'kantor' => $kantor->kantor,
                        'kantor_id' => $kantor->id,
                        'position' => $position + 1,
                        'sufix_name' => $sufixAtPosition->nama_sufix,
                        'total' => $total
                    ];
                }
            }
        }
        
        return $totalsCreated;
    }
}
