<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Kata Sandi - {{ \App\Models\LibrarySetting::get('app_title', 'LMS Tzu Chi') }}</title>
    
    @php
        $faviconLogo = \App\Models\LibrarySetting::get('institution_logo');
        $faviconUrl = ($faviconLogo && file_exists(public_path($faviconLogo))) ? asset($faviconLogo) : asset('img/logo.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <link rel="stylesheet" href="{{ asset('css/tzuchi-library.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {!! \App\Models\LibrarySetting::getCustomCssVariables() !!}
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('tzuchi_theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body style="margin: 0; padding: 0; overflow-x: hidden; font-family: 'Plus Jakarta Sans', sans-serif;">

@php
    $customHeroImg = \App\Models\LibrarySetting::get('auth_hero_image');
    $heroBgUrl = ($customHeroImg && file_exists(public_path($customHeroImg))) 
                 ? asset($customHeroImg) 
                 : 'https://images.unsplash.com/photo-1568667256549-094345857637?q=80&w=1600&auto=format&fit=crop';
    
    $institutionName = \App\Models\LibrarySetting::get('institution_name', 'Sekolah Cinta Kasih Tzu Chi');
    $customLogo = \App\Models\LibrarySetting::get('institution_logo');
    $logoSrc = ($customLogo && file_exists(public_path($customLogo))) ? asset($customLogo) : asset('img/logo.png');
@endphp

<div class="auth-split-wrapper" style="justify-content: center; align-items: center; background: var(--bg-color);">
    <div style="position: relative; z-index: 10; background: var(--surface); width: 100%; max-width: 480px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); overflow: hidden; border: 1px solid var(--border-color); margin: 1.5rem;">
        <div style="padding: 2.5rem 2.5rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <img src="{{ $logoSrc }}" alt="Logo" style="height: 52px; width: auto; object-fit: contain; margin-bottom: 1rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.35rem;">Lupa Kata Sandi?</h2>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">Masukkan alamat email Anda untuk menerima tautan reset kata sandi.</p>
            </div>

            @if (session('status'))
                <div style="background: rgba(74, 222, 128, 0.15); border: 1px solid rgba(74, 222, 128, 0.4); color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="email" class="form-label required" style="font-size: 0.825rem; font-weight: 700;">Alamat Email</label>
                    <input id="email" type="email" class="form-control-tzuchi @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="email@domain.com">
                    @error('email')
                        <div class="form-error" style="color: #DC2626; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 0.95rem; border-radius: 12px; margin-bottom: 1.5rem;">
                    Kirim Tautan Reset
                </button>
                
                <div style="text-align: center; font-size: 0.85rem;">
                    <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">
                        <i class="bi bi-arrow-left"></i> Kembali ke Halaman Masuk
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
