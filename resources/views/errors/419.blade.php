@extends('layouts.master')

@section('title', '419 - Sesi Berakhir')

@section('content')
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; text-align: center; padding: 2rem 1.25rem;">
    <div style="max-width: 500px;">
        <div style="font-size: 5rem; font-weight: 800; color: var(--primary); line-height: 1;">419</div>
        <h2 style="font-size: 1.5rem; margin-top: 1rem; margin-bottom: 0.5rem;">Sesi Anda Telah Berakhir</h2>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            Silakan muat ulang halaman ini atau lakukan login kembali untuk melanjutkan.
        </p>
        <a href="{{ route('login') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-box-arrow-in-right"></i> Login Kembali
        </a>
    </div>
</div>
@endsection
