@extends('layouts.admin')

@section('title', 'Dashboard Petugas Perpustakaan')
@section('header_title', 'Dashboard Operasional Petugas')

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Total Buku & Eksemplar</div>
            <div class="stat-value">{{ $totalBooks }}</div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-success" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;">{{ $totalCopies }} Fisik</span>
                <span>Tersedia</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-green">
            <i class="bi bi-journal-bookmark-fill"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Peminjaman Aktif</div>
            <div class="stat-value">{{ $activeBorrowings }}</div>
            <div class="stat-meta">
                @if($overdueBorrowings > 0)
                    <span class="badge-tzuchi badge-danger" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-exclamation-circle-fill"></i> {{ $overdueBorrowings }} Terlambat</span>
                @else
                    <span class="badge-tzuchi badge-success" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-check-all"></i> Lancar</span>
                @endif
                <span style="color: var(--text-muted);">Sirkulasi</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-orange">
            <i class="bi bi-arrow-repeat" style="-webkit-text-stroke: 0.8px currentColor; font-size: 1.85rem;"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Reservasi Buku</div>
            <div class="stat-value">{{ $totalReservations }}</div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-secondary" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-clock"></i> Antrean</span>
                <span>Menunggu ketersediaan</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-blue">
            <i class="bi bi-clock-history"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Denda Belum Dibayar</div>
            <div class="stat-value" style="color: var(--danger);">
                Rp{{ number_format($totalFines, 0, ',', '.') }}
            </div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-danger" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-cash-stack"></i> Kas</span>
                <span>Pending pembayaran kas</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-red">
            <i class="bi bi-wallet-fill"></i>
        </div>
    </div>
</div>

<!-- Quick Action Shortcuts -->
<div class="card-tzuchi" style="margin-bottom: 1.5rem;">
    <div class="card-header-tzuchi">
        <h3 style="font-size: 1.1rem; margin: 0; font-weight: 800;">Aksi Cepat Sirkulasi</h3>
    </div>
    <div style="display: flex; gap: 0.85rem; flex-wrap: wrap;">
        <a href="{{ route('peminjaman.index') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-plus-lg"></i> Proses Peminjaman Baru
        </a>
        <a href="{{ url('admin/pengembalian') }}" class="btn-tzuchi btn-secondary-tzuchi">
            <i class="bi bi-box-arrow-in-left"></i> Pengembalian & Denda
        </a>
        <a href="{{ route('buku.index') }}" class="btn-tzuchi btn-secondary-tzuchi">
            <i class="bi bi-journal-plus"></i> Kelola Katalog Buku
        </a>
        <a href="{{ route('denda.index') }}" class="btn-tzuchi btn-secondary-tzuchi">
            <i class="bi bi-wallet2"></i> Kelola Denda
        </a>
    </div>
</div>
@endsection
