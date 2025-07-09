<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Sufix;
use App\Models\Kantor;

try {
    // Get a kantor
    $kantor = Kantor::first();
    if (!$kantor) {
        echo "No kantor found!\n";
        exit;
    }
    
    echo "Found kantor: " . $kantor->kantor . "\n";
    
    // Try to create a sufix
    $data = [
        'kantor_id' => $kantor->id,
        'nama_sufix' => 'Test Sufix from Script'
    ];
    
    echo "Creating sufix with data: " . json_encode($data) . "\n";
    
    $sufix = Sufix::create($data);
    echo "Successfully created sufix: " . json_encode($sufix->toArray()) . "\n";
    
    // Now try using the fillable approach
    $sufix2 = new Sufix();
    $sufix2->fill($data);
    $sufix2->save();
    
    echo "Successfully created sufix2: " . json_encode($sufix2->toArray()) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
