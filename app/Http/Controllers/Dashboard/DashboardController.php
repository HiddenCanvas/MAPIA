<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\RiwayatPenyiraman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil semua sensor milik user beserta data terbaru
        $sensors = Sensor::where('id_user', $userId)
            ->with(['riwayat_sensors' => function ($q) {
                $q->latest('created_at')->limit(1);
            }, 'parameterPenyiraman'])
            ->get();

        // Build sensorData: gabung sensor + latest reading
        $sensorData = $sensors->map(function ($sensor) {
            $latest = $sensor->riwayat_sensors->first();
            return (object) [
                'id_sensor'    => $sensor->id_sensor,
                'nama_sensor'  => $sensor->nama_sensor,
                'lokasi'       => $sensor->lokasi,
                'status'       => $sensor->status,
                'kelembapan'   => $latest->kelembapan ?? 0,
                'ph_tanah'     => $latest->ph_tanah ?? 0,
                'created_at'   => $latest->created_at ?? null,
                'mode_auto'    => $sensor->parameterPenyiraman->mode_auto ?? false,
            ];
        });

        // Penyiraman sedang berlangsung (waktu_selesai null)
        $penyiramanAktifIds = RiwayatPenyiraman::whereIn('id_sensor', $sensors->pluck('id_sensor'))
            ->whereNull('waktu_selesai')
            ->pluck('id_sensor')
            ->toArray();

        $stats = [
            'total_sensor'      => $sensors->count(),
            'sensor_online'     => $sensors->where('status', true)->count(),
            'tanah_kering'      => $sensorData->filter(fn($s) => $s->kelembapan < 30)->count(),
            'penyiraman_aktif'  => count($penyiramanAktifIds),
        ];

        // Hitung notifikasi belum dibaca (kolom dibaca belum ada di schema, default 0)
        $unreadCount = 0;

        return view('dashboard.index', compact('sensorData', 'stats', 'unreadCount'));
    }
}
