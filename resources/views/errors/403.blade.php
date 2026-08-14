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
        <a href="{{ url('/') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-house"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
