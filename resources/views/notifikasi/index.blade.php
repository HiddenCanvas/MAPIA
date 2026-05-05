@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Peringatan dan pesan otomatis dari sensor Anda')

@section('page-actions')
<form action="{{ route('notifikasi.tandai-semua') }}" method="POST">
    @csrf
    <button type="submit" class="btn-mark">
        Tandai Semua Dibaca
    </button>
</form>
@endsection

@push('styles')
<style>
    .notif-list {
        max-width: 900px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .notif-item {
        background: #FFFFFF;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #E5E0D5;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        transition: all 0.2s ease;
    }
    .notif-item:hover {
        border-color: var(--text);
    }
    .notif-item.unread {
        border-left: 4px solid var(--accent);
        background: #F5F0E8;
    }

    .notif-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .icon-kering { background: rgba(217,119,6,.1); }
    .icon-basah { background: rgba(2,132,199,.1); }
    .icon-ph { background: rgba(101,163,13,.1); }
    .icon-info { background: #E5E0D5; }

    .notif-content {
        flex: 1;
        min-width: 0;
    }
    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
        gap: 12px;
    }
    .notif-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        font-family: 'Sora', sans-serif;
    }
    .notif-time {
        font-size: 12px;
        color: #888;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .notif-body {
        font-size: 14px;
        color: #666;
        line-height: 1.5;
    }

    .btn-mark {
        background: var(--text);
        color: #FFFFFF;
        border: none;
        padding: 10px 20px;
        border-radius: 999px;
        font-weight: 600;
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-mark:hover { background: #333; }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #888;
    }
    .empty-state h2 { color: var(--text); margin-bottom: 8px; font-family: 'Sora', sans-serif; }

    @media (max-width: 600px) {
        .notif-item { flex-direction: column; gap: 14px; padding: 20px; }
        .notif-icon { width: 48px; height: 48px; font-size: 22px; }
    }
</style>
@endpush

@section('content')
<div class="notif-list">
    @forelse($notifikasi as $n)
    @php
        $icon = 'i';
        $iconClass = 'icon-info';
        $isi = $n->isi_data ?? '';
        if (str_contains($isi, 'Kering') || str_contains($isi, 'kering')) {
            $icon = '!'; $iconClass = 'icon-kering';
        } elseif (str_contains($isi, 'Basah') || str_contains($isi, 'basah')) {
            $icon = '~'; $iconClass = 'icon-basah';
        } elseif (str_contains($isi, 'pH') || str_contains($isi, 'ph')) {
            $icon = 'pH'; $iconClass = 'icon-ph';
        } elseif (str_contains($isi, 'Selesai') || str_contains($isi, 'selesai')) {
            $icon = 'ok'; $iconClass = 'icon-info';
        }
    @endphp
    <div class="notif-item {{ ($n->dibaca ?? false) ? '' : 'unread' }}">
        <div class="notif-icon {{ $iconClass }}">{{ $icon }}</div>
        <div class="notif-content">
            <div class="notif-header">
                <span class="notif-title">{{ $n->jenisNotif->keterangan ?? 'Pesan Sistem' }}</span>
                <span class="notif-time">{{ $n->tanggal }} — {{ $n->waktu }}</span>
            </div>
            <div class="notif-body">{{ $isi }}</div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <h2>Semua aman!</h2>
        <p>Tidak ada notifikasi baru saat ini.</p>
    </div>
    @endforelse
</div>
@endsection
