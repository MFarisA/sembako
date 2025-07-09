<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $table = 'kantor';
    
    protected $fillable = [
        'kantor',
        'nopen',
        'kab_kota',
        'alokasi_kpm',
        'alokasi_jml_uang',
    ];

    public function sufixes()
    {
        return $this->hasMany(Sufix::class);
    }

    public function subSufixes()
    {
        return $this->hasManyThrough(SubSufix::class, Sufix::class);
    }

    public function totals()
    {
        return $this->hasManyThrough(Total::class, Sufix::class);
    }
}
