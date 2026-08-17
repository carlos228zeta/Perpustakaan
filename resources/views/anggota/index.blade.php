@extends('layouts.admin')

@section('page_title', 'Manajemen Anggota')

@section('content')
<div class="card border-0 shadow-sm rounded-4" style="background-color: #ffffff;">
    <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center border-bottom" style="border-bottom-color: #f1f5f9 !important;">
        <div>
            <h5 class="mb-1 fw-bold text-dark" style="font-size: 1.1rem;">Daftar Anggota (Siswa & Guru)</h5>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Kelola seluruh anggota perpustakaan yang terdaftar.</p>
        </div>
        <a href="{{ url('/admin/anggota/create') }}" class="btn btn-primary" style="background-color: var(--primary); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.95rem; font-weight: 500;">
            <i class="fas fa-plus me-2"></i> Tambah Anggota
        </a>
    </div>
    
    <div class="card-body p-0">
        @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show m-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                <thead style="background-color: #f8fafc; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th width="5%" class="text-center py-3 border-0">No</th>
                        <th width="35%" class="py-3 border-0">Nama & Email</th>
                        <th width="20%" class="py-3 border-0 text-center">Status Peran</th>
                        <th width="20%" class="py-3 border-0 text-center">Terdaftar Pada</th>
                        <th width="20%" class="text-center py-3 border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($users as $key => $u)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td class="text-center text-muted">{{ $users->firstItem() + $key }}</td>
                            <td>
                                <div class="d-flex align-items-center py-1">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=random&color=fff" alt="Avatar" class="rounded-circle me-3 border" style="width: 40px; height: 40px;">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark" style="font-size: 1rem;">{{ $u->name }}</span>
                                        <span class="text-muted" style="font-size: 0.85rem;">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($u->role_id == 3)
                                    <span class="badge" style="background-color: #dbeafe; color: #1e3a8a; font-weight: 500; font-size: 0.85rem;">Guru</span>
                                @elseif($u->role_id == 4)
                                    <span class="badge" style="background-color: #dcfce7; color: #166534; font-weight: 500; font-size: 0.85rem;">Siswa</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            <td class="text-muted text-center">{{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}</td>
                            <td class="text-center">
                                <div class="btn-group gap-2">
                                    <a href="{{ url('/admin/anggota/'.$u->id) }}" class="btn btn-sm btn-light text-primary border-0 shadow-sm" style="background: #f0f9ff;" title="Lihat Profil">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ url('/admin/anggota/'.$u->id.'/edit') }}" class="btn btn-sm btn-light text-warning border-0 shadow-sm" style="background: #fffbeb;" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ url('/admin/anggota/'.$u->id) }}" method="POST" class="d-inline" onsubmit="return confirmDeleteModal(event, 'Hapus Data Anggota?', 'Apakah Anda yakin ingin menghapus anggota ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border-0 shadow-sm" style="background: #fef2f2;" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-users-slash fa-3x mb-3 opacity-25 d-block"></i>
                                <span style="font-size: 0.95rem;">Belum ada data anggota.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top" style="background-color: #f8fafc; border-radius: 0 0 16px 16px;">
            <div class="text-muted" style="font-size: 0.85rem;">
                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
