<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatSensor;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        try {
            // 1. Validasi: Pastikan id_sensor ada di tabel sensors
            $validated = $request->validate([
                'id_sensor'  => 'required|integer|exists:sensors,id_sensor',
                'kelembapan' => 'required|numeric',
                'ph_tanah'   => 'required|numeric',
            ]);

            // 2. Simpan ke Database
            $data = RiwayatSensor::create([
                'id_sensor'  => $validated['id_sensor'],
                'kelembapan' => $validated['kelembapan'],
                'ph_tanah'   => $validated['ph_tanah'],
                'created_at' => now(), // Karena $timestamps = false di model
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Data sensor berhasil disimpan',
                'data'    => $data
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 422);
        }    
    }
}
