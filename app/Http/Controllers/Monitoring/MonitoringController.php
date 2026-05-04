<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\RiwayatPenyiraman;
use App\Models\ParameterPenyiraman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $sensors = Sensor::where('id_user', $userId)
            ->with([
                'riwayat_sensors' => fn($q) => $q->latest('created_at')->limit(1),
                'parameterPenyiraman',
            ])
            ->get();

        // Sensor mana yang pompanya sedang aktif (waktu_selesai null)
        $aktifIds = RiwayatPenyiraman::whereIn('id_sensor', $sensors->pluck('id_sensor'))
            ->whereNull('waktu_selesai')
            ->pluck('id_sensor')
            ->toArray();

        $penyiramanAktif = array_fill_keys($aktifIds, true);

        return view('monitoring.kontrol', compact('sensors', 'penyiramanAktif'));
    }

    public function toggleMode(Request $request, $id)
    {
        $sensor = Sensor::where('id_user', Auth::id())->findOrFail($id);
        $param  = ParameterPenyiraman::where('id_sensor', $id)->firstOrFail();

        $param->update([
            'mode_auto' => $request->has('mode_auto') ? true : false,
        ]);

        return back()->with('success', 'Mode penyiraman berhasil diubah.');
    }

    public function nyalakan($id)
    {
        $sensor = Sensor::where('id_user', Auth::id())->findOrFail($id);

        // Cek apakah sudah ada yang aktif
        $sudahAktif = RiwayatPenyiraman::where('id_sensor', $id)
            ->whereNull('waktu_selesai')
            ->exists();

        if (!$sudahAktif) {
            RiwayatPenyiraman::create([
                'id_sensor'    => $id,
                'mode'         => 'manual',
                'status'       => 'berhasil',
                'waktu_mulai'  => now(),
                'waktu_selesai'=> null,
                'keterangan'   => 'Dinyalakan manual oleh pengguna',
            ]);
        }

        return back()->with('success', 'Pompa berhasil dinyalakan.');
    }

    public function matikan($id)
    {
        $sensor = Sensor::where('id_user', Auth::id())->findOrFail($id);

        // Tutup semua sesi penyiraman aktif untuk sensor ini
        RiwayatPenyiraman::where('id_sensor', $id)
            ->whereNull('waktu_selesai')
            ->update(['waktu_selesai' => now()]);

        return back()->with('success', 'Pompa berhasil dimatikan.');
    }
}
