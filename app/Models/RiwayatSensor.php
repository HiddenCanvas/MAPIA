<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatSensor extends Model
{
protected $table = 'riwayat_sensors';
    protected $primaryKey = 'id_riwayat_sensor';
    public $timestamps = false; // Karena hanya pakai created_at di migration

    protected $fillable = ['id_sensor', 'kelembapan', 'ph_tanah', 'created_at'];

    }
