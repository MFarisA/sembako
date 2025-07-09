<?php

namespace App\Console\Commands;

use App\Models\Sufix;
use Illuminate\Console\Command;

class GenerateTotals extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'generate:totals';

    /**
     * The console command description.
     */
    protected $description = 'Generate Total records by aggregating SubSufix data for each Sufix';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Generating totals from SubSufix data...');
        
        $sufixes = Sufix::with('subSufixes')->get();
        
        foreach ($sufixes as $sufix) {
            $total = $sufix->generateTotal();
            
            $this->line("✅ Generated total for Sufix: {$sufix->nama_sufix}");
            $this->line("   Total Alokasi: {$total->jumlah_alokasi_bnba}");
            $this->line("   Total Realisasi: {$total->jumlah_realisasi}");
            $this->line("   Persentase: {$total->persentase}%");
            $this->line('');
        }
        
        $this->info("✅ Successfully generated totals for {$sufixes->count()} sufixes!");
        
        return Command::SUCCESS;
    }
}
