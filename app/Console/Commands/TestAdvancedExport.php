<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Filament\Exports\AdvancedKantorExporter;

class TestAdvancedExport extends Command
{
    protected $signature = 'test:advanced-export';
    protected $description = 'Test the advanced Kantor export with multi-level headers';

    public function handle()
    {
        $this->info('Generating advanced export...');
        
        try {
            $filepath = AdvancedKantorExporter::createExcelFile();
            $this->info("Export created successfully at: {$filepath}");
            
            if (file_exists($filepath)) {
                $this->info("File size: " . number_format(filesize($filepath)) . " bytes");
            }
            
        } catch (\Exception $e) {
            $this->error("Export failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
