@extends('layouts.admin')

@section('title', 'Data Petugas')
@section('header_title', 'Manajemen Data Petugas Perpustakaan')

@section('content')
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Daftar Petugas Perpustakaan</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Daftar akun dan pengelola sirkulasi perpustakaan yang terdaftar lewat pendaftaran.</div>
        </div>
    </div>

    <!-- Search Form -->
    <div style="margin-bottom: 1.25rem;">
        <form action="{{ route('petugas.index') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 400px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control-tzuchi" placeholder="Cari nama atau email petugas...">
            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th width="35%">Nama Petugas</th>
                    <th width="30%">Alamat Email</th>
                    <th width="12%">Status</th>
                    <th width="15%" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($librarians as $key => $lib)
                    <tr>
                        <td>{{ $librarians->firstItem() + $key }}</td>
                        <td><strong>{{ $lib->name }}</strong></td>
                        <td><code>{{ $lib->email }}</code></td>
                        <td><span class="badge-tzuchi badge-success">{{ ucfirst($lib->status ?? 'Active') }}</span></td>
                        <td style="text-align: center;">
                            <div class="action-btn-group">
                                <a href="{{ route('petugas.edit', $lib->id) }}" class="action-btn action-btn-edit" title="Edit Data Petugas">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('petugas.destroy', $lib->id) }}" method="POST" onsubmit="return confirmDeleteModal(event, 'Hapus Petugas?', 'Apakah Anda yakin ingin menghapus petugas {{ addslashes($lib->name) }}?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Hapus Petugas">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Belum ada petugas perpustakaan terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.825rem; color: var(--text-muted);">
            Menampilkan {{ $librarians->firstItem() ?? 0 }} - {{ $librarians->lastItem() ?? 0 }} dari {{ $librarians->total() }} data
        </div>
        <div>
            {{ $librarians->links() }}
        </div>
    </div>
</div>
@endsection
