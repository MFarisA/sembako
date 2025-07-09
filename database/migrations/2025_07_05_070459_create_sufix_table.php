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
        Schema::create('sufix', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kantor_id')->constrained('kantor')->onDelete('cascade');

            $table->string('nama_sufix');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sufix');
    }
};
