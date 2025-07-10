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
        $this->info('This will create Total records by summing SubSufix records by their position PER KANTOR:');
        $this->info('- Kantor A: Data 1 = A1 + B1 + C1 (all 1st SubSufixes in Kantor A)');
        $this->info('- Kantor A: Data 2 = A2 + B2 + C2 (all 2nd SubSufixes in Kantor A)');
        $this->info('- Kantor B: Data 1 = D1 + E1 + F1 (all 1st SubSufixes in Kantor B)');
        $this->info('- etc.');
        $this->info('Each positional total will be calculated separately for each Kantor.');
        $this->line('');
        
        $totals = Total::generatePositionalTotals();
        
        if (empty($totals)) {
            $this->warn('No SubSufix data found to generate totals from.');
            return Command::FAILURE;
        }
        
        $this->info('✅ Successfully generated positional totals:');
        $this->line('');
        
        // Group by Kantor for better display
        $groupedByKantor = collect($totals)->groupBy('kantor');
        
        foreach ($groupedByKantor as $kantorName => $kantorTotals) {
            $this->line("🏢 Kantor: {$kantorName}");
            foreach ($kantorTotals as $total) {
                $this->line("   📊 Position {$total['position']} → {$total['sufix_name']}:");
                $this->line("      Sufix ID: {$total['total']->sufix_id}");
                $this->line("      Alokasi: " . number_format($total['total']->jumlah_alokasi_bnba));
                $this->line("      Realisasi: " . number_format($total['total']->jumlah_realisasi));
                $this->line("      Persentase: " . number_format($total['total']->persentase, 2) . "%");
                $this->line("      Record ID: {$total['total']->id}");
            }
            $this->line('');
        }
        
        $this->info("✅ Generated {" . count($totals) . "} positional total records across " . count($groupedByKantor) . " Kantors!");
        $this->info("Each Kantor now has its own positional totals calculated separately.");
        
        return Command::SUCCESS;
    }
}
