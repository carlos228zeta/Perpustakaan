@extends('layouts.admin')

@section('title', 'Dashboard Petugas Perpustakaan')
@section('header_title', 'Dashboard Operasional Petugas')

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <div>
            <div class="stat-label">Total Buku & Eksemplar</div>
            <div class="stat-value">{{ $totalBooks }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                {{ $totalCopies }} Fisik Eksemplar
            </div>
        </div>
        <div style="width: 44px; height: 44px; background: #E8F5E9; color: var(--primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-journal-bookmark"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">Peminjaman Aktif</div>
            <div class="stat-value">{{ $activeBorrowings }}</div>
            <div style="font-size: 0.75rem; color: var(--danger); margin-top: 0.25rem;">
                {{ $overdueBorrowings }} Terlambat
            </div>
        </div>
        <div style="width: 44px; height: 44px; background: #FFF3E0; color: #F57C00; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-arrow-left-right"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">Reservasi Buku</div>
            <div class="stat-value">{{ $totalReservations }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                Menunggu antrean
            </div>
        </div>
        <div style="width: 44px; height: 44px; background: #E3F2FD; color: #1976D2; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-clock-history"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">Denda Belum Dibayar</div>
            <div class="stat-value" style="font-size: 1.35rem; color: var(--danger);">
                Rp{{ number_format($totalFines, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                Pending pembayaran
            </div>
        </div>
        <div style="width: 44px; height: 44px; background: #FFEBEE; color: var(--danger); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
    </div>
</div>

<!-- Quick Action Shortcuts -->
<div class="card-tzuchi" style="margin-bottom: 1.5rem;">
    <div class="card-header-tzuchi">
        <h3 style="font-size: 1.05rem; margin: 0;">Aksi Cepat Operasional</h3>
    </div>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('peminjaman.index') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-plus-lg"></i> Proses Peminjaman Baru
        </a>
        <a href="{{ url('admin/pengembalian') }}" class="btn-tzuchi btn-secondary-tzuchi">
            <i class="bi bi-box-arrow-in-left"></i> Pengembalian Buku
        </a>
        <a href="{{ route('buku.index') }}" class="btn-tzuchi btn-secondary-tzuchi">
            <i class="bi bi-journal-plus"></i> Kelola Katalog Buku
        </a>
    </div>
</div>
@endsection
