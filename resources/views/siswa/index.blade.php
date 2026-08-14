@extends('layouts.admin')

@section('title', 'Data Siswa')
@section('header_title', 'Manajemen Data Siswa')

@section('content')
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Daftar Siswa Perpustakaan</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Kelola akun dan profil siswa Cinta Kasih Tzu Chi.</div>
        </div>
        <a href="{{ route('siswa.create') }}" class="btn-tzuchi btn-primary-tzuchi">
            <i class="bi bi-person-plus"></i> Tambah Siswa Baru
        </a>
    </div>

    <!-- Search Form -->
    <div style="margin-bottom: 1.25rem;">
        <form action="{{ route('siswa.index') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 400px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control-tzuchi" placeholder="Cari nama, NIS, atau email...">
            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">NIS / NISN</th>
                    <th width="30%">Nama Siswa & Email</th>
                    <th width="15%">Kelas</th>
                    <th width="15%">Jurusan</th>
                    <th width="20%" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $key => $stu)
                    <tr>
                        <td>{{ $students->firstItem() + $key }}</td>
                        <td>
                            <code>{{ $stu->nis ?? '-' }}</code>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $stu->nisn ?? '' }}</div>
                        </td>
                        <td>
                            <strong>{{ $stu->name }}</strong>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $stu->email }}</div>
                        </td>
                        <td><span class="badge-tzuchi badge-secondary">{{ $stu->class_name ?? 'Umum' }}</span></td>
                        <td>{{ $stu->major ?? 'PPLG' }}</td>
                        <td style="text-align: center;">
                            <div class="action-btn-group">
                                <a href="{{ route('siswa.edit', $stu->id) }}" class="action-btn action-btn-edit" title="Edit Data Siswa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('siswa.destroy', $stu->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ addslashes($stu->name) }}?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Hapus Data Siswa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Belum ada data siswa yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.825rem; color: var(--text-muted);">
            Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} data
        </div>
        <div>
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
