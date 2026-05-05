@extends('layouts.app')

@section('title', 'Riwayat Penyiraman')
@section('page-title', 'Riwayat Penyiraman')
@section('page-subtitle', 'Catatan aktivitas penyiraman otomatis dan manual')

@section('page-actions')
<a href="{{ route('riwayat.export', request()->query()) }}" class="btn-export">
    Unduh CSV
</a>
@endsection

@push('styles')
<style>
    /* ── Filter ── */
    .filter-card {
        background: #FFFFFF;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid #E5E0D5;
        margin-bottom: 24px;
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
        font-size: 12px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-control {
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid #D5D0C5;
        font-family: inherit;
        font-size: 14px;
        background: #fff;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--text);
    }
    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .btn-filter {
        background: #0D0D0D;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 999px;
        font-weight: 700;
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        white-space: nowrap;
    }
    .btn-filter:hover { background: #333; }
    .btn-reset {
        color: #888;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-reset:hover { color: var(--text); }

    /* ── Table ── */
    .history-card {
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #E5E0D5;
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
        background: #F0EBE0;
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #E5E0D5;
        font-family: 'Sora', sans-serif;
    }
    td {
        padding: 16px 20px;
        border-bottom: 1px solid #F5F0E8;
        font-size: 14px;
        color: #555;
        vertical-align: middle;
    }
    tr:nth-child(even) td { background: #FAFAF7; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #F5F5F0; }

    .badge-status {
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-berhasil { background: rgba(101,163,13,.1); color: #65A30D; }
    .status-gagal { background: rgba(217,119,6,.1); color: #D97706; }
    .status-berjalan { background: var(--accent); color: #0D0D0D; }

    .btn-export {
        background: var(--accent);
        color: #0D0D0D;
        padding: 10px 20px;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: 0.2s;
    }
    .btn-export:hover { opacity: 0.9; }

    .pagination-wrap {
        padding: 20px;
        display: flex;
        justify-content: center;
    }

    .empty-row td {
        text-align: center;
        padding: 80px 20px !important;
        color: #888;
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
            <button type="submit" class="btn-filter">Filter</button>
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
                        <div style="font-size: 13px; color: #888;">{{ $row->sensor->lokasi ?? '-' }}</div>
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
