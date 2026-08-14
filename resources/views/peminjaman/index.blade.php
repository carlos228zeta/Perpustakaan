@extends('layouts.admin')

@section('title', 'Sirkulasi Peminjaman')
@section('header_title', 'Sirkulasi Transaksi Peminjaman')

@section('content')
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Daftar Transaksi Peminjaman</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Kelola pengajuan, persetujuan, dan transaksi pinjam buku.</div>
        </div>
        <a href="{{ route('peminjaman.create') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-plus-lg"></i> Peminjaman Baru
        </a>
    </div>

    <!-- Status Filters -->
    <div style="margin-bottom: 1.25rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('peminjaman.index') }}" class="btn-tzuchi {{ request('status') ? 'btn-secondary-tzuchi' : 'btn-primary-tzuchi' }} btn-sm">Semua</a>
        <a href="{{ route('peminjaman.index', ['status' => 'pending']) }}" class="btn-tzuchi {{ request('status') === 'pending' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">Menunggu Persetujuan</a>
        <a href="{{ route('peminjaman.index', ['status' => 'borrowed']) }}" class="btn-tzuchi {{ request('status') === 'borrowed' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">Dipinjam</a>
        <a href="{{ route('peminjaman.index', ['status' => 'returned']) }}" class="btn-tzuchi {{ request('status') === 'returned' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">Dikembalikan</a>
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
                    <th width="10%">Status</th>
                    <th width="10%" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $key => $b)
                    <tr>
                        <td>{{ $borrowings->firstItem() + $key }}</td>
                        <td><strong>{{ $b->user_name }}</strong></td>
                        <td>
                            <strong>{{ $b->book_title }}</strong>
                            <div style="font-size: 0.775rem; color: var(--text-muted);">Kode: <code>{{ $b->copy_code }}</code></div>
                        </td>
                        <td>{{ $b->borrow_date }}</td>
                        <td>
                            <strong style="{{ \Carbon\Carbon::parse($b->due_date)->isPast() && $b->status === 'borrowed' ? 'color: var(--danger);' : '' }}">
                                {{ $b->due_date }}
                            </strong>
                        </td>
                        <td>
                            @if($b->status === 'pending')
                                <span class="badge-tzuchi badge-warning">Menunggu</span>
                            @elseif($b->status === 'borrowed')
                                <span class="badge-tzuchi badge-success">Dipinjam</span>
                            @elseif($b->status === 'returned')
                                <span class="badge-tzuchi badge-secondary">Dikembalikan</span>
                            @else
                                <span class="badge-tzuchi badge-danger">{{ $b->status }}</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($b->status === 'pending')
                                <form action="{{ route('peminjaman.update', $b->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="borrowed">
                                    <button type="submit" class="btn-tzuchi btn-primary-tzuchi btn-sm" title="Setujui">Setujui</button>
                                </form>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Tidak ada transaksi peminjaman ditemukan.
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
