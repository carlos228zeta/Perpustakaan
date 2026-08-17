@extends('layouts.admin')

@section('title', 'Kelola Denda & Kas')
@section('header_title', 'Kelola Denda & Kas Perpustakaan')

@section('content')
<div style="max-width: 1150px; margin: 0 auto;">
    <!-- Page Header Title & Subtitle -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.4rem; margin-bottom: 0.35rem; font-weight: 800; color: var(--text-main);">
                <i class="bi bi-wallet2" style="color: var(--primary);"></i> Kelola Denda & Kas Perpustakaan
            </h2>
            <div style="font-size: 0.875rem; color: var(--text-muted);">
                Pantau akumulasi denda keterlambatan, status pengembalian, dan kas denda peminjaman.
            </div>
        </div>
    </div>

    <!-- 4 Stat Cards Grid -->
    <div class="stat-grid" style="margin-bottom: 1.75rem;">
        <div class="stat-card">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.35rem;">AKUMULASI DENDA</div>
                <div style="font-size: 1.65rem; font-weight: 800; color: var(--primary);">Rp {{ number_format($totalAccumulated, 0, ',', '.') }}</div>
                <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Total Kas & Denda Tercatat</div>
            </div>
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(46, 125, 50, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.35rem;">BELUM LUNAS</div>
                <div style="font-size: 1.65rem; font-weight: 800; color: var(--danger);">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
                <div style="font-size: 0.775rem; color: var(--danger); margin-top: 0.35rem; font-weight: 600;">{{ $overdueMembersCount }} Anggota Terlambat</div>
            </div>
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(220, 38, 38, 0.12); color: var(--danger); display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.35rem;">SUDAH LUNAS</div>
                <div style="font-size: 1.65rem; font-weight: 800; color: #166534;">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Denda Masuk Kas</div>
            </div>
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(34, 197, 94, 0.12); color: #166534; display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.35rem;">PEMINJAM TERLAMBAT</div>
                <div style="font-size: 1.65rem; font-weight: 800; color: var(--warning);">{{ $overdueMembersCount }} Orang</div>
                <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Perlu Tindakan Pengembalian</div>
            </div>
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(245, 158, 11, 0.12); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('denda.index', ['status' => 'all', 'search' => $search]) }}" class="btn-tzuchi {{ $filterStatus === 'all' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
                <i class="bi bi-grid"></i> Semua Denda
            </a>
            <a href="{{ route('denda.index', ['status' => 'unpaid', 'search' => $search]) }}" class="btn-tzuchi {{ $filterStatus === 'unpaid' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
                <i class="bi bi-exclamation-circle"></i> Belum Lunas
            </a>
            <a href="{{ route('denda.index', ['status' => 'paid', 'search' => $search]) }}" class="btn-tzuchi {{ $filterStatus === 'paid' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
                <i class="bi bi-check-lg"></i> Sudah Lunas
            </a>
        </div>

        <form action="{{ route('denda.index') }}" method="GET" style="display: flex; gap: 0.5rem; width: 100%; max-width: 380px;">
            <input type="hidden" name="status" value="{{ $filterStatus }}">
            <input type="text" name="search" value="{{ $search }}" class="form-control-tzuchi" placeholder="Cari peminjam, judul buku, kode...">
            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <!-- Denda Table Container -->
    <div class="card-tzuchi" style="padding: 0; overflow: hidden;">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">NO</th>
                    <th>PEMINJAM</th>
                    <th>BUKU & EKSEMPLAR</th>
                    <th style="width: 140px;">HARUS KEMBALI</th>
                    <th style="width: 130px; text-align: center;">KETERLAMBATAN</th>
                    <th style="width: 140px;">JUMLAH DENDA</th>
                    <th style="width: 130px; text-align: center;">STATUS</th>
                    <th style="width: 140px; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($finesList as $index => $fine)
                    <tr>
                        <td style="text-align: center; font-weight: 600;">{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $fine->user_name }}</div>
                            @if(!empty($fine->class_name))
                                <div style="font-size: 0.775rem; color: var(--primary); font-weight: 600; margin-top: 0.15rem;">
                                    <i class="bi bi-mortarboard-fill"></i> {{ $fine->class_name }} @if(!empty($fine->student_major)) • {{ $fine->student_major }} @endif
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main);">{{ $fine->book_title }}</div>
                            <div style="font-size: 0.775rem; color: var(--text-muted);">Kode: {{ $fine->copy_code }}</div>
                        </td>
                        <td>{{ $fine->due_date }}</td>
                        <td style="text-align: center;">
                            @if($fine->days_late > 0)
                                <span class="badge-tzuchi badge-danger">{{ $fine->days_late }} Hari</span>
                            @else
                                <span class="badge-tzuchi badge-secondary">Tepat Waktu</span>
                            @endif
                        </td>
                        <td style="font-weight: 800; color: {{ $fine->is_paid ? '#166534' : 'var(--danger)' }};">
                            Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            @if($fine->is_paid)
                                <span class="badge-tzuchi badge-success"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                            @else
                                <span class="badge-tzuchi badge-danger"><i class="bi bi-clock-fill"></i> Belum Lunas</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if(!$fine->is_paid)
                                <form action="{{ route('denda.pay', $fine->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDeleteModal(event, 'Konfirmasi Pelunasan Denda', 'Tandai denda Rp {{ number_format($fine->fine_amount, 0, ',', '.') }} sebagai LUNAS dan proses pengembalian buku?')">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $fine->type }}">
                                    <button type="submit" class="btn-tzuchi btn-primary-tzuchi btn-sm" style="font-size: 0.775rem;">
                                        <i class="bi bi-check-lg"></i> Tandai Lunas
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 0.8rem; color: var(--text-muted);"><i class="bi bi-check-all"></i> Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                            <i class="bi bi-wallet2" style="font-size: 2.5rem; color: var(--primary); display: block; margin-bottom: 0.5rem;"></i>
                            Tidak ada catatan denda peminjaman ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
