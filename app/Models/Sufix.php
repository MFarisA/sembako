<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sufix extends Model
{
    protected $table = 'sufix'; 
    
    protected $fillable = [
        'nama_sufix',
        'kantor_id', 
    ];

    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    public function subSufixes()
    {
        return $this->hasMany(SubSufix::class);
    }

    public function total()
    {
        return $this->hasOne(Total::class);
    }

    /**
     * Generate or update the Total record by aggregating all SubSufix records
     */
    public function generateTotal()
    {
        $totalData = [
            'jumlah_alokasi_bnba' => $this->subSufixes()->sum('alokasi'),
            'jumlah_alokasi_biaya' => $this->subSufixes()->sum('alokasi_biaya'),
            'jumlah_realisasi' => $this->subSufixes()->sum('realisasi'),
            'jumlah_realisasi_biaya' => $this->subSufixes()->sum('realisasi_biaya'),
        ];
        
        // Calculate percentage
        $totalData['persentase'] = $totalData['jumlah_alokasi_bnba'] > 0 ? 
            ($totalData['jumlah_realisasi'] / $totalData['jumlah_alokasi_bnba']) * 100 : 0;
        
        return $this->total()->updateOrCreate(
            ['sufix_id' => $this->id],
            $totalData
        );
    }
}
