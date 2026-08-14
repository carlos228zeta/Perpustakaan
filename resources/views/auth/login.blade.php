@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div style="min-height: calc(100vh - 180px); display: flex; align-items: center; justify-content: center; padding: 2rem 1.25rem;">
    <div class="card-tzuchi" style="width: 100%; max-width: 440px; padding: 2.25rem; background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 56px; width: auto; object-fit: contain; margin-bottom: 0.75rem;">
            <h2 style="font-size: 1.35rem; margin-bottom: 0.25rem;">Masuk ke Akun Anda</h2>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Library Management System - Cinta Kasih Tzu Chi</div>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label required">Alamat Email / Username</label>
                <input id="email" type="email" class="form-control-tzuchi @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@tzuchi.sch.id">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label required">Password</label>
                <div style="position: relative;">
                    <input id="password" type="password" class="form-control-tzuchi @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <button type="button" onclick="togglePasswordVisibility()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted);">
                        <i class="bi bi-eye" id="toggle-icon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; font-size: 0.85rem;">
                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; color: var(--text-main);">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ingat Saya
                </label>
                <a href="{{ route('register') }}" style="font-weight: 600;">Belum Punya Akun? Daftar</a>
            </div>

            <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="width: 100%; padding: 0.75rem; font-size: 0.95rem; font-weight: 600;">
                <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
            </button>
        </form>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggle-icon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
@endsection
