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
        <div style="display: flex; gap: 0.5rem;">
            <button type="button" onclick="openImportModal()" class="btn-tzuchi btn-secondary-tzuchi">
                <i class="bi bi-file-earmark-arrow-up"></i> Import CSV
            </button>
            <a href="{{ route('siswa.create') }}" class="btn-tzuchi btn-primary-tzuchi">
                <i class="bi bi-person-plus"></i> Tambah Siswa Baru
            </a>
        </div>
    </div>

    <div style="margin-bottom: 1.25rem;">
        <form action="{{ route('siswa.index') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 600px; align-items: center;">
            <div style="flex: 1;">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control-tzuchi" placeholder="Cari nama, NIS, atau email...">
            </div>
            <div style="flex: 1;">
                <select name="class_id" class="form-control-tzuchi searchable-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ (isset($class_id) && $class_id == $c->id) ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi"><i class="bi bi-search"></i> Cari</button>
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
                        <td>
                            @if(!$stu->major || $stu->major === 'Tidak Ada (Umum)')
                                <span style="color: var(--text-muted);">-</span>
                            @else
                                {{ $stu->major }}
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div class="action-btn-group">
                                <a href="{{ route('siswa.edit', $stu->id) }}" class="action-btn action-btn-edit" title="Edit Data Siswa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('siswa.destroy', $stu->id) }}" method="POST" onsubmit="return confirmDeleteModal(event, 'Hapus Data Siswa?', 'Apakah Anda yakin ingin menghapus data siswa {{ addslashes($stu->name) }}?')" style="display: inline;">
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

<!-- Modal Import Siswa -->
<div class="modal-tzuchi-backdrop" id="importModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Import Data Siswa via CSV</h3>
            <button type="button" class="modal-tzuchi-close" onclick="closeImportModal()">&times;</button>
        </div>
        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-tzuchi-body">
                <div style="margin-bottom: 1rem; padding: 1rem; background: var(--bg-color); border-radius: var(--radius-md); font-size: 0.85rem; border: 1px dashed var(--border-color); color: var(--text-main); line-height: 1.6;">
                    <strong>Panduan Import:</strong><br>
                    1. Unduh <a href="{{ route('siswa.importTemplate') }}" style="color: var(--primary); font-weight: bold; text-decoration: underline;">Template CSV ini</a>.<br>
                    2. Isi data siswa sesuai format pada kolom-kolom yang tersedia.<br>
                    3. Simpan file dalam format <code>.csv</code> dan unggah di bawah ini.<br>
                    <span style="color: var(--text-muted); font-size: 0.8rem;">*Untuk <strong>ID_Kelas</strong>, masukkan ID angka kelas (kosongkan jika belum ada).</span>
                </div>
                <div class="form-group">
                    <label class="form-label required">Unggah File CSV</label>
                    <input type="file" name="csv_file" class="form-control-tzuchi" accept=".csv" required style="padding: 0.5rem;">
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeImportModal()" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi"><i class="bi bi-upload"></i> Mulai Import</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control {
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        padding: 0.6rem 0.85rem;
        font-family: inherit;
        font-size: 0.95rem;
        box-shadow: none;
        background-color: var(--bg-color);
    }
    .ts-dropdown {
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        font-family: inherit;
        font-size: 0.95rem;
        margin-top: 4px;
        z-index: 9999 !important;
    }
    .ts-dropdown .option:hover, .ts-dropdown .option.active {
        background-color: var(--primary-light);
        color: var(--primary);
    }
    /* Dark mode overrides */
    [data-theme='dark'] .ts-control {
        background-color: var(--bg-color);
        border-color: var(--border-color);
        color: var(--text-main);
    }
    [data-theme='dark'] .ts-dropdown {
        background-color: var(--bg-color);
        border-color: var(--border-color);
        color: var(--text-main);
    }
    [data-theme='dark'] .ts-dropdown .option:hover, [data-theme='dark'] .ts-dropdown .option.active {
        background-color: #2D3748;
        color: var(--primary);
    }
    [data-theme='dark'] .ts-control input {
        color: var(--text-main);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.searchable-select').forEach(function(el) {
            new TomSelect(el, {
                create: false,
                plugins: ['dropdown_input'],
                placeholder: "-- Pilih --",
                dropdownParent: null
            });
        });
    });

    function openImportModal() {
        document.getElementById('importModal').style.display = 'flex';
    }

    function closeImportModal() {
        document.getElementById('importModal').style.display = 'none';
    }
</script>
@endpush
