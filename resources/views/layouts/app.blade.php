<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MAPIA — @yield('title', 'Dashboard')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@stack('styles')
<style>
:root{--text:#0a1a0a;--background:#fdfcf0;--primary:#1b4332;--secondary:#2d6a4f;--accent:#e67e22;--sidebar-w:220px;--topbar-h:64px}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{font-size:16px;height:100%}
body{font-family:'Outfit',sans-serif;background:var(--background);color:var(--text);min-height:100%;display:flex;font-size:15px;line-height:1.5;overflow:hidden}

/* ── SIDEBAR ── */
.sidebar{position:fixed;top:0;left:0;width:var(--sidebar-w);height:100vh;background:var(--primary);display:flex;flex-direction:column;z-index:200;transition:transform .3s ease}
.sidebar-logo{padding:22px 20px 18px;border-bottom:1px solid rgba(255,255,255,.12)}
.sidebar-logo a{display:flex;align-items:center;gap:10px;text-decoration:none;color:#fff;font-size:22px;font-weight:700;letter-spacing:-.3px}
.sidebar-logo span{font-size:26px}
.sidebar-nav{flex:1;padding:16px 0;overflow-y:auto}
.nav-label{font-size:11px;font-weight:600;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px;padding:12px 20px 6px}
.nav-item{display:flex;align-items:center;gap:12px;padding:12px 20px;color:rgba(255,255,255,.75);text-decoration:none;font-size:15px;font-weight:500;border-left:3px solid transparent;transition:all .2s;cursor:pointer;min-height:48px}
.nav-item:hover{background:rgba(255,255,255,.08);color:#fff;border-left-color:rgba(255,255,255,.3)}
.nav-item.active{background:rgba(255,255,255,.14);color:#fff;border-left-color:var(--accent)}
.nav-item .icon{font-size:20px;min-width:24px;text-align:center}
.nav-badge{margin-left:auto;background:var(--accent);color:#fff;font-size:11px;font-weight:700;padding:2px 7px;border-radius:10px;min-width:20px;text-align:center}
.sidebar-footer{padding:16px 20px;border-top:1px solid rgba(255,255,255,.12)}
.sidebar-user{display:flex;align-items:center;gap:10px;margin-bottom:12px}
.user-avatar{width:40px;height:40px;border-radius:50%;background:var(--secondary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px;flex-shrink:0;overflow:hidden}
.user-avatar img{width:100%;height:100%;object-fit:cover}
.user-info{flex:1;min-width:0}
.user-name{color:#fff;font-weight:600;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.user-role{color:rgba(255,255,255,.5);font-size:12px}
.btn-logout{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:10px;background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);border:1px solid rgba(255,255,255,.15);border-radius:8px;font-size:14px;font-weight:500;cursor:pointer;text-decoration:none;transition:all .2s;min-height:44px}
.btn-logout:hover{background:rgba(255,255,255,.18);color:#fff}

/* ── TOPBAR (mobile) ── */
.topbar{display:none;position:fixed;top:0;left:0;right:0;height:var(--topbar-h);background:var(--primary);align-items:center;justify-content:space-between;padding:0 16px;z-index:150;box-shadow:0 2px 8px rgba(0,0,0,.15)}
.topbar-logo{color:#fff;font-size:20px;font-weight:700;display:flex;align-items:center;gap:8px}
.hamburger{background:none;border:none;cursor:pointer;padding:8px;color:#fff;font-size:24px;min-height:44px;min-width:44px;display:flex;align-items:center;justify-content:center;border-radius:6px}
.hamburger:hover{background:rgba(255,255,255,.1)}

/* ── MAIN CONTENT ── */
.main-wrap{margin-left:var(--sidebar-w);height:100vh;display:flex;flex-direction:column;flex:1;overflow-y:auto;overflow-x:hidden}
.page-header{background:#fff;border-bottom:1px solid rgba(0,0,0,.08);padding:24px 36px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;flex-shrink:0}
.page-title{font-size:24px;font-weight:700;color:var(--text)}
.page-subtitle{font-size:14px;color:#666;margin-top:4px}
.page-body{padding:32px 36px;flex:1}

/* ── OVERLAY ── */
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:190}
.sidebar-overlay.show{display:block}

@media(max-width:767px){
.topbar{display:flex}
.sidebar{transform:translateX(-100%)}
.sidebar.open{transform:translateX(0)}
.main-wrap{margin-left:0;padding-top:var(--topbar-h);height:auto;min-height:100vh;overflow-y:auto}
.page-header{padding:20px}
.page-body{padding:20px}
}
</style>
</head>
<body>

{{-- Mobile Topbar --}}
<div class="topbar">
    <div class="topbar-logo"><span>🌱</span> MAPIA</div>
    <button class="hamburger" id="menuBtn" aria-label="Buka menu">☰</button>
</div>
<div class="sidebar-overlay" id="overlay"></div>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}"><span>🌱</span> MAPIA</a>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ Request::routeIs('dashboard*') ? 'active' : '' }}">
            <span class="icon">📊</span> Dashboard
        </a>
        <a href="{{ route('monitoring.kontrol') }}" class="nav-item {{ Request::routeIs('monitoring*') ? 'active' : '' }}">
            <span class="icon">💧</span> Kontrol Siram
        </a>
        <a href="{{ route('parameter.index') }}" class="nav-item {{ Request::routeIs('parameter*') ? 'active' : '' }}">
            <span class="icon">⚙️</span> Parameter
        </a>
        <a href="{{ route('riwayat.index') }}" class="nav-item {{ Request::routeIs('riwayat*') ? 'active' : '' }}">
            <span class="icon">📋</span> Riwayat
        </a>
        <a href="{{ route('notifikasi.index') }}" class="nav-item {{ Request::routeIs('notifikasi*') ? 'active' : '' }}">
            <span class="icon">🔔</span> Notifikasi
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="nav-badge">{{ $unreadCount }}</span>
            @endif
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">
                @if(Auth::user()->foto ?? null)
                    <img src="{{ asset('storage/'.Auth::user()->foto) }}" alt="Foto">
                @else
                    {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->nama ?? 'Pengguna' }}</div>
                <div class="user-role">Petani</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">🚪 Keluar</button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="main-wrap">
    <div class="page-header">
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            <div class="page-subtitle">@yield('page-subtitle', '')</div>
        </div>
        <div>@yield('page-actions')</div>
    </div>
    <div class="page-body">
        @if(session('success'))
        <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:15px;">
            ✅ {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:15px;">
            ❌ {{ session('error') }}
        </div>
        @endif
        @yield('content')
    </div>
</div>

<script>
const menuBtn=document.getElementById('menuBtn');
const sidebar=document.getElementById('sidebar');
const overlay=document.getElementById('overlay');
function toggleSidebar(){sidebar.classList.toggle('open');overlay.classList.toggle('show')}
menuBtn&&menuBtn.addEventListener('click',toggleSidebar);
overlay&&overlay.addEventListener('click',toggleSidebar);
</script>
@stack('scripts')
</body>
</html>
