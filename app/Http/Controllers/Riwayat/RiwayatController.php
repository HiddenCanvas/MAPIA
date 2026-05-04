<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPenyiraman;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $userId  = Auth::id();
        $sensors = Sensor::where('id_user', $userId)->get();

        $query = RiwayatPenyiraman::with('sensor')
            ->whereHas('sensor', fn($q) => $q->where('id_user', $userId))
            ->orderBy('waktu_mulai', 'desc');

        // Filter sensor
        if ($request->filled('sensor')) {
            $query->where('id_sensor', $request->sensor);
        }

        // Filter mode
        if ($request->filled('mode') && in_array($request->mode, ['otomatis', 'manual'])) {
            $query->where('mode', $request->mode);
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('waktu_mulai', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('waktu_mulai', '<=', $request->sampai);
        }

        $riwayat = $query->paginate(20)->withQueryString();

        return view('riwayat.index', compact('riwayat', 'sensors'));
    }

    public function export(Request $request)
    {
        $userId = Auth::id();

        $query = RiwayatPenyiraman::with('sensor')
            ->whereHas('sensor', fn($q) => $q->where('id_user', $userId))
            ->orderBy('waktu_mulai', 'desc');

        if ($request->filled('sensor'))  $query->where('id_sensor', $request->sensor);
        if ($request->filled('mode'))    $query->where('mode', $request->mode);
        if ($request->filled('dari'))    $query->whereDate('waktu_mulai', '>=', $request->dari);
        if ($request->filled('sampai'))  $query->whereDate('waktu_mulai', '<=', $request->sampai);

        $data = $query->get();

        $filename = 'riwayat-penyiraman-'.now()->format('Ymd-His').'.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            // BOM untuk Excel agar bisa baca UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['Waktu Mulai', 'Waktu Selesai', 'Sensor', 'Lokasi', 'Mode', 'Status', 'Keterangan']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->waktu_mulai,
                    $row->waktu_selesai ?? '-',
                    $row->sensor->nama_sensor ?? '-',
                    $row->sensor->lokasi ?? '-',
                    ucfirst($row->mode),
                    ucfirst($row->status),
                    $row->keterangan ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
