@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('header_title', 'Manajemen Kategori Buku')

@section('content')
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Daftar Kategori Buku</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Kelola klasifikasi topik dan bidang studi koleksi buku.</div>
        </div>
        <a href="{{ route('kategori.create') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </a>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th width="25%">Nama Kategori</th>
                    <th width="35%">Deskripsi</th>
                    <th width="15%">Jumlah Buku</th>
                    <th width="17%" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $key => $kat)
                    <tr>
                        <td>{{ $categories->firstItem() + $key }}</td>
                        <td><strong>{{ $kat->name }}</strong></td>
                        <td style="color: var(--text-muted);">{{ $kat->description ?? '-' }}</td>
                        <td><span class="badge-tzuchi badge-secondary">{{ $kat->books_count }} Judul Buku</span></td>
                        <td style="text-align: center;">
                            <div class="action-btn-group">
                                <a href="{{ route('kategori.edit', $kat->id) }}" class="action-btn action-btn-edit" title="Edit Kategori">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('kategori.destroy', $kat->id) }}" method="POST" onsubmit="return confirmDeleteModal(event, 'Hapus Kategori?', 'Apakah Anda yakin ingin menghapus kategori {{ addslashes($kat->name) }}?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Hapus Kategori">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Belum ada data kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.825rem; color: var(--text-muted);">
            Menampilkan {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} dari {{ $categories->total() }} data
        </div>
        <div>
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
