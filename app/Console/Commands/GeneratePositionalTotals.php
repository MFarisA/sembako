<?php

namespace App\Console\Commands;

use App\Models\Total;
use Illuminate\Console\Command;

class GeneratePositionalTotals extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'generate:positional-totals';

    /**
     * The console command description.
     */
    protected $description = 'Generate positional Total records by aggregating SubSufix data by position';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Generating positional totals...');
        $this->info('This will create Total records by summing SubSufix records by their position:');
        $this->info('- Data 1 = A1 + B1 + C1 (all 1st SubSufixes)');
        $this->info('- Data 2 = A2 + B2 + C2 (all 2nd SubSufixes)');
        $this->info('- etc.');
        $this->line('');
        
        $totals = Total::generatePositionalTotals();
        
        if (empty($totals)) {
            $this->warn('No SubSufix data found to generate totals from.');
            return Command::FAILURE;
        }
        
        $this->info('✅ Successfully generated positional totals:');
        $this->line('');
        
        foreach ($totals as $total) {
            $this->line("📊 Total Data {$total['position']}:");
            $this->line("   Alokasi: " . number_format($total['total']->jumlah_alokasi_bnba));
            $this->line("   Realisasi: " . number_format($total['total']->jumlah_realisasi));
            $this->line("   Persentase: " . number_format($total['total']->persentase, 2) . "%");
            $this->line("   ID: {$total['total']->id}");
            $this->line('');
        }
        
        $this->info("✅ Generated {" . count($totals) . "} positional total records!");
        
        return Command::SUCCESS;
    }
}
