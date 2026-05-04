<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPenyiraman extends Model
{
    protected $table      = 'riwayat_penyiramans';
    protected $primaryKey = 'id_riwayat_penyiraman';
    public    $timestamps = false;

    protected $fillable   = ['id_sensor', 'mode', 'status', 'waktu_mulai', 'waktu_selesai', 'keterangan'];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'id_sensor', 'id_sensor');
    }
}
