@extends('layouts.admin')

@section('title', 'Manajemen Buku')
@section('header_title', 'Manajemen Buku Perpustakaan')

@section('content')
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Daftar Koleksi Buku</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Kelola data katalog, foto sampul, dan eksemplar fisik buku.</div>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('buku.create') }}" class="btn-tzuchi btn-primary-tzuchi">
                <i class="bi bi-plus-lg"></i> Tambah Buku Baru
            </a>

            @if(auth()->user()->hasRole('admin'))
                <button type="button" id="btnBulkDelete" onclick="confirmBulkDelete()" class="btn-tzuchi btn-danger-tzuchi" style="display: none;">
                    <i class="bi bi-trash-fill"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                </button>

                <form action="{{ route('buku.deleteAll') }}" method="POST" id="deleteAllForm" style="display: inline-flex; margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDeleteAll()" class="btn-tzuchi btn-danger-tzuchi">
                        <i class="bi bi-trash3-fill"></i> Hapus Semua Buku
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div style="margin-bottom: 1.25rem;">
        <form action="{{ route('buku.index') }}" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 240px; max-width: 380px;">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control-tzuchi" placeholder="Cari judul, ISBN, atau penulis...">
            </div>
            
            <div style="width: 200px;">
                <select name="category_id" class="form-control-tzuchi" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($categoryId ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi"><i class="bi bi-search"></i> Cari</button>
            @if(!empty($search) || !empty($categoryId))
                <a href="{{ route('buku.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm" style="color: var(--danger);"><i class="bi bi-x-circle"></i> Reset Filter</a>
            @endif
        </form>
    </div>

    @if(auth()->user()->hasRole('admin'))
        <form action="{{ route('buku.bulkDelete') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')
    @endif

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    @if(auth()->user()->hasRole('admin'))
                        <th width="3%" style="text-align: center;">
                            <input type="checkbox" id="selectAllCheckbox" style="cursor: pointer; width: 16px; height: 16px;">
                        </th>
                    @endif
                    <th width="5%">No</th>
                    <th width="8%">Sampul</th>
                    <th width="14%">ISBN</th>
                    <th width="28%">Info Buku</th>
                    <th width="15%">Kategori</th>
                    <th width="12%">Eksemplar</th>
                    <th width="15%" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $key => $buku)
                    <tr>
                        @if(auth()->user()->hasRole('admin'))
                            <td style="text-align: center;">
                                <input type="checkbox" name="ids[]" value="{{ $buku->id }}" class="book-checkbox" style="cursor: pointer; width: 16px; height: 16px;" onchange="updateBulkDeleteState()">
                            </td>
                        @endif
                        <td>{{ $books->firstItem() + $key }}</td>
                        <td>
                            @if(!empty($buku->cover_image) && file_exists(public_path($buku->cover_image)))
                                <img src="{{ asset($buku->cover_image) }}" alt="Cover" style="height: 48px; width: 36px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                            @else
                                <div style="height: 48px; width: 36px; background: var(--primary-light); color: var(--primary); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                    <i class="bi bi-book"></i>
                                </div>
                            @endif
                        </td>
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
                                <a href="{{ route('katalog.show', $buku->id) }}" target="_blank" class="action-btn action-btn-view" title="Pratinjau Buku">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('buku.edit', $buku->id) }}" class="action-btn action-btn-edit" title="Edit Buku">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" onsubmit="return confirmDeleteModal(event, 'Hapus Data Buku?', 'Apakah Anda yakin ingin menghapus buku {{ addslashes($buku->title) }}?')" style="display: inline;">
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
                        <td colspan="{{ auth()->user()->hasRole('admin') ? '8' : '7' }}" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Tidak ada data buku yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(auth()->user()->hasRole('admin'))
        </form>
    @endif

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

@push('scripts')
<script>
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const bookCheckboxes = document.querySelectorAll('.book-checkbox');
    const btnBulkDelete = document.getElementById('btnBulkDelete');
    const selectedCountSpan = document.getElementById('selectedCount');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            bookCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteState();
        });
    }

    function updateBulkDeleteState() {
        if (!bookCheckboxes || bookCheckboxes.length === 0) return;
        
        const checkedBoxes = Array.from(bookCheckboxes).filter(cb => cb.checked);
        const count = checkedBoxes.length;

        if (count > 0) {
            btnBulkDelete.style.display = 'inline-flex';
            selectedCountSpan.textContent = count;
        } else {
            btnBulkDelete.style.display = 'none';
            selectedCountSpan.textContent = 0;
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (count === bookCheckboxes.length && count > 0);
        }
    }

    function confirmBulkDelete() {
        const checkedBoxes = Array.from(bookCheckboxes).filter(cb => cb.checked);
        if (checkedBoxes.length === 0) return;

        Swal.fire({
            title: `Hapus ${checkedBoxes.length} Buku Terpilih?`,
            text: `Apakah Anda yakin ingin menghapus ${checkedBoxes.length} buku yang telah dicentang?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Hapus Terpilih!',
            cancelButtonText: 'Batal',
            borderRadius: '16px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }

    function confirmDeleteAll() {
        Swal.fire({
            title: 'Hapus SEMUA Buku dalam Sistem?',
            text: 'PERHATIAN PENTING: Seluruh katalog data buku akan dihapus dari sistem! Ketik kata HAPUS di bawah untuk mengonfirmasi:',
            icon: 'error',
            input: 'text',
            inputPlaceholder: 'Ketik "HAPUS" di sini...',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'HAPUS SEMUA BUKU',
            cancelButtonText: 'Batal',
            borderRadius: '16px',
            inputValidator: (value) => {
                if (!value || value.trim().toUpperCase() !== 'HAPUS') {
                    return 'Kata kunci salah! Ketik HAPUS dengan huruf kapital.';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteAllForm').submit();
            }
        });
    }
</script>
@endpush
