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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id('id_notif');
            $table->foreignId('id_jenis_notif')->constrained('jenis_notifs', 'id_jenis_notif');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('isi_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
