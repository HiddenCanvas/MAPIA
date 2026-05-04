@extends('layouts.app')

@section('title', 'Kontrol Penyiraman')
@section('page-title', '💧 Kontrol Penyiraman')
@section('page-subtitle', 'Kendalikan pompa air untuk setiap sensor secara langsung')

@push('styles')
<style>
    .sensor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 28px;
    }
    .sensor-card {
        background: #fff;
        border-radius: 24px;
        border: 2px solid rgba(0,0,0,0.06);
        overflow: hidden;
        transition: all 0.25s ease;
    }
    .sensor-card:hover {
        border-color: var(--secondary);
        box-shadow: 0 8px 28px rgba(0,0,0,0.07);
    }
    .sensor-card-head {
        background: var(--primary);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .sensor-name {
        color: #fff;
        font-size: 18px;
        font-weight: 700;
    }
    .sensor-loc {
        color: rgba(255,255,255,0.6);
        font-size: 13px;
        margin-top: 4px;
    }
    .sensor-status-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #4ade80;
        flex-shrink: 0;
        box-shadow: 0 0 0 3px rgba(74,222,128,0.25);
    }
    .sensor-status-dot.offline {
        background: #9ca3af;
        box-shadow: none;
    }

    .sensor-card-body {
        padding: 24px;
    }

    /* Pump status */
    .pump-status {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 18px;
        border-radius: 14px;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .pump-on { background: #dcfce7; color: #16a34a; }
    .pump-off { background: #f3f4f6; color: #6b7280; }
    .drip-wrap { display: flex; gap: 5px; align-items: flex-end; height: 20px; }
    .drip {
        width: 5px;
        border-radius: 99px;
        background: #3b82f6;
        animation: drip-anim 1.2s ease-in-out infinite;
    }
    .drip:nth-child(1) { height: 16px; }
    .drip:nth-child(2) { animation-delay: 0.2s; height: 12px; }
    .drip:nth-child(3) { animation-delay: 0.4s; height: 8px; }
    @keyframes drip-anim {
        0%, 100% { opacity: 1; transform: translateY(0); }
        50% { opacity: 0.4; transform: translateY(4px); }
    }

    /* Data readings */
    .data-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .data-label {
        font-size: 15px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .data-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
    }
    .data-value.warn { color: #ef4444; }
    .data-value.good { color: #16a34a; }

    .progress-bar {
        background: #e5e7eb;
        border-radius: 99px;
        height: 10px;
        overflow: hidden;
        margin: 6px 0 20px;
    }
    .progress-fill {
        height: 100%;
        border-radius: 99px;
        transition: width 0.5s ease;
    }
    .prog-dry { background: #ef4444; }
    .prog-ok { background: #22c55e; }
    .prog-wet { background: #3b82f6; }

    .divider {
        border: none;
        border-top: 1px solid #f0ede0;
        margin: 20px 0;
    }

    /* Mode toggle */
    .mode-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .mode-lbl {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
    }
    .switch-form {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 56px;
        height: 28px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider-sw {
        position: absolute;
        inset: 0;
        background: #ccc;
        border-radius: 28px;
        cursor: pointer;
        transition: 0.3s;
    }
    .slider-sw:before {
        content: '';
        position: absolute;
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 4px;
        background: #fff;
        border-radius: 50%;
        transition: 0.3s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }
    input:checked + .slider-sw { background: var(--secondary); }
    input:checked + .slider-sw:before { transform: translateX(28px); }
    .switch-label {
        font-size: 14px;
        font-weight: 700;
        min-width: 80px;
    }

    /* Manual buttons */
    .manual-btns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .btn-on, .btn-off {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 16px 12px;
        border: none;
        border-radius: 14px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        min-height: 52px;
        font-family: 'Outfit', sans-serif;
        transition: all 0.2s;
        width: 100%;
    }
    .btn-on { background: #16a34a; color: #fff; }
    .btn-on:hover { background: #15803d; transform: translateY(-2px); }
    .btn-on:disabled { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; transform: none; }
    .btn-off { background: #dc2626; color: #fff; }
    .btn-off:hover { background: #b91c1c; transform: translateY(-2px); }
    .btn-off:disabled { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; transform: none; }

    /* Auto info */
    .auto-info {
        background: #f0fdf4;
        border: 2px solid #86efac;
        border-radius: 16px;
        padding: 18px 20px;
        font-size: 14px;
        color: #166534;
    }
    .auto-info-title {
        font-weight: 700;
        margin-bottom: 8px;
        font-size: 15px;
    }
    .auto-info-row {
        display: flex;
        justify-content: space-between;
        margin-top: 6px;
    }

    .no-data {
        text-align: center;
        padding: 80px 20px;
        color: #999;
        font-size: 16px;
    }

    @media (max-width: 600px) {
        .sensor-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="sensor-grid">
    @forelse($sensors as $sensor)
    @php
        $latest = $sensor->riwayat_sensors->last();
        $kel    = $latest->kelembapan ?? 0;
        $ph     = $latest->ph_tanah ?? 0;
        $progClass = $kel < 30 ? 'prog-dry' : ($kel > 70 ? 'prog-wet' : 'prog-ok');
        $param  = $sensor->parameterPenyiraman ?? null;
        $modeAuto = $param->mode_auto ?? false;
        $pumpActive = isset($penyiramanAktif[$sensor->id_sensor]) && $penyiramanAktif[$sensor->id_sensor];
        $online = $sensor->status;
    @endphp
    <div class="sensor-card">
        <div class="sensor-card-head">
            <div>
                <div class="sensor-name">{{ $sensor->nama_sensor }}</div>
                <div class="sensor-loc">📍 {{ $sensor->lokasi ?? 'Lokasi tidak diset' }}</div>
            </div>
            <div class="sensor-status-dot {{ $online ? '' : 'offline' }}" title="{{ $online ? 'Online' : 'Offline' }}"></div>
        </div>
        <div class="sensor-card-body">
            {{-- Pump status --}}
            @if($pumpActive)
            <div class="pump-status pump-on">
                💧 Pompa Sedang Menyiram
                <div class="drip-wrap">
                    <div class="drip"></div><div class="drip"></div><div class="drip"></div>
                </div>
            </div>
            @else
            <div class="pump-status pump-off">
                ⏸ Pompa Tidak Aktif
            </div>
            @endif

            {{-- Data sensor --}}
            <div class="data-row">
                <span class="data-label">💧 Kelembapan</span>
                <span class="data-value {{ $kel < 30 ? 'warn' : 'good' }}">{{ number_format($kel, 1) }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill {{ $progClass }}" style="width:{{ min(100, $kel) }}%"></div>
            </div>
            <div class="data-row">
                <span class="data-label">🧪 pH Tanah</span>
                <span class="data-value">{{ number_format($ph, 1) }}</span>
            </div>

            <hr class="divider">

            {{-- Mode toggle --}}
            <div class="mode-row">
                <span class="mode-lbl">Mode Penyiraman</span>
            </div>
            <form method="POST" action="{{ route('monitoring.toggle-mode', $sensor->id_sensor) }}" style="margin-bottom: 20px;">
                @csrf
                @method('PATCH')
                <div class="switch-form">
                    <span class="switch-label" style="color:{{ $modeAuto ? '#aaa' : 'var(--text)' }}">🖐 Manual</span>
                    <label class="switch">
                        <input type="checkbox" name="mode_auto" value="1"
                            {{ $modeAuto ? 'checked' : '' }}
                            onchange="this.form.submit()" aria-label="Toggle mode otomatis">
                        <span class="slider-sw"></span>
                    </label>
                    <span class="switch-label" style="color:{{ $modeAuto ? 'var(--secondary)' : '#aaa' }}">⚡ Otomatis</span>
                </div>
            </form>

            {{-- Auto: show threshold info --}}
            @if($modeAuto)
            <div class="auto-info">
                <div class="auto-info-title">⚡ Mode Otomatis Aktif</div>
                <div class="auto-info-row">
                    <span>Kelembapan Min:</span>
                    <strong>{{ number_format($param->min_kelembapan ?? 0, 1) }}%</strong>
                </div>
                <div class="auto-info-row">
                    <span>Kelembapan Maks:</span>
                    <strong>{{ number_format($param->max_kelembapan ?? 0, 1) }}%</strong>
                </div>
                <div class="auto-info-row">
                    <span>pH Min–Maks:</span>
                    <strong>{{ $param->min_ph ?? 0 }} – {{ $param->max_ph ?? 14 }}</strong>
                </div>
            </div>
            @else
            {{-- Manual: ON / OFF buttons --}}
            <div class="manual-btns">
                <form method="POST" action="{{ route('monitoring.nyalakan', $sensor->id_sensor) }}">
                    @csrf
                    <button type="submit" class="btn-on" {{ $pumpActive ? 'disabled' : '' }}>
                        ▶ Nyalakan Pompa
                    </button>
                </form>
                <form method="POST" action="{{ route('monitoring.matikan', $sensor->id_sensor) }}">
                    @csrf
                    <button type="submit" class="btn-off" {{ !$pumpActive ? 'disabled' : '' }}>
                        ⏹ Matikan Pompa
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="no-data" style="grid-column: 1/-1;">
        📡 Tidak ada sensor yang terdaftar.<br>
        <small>Hubungi administrator untuk menambah sensor.</small>
    </div>
    @endforelse
</div>
@endsection
