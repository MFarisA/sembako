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

    /**
     * Get the single Total for this Kantor (should be attached to the first Sufix)
     */
    public function total()
    {
        return $this->totals()->first();
    }

    /**
     * Get Total relationship for easier access
     */
    public function kantorTotal()
    {
        return $this->hasOneThrough(Total::class, Sufix::class);
    }
}
