@extends('layouts.master')

@section('title', '403 - Akses Ditolak')

@section('content')
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; text-align: center; padding: 2rem 1.25rem;">
    <div style="max-width: 500px;">
        <div style="font-size: 5rem; font-weight: 800; color: var(--primary); line-height: 1;">403</div>
        <h2 style="font-size: 1.5rem; margin-top: 1rem; margin-bottom: 0.5rem;">Akses Ditolak</h2>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi petugas perpustakaan jika Anda memerlukan akses.
        </p>
        <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ url('/') }}" class="btn-tzuchi btn-secondary-tzuchi">
                <i class="bi bi-house"></i> Beranda Publik
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-tzuchi btn-primary-tzuchi">
                    <i class="bi bi-speedometer2"></i> Buka Dashboard Saya
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-tzuchi" style="background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3);">
                        <i class="bi bi-box-arrow-right"></i> Ganti Akun (Logout)
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-tzuchi btn-primary-tzuchi">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Akun
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
