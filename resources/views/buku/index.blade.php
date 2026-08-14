@extends('layouts.admin')

@section('title', 'Manajemen Buku')
@section('header_title', 'Manajemen Buku Perpustakaan')

@section('content')
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Daftar Koleksi Buku</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Kelola data katalog dan eksemplar fisik buku.</div>
        </div>
        <a href="{{ route('buku.create') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-plus-lg"></i> Tambah Buku Baru
        </a>
    </div>

    <!-- Search Bar -->
    <div style="margin-bottom: 1.25rem;">
        <form action="{{ route('buku.index') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 400px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control-tzuchi" placeholder="Cari judul, ISBN, atau penulis...">
            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">ISBN</th>
                    <th width="30%">Info Buku</th>
                    <th width="15%">Kategori</th>
                    <th width="15%">Eksemplar</th>
                    <th width="20%" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $key => $buku)
                    <tr>
                        <td>{{ $books->firstItem() + $key }}</td>
                        <td><code>{{ $buku->isbn ?? '-' }}</code></td>
                        <td>
                            <strong style="font-size: 0.95rem; color: var(--text-main); display: block;">{{ $buku->title }}</strong>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                {{ $buku->author_name ?? '-' }} • {{ $buku->publisher_name ?? '-' }} ({{ $buku->publication_year ?? '-' }})
                            </div>
                        </td>
                        <td>
                            <span class="badge-tzuchi badge-secondary">{{ $buku->category_name ?? 'Umum' }}</span>
                        </td>
                        <td>
                            <span class="badge-tzuchi badge-success">{{ $buku->available_copies }} / {{ $buku->total_copies }} Tersedia</span>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-btn-group">
                                <a href="{{ route('public.books.show', $buku->id) }}" target="_blank" class="action-btn action-btn-view" title="Pratinjau Buku">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('buku.edit', $buku->id) }}" class="action-btn action-btn-edit" title="Edit Buku">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku {{ addslashes($buku->title) }}?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Hapus Buku">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Tidak ada data buku yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.825rem; color: var(--text-muted);">
            Menampilkan {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} dari {{ $books->total() }} data
        </div>
        <div>
            {{ $books->links() }}
        </div>
    </div>
</div>
@endsection
