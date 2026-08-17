@extends('layouts.admin')

@section('title', 'Edit Buku')
@section('header_title', 'Edit Buku')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Edit Data Buku</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Perbarui informasi data buku {{ $book->title }}.</div>
            </div>
            <a href="{{ route('buku.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div style="background-color: #FEE2E2; color: var(--danger); border: 1px solid #FCA5A5; padding: 0.85rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; font-size: 0.875rem;">
                <ul style="margin-left: 1.25rem; margin-bottom: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('buku.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label required">Judul Buku</label>
                <input type="text" name="title" value="{{ old('title', $book->title) }}" class="form-control-tzuchi" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Ganti Foto Sampul Buku (Cover Image)</label>
                    <input type="file" name="cover_image" accept="image/*" class="form-control-tzuchi">
                    <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.25rem;">Format: JPG, PNG, WEBP, GIF (Maks. 2MB).</div>
                    
                    @if(!empty($book->cover_image))
                        <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <img src="{{ asset($book->cover_image) }}" alt="Sampul" style="height: 50px; width: 40px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                            <span style="font-size: 0.775rem; color: var(--text-muted);">Sampul Saat Ini</span>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="form-control-tzuchi">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <label class="form-label required" style="margin-bottom: 0;">Kategori</label>
                        <button type="button" onclick="openQuickModal('categoryModal')" class="btn-tzuchi btn-secondary-tzuchi btn-sm" style="padding: 0.15rem 0.5rem; font-size: 0.75rem;">
                            <i class="bi bi-plus-lg"></i> Tambah Kategori
                        </button>
                    </div>
                    <select name="category_id" id="select_category" class="form-control-tzuchi" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <label class="form-label required" style="margin-bottom: 0;">Penulis</label>
                        <button type="button" onclick="openQuickModal('authorModal')" class="btn-tzuchi btn-secondary-tzuchi btn-sm" style="padding: 0.15rem 0.5rem; font-size: 0.75rem;">
                            <i class="bi bi-plus-lg"></i> Tambah Penulis
                        </button>
                    </div>
                    <select name="author_id" id="select_author" class="form-control-tzuchi" required>
                        <option value="">-- Pilih Penulis --</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <label class="form-label required" style="margin-bottom: 0;">Penerbit</label>
                        <button type="button" onclick="openQuickModal('publisherModal')" class="btn-tzuchi btn-secondary-tzuchi btn-sm" style="padding: 0.15rem 0.5rem; font-size: 0.75rem;">
                            <i class="bi bi-plus-lg"></i> Tambah Penerbit
                        </button>
                    </div>
                    <select name="publisher_id" id="select_publisher" class="form-control-tzuchi" required>
                        <option value="">-- Pilih Penerbit --</option>
                        @foreach($publishers as $pub)
                            <option value="{{ $pub->id }}" {{ old('publisher_id', $book->publisher_id) == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <label class="form-label" style="margin-bottom: 0;">Lokasi Rak</label>
                        <button type="button" onclick="openQuickModal('shelfModal')" class="btn-tzuchi btn-secondary-tzuchi btn-sm" style="padding: 0.15rem 0.5rem; font-size: 0.75rem;">
                            <i class="bi bi-plus-lg"></i> Tambah Rak
                        </button>
                    </div>
                    <select name="shelf_id" id="select_shelf" class="form-control-tzuchi">
                        <option value="">-- Pilih Rak --</option>
                        @foreach($shelves as $shelf)
                            <option value="{{ $shelf->id }}" {{ old('shelf_id', $book->shelf_id) == $shelf->id ? 'selected' : '' }}>{{ $shelf->code }} - {{ $shelf->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" class="form-control-tzuchi">
            </div>

            <div class="form-group">
                <label class="form-label">Sinopsis / Ringkasan Buku</label>
                <textarea name="synopsis" rows="4" class="form-control-tzuchi">{{ old('synopsis', $book->synopsis) }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <a href="{{ route('buku.index') }}" class="btn-tzuchi btn-secondary-tzuchi">Batal</a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">
                    <i class="bi bi-check-lg"></i> Perbarui Buku
                </button>
            </div>
        </form>
    </div>
</div>
<div style="height: 120px;"></div>

<!-- Inline Quick Add Modal Kategori -->
<div class="modal-tzuchi-backdrop" id="categoryModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Tambah Kategori Buku Baru (Cepat)</h3>
            <button type="button" class="modal-tzuchi-close" onclick="closeQuickModal('categoryModal')">&times;</button>
        </div>
        <div class="modal-tzuchi-body">
            <div class="form-group">
                <label class="form-label required">Nama Kategori</label>
                <input type="text" id="quick_category_name" class="form-control-tzuchi" placeholder="Contoh: Pemrograman & Komputer">
            </div>
        </div>
        <div class="modal-tzuchi-footer">
            <button type="button" onclick="closeQuickModal('categoryModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
            <button type="button" onclick="saveQuickCategory()" class="btn-tzuchi btn-primary-tzuchi">Simpan & Pilih</button>
        </div>
    </div>
</div>

<!-- Inline Quick Add Modal Penulis -->
<div class="modal-tzuchi-backdrop" id="authorModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Tambah Penulis Baru (Cepat)</h3>
            <button type="button" class="modal-tzuchi-close" onclick="closeQuickModal('authorModal')">&times;</button>
        </div>
        <div class="modal-tzuchi-body">
            <div class="form-group">
                <label class="form-label required">Nama Penulis</label>
                <input type="text" id="quick_author_name" class="form-control-tzuchi" placeholder="Contoh: Andrea Hirata">
            </div>
        </div>
        <div class="modal-tzuchi-footer">
            <button type="button" onclick="closeQuickModal('authorModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
            <button type="button" onclick="saveQuickAuthor()" class="btn-tzuchi btn-primary-tzuchi">Simpan & Pilih</button>
        </div>
    </div>
</div>

<!-- Inline Quick Add Modal Penerbit -->
<div class="modal-tzuchi-backdrop" id="publisherModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Tambah Penerbit Baru (Cepat)</h3>
            <button type="button" class="modal-tzuchi-close" onclick="closeQuickModal('publisherModal')">&times;</button>
        </div>
        <div class="modal-tzuchi-body">
            <div class="form-group">
                <label class="form-label required">Nama Penerbit</label>
                <input type="text" id="quick_publisher_name" class="form-control-tzuchi" placeholder="Contoh: Penerbit Bentang Pustaka">
            </div>
        </div>
        <div class="modal-tzuchi-footer">
            <button type="button" onclick="closeQuickModal('publisherModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
            <button type="button" onclick="saveQuickPublisher()" class="btn-tzuchi btn-primary-tzuchi">Simpan & Pilih</button>
        </div>
    </div>
</div>

<!-- Inline Quick Add Modal Rak -->
<div class="modal-tzuchi-backdrop" id="shelfModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Tambah Lokasi Rak Baru (Cepat)</h3>
            <button type="button" class="modal-tzuchi-close" onclick="closeQuickModal('shelfModal')">&times;</button>
        </div>
        <div class="modal-tzuchi-body">
            <div class="form-group">
                <label class="form-label required">Kode Rak</label>
                <input type="text" id="quick_shelf_code" class="form-control-tzuchi" placeholder="Contoh: RAK-05-B">
            </div>
            <div class="form-group">
                <label class="form-label required">Nama Lokasi / Deskripsi Rak</label>
                <input type="text" id="quick_shelf_name" class="form-control-tzuchi" placeholder="Contoh: Rak Sastra & Novel">
            </div>
        </div>
        <div class="modal-tzuchi-footer">
            <button type="button" onclick="closeQuickModal('shelfModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
            <button type="button" onclick="saveQuickShelf()" class="btn-tzuchi btn-primary-tzuchi">Simpan & Pilih</button>
        </div>
    </div>
</div>

<script>
function openQuickModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeQuickModal(id) {
    document.getElementById(id).style.display = 'none';
}

function saveQuickCategory() {
    const val = document.getElementById('quick_category_name').value.trim();
    if (!val) return alert('Nama Kategori wajib diisi!');

    fetch("{{ route('categories.quickStore') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ name: val })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('select_category');
            const opt = new Option(data.name, data.id, true, true);
            select.add(opt);
            document.getElementById('quick_category_name').value = '';
            closeQuickModal('categoryModal');
        } else {
            alert('Gagal menyimpan Kategori.');
        }
    });
}

function saveQuickAuthor() {
    const val = document.getElementById('quick_author_name').value.trim();
    if (!val) return alert('Nama Penulis wajib diisi!');

    fetch("{{ route('authors.quickStore') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ name: val })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('select_author');
            const opt = new Option(data.name, data.id, true, true);
            select.add(opt);
            document.getElementById('quick_author_name').value = '';
            closeQuickModal('authorModal');
        } else {
            alert('Gagal menyimpan Penulis.');
        }
    });
}

function saveQuickPublisher() {
    const val = document.getElementById('quick_publisher_name').value.trim();
    if (!val) return alert('Nama Penerbit wajib diisi!');

    fetch("{{ route('publishers.quickStore') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ name: val })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('select_publisher');
            const opt = new Option(data.name, data.id, true, true);
            select.add(opt);
            document.getElementById('quick_publisher_name').value = '';
            closeQuickModal('publisherModal');
        } else {
            alert('Gagal menyimpan Penerbit.');
        }
    });
}

function saveQuickShelf() {
    const code = document.getElementById('quick_shelf_code').value.trim();
    const name = document.getElementById('quick_shelf_name').value.trim();
    if (!code || !name) return alert('Kode dan Nama Rak wajib diisi!');

    fetch("{{ route('shelves.quickStore') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ code: code, name: name })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('select_shelf');
            const opt = new Option(data.name, data.id, true, true);
            select.add(opt);
            document.getElementById('quick_shelf_code').value = '';
            document.getElementById('quick_shelf_name').value = '';
            closeQuickModal('shelfModal');
        } else {
            alert('Gagal menyimpan Lokasi Rak.');
        }
    });
}
</script>
@endsection
