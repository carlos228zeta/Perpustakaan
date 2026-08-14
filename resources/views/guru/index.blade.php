@extends('layouts.admin')

@section('title', 'Data Guru')
@section('header_title', 'Manajemen Data Guru')

@section('content')
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Daftar Guru / Tenaga Pendidik</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Kelola akun dan informasi pengajar sekolah.</div>
        </div>
        <a href="{{ route('guru.create') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-person-plus"></i> Tambah Guru Baru
        </a>
    </div>

    <!-- Search Form -->
    <div style="margin-bottom: 1.25rem;">
        <form action="{{ route('guru.index') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 400px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control-tzuchi" placeholder="Cari nama, NIP, atau mata pelajaran...">
            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">NIP</th>
                    <th width="30%">Nama Guru & Email</th>
                    <th width="20%">Mata Pelajaran</th>
                    <th width="25%" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $key => $tch)
                    <tr>
                        <td>{{ $teachers->firstItem() + $key }}</td>
                        <td><code>{{ $tch->nip ?? '-' }}</code></td>
                        <td>
                            <strong>{{ $tch->name }}</strong>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $tch->email }}</div>
                        </td>
                        <td><span class="badge-tzuchi badge-secondary">{{ $tch->subject ?? 'Umum' }}</span></td>
                        <td style="text-align: center;">
                            <div class="action-btn-group">
                                <a href="{{ route('guru.edit', $tch->id) }}" class="action-btn action-btn-edit" title="Edit Data Guru">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('guru.destroy', $tch->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru {{ addslashes($tch->name) }}?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Hapus Data Guru">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Belum ada data guru terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.825rem; color: var(--text-muted);">
            Menampilkan {{ $teachers->firstItem() ?? 0 }} - {{ $teachers->lastItem() ?? 0 }} dari {{ $teachers->total() }} data
        </div>
        <div>
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection
