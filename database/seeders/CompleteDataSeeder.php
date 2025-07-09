<?php

namespace Database\Seeders;

use App\Models\Kantor;
use App\Models\Sufix;
use App\Models\SubSufix;
use App\Models\Total;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompleteDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        Total::truncate();
        SubSufix::truncate();
        Sufix::truncate();
        Kantor::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create sample Kantor
        $kantor = Kantor::create([
            'kantor' => 'Kantor Pos Jakarta Pusat',
            'nopen' => 12345,
            'Kab/Kota' => 'Jakarta Pusat',
            'alokasi_kpm' => '5000',
            'alokasi_jml_uang' => 15000000000,
        ]);

        // Create sample Sufix
        $sufix = Sufix::create([
            'kantor_id' => $kantor->id,
            'nama_sufix' => 'Sufix Region Jakarta',
        ]);

        // Create multiple SubSufix records (A, B, C)
        $subSufixA = SubSufix::create([
            'sufix_id' => $sufix->id,
            'alokasi' => 1000,
            'alokasi_biaya' => 3000000000,
            'realisasi' => 800,
            'realisasi_biaya' => 2400000000,
            'gagal_bayar_tolak' => 50,
            'sisa_aktif' => 150,
            'sisa_biaya' => 600000000,
        ]);

        $subSufixB = SubSufix::create([
            'sufix_id' => $sufix->id,
            'alokasi' => 1200,
            'alokasi_biaya' => 3600000000,
            'realisasi' => 1000,
            'realisasi_biaya' => 3000000000,
            'gagal_bayar_tolak' => 80,
            'sisa_aktif' => 120,
            'sisa_biaya' => 600000000,
        ]);

        $subSufixC = SubSufix::create([
            'sufix_id' => $sufix->id,
            'alokasi' => 800,
            'alokasi_biaya' => 2400000000,
            'realisasi' => 700,
            'realisasi_biaya' => 2100000000,
            'gagal_bayar_tolak' => 30,
            'sisa_aktif' => 70,
            'sisa_biaya' => 300000000,
        ]);

        // Generate the Total record by aggregating SubSufix A, B, C
        $sufix->generateTotal();

        $this->command->info('✅ Complete data seeded successfully!');
        $this->command->info('📊 Data structure:');
        $this->command->info("   Kantor: {$kantor->kantor}");
        $this->command->info("   └── Sufix: {$sufix->nama_sufix}");
        $this->command->info("       ├── SubSufix A: {$subSufixA->alokasi} alokasi, {$subSufixA->realisasi} realisasi");
        $this->command->info("       ├── SubSufix B: {$subSufixB->alokasi} alokasi, {$subSufixB->realisasi} realisasi");
        $this->command->info("       ├── SubSufix C: {$subSufixC->alokasi} alokasi, {$subSufixC->realisasi} realisasi");
        $this->command->info("       └── Total: {$sufix->total->jumlah_alokasi_bnba} total alokasi, {$sufix->total->jumlah_realisasi} total realisasi");
    }
}
