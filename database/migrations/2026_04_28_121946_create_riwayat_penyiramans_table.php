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
        Schema::create('riwayat_penyiramans', function (Blueprint $table) {
            $table->id('id_riwayat_penyiraman');
            $table->foreignId('id_sensor')->constrained('sensors', 'id_sensor')->onDelete('cascade');
            $table->enum('mode', ['otomatis', 'manual']);
            $table->enum('status', ['berhasil', 'gagal']);
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->text('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_penyiramans');
    }
};
