@extends('layouts.admin')

@section('title', 'Sirkulasi Pengembalian')
@section('header_title', 'Pengembalian & Perhitungan Denda')

@section('content')
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Buku Sedang Dipinjam</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Proses pengembalian buku dan hitung otomatis denda keterlambatan.</div>
        </div>
    </div>

    <!-- Search Form -->
    <div style="margin-bottom: 1.25rem;">
        <form action="{{ url('admin/pengembalian') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 420px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control-tzuchi" placeholder="Cari nama peminjam, judul buku, atau kode...">
            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi"><i class="bi bi-search"></i> Cari</button>
            @if(!empty($search))
                <a href="{{ url('admin/pengembalian') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm" style="color: var(--danger);"><i class="bi bi-x-circle"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Peminjam</th>
                    <th width="25%">Buku & Kode Eksemplar</th>
                    <th width="15%">Tgl Pinjam</th>
                    <th width="15%">Jatuh Tempo</th>
                    <th width="20%" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $key => $b)
                    @php
                        $dueDateObj = \Carbon\Carbon::parse($b->due_date);
                        $isOverdue = \Carbon\Carbon::now()->greaterThan($dueDateObj);
                        $overdueDays = $isOverdue ? \Carbon\Carbon::now()->diffInDays($dueDateObj) : 0;
                    @endphp
                    <tr>
                        <td>{{ $borrowings->firstItem() + $key }}</td>
                        <td><strong>{{ $b->user_name }}</strong></td>
                        <td>
                            <strong>{{ $b->book_title }}</strong>
                            <div style="font-size: 0.775rem; color: var(--text-muted);">Kode: <code>{{ $b->copy_code }}</code></div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}</td>
                        <td>
                            <strong style="{{ $isOverdue ? 'color: var(--danger);' : '' }}">
                                {{ $dueDateObj->format('d/m/Y') }}
                            </strong>
                            @if($isOverdue)
                                <div style="font-size: 0.75rem; color: var(--danger); font-weight: 600;">
                                    Terlambat {{ $overdueDays }} Hari (Est. Denda Rp{{ number_format($overdueDays * 1000, 0, ',', '.') }})
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ url('admin/pengembalian/'.$b->id) }}" method="POST" onsubmit="return confirmDeleteModal(event, 'Proses Pengembalian Buku?', 'Apakah Anda yakin ingin memproses pengembalian buku ini?')">
                                @csrf
                                <button type="submit" class="btn-tzuchi btn-primary-tzuchi btn-sm">
                                    <i class="bi bi-box-arrow-in-left"></i> Proses Kembalikan
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Tidak ada transaksi buku dipinjam saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.825rem; color: var(--text-muted);">
            Menampilkan {{ $borrowings->firstItem() ?? 0 }} - {{ $borrowings->lastItem() ?? 0 }} dari {{ $borrowings->total() }} data
        </div>
        <div>
            {{ $borrowings->links() }}
        </div>
    </div>
</div>
@endsection
