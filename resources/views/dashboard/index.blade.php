@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page-title', 'Dashboard Utama')
@section('page-subtitle', 'Pantauan kebun Anda dalam satu tampilan')

@section('page-actions')
<button onclick="window.location.reload()" class="btn-refresh">
    Perbarui Data
</button>
@endsection

@push('styles')
<style>
    /* ── Welcome Banner ── */
    .welcome-banner {
        background: #0D0D0D;
        border: 1px solid #E5E0D5;
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 18px;
        font-weight: 600;
        color: #FFFFFF;
        font-family: 'Sora', sans-serif;
    }
    .welcome-banner .emoji { font-size: 28px; }

    /* ── Stat Cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: #FFFFFF;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #E5E0D5;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        border-color: var(--text);
        transform: translateY(-4px);
    }
    .stat-card .stat-label {
        font-size: 14px;
        font-weight: 600;
        color: #666;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .stat-card .stat-value {
        font-size: 40px;
        font-weight: 800;
        line-height: 1;
        font-family: 'Sora', sans-serif;
    }
    .stat-card.primary-card {
        background: #0D0D0D;
    }
    .stat-card.primary-card .stat-label { color: #888; }
    .stat-card.primary-card .stat-value { color: #FFFFFF; }

    /* ── Section Headers ── */
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* ── Sensor Cards ── */
    .sensor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 16px;
        margin-bottom: 40px;
    }
    .sensor-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #E5E0D5;
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
    }
    .sensor-card:hover {
        border-color: var(--text);
    }
    .sensor-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--text);
    }
    .sensor-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    .sensor-card .card-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .sensor-card .card-header p {
        font-size: 13px;
        color: #888;
    }
    .badge-status {
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-aktif { background: var(--accent); color: #0D0D0D; }
    .badge-mati { background: #E5E0D5; color: #666; }

    .sensor-readings {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }
    .reading-box {
        background: #F5F0E8;
        padding: 16px 12px;
        border-radius: 12px;
        text-align: center;
    }
    .reading-box .reading-value {
        font-size: 26px;
        font-weight: 800;
        display: block;
        color: var(--text);
        margin-bottom: 4px;
        font-family: 'Sora', sans-serif;
    }
    .reading-box .reading-label {
        font-size: 12px;
        color: #666;
        font-weight: 600;
    }

    .sensor-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid #E5E0D5;
    }
    .sensor-footer .time {
        font-size: 12px;
        color: #888;
    }
    .sensor-footer .condition {
        font-weight: 700;
        font-size: 13px;
    }
    .condition-aman { color: #0D0D0D; }
    .condition-kering { color: #D97706; }

    /* ── IoT Preview ── */
    .iot-preview {
        background: #FFFFFF;
        color: var(--text);
        padding: 32px;
        border-radius: 16px;
        border: 1px solid #E5E0D5;
        text-align: center;
        margin-bottom: 32px;
    }
    .iot-preview h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .iot-preview p {
        color: #666;
        font-size: 14px;
    }
    .signal-bars {
        display: flex;
        justify-content: center;
        gap: 8px;
        height: 60px;
        align-items: flex-end;
        margin: 20px 0;
    }
    .signal-bar {
        width: 10px;
        background: var(--accent);
        border-radius: 4px;
        animation: signal-pulse 1.2s ease-in-out infinite alternate;
    }
    @keyframes signal-pulse {
        from { opacity: 0.3; transform: scaleY(0.7); }
        to   { opacity: 1;   transform: scaleY(1);   }
    }

    /* ── Btn Refresh ── */
    .btn-refresh {
        background: #0D0D0D;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 999px;
        font-weight: 600;
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-refresh:hover { background: #333; }

    /* ── Empty State ── */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        color: #aaa;
    }
    .empty-state h2 { font-size: 22px; margin-bottom: 8px; color: #888; }
    .empty-state p { font-size: 15px; }

    @media (max-width: 600px) {
        .stat-grid { grid-template-columns: 1fr 1fr; gap: 16px; }
        .sensor-grid { grid-template-columns: 1fr; }
        .stat-card .stat-value { font-size: 32px; }
    }
</style>
@endpush

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <span class="emoji">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
    </span>
    Selamat datang, {{ Auth::user()->nama }}. Kebun Anda terpantau aman hari ini.
</div>

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card primary-card">
        <div class="stat-label">Total Sensor</div>
        <div class="stat-value">{{ $stats['total_sensor'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sensor Online</div>
        <div class="stat-value" style="color: #65A30D;">{{ $stats['sensor_online'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Tanah Kering</div>
        <div class="stat-value" style="color: {{ $stats['tanah_kering'] > 0 ? '#D97706' : '#65A30D' }};">
            {{ $stats['tanah_kering'] }}
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Penyiraman</div>
        <div class="stat-value" style="color: #0284C7;">{{ $stats['penyiraman_aktif'] }}</div>
    </div>
</div>

{{-- Sensor List --}}
<div class="section-title">Kondisi Lokasi Kebun</div>

<div class="sensor-grid">
    @forelse($sensorData as $row)
    <div class="sensor-card">
        <div class="card-header">
            <div>
                <h3>{{ $row->nama_sensor }}</h3>
                <p>{{ $row->lokasi ?? 'Lokasi Umum' }}</p>
            </div>
            <span class="badge-status {{ $row->status ? 'badge-aktif' : 'badge-mati' }}">
                {{ $row->status ? 'Aktif' : 'Mati' }}
            </span>
        </div>

        <div class="sensor-readings">
            <div class="reading-box">
                <!-- // TODO: IoT — this moisture value is currently read from sensor_readings table
//             In Sprint 3, replace with live MQTT topic: mapia/sensor/moisture
//             ESP32 publishes every 30 seconds via WiFi to mosquitto broker -->
                <span class="reading-value">{{ number_format($row->kelembapan, 0) }}%</span>
                <span class="reading-label">Kelembapan</span>
            </div>
            <div class="reading-box">
                <!-- // TODO: IoT — this pH value is currently read from sensor_readings table
//             In Sprint 3, replace with live MQTT topic: mapia/sensor/ph
//             Trigger notification if ph_value > 7 (abnormal for papaya) -->
                <span class="reading-value">{{ number_format($row->ph_tanah, 1) }}</span>
                <span class="reading-label">pH Tanah</span>
            </div>
        </div>

        <div class="sensor-footer">
            <span class="time">
                {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->diffForHumans() : 'Belum ada data' }}
            </span>
            @if($row->kelembapan < 30)
                <span class="condition condition-kering">[!] Perlu Air</span>
            @else
                <span class="condition condition-aman">Aman</span>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state">
        <h2>Belum ada data sensor.</h2>
        <p>Hubungi admin untuk mendaftarkan alat IoT Anda.</p>
    </div>
    @endforelse
</div>

{{-- IoT Signal Preview --}}
<div class="section-title">Visualisasi IoT (Pratinjau)</div>
<div class="iot-preview">
    <h3>Sinyal dari Perangkat IoT Kebun</h3>
    <p>Data dienkripsi dan dikirim via LoRaWAN / MQTT</p>
    <div class="signal-bars">
        @for($i = 0; $i < 20; $i++)
            <div class="signal-bar" style="height: {{ rand(20, 90) }}%; animation-delay: {{ $i * 0.1 }}s;"></div>
        @endfor
    </div>
</div>

@endsection
