<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::table('kantor', function (Blueprint $table) {
        //     $table->renameColumn('Kab/Kota', 'kab_kota');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kantor', function (Blueprint $table) {
            $table->renameColumn('kab_kota', 'Kab/Kota');
        });
    }
};
