@extends('layouts.admin')

@section('title', 'Dashboard Siswa')
@section('header_title', 'Selamat Datang, ' . auth()->user()->name)

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <div>
            <div class="stat-label">Buku Dipinjam</div>
            <div class="stat-value">{{ $borrowedBooks }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Batas Maksimal: 3 Buku</div>
        </div>
        <div style="width: 44px; height: 44px; background: #E8F5E9; color: var(--primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-journal-check"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">Segera Jatuh Tempo</div>
            <div class="stat-value">{{ $dueSoon }}</div>
            <div style="font-size: 0.75rem; color: var(--warning); margin-top: 0.25rem;">Dalam 3 Hari Ke Depan</div>
        </div>
        <div style="width: 44px; height: 44px; background: #FEF3C7; color: var(--warning); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-clock"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">Reservasi Aktif</div>
            <div class="stat-value">{{ $reservations }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Menunggu Antrean</div>
        </div>
        <div style="width: 44px; height: 44px; background: #E3F2FD; color: #1976D2; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-bookmark"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">Denda Saya</div>
            <div class="stat-value" style="font-size: 1.35rem; color: var(--danger);">
                Rp{{ number_format($fines, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Tagihan Belum Lunas</div>
        </div>
        <div style="width: 44px; height: 44px; background: #FFEBEE; color: var(--danger); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-cash"></i>
        </div>
    </div>
</div>

<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <h3 style="font-size: 1.05rem; margin: 0;">Peminjaman Aktif Saya</h3>
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
                        <td><strong>{{ $item->book_title }}</strong></td>
                        <td><code>{{ $item->copy_code }}</code></td>
                        <td>{{ $item->borrow_date }}</td>
                        <td>
                            <strong style="{{ \Carbon\Carbon::parse($item->due_date)->isPast() ? 'color: var(--danger);' : '' }}">
                                {{ $item->due_date }}
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
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Kamu belum meminjam buku saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
