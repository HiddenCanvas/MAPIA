<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk — MAPIA</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--text:#0D0D0D;--background:#F5F0E8;--primary:#0D0D0D;--secondary:#FFFFFF;--accent:#C8F135}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;min-height:100vh;background:#0D0D0D;display:flex;align-items:center;justify-content:center;padding:20px;position:relative;overflow:hidden}
body::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");pointer-events:none}
.login-card{background:#FFFFFF;border-radius:24px;padding:48px 40px;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,.3);position:relative;z-index:1;border:1px solid #E5E0D5}
.login-header{text-align:center;margin-bottom:36px}
.login-logo{font-size:52px;margin-bottom:10px;line-height:1}
.login-title{font-size:28px;font-weight:700;color:var(--primary);margin-bottom:4px;font-family:'Sora',sans-serif}
.login-subtitle{font-size:15px;color:#666;line-height:1.4}
.login-subtitle strong{color:#0D0D0D}
.form-group{margin-bottom:20px}
.form-label{display:block;font-size:14px;font-weight:700;color:var(--text);margin-bottom:8px}
.form-input{width:100%;padding:14px 16px;border:1px solid #D5D0C5;border-radius:10px;font-size:15px;font-family:'DM Sans',sans-serif;color:var(--text);background:#fff;transition:border-color .2s,box-shadow .2s;min-height:48px}
.form-input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(13,13,13,.1)}
.form-input.is-invalid{border-color:#D97706}
.error-msg{color:#D97706;font-size:13px;margin-top:6px;display:flex;align-items:center;gap:4px}
.btn-login{width:100%;padding:14px;background:var(--accent);color:#0D0D0D;border:none;border-radius:999px;font-size:16px;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:all .2s;min-height:48px;letter-spacing:.3px;margin-top:8px}
.btn-login:hover{background:#b2d92f;transform:translateY(-1px);box-shadow:0 6px 20px rgba(200,241,53,.3)}
.btn-login:active{transform:translateY(0)}
.login-footer{text-align:center;margin-top:24px;font-size:13px;color:#888}
.alert-error{background:rgba(217,119,6,.1);border:1px solid rgba(217,119,6,.2);color:#D97706;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:14px}
</style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <div class="login-logo">🌱</div>
        <h1 class="login-title">MAPIA</h1>
        <p class="login-subtitle">Sistem Monitoring Tanah &amp;<br><strong>Otomasi Penyiraman Pepaya</strong></p>
    </div>

    @if($errors->any())
    <div class="alert-error">
        ⚠️ Email atau kata sandi tidak sesuai. Silakan coba lagi.
    </div>
    @endif

    @if(session('status'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:14px;">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">📧 Alamat Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                value="{{ old('email') }}"
                placeholder="Masukkan email Anda"
                autocomplete="email"
                required
            >
            @error('email')
            <div class="error-msg">⚠️ {{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="password">🔒 Kata Sandi</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="Masukkan kata sandi"
                autocomplete="current-password"
                required
            >
            @error('password')
            <div class="error-msg">⚠️ {{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn-login">🚀 Masuk ke Sistem</button>
    </form>
    <div class="login-footer">© {{ date('Y') }} MAPIA — Sistem Pertanian Cerdas</div>
</div>
</body>
</html>
