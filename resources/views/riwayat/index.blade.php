@extends('layouts.app')

@section('title', 'Riwayat Penyiraman')
@section('page-title', '📋 Riwayat Penyiraman')
@section('page-subtitle', 'Catatan aktivitas penyiraman otomatis dan manual')

@section('page-actions')
<a href="{{ route('riwayat.export', request()->query()) }}" class="btn-export">
    📥 Unduh CSV
</a>
@endsection

@push('styles')
<style>
    /* ── Filter ── */
    .filter-card {
        background: #fff;
        padding: 28px;
        border-radius: 24px;
        border: 2px solid rgba(0,0,0,0.05);
        margin-bottom: 32px;
    }
    .filter-form {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        min-width: 160px;
    }
    .form-group label {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-control {
        padding: 14px 16px;
        border-radius: 14px;
        border: 2px solid #eee;
        font-family: inherit;
        font-size: 15px;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--secondary);
    }
    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .btn-filter {
        background: var(--secondary);
        color: #fff;
        border: none;
        padding: 14px 28px;
        border-radius: 14px;
        font-weight: 700;
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        white-space: nowrap;
    }
    .btn-filter:hover { background: var(--primary); }
    .btn-reset {
        color: #aaa;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-reset:hover { color: var(--primary); }

    /* ── Table ── */
    .history-card {
        background: #fff;
        border-radius: 24px;
        border: 2px solid rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }
    th {
        background: #f8f9fa;
        padding: 18px 20px;
        text-align: left;
        font-size: 13px;
        font-weight: 800;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eee;
    }
    td {
        padding: 20px;
        border-bottom: 1px solid #f5f5f5;
        font-size: 15px;
        color: var(--text);
        vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fdfcf0; }

    .badge-status {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .status-berhasil { background: #dcfce7; color: #15803d; }
    .status-gagal { background: #fee2e2; color: #b91c1c; }
    .status-berjalan { background: #fff3bf; color: #e67e22; }

    .btn-export {
        background: var(--accent);
        color: #fff;
        padding: 12px 24px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: 0.2s;
    }
    .btn-export:hover { opacity: 0.9; }

    .pagination-wrap {
        padding: 24px;
        display: flex;
        justify-content: center;
    }

    .empty-row td {
        text-align: center;
        padding: 80px 20px !important;
        color: #aaa;
    }

    @media (max-width: 600px) {
        .filter-form { flex-direction: column; }
        .form-group { min-width: auto; }
    }
</style>
@endpush

@section('content')

{{-- Filters --}}
<div class="filter-card">
    <form action="{{ route('riwayat.index') }}" method="GET" class="filter-form">
        <div class="form-group">
            <label>Sensor</label>
            <select name="sensor" class="form-control">
                <option value="">Semua Sensor</option>
                @foreach($sensors as $s)
                    <option value="{{ $s->id_sensor }}" {{ request('sensor') == $s->id_sensor ? 'selected' : '' }}>
                        {{ $s->nama_sensor }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Mode</label>
            <select name="mode" class="form-control">
                <option value="">Semua Mode</option>
                <option value="otomatis" {{ request('mode') == 'otomatis' ? 'selected' : '' }}>Otomatis</option>
                <option value="manual" {{ request('mode') == 'manual' ? 'selected' : '' }}>Manual</option>
            </select>
        </div>
        <div class="form-group">
            <label>Dari Tanggal</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
        </div>
        <div class="form-group">
            <label>Hingga Tanggal</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-filter">🔍 Filter</button>
            <a href="{{ route('riwayat.index') }}" class="btn-reset">Reset</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="history-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Sensor / Lokasi</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $row)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: var(--primary);">
                            {{ $row->waktu_mulai->format('d M Y') }}
                        </div>
                        <div style="font-size: 13px; color: #aaa;">
                            {{ $row->waktu_mulai->format('H:i') }} WIB
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 700;">{{ $row->sensor->nama_sensor ?? '-' }}</div>
                        <div style="font-size: 13px; color: #aaa;">📍 {{ $row->sensor->lokasi ?? '-' }}</div>
                    </td>
                    <td>
                        <span style="font-weight: 600; color: {{ $row->mode == 'otomatis' ? '#e67e22' : '#3498db' }};">
                            {{ ucfirst($row->mode) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status status-{{ $row->status }}">
                            {{ ucfirst($row->status) }}
                        </span>
                    </td>
                    <td style="max-width: 250px; font-size: 14px; color: #888;">
                        {{ $row->keterangan ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="5">
                        <h3 style="color: #888; margin-bottom: 8px;">Belum ada catatan riwayat</h3>
                        <p>Aktivitas penyiraman akan muncul di sini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($riwayat->hasPages())
    <div class="pagination-wrap">
        {{ $riwayat->links() }}
    </div>
    @endif
</div>
@endsection
