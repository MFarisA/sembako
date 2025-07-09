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
        Schema::create('total', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sufix_id')->nullable()->constrained('sufix')->onDelete('cascade');

            $table->bigInteger('jumlah_alokasi_bnba')->nullable();
            $table->bigInteger('jumlah_alokasi_biaya')->nullable();
            $table->bigInteger('jumlah_realisasi')->nullable();
            $table->bigInteger('jumlah_realisasi_biaya')->nullable();
            $table->decimal('persentase', 8, 2)->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total');
    }
};
