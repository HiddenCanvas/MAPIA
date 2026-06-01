<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;
use App\Models\RiwayatSensor;
use Carbon\Carbon;

#[Signature('app:mqtt-subscribe-command')]
#[Description('Subscribe to sensor data from HiveMQ and store it in the database')]
class MqttSubscribeCommand extends Command
{
    public function handle()
    {
        // Topic wildcard: mapia/sensor/+/data
        $topic = 'mapia/sensor/+/data';

        // Callback ketika pesan tiba
        $callback = function (string $topic, string $message) {
            // Extract MAC address dari topic
            // contoh topic: mapia/sensor/AA:BB:CC:DD:EE:FF/data
            $parts = explode('/', $topic);
            $mac   = $parts[2] ?? 'unknown';

            // Parse JSON payload
            $payload = json_decode($message, true);
            if (!is_array($payload)) {
                $this->error("Payload not JSON on topic $topic");
                return;
            }

            // Simpan ke tabel riwayat_sensors
            RiwayatSensor::create([
                'id_sensor'   => $this->sensorIdFromMac($mac),   // fungsi bantu di bawah
                'kelembapan'  => $payload['kelembapan'] ?? null,
                'ph_tanah'    => $payload['ph_tanah'] ?? null,
                'created_at'  => Carbon::now(),
            ]);

            $this->info("Data saved for sensor $mac");
        };

        // Mulai subscribe – gunakan nama koneksi default (config/mqtt-client.php)
        MQTT::subscribe($topic, $callback, 0);

        // Loop event (blocking) – Ctrl‑C untuk menghentikan
        $this->info('Listening to MQTT topic: ' . $topic);
        MQTT::loop(true);
    }

    /** Cari id_sensor berdasarkan MAC address (Anda dapat sesuaikan) */
    private function sensorIdFromMac(string $mac): int
    {
        // Asumsi tabel sensors memiliki kolom `mac_address`.
        $sensor = \App\Models\Sensor::where('mac_address', $mac)->first();

        return $sensor ? $sensor->id_sensor : 0; // 0 → akan gagal insert bila tidak ada
    }
}
