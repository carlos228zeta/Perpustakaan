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

        <form action="{{ route('buku.update', $book->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label required">Judul Buku</label>
                <input type="text" name="title" value="{{ old('title', $book->title) }}" class="form-control-tzuchi" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Nomor ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="form-control-tzuchi">
                </div>

                <div class="form-group">
                    <label class="form-label">Tahun Terbit</label>
                    <input type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" class="form-control-tzuchi">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">Kategori</label>
                    <select name="category_id" class="form-control-tzuchi" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label required">Penulis</label>
                    <select name="author_id" class="form-control-tzuchi" required>
                        <option value="">-- Pilih Penulis --</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">Penerbit</label>
                    <select name="publisher_id" class="form-control-tzuchi" required>
                        <option value="">-- Pilih Penerbit --</option>
                        @foreach($publishers as $pub)
                            <option value="{{ $pub->id }}" {{ old('publisher_id', $book->publisher_id) == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi Rak</label>
                    <select name="shelf_id" class="form-control-tzuchi">
                        <option value="">-- Pilih Rak --</option>
                        @foreach($shelves as $shelf)
                            <option value="{{ $shelf->id }}" {{ old('shelf_id', $book->shelf_id) == $shelf->id ? 'selected' : '' }}>{{ $shelf->code }} - {{ $shelf->name }}</option>
                        @endforeach
                    </select>
                </div>
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
@endsection
