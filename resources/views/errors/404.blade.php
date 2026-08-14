@extends('layouts.master')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; text-align: center; padding: 2rem 1.25rem;">
    <div style="max-width: 500px;">
        <div style="font-size: 5rem; font-weight: 800; color: var(--primary); line-height: 1;">404</div>
        <h2 style="font-size: 1.5rem; margin-top: 1rem; margin-bottom: 0.5rem;">Halaman Tidak Ditemukan</h2>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            Halaman atau data yang Anda cari tidak dapat ditemukan di perpustakaan.
        </p>
        <a href="{{ url('/') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-house"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
