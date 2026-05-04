@extends('layouts.app')

@section('title', 'Pengaturan Parameter')
@section('page-title', '⚙️ Pengaturan Parameter')
@section('page-subtitle', 'Tentukan ambang batas otomatisasi penyiraman')

@push('styles')
<style>
    .param-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 28px;
    }
    .param-card {
        background: #fff;
        border-radius: 24px;
        padding: 32px;
        border: 2px solid rgba(0,0,0,0.06);
        transition: all 0.25s ease;
    }
    .param-card:hover {
        border-color: var(--secondary);
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.06);
    }
    .param-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f0f0f0;
    }
    .sensor-info h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 4px;
    }
    .sensor-info p {
        font-size: 14px;
        color: #999;
    }
    .mode-badge {
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
    }
    .mode-auto { background: #fff3bf; color: #e67e22; }
    .mode-manual { background: #e7f5ff; color: #339af0; }

    .threshold-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 28px;
    }
    .threshold-item {
        background: #f8f9fa;
        padding: 22px 18px;
        border-radius: 18px;
        text-align: center;
    }
    .threshold-label {
        font-size: 13px;
        color: #888;
        font-weight: 600;
        display: block;
        margin-bottom: 10px;
    }
    .threshold-range {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
    }
    .threshold-unit {
        font-size: 14px;
        color: #aaa;
        font-weight: 500;
    }

    .btn-edit-full {
        display: block;
        width: 100%;
        text-align: center;
        background: var(--primary);
        color: #fff;
        padding: 18px;
        border-radius: 16px;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.2s;
    }
    .btn-edit-full:hover {
        background: var(--secondary);
        transform: translateY(-2px);
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        color: #aaa;
    }
    .empty-state h2 { font-size: 22px; margin-bottom: 8px; color: #888; }

    @media (max-width: 500px) {
        .param-grid { grid-template-columns: 1fr; }
        .threshold-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="param-grid">
    @forelse($parameter as $p)
    <div class="param-card">
        <div class="param-header">
            <div class="sensor-info">
                <h3>{{ $p->sensor->nama_sensor ?? 'Sensor Tanpa Nama' }}</h3>
                <p>📍 {{ $p->sensor->lokasi ?? 'Lokasi Umum' }}</p>
            </div>
            <span class="mode-badge {{ $p->mode_auto ? 'mode-auto' : 'mode-manual' }}">
                {{ $p->mode_auto ? '⚡ Otomatis' : '🖐 Manual' }}
            </span>
        </div>

        <div class="threshold-grid">
            <div class="threshold-item">
                <span class="threshold-label">💧 Kelembapan Min</span>
                <div class="threshold-range">
                    {{ number_format($p->min_kelembapan, 0) }}<span class="threshold-unit">%</span>
                </div>
            </div>
            <div class="threshold-item">
                <span class="threshold-label">💧 Kelembapan Maks</span>
                <div class="threshold-range">
                    {{ number_format($p->max_kelembapan, 0) }}<span class="threshold-unit">%</span>
                </div>
            </div>
            <div class="threshold-item">
                <span class="threshold-label">🧪 pH Min</span>
                <div class="threshold-range">{{ number_format($p->min_ph, 1) }}</div>
            </div>
            <div class="threshold-item">
                <span class="threshold-label">🧪 pH Maks</span>
                <div class="threshold-range">{{ number_format($p->max_ph, 1) }}</div>
            </div>
        </div>

        <a href="{{ route('parameter.edit', $p->id_parameter) }}" class="btn-edit-full">
            ✏️ Ubah Pengaturan
        </a>
    </div>
    @empty
    <div class="empty-state">
        <h2>Belum ada parameter.</h2>
        <p>Silakan daftarkan sensor di menu manajemen.</p>
    </div>
    @endforelse
</div>
@endsection
