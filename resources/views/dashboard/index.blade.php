@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page-title', '🌿 Dashboard Utama')
@section('page-subtitle', 'Pantauan kebun Anda dalam satu tampilan')

@section('page-actions')
<button onclick="window.location.reload()" class="btn-refresh">
    🔄 Perbarui Data
</button>
@endsection

@push('styles')
<style>
    /* ── Welcome Banner ── */
    .welcome-banner {
        background: linear-gradient(135deg, #fff9db 0%, #fff3bf 100%);
        border: 2px solid #fab005;
        padding: 24px 28px;
        border-radius: 20px;
        margin-bottom: 32px;
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 18px;
        font-weight: 600;
        color: #5c4300;
    }
    .welcome-banner .emoji { font-size: 28px; }

    /* ── Stat Cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    .stat-card {
        background: #fff;
        padding: 28px 24px;
        border-radius: 20px;
        border: 2px solid rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        border-color: var(--secondary);
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.06);
    }
    .stat-card .stat-label {
        font-size: 15px;
        font-weight: 600;
        color: #888;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .stat-card .stat-value {
        font-size: 44px;
        font-weight: 800;
        line-height: 1;
    }
    .stat-card.primary-card {
        background: var(--primary);
    }
    .stat-card.primary-card .stat-label { color: rgba(255,255,255,0.65); }
    .stat-card.primary-card .stat-value { color: #fff; }

    /* ── Section Headers ── */
    .section-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* ── Sensor Cards ── */
    .sensor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 24px;
        margin-bottom: 48px;
    }
    .sensor-card {
        background: #fff;
        border-radius: 24px;
        padding: 28px;
        border: 2px solid rgba(0,0,0,0.06);
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
    }
    .sensor-card:hover {
        border-color: var(--secondary);
        box-shadow: 0 8px 28px rgba(0,0,0,0.06);
    }
    .sensor-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: var(--secondary);
    }
    .sensor-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    .sensor-card .card-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .sensor-card .card-header p {
        font-size: 14px;
        color: #999;
    }
    .badge-status {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-aktif { background: #dcfce7; color: #15803d; }
    .badge-mati { background: #f3f4f6; color: #9ca3af; }

    .sensor-readings {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    .reading-box {
        background: #f8f9fa;
        padding: 20px 16px;
        border-radius: 16px;
        text-align: center;
    }
    .reading-box .reading-value {
        font-size: 30px;
        font-weight: 800;
        display: block;
        color: var(--text);
        margin-bottom: 4px;
    }
    .reading-box .reading-label {
        font-size: 13px;
        color: #888;
        font-weight: 600;
    }

    .sensor-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid #f0f0f0;
    }
    .sensor-footer .time {
        font-size: 13px;
        color: #aaa;
    }
    .sensor-footer .condition {
        font-weight: 700;
        font-size: 14px;
    }
    .condition-aman { color: #27ae60; }
    .condition-kering { color: #e67e22; }

    /* ── IoT Preview ── */
    .iot-preview {
        background: var(--primary);
        color: #fff;
        padding: 40px;
        border-radius: 24px;
        text-align: center;
        margin-bottom: 32px;
    }
    .iot-preview h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .iot-preview p {
        opacity: 0.7;
        font-size: 15px;
    }
    .signal-bars {
        display: flex;
        justify-content: center;
        gap: 8px;
        height: 80px;
        align-items: flex-end;
        margin: 24px 0;
    }
    .signal-bar {
        width: 12px;
        background: rgba(255,255,255,0.15);
        border-radius: 4px;
        animation: signal-pulse 1.2s ease-in-out infinite alternate;
    }
    @keyframes signal-pulse {
        from { opacity: 0.3; transform: scaleY(0.7); }
        to   { opacity: 1;   transform: scaleY(1);   }
    }

    /* ── Btn Refresh ── */
    .btn-refresh {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-refresh:hover { background: var(--secondary); }

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
    <span class="emoji">💡</span>
    Selamat datang, {{ Auth::user()->nama }}. Kebun Anda terpantau aman hari ini.
</div>

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card primary-card">
        <div class="stat-label">📡 Total Sensor</div>
        <div class="stat-value">{{ $stats['total_sensor'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">✅ Sensor Online</div>
        <div class="stat-value" style="color: #2ecc71;">{{ $stats['sensor_online'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">🌵 Tanah Kering</div>
        <div class="stat-value" style="color: {{ $stats['tanah_kering'] > 0 ? '#e67e22' : '#2ecc71' }};">
            {{ $stats['tanah_kering'] }}
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">💧 Penyiraman</div>
        <div class="stat-value" style="color: #3498db;">{{ $stats['penyiraman_aktif'] }}</div>
    </div>
</div>

{{-- Sensor List --}}
<div class="section-title">📍 Kondisi Lokasi Kebun</div>

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
                <span class="reading-value">{{ number_format($row->kelembapan, 0) }}%</span>
                <span class="reading-label">Kelembapan</span>
            </div>
            <div class="reading-box">
                <span class="reading-value">{{ number_format($row->ph_tanah, 1) }}</span>
                <span class="reading-label">pH Tanah</span>
            </div>
        </div>

        <div class="sensor-footer">
            <span class="time">
                {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->diffForHumans() : 'Belum ada data' }}
            </span>
            @if($row->kelembapan < 30)
                <span class="condition condition-kering">⚠️ Perlu Air</span>
            @else
                <span class="condition condition-aman">✅ Aman</span>
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
<div class="section-title">📡 Visualisasi IoT (Pratinjau)</div>
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
