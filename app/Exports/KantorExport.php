<?php

namespace App\Exports;

use App\Models\Kantor;
use Maatwebsite\Excel\Concerns\FromCollection;

class KantorExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Kantor::all();
    }
}
