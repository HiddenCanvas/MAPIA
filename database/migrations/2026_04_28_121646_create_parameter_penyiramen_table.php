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
        Schema::create('parameter_penyiramans', function (Blueprint $table) {
            $table->id('id_parameter');
            $table->foreignId('id_sensor')->constrained('sensors', 'id_sensor')->onDelete('cascade');
            $table->float('min_kelembapan');
            $table->float('max_kelembapan');
            $table->bigInteger('min_ph');
            $table->bigInteger('max_ph');
            $table->boolean('mode_auto');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_penyiramen');
    }
};
