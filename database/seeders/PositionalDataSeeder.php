<?php

namespace Database\Seeders;

use App\Models\Kantor;
use App\Models\Sufix;
use App\Models\SubSufix;
use App\Models\Total;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionalDataSeeder extends Seeder
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

        // Create Sufixes A, B, C
        $sufixA = Sufix::create([
            'kantor_id' => $kantor->id,
            'nama_sufix' => 'Sufix A',
        ]);

        $sufixB = Sufix::create([
            'kantor_id' => $kantor->id,
            'nama_sufix' => 'Sufix B',
        ]);

        $sufixC = Sufix::create([
            'kantor_id' => $kantor->id,
            'nama_sufix' => 'Sufix C',
        ]);

        // Create SubSufixes for Sufix A (A1, A2, A3)
        $subSufixA1 = SubSufix::create([
            'sufix_id' => $sufixA->id,
            'alokasi' => 1000,
            'alokasi_biaya' => 2000000000,
            'realisasi' => 800,
            'realisasi_biaya' => 1600000000,
            'gagal_bayar_tolak' => 50,
            'sisa_aktif' => 150,
            'sisa_biaya' => 400000000,
        ]);

        $subSufixA2 = SubSufix::create([
            'sufix_id' => $sufixA->id,
            'alokasi' => 1200,
            'alokasi_biaya' => 2400000000,
            'realisasi' => 1000,
            'realisasi_biaya' => 2000000000,
            'gagal_bayar_tolak' => 60,
            'sisa_aktif' => 140,
            'sisa_biaya' => 400000000,
        ]);

        $subSufixA3 = SubSufix::create([
            'sufix_id' => $sufixA->id,
            'alokasi' => 800,
            'alokasi_biaya' => 1600000000,
            'realisasi' => 700,
            'realisasi_biaya' => 1400000000,
            'gagal_bayar_tolak' => 30,
            'sisa_aktif' => 70,
            'sisa_biaya' => 200000000,
        ]);

        // Create SubSufixes for Sufix B (B1, B2, B3)
        $subSufixB1 = SubSufix::create([
            'sufix_id' => $sufixB->id,
            'alokasi' => 1500,
            'alokasi_biaya' => 3000000000,
            'realisasi' => 1200,
            'realisasi_biaya' => 2400000000,
            'gagal_bayar_tolak' => 80,
            'sisa_aktif' => 220,
            'sisa_biaya' => 600000000,
        ]);

        $subSufixB2 = SubSufix::create([
            'sufix_id' => $sufixB->id,
            'alokasi' => 900,
            'alokasi_biaya' => 1800000000,
            'realisasi' => 750,
            'realisasi_biaya' => 1500000000,
            'gagal_bayar_tolak' => 40,
            'sisa_aktif' => 110,
            'sisa_biaya' => 300000000,
        ]);

        $subSufixB3 = SubSufix::create([
            'sufix_id' => $sufixB->id,
            'alokasi' => 1100,
            'alokasi_biaya' => 2200000000,
            'realisasi' => 900,
            'realisasi_biaya' => 1800000000,
            'gagal_bayar_tolak' => 50,
            'sisa_aktif' => 150,
            'sisa_biaya' => 400000000,
        ]);

        // Create SubSufixes for Sufix C (C1, C2, C3)
        $subSufixC1 = SubSufix::create([
            'sufix_id' => $sufixC->id,
            'alokasi' => 700,
            'alokasi_biaya' => 1400000000,
            'realisasi' => 600,
            'realisasi_biaya' => 1200000000,
            'gagal_bayar_tolak' => 25,
            'sisa_aktif' => 75,
            'sisa_biaya' => 200000000,
        ]);

        $subSufixC2 = SubSufix::create([
            'sufix_id' => $sufixC->id,
            'alokasi' => 1300,
            'alokasi_biaya' => 2600000000,
            'realisasi' => 1100,
            'realisasi_biaya' => 2200000000,
            'gagal_bayar_tolak' => 70,
            'sisa_aktif' => 130,
            'sisa_biaya' => 400000000,
        ]);

        $subSufixC3 = SubSufix::create([
            'sufix_id' => $sufixC->id,
            'alokasi' => 600,
            'alokasi_biaya' => 1200000000,
            'realisasi' => 500,
            'realisasi_biaya' => 1000000000,
            'gagal_bayar_tolak' => 20,
            'sisa_aktif' => 80,
            'sisa_biaya' => 200000000,
        ]);

        // Generate positional totals
        $totals = Total::generatePositionalTotals();

        $this->command->info('✅ Positional data seeded successfully!');
        $this->command->info('📊 Data structure:');
        $this->command->info("   Kantor: {$kantor->kantor}");
        $this->command->info("   ├── Sufix A: {$sufixA->nama_sufix}");
        $this->command->info("   │   ├── SubSufix A1: {$subSufixA1->alokasi} alokasi, {$subSufixA1->realisasi} realisasi");
        $this->command->info("   │   ├── SubSufix A2: {$subSufixA2->alokasi} alokasi, {$subSufixA2->realisasi} realisasi");
        $this->command->info("   │   └── SubSufix A3: {$subSufixA3->alokasi} alokasi, {$subSufixA3->realisasi} realisasi");
        $this->command->info("   ├── Sufix B: {$sufixB->nama_sufix}");
        $this->command->info("   │   ├── SubSufix B1: {$subSufixB1->alokasi} alokasi, {$subSufixB1->realisasi} realisasi");
        $this->command->info("   │   ├── SubSufix B2: {$subSufixB2->alokasi} alokasi, {$subSufixB2->realisasi} realisasi");
        $this->command->info("   │   └── SubSufix B3: {$subSufixB3->alokasi} alokasi, {$subSufixB3->realisasi} realisasi");
        $this->command->info("   └── Sufix C: {$sufixC->nama_sufix}");
        $this->command->info("       ├── SubSufix C1: {$subSufixC1->alokasi} alokasi, {$subSufixC1->realisasi} realisasi");
        $this->command->info("       ├── SubSufix C2: {$subSufixC2->alokasi} alokasi, {$subSufixC2->realisasi} realisasi");
        $this->command->info("       └── SubSufix C3: {$subSufixC3->alokasi} alokasi, {$subSufixC3->realisasi} realisasi");

        $this->command->info('');
        $this->command->info('📈 Generated Positional Totals:');
        foreach ($totals as $total) {
            $this->command->info("   Total Data {$total['position']}: {$total['total']->jumlah_alokasi_bnba} alokasi, {$total['total']->jumlah_realisasi} realisasi");
        }
        
        // Expected results:
        // Total Data 1 = A1(1000) + B1(1500) + C1(700) = 3200 alokasi, A1(800) + B1(1200) + C1(600) = 2600 realisasi
        // Total Data 2 = A2(1200) + B2(900) + C2(1300) = 3400 alokasi, A2(1000) + B2(750) + C2(1100) = 2850 realisasi
        // Total Data 3 = A3(800) + B3(1100) + C3(600) = 2500 alokasi, A3(700) + B3(900) + C3(500) = 2100 realisasi
    }
}
