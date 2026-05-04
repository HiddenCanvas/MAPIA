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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id('id_sensor');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('nama_sensor');
            $table->string('mac_address');
            $table->string('lokasi')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Sensors');
    }
};
