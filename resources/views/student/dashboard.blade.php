@extends('layouts.admin')

@section('title', 'Dashboard Siswa')
@section('header_title', 'Dashboard Peserta Didik — ' . auth()->user()->name)

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Buku Dipinjam</div>
            <div class="stat-value">{{ $borrowedBooks }}</div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-success" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-journal-bookmark"></i> Kuota</span>
                <span>Batas: 3 Buku</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-green">
            <i class="bi bi-journal-check"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Segera Jatuh Tempo</div>
            <div class="stat-value">{{ $dueSoon }}</div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-warning" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-clock-history"></i> Waktu</span>
                <span>3 Hari Ke Depan</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-orange">
            <i class="bi bi-clock-fill"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Reservasi Aktif</div>
            <div class="stat-value">{{ $reservations }}</div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-secondary" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-bookmark"></i> Antrean</span>
                <span>Menunggu Antrean</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-blue">
            <i class="bi bi-bookmark-star-fill"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Denda Saya</div>
            <div class="stat-value" style="color: var(--danger);">
                Rp{{ number_format($fines, 0, ',', '.') }}
            </div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-danger" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-cash-stack"></i> Tagihan</span>
                <span>Belum Lunas</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-red">
            <i class="bi bi-wallet-fill"></i>
        </div>
    </div>
</div>

<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin: 0; font-weight: 800;">Peminjaman Aktif Saya</h3>
            <div style="font-size: 0.775rem; color: var(--text-muted);">Daftar koleksi buku yang sedang kamu pinjam</div>
        </div>
        <a href="{{ route('public.books.index') }}" class="btn-tzuchi btn-primary-tzuchi btn-sm"><i class="bi bi-search"></i> Telusuri Buku</a>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th>Buku</th>
                    <th>Kode Eksemplar</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeBorrowList as $item)
                    <tr>
                        <td><strong style="color: var(--text-main);">{{ $item->book_title }}</strong></td>
                        <td><code>{{ $item->copy_code }}</code></td>
                        <td>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d/m/Y') }}</td>
                        <td>
                            <strong style="{{ \Carbon\Carbon::parse($item->due_date)->isPast() ? 'color: var(--danger);' : '' }}">
                                {{ \Carbon\Carbon::parse($item->due_date)->format('d/m/Y') }}
                            </strong>
                        </td>
                        <td>
                            @if(\Carbon\Carbon::parse($item->due_date)->isPast())
                                <span class="badge-tzuchi badge-danger">Terlambat</span>
                            @else
                                <span class="badge-tzuchi badge-success">Dipinjam</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">
                            <i class="bi bi-journal-x" style="font-size: 2rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;"></i>
                            Kamu belum meminjam buku saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
