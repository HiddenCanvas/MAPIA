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
        Schema::create('riwayat_sensors', function (Blueprint $table) {
            $table->id('id_riwayat_sensor');
            $table->foreignId('id_sensor')->constrained('sensors', 'id_sensor')->onDelete('cascade');
            $table->float('kelembapan'); 
            $table->float('ph_tanah');
            $table->timestamp('created_at')->useCurrent();
        });   
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_sensors');
    }
};
