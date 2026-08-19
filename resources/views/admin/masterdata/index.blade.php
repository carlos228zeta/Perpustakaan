@extends('layouts.admin')

@section('title', 'Data Master Buku')
@section('header_title', 'Kelola Penulis, Penerbit & Lokasi Rak')

@section('content')
<div style="max-width: 1100px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.35rem; margin-bottom: 0.25rem; font-weight: 800;">Data Master Atribut Buku</h2>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Kelola data master Penulis, Penerbit, dan Lokasi Rak penyimpanan buku.</div>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <!-- Bulk Delete Form Button -->
            <form id="bulkDeleteForm" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
                <div id="bulkDeleteInputs"></div>
                <button type="button" onclick="submitBulkDelete()" class="btn-tzuchi btn-danger-tzuchi">
                    <i class="bi bi-trash-fill"></i> Hapus <span id="selectedCountBadge">0</span> Terpilih
                </button>
            </form>

            @if($tab === 'penulis')
                <a href="{{ route('masterdata.author.create') }}" class="btn-tzuchi btn-primary-tzuchi">
                    <i class="bi bi-plus-lg"></i> Tambah Penulis
                </a>
            @elseif($tab === 'penerbit')
                <a href="{{ route('masterdata.publisher.create') }}" class="btn-tzuchi btn-primary-tzuchi">
                    <i class="bi bi-plus-lg"></i> Tambah Penerbit
                </a>
            @elseif($tab === 'rak')
                <a href="{{ route('masterdata.shelf.create') }}" class="btn-tzuchi btn-primary-tzuchi">
                    <i class="bi bi-plus-lg"></i> Tambah Lokasi Rak
                </a>
            @elseif($tab === 'kelas')
                <a href="{{ route('masterdata.class.create') }}" class="btn-tzuchi btn-primary-tzuchi">
                    <i class="bi bi-plus-lg"></i> Tambah Kelas
                </a>
            @elseif($tab === 'jurusan')
                <a href="{{ route('masterdata.major.create') }}" class="btn-tzuchi btn-primary-tzuchi">
                    <i class="bi bi-plus-lg"></i> Tambah Jurusan
                </a>
            @endif
        </div>
    </div>

    <!-- Tab Navigation Bar -->
    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
        <a href="{{ route('masterdata.index', ['tab' => 'penulis']) }}" class="btn-tzuchi {{ $tab === 'penulis' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
            <i class="bi bi-pen"></i> Penulis Buku
        </a>
        <a href="{{ route('masterdata.index', ['tab' => 'penerbit']) }}" class="btn-tzuchi {{ $tab === 'penerbit' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
            <i class="bi bi-building"></i> Penerbit Buku
        </a>
        <a href="{{ route('masterdata.index', ['tab' => 'rak']) }}" class="btn-tzuchi {{ $tab === 'rak' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
            <i class="bi bi-archive"></i> Lokasi Rak Buku
        </a>
        <a href="{{ route('masterdata.index', ['tab' => 'kelas']) }}" class="btn-tzuchi {{ $tab === 'kelas' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
            <i class="bi bi-door-open"></i> Kelas
        </a>
        <a href="{{ route('masterdata.index', ['tab' => 'jurusan']) }}" class="btn-tzuchi {{ $tab === 'jurusan' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
            <i class="bi bi-diagram-3"></i> Jurusan
        </a>
        <a href="{{ route('kategori.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm" style="margin-left: auto;">
            <i class="bi bi-tags"></i> Ke Kategori Buku
        </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('masterdata.index') }}" method="GET" style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem; max-width: 450px;">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input type="text" name="search" value="{{ $search }}" class="form-control-tzuchi" placeholder="Cari nama {{ $tab }}...">
        <button type="submit" class="btn-tzuchi btn-secondary-tzuchi">
            <i class="bi bi-search"></i>
        </button>
    </form>

    <!-- Table Content Area -->
    <div class="card-tzuchi" style="padding: 0; overflow: hidden;">
        @if($tab === 'penulis')
            <table class="table-tzuchi">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAllMaster" onclick="toggleSelectAllMaster(this)" title="Pilih Semua">
                        </th>
                        <th style="width: 60px;">NO</th>
                        <th>NAMA PENULIS</th>
                        <th style="width: 150px; text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $key => $author)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" class="item-master-chk" value="{{ $author->id }}" onchange="updateBulkBtnState()">
                            </td>
                            <td>{{ $authors->firstItem() + $key }}</td>
                            <td style="font-weight: 700;">{{ $author->name }}</td>
                            <td style="text-align: center;">
                                <div class="action-btn-group">
                                    <button onclick="editAuthor({{ $author->id }}, '{{ addslashes($author->name) }}')" class="action-btn action-btn-edit" title="Edit Data">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('masterdata.author.destroy', $author->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDeleteModal(event, 'Hapus Data Penulis?', 'Apakah Anda yakin ingin menghapus data penulis {{ addslashes($author->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4" style="color: var(--text-muted);">Belum ada data penulis.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($authors->hasPages())
                <div style="padding: 0.75rem 1rem; border-top: 1px solid var(--border-color);">
                    {{ $authors->appends(['tab' => $tab, 'search' => $search])->links() }}
                </div>
            @endif

        @elseif($tab === 'penerbit')
            <table class="table-tzuchi">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAllMaster" onclick="toggleSelectAllMaster(this)" title="Pilih Semua">
                        </th>
                        <th style="width: 60px;">NO</th>
                        <th>NAMA PENERBIT</th>
                        <th style="width: 150px; text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publishers as $key => $pub)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" class="item-master-chk" value="{{ $pub->id }}" onchange="updateBulkBtnState()">
                            </td>
                            <td>{{ $publishers->firstItem() + $key }}</td>
                            <td style="font-weight: 700;">{{ $pub->name }}</td>
                            <td style="text-align: center;">
                                <div class="action-btn-group">
                                    <button onclick="editPublisher({{ $pub->id }}, '{{ addslashes($pub->name) }}')" class="action-btn action-btn-edit" title="Edit Data">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('masterdata.publisher.destroy', $pub->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDeleteModal(event, 'Hapus Data Penerbit?', 'Apakah Anda yakin ingin menghapus data penerbit {{ addslashes($pub->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4" style="color: var(--text-muted);">Belum ada data penerbit.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($publishers->hasPages())
                <div style="padding: 0.75rem 1rem; border-top: 1px solid var(--border-color);">
                    {{ $publishers->appends(['tab' => $tab, 'search' => $search])->links() }}
                </div>
            @endif

        @elseif($tab === 'rak')
            <table class="table-tzuchi">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAllMaster" onclick="toggleSelectAllMaster(this)" title="Pilih Semua">
                        </th>
                        <th style="width: 60px;">NO</th>
                        <th style="width: 150px;">KODE RAK</th>
                        <th>NAMA LOKASI RAK</th>
                        <th style="width: 150px; text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shelves as $key => $shelf)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" class="item-master-chk" value="{{ $shelf->id }}" onchange="updateBulkBtnState()">
                            </td>
                            <td>{{ $shelves->firstItem() + $key }}</td>
                            <td><span class="badge-tzuchi badge-secondary">{{ $shelf->code }}</span></td>
                            <td style="font-weight: 700;">{{ $shelf->name }}</td>
                            <td style="text-align: center;">
                                <div class="action-btn-group">
                                    <button onclick="editShelf({{ $shelf->id }}, '{{ addslashes($shelf->code) }}', '{{ addslashes($shelf->name) }}')" class="action-btn action-btn-edit" title="Edit Data">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('masterdata.shelf.destroy', $shelf->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDeleteModal(event, 'Hapus Lokasi Rak?', 'Apakah Anda yakin ingin menghapus lokasi rak {{ addslashes($shelf->code) }} - {{ addslashes($shelf->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4" style="color: var(--text-muted);">Belum ada data lokasi rak.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($shelves->hasPages())
                <div style="padding: 0.75rem 1rem; border-top: 1px solid var(--border-color);">
                    {{ $shelves->appends(['tab' => $tab, 'search' => $search])->links() }}
                </div>
            @endif

        @elseif($tab === 'kelas')
            <table class="table-tzuchi">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAllMaster" onclick="toggleSelectAllMaster(this)" title="Pilih Semua">
                        </th>
                        <th style="width: 60px;">NO</th>
                        <th>NAMA KELAS</th>
                        <th style="width: 150px; text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $key => $cls)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" class="item-master-chk" value="{{ $cls->id }}" onchange="updateBulkBtnState()">
                            </td>
                            <td>{{ $classes->firstItem() + $key }}</td>
                            <td style="font-weight: 700;">{{ $cls->name }}</td>
                            <td style="text-align: center;">
                                <div class="action-btn-group">
                                    <button onclick="editClass({{ $cls->id }}, '{{ addslashes($cls->name) }}')" class="action-btn action-btn-edit" title="Edit Data">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('masterdata.class.destroy', $cls->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDeleteModal(event, 'Hapus Data Kelas?', 'Apakah Anda yakin ingin menghapus data kelas {{ addslashes($cls->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4" style="color: var(--text-muted);">Belum ada data kelas.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($classes->hasPages())
                <div style="padding: 0.75rem 1rem; border-top: 1px solid var(--border-color);">
                    {{ $classes->appends(['tab' => $tab, 'search' => $search])->links() }}
                </div>
            @endif

        @elseif($tab === 'jurusan')
            <table class="table-tzuchi">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAllMaster" onclick="toggleSelectAllMaster(this)" title="Pilih Semua">
                        </th>
                        <th style="width: 60px;">NO</th>
                        <th>NAMA JURUSAN / KONSENTRASI KEAHLIAN</th>
                        <th style="width: 150px; text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($majors as $key => $major)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" class="item-master-chk" value="{{ $major->id }}" onchange="updateBulkBtnState()">
                            </td>
                            <td>{{ $majors->firstItem() + $key }}</td>
                            <td style="font-weight: 700;">{{ $major->name }}</td>
                            <td style="text-align: center;">
                                <div class="action-btn-group">
                                    <button onclick="editMajor({{ $major->id }}, '{{ addslashes($major->name) }}')" class="action-btn action-btn-edit" title="Edit Data">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('masterdata.major.destroy', $major->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDeleteModal(event, 'Hapus Data Jurusan?', 'Apakah Anda yakin ingin menghapus jurusan {{ addslashes($major->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4" style="color: var(--text-muted);">Belum ada data jurusan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($majors->hasPages())
                <div style="padding: 0.75rem 1rem; border-top: 1px solid var(--border-color);">
                    {{ $majors->appends(['tab' => $tab, 'search' => $search])->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Modal Add Author -->
<div class="modal-tzuchi-backdrop" id="addAuthorModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Tambah Data Penulis Baru</h3>
            <button class="modal-tzuchi-close" onclick="closeModal('addAuthorModal')">&times;</button>
        </div>
        <form action="{{ route('masterdata.author.store') }}" method="POST">
            @csrf
            <div class="modal-tzuchi-body">
                <div class="form-group">
                    <label class="form-label required">Nama Penulis</label>
                    <input type="text" name="name" class="form-control-tzuchi" required placeholder="Contoh: Prof. Dr. Budi Santoso">
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('addAuthorModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">Simpan Penulis</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Author -->
<div class="modal-tzuchi-backdrop" id="editAuthorModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Edit Data Penulis</h3>
            <button class="modal-tzuchi-close" onclick="closeModal('editAuthorModal')">&times;</button>
        </div>
        <form id="editAuthorForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-tzuchi-body">
                <div class="form-group">
                    <label class="form-label required">Nama Penulis</label>
                    <input type="text" name="name" id="editAuthorName" class="form-control-tzuchi" required>
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('editAuthorModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Publisher -->
<div class="modal-tzuchi-backdrop" id="addPublisherModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Tambah Data Penerbit Baru</h3>
            <button class="modal-tzuchi-close" onclick="closeModal('addPublisherModal')">&times;</button>
        </div>
        <form action="{{ route('masterdata.publisher.store') }}" method="POST">
            @csrf
            <div class="modal-tzuchi-body">
                <div class="form-group">
                    <label class="form-label required">Nama Penerbit</label>
                    <input type="text" name="name" class="form-control-tzuchi" required placeholder="Contoh: PT Gramedia Pustaka Utama">
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('addPublisherModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">Simpan Penerbit</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Publisher -->
<div class="modal-tzuchi-backdrop" id="editPublisherModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Edit Data Penerbit</h3>
            <button class="modal-tzuchi-close" onclick="closeModal('editPublisherModal')">&times;</button>
        </div>
        <form id="editPublisherForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-tzuchi-body">
                <div class="form-group">
                    <label class="form-label required">Nama Penerbit</label>
                    <input type="text" name="name" id="editPublisherName" class="form-control-tzuchi" required>
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('editPublisherModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Shelf -->
<div class="modal-tzuchi-backdrop" id="addShelfModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Tambah Lokasi Rak Baru</h3>
            <button class="modal-tzuchi-close" onclick="closeModal('addShelfModal')">&times;</button>
        </div>
        <form action="{{ route('masterdata.shelf.store') }}" method="POST">
            @csrf
            <div class="modal-tzuchi-body">
                <div class="form-group">
                    <label class="form-label required">Kode Rak</label>
                    <input type="text" name="code" class="form-control-tzuchi" required placeholder="Contoh: RAK-01-A">
                </div>
                <div class="form-group">
                    <label class="form-label required">Nama Lokasi / Deskripsi Rak</label>
                    <input type="text" name="name" class="form-control-tzuchi" required placeholder="Contoh: Rak Buku Sains & Matematika">
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('addShelfModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">Simpan Lokasi Rak</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Shelf -->
<div class="modal-tzuchi-backdrop" id="editShelfModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Edit Lokasi Rak</h3>
            <button class="modal-tzuchi-close" onclick="closeModal('editShelfModal')">&times;</button>
        </div>
        <form id="editShelfForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-tzuchi-body">
                <div class="form-group">
                    <label class="form-label required">Kode Rak</label>
                    <input type="text" name="code" id="editShelfCode" class="form-control-tzuchi" required>
                </div>
                <div class="form-group">
                    <label class="form-label required">Nama Lokasi / Deskripsi Rak</label>
                    <input type="text" name="name" id="editShelfName" class="form-control-tzuchi" required>
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('editShelfModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Class -->
<div class="modal-tzuchi-backdrop" id="addClassModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Tambah Data Kelas Baru</h3>
            <button class="modal-tzuchi-close" onclick="closeModal('addClassModal')">&times;</button>
        </div>
        <form action="{{ route('masterdata.class.store') }}" method="POST">
            @csrf
            <div class="modal-tzuchi-body">
                <div class="form-group">
                    <label class="form-label required">Nama Kelas</label>
                    <input type="text" name="name" class="form-control-tzuchi" required placeholder="Contoh: 10 RPL 1">
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('addClassModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Class -->
<div class="modal-tzuchi-backdrop" id="editClassModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Edit Data Kelas</h3>
            <button class="modal-tzuchi-close" onclick="closeModal('editClassModal')">&times;</button>
        </div>
        <form id="editClassForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-tzuchi-body">
                <div class="form-group">
                    <label class="form-label required">Nama Kelas</label>
                    <input type="text" name="name" id="editClassName" class="form-control-tzuchi" required>
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('editClassModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Major -->
<div class="modal-tzuchi-backdrop" id="editMajorModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Edit Data Jurusan</h3>
            <button class="modal-tzuchi-close" onclick="closeModal('editMajorModal')">&times;</button>
        </div>
        <form id="editMajorForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-tzuchi-body">
                <div class="form-group">
                    <label class="form-label required">Nama Jurusan</label>
                    <input type="text" name="name" id="editMajorName" class="form-control-tzuchi" required>
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('editMajorModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
const currentTab = "{{ $tab }}";

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function editAuthor(id, name) {
    document.getElementById('editAuthorForm').action = '/admin/masterdata/author/' + id;
    document.getElementById('editAuthorName').value = name;
    openModal('editAuthorModal');
}

function editPublisher(id, name) {
    document.getElementById('editPublisherForm').action = '/admin/masterdata/publisher/' + id;
    document.getElementById('editPublisherName').value = name;
    openModal('editPublisherModal');
}

function editShelf(id, code, name) {
    document.getElementById('editShelfForm').action = '/admin/masterdata/shelf/' + id;
    document.getElementById('editShelfCode').value = code;
    document.getElementById('editShelfName').value = name;
    openModal('editShelfModal');
}

function editClass(id, name) {
    document.getElementById('editClassForm').action = '/admin/masterdata/class/' + id;
    document.getElementById('editClassName').value = name;
    openModal('editClassModal');
}

function editMajor(id, name) {
    document.getElementById('editMajorForm').action = '/admin/masterdata/major/' + id;
    document.getElementById('editMajorName').value = name;
    openModal('editMajorModal');
}

function toggleSelectAllMaster(masterChk) {
    const checkboxes = document.querySelectorAll('.item-master-chk');
    checkboxes.forEach(chk => chk.checked = masterChk.checked);
    updateBulkBtnState();
}

function updateBulkBtnState() {
    const checkedBoxes = document.querySelectorAll('.item-master-chk:checked');
    const bulkForm = document.getElementById('bulkDeleteForm');
    const badge = document.getElementById('selectedCountBadge');
    
    if (checkedBoxes.length > 0) {
        bulkForm.style.display = 'inline-block';
        badge.innerText = checkedBoxes.length;
    } else {
        bulkForm.style.display = 'none';
    }
}
 
function submitBulkDelete() {
    const checkedBoxes = document.querySelectorAll('.item-master-chk:checked');
    if (checkedBoxes.length === 0) return;

    const form = document.getElementById('bulkDeleteForm');
    const inputsContainer = document.getElementById('bulkDeleteInputs');
    inputsContainer.innerHTML = '';

    checkedBoxes.forEach(chk => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'ids[]';
        inp.value = chk.value;
        inputsContainer.appendChild(inp);
    });

    let actionUrl = '';
    let tabLabel = '';
    if (currentTab === 'penulis') {
        actionUrl = "{{ route('masterdata.author.bulkDestroy') }}";
        tabLabel = 'Penulis';
    } else if (currentTab === 'penerbit') {
        actionUrl = "{{ route('masterdata.publisher.bulkDestroy') }}";
        tabLabel = 'Penerbit';
    } else if (currentTab === 'rak') {
        actionUrl = "{{ route('masterdata.shelf.bulkDestroy') }}";
        tabLabel = 'Lokasi Rak';
    } else if (currentTab === 'kelas') {
        actionUrl = "{{ route('masterdata.class.bulkDestroy') }}";
        tabLabel = 'Kelas';
    } else if (currentTab === 'jurusan') {
        actionUrl = "{{ route('masterdata.major.bulkDestroy') }}";
        tabLabel = 'Jurusan';
    }
    form.action = actionUrl;

    Swal.fire({
        title: `Hapus ${checkedBoxes.length} ${tabLabel} Terpilih?`,
        text: `Apakah Anda yakin ingin menghapus ${checkedBoxes.length} data ${tabLabel} yang telah dicentang?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Hapus Semua Terpilih!',
        cancelButtonText: 'Batal',
        borderRadius: '16px'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endsection
