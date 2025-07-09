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
       Schema::create('sub_sufix', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sufix_id')->constrained('sufix')->onDelete('cascade');

            $table->bigInteger('alokasi')->nullable();
            $table->bigInteger('alokasi_biaya')->nullable();
            $table->bigInteger('realisasi')->nullable();
            $table->bigInteger('realisasi_biaya')->nullable();
            $table->bigInteger('gagal_bayar_tolak')->nullable();
            $table->bigInteger('sisa_aktif')->nullable();
            $table->bigInteger('sisa_biaya')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_sufix');
    }
};
