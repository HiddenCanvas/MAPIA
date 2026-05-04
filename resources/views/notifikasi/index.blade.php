@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', '🔔 Notifikasi')
@section('page-subtitle', 'Peringatan dan pesan otomatis dari sensor Anda')

@section('page-actions')
<form action="{{ route('notifikasi.tandai-semua') }}" method="POST">
    @csrf
    <button type="submit" class="btn-mark">
        ✔️ Tandai Semua Dibaca
    </button>
</form>
@endsection

@push('styles')
<style>
    .notif-list {
        max-width: 900px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .notif-item {
        background: #fff;
        padding: 28px;
        border-radius: 24px;
        border: 2px solid rgba(0,0,0,0.05);
        display: flex;
        gap: 20px;
        align-items: flex-start;
        transition: all 0.2s ease;
    }
    .notif-item:hover {
        border-color: var(--secondary);
        transform: translateX(4px);
    }
    .notif-item.unread {
        border-left: 6px solid var(--accent);
        background: #fffcf0;
    }

    .notif-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }
    .icon-kering { background: #fee2e2; }
    .icon-basah { background: #dbeafe; }
    .icon-ph { background: #fef3c7; }
    .icon-info { background: #f3f4f6; }

    .notif-content {
        flex: 1;
        min-width: 0;
    }
    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
        gap: 12px;
    }
    .notif-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--primary);
    }
    .notif-time {
        font-size: 13px;
        color: #aaa;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .notif-body {
        font-size: 16px;
        color: #555;
        line-height: 1.6;
    }

    .btn-mark {
        background: var(--secondary);
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 700;
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-mark:hover { background: var(--primary); }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #aaa;
    }
    .empty-state h2 { color: #888; margin-bottom: 8px; }

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
        $icon = '🔔';
        $iconClass = 'icon-info';
        $isi = $n->isi_data ?? '';
        if (str_contains($isi, 'Kering') || str_contains($isi, 'kering')) {
            $icon = '🌵'; $iconClass = 'icon-kering';
        } elseif (str_contains($isi, 'Basah') || str_contains($isi, 'basah')) {
            $icon = '💧'; $iconClass = 'icon-basah';
        } elseif (str_contains($isi, 'pH') || str_contains($isi, 'ph')) {
            $icon = '🧪'; $iconClass = 'icon-ph';
        } elseif (str_contains($isi, 'Selesai') || str_contains($isi, 'selesai')) {
            $icon = '✅'; $iconClass = 'icon-info';
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
        <h2>🎉 Semua aman!</h2>
        <p>Tidak ada notifikasi baru saat ini.</p>
    </div>
    @endforelse
</div>
@endsection
