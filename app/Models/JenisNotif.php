<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisNotif extends Model
{
    protected $table = 'jenis_notifs';
    protected $primaryKey = 'id_jenis_notif';
    public $timestamps = false;

    protected $fillable = ['kategori', 'keterangan'];
    
}
