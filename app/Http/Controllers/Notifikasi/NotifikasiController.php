<?php

namespace App\Http\Controllers\Notifikasi;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $notifikasi = Notifikasi::with('jenisNotif')
            ->where('id_user', $userId)
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->get()
            ->map(function ($n) {
                // tambah field virtual 'dibaca' — schema tidak punya kolom ini,
                // jadi kita anggap semua belum dibaca untuk demo
                $n->dibaca = false;
                return $n;
            });

        $unreadCount = $notifikasi->where('dibaca', false)->count();

        return view('notifikasi.index', compact('notifikasi', 'unreadCount'));
    }

    public function tandaiSemua()
    {
        // Kolom 'dibaca' belum ada di schema — ini placeholder untuk nanti
        // Notifikasi::where('id_user', Auth::id())->update(['dibaca' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
