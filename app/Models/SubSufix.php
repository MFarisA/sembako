<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSufix extends Model
{
    protected $table = 'sub_sufix';
    
    protected $fillable = [
        'alokasi',
        'alokasi_biaya',
        'realisasi',
        'realisasi_biaya',
        'gagal_bayar_tolak',
        'sisa_aktif',
        'sisa_biaya',
        'sufix_id',
    ];

    public function sufix()
    {
        return $this->belongsTo(Sufix::class);
    }

    public function totals()
    {
        return $this->hasMany(Total::class);
    }
}
