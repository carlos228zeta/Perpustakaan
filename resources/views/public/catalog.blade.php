@extends('layouts.master')

@section('title', 'Katalog Buku Perpustakaan')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem;">
    <!-- Page Header -->
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; margin-bottom: 0.25rem;">Katalog Buku Perpustakaan</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Telusuri seluruh koleksi fisik dan digital perpustakaan Cinta Kasih Tzu Chi Cengkareng.</p>
    </div>

    <!-- Filter & Search Section -->
    <div class="card-tzuchi" style="margin-bottom: 2rem;">
        <form action="{{ route('public.books.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Pencarian</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control-tzuchi" placeholder="Judul, ISBN, Penulis...">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-control-tzuchi">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Penulis</label>
                <select name="author_id" class="form-control-tzuchi">
                    <option value="">-- Semua Penulis --</option>
                    @foreach($authors as $auth)
                        <option value="{{ $auth->id }}" {{ request('author_id') == $auth->id ? 'selected' : '' }}>{{ $auth->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Urutan</label>
                <select name="sort" class="form-control-tzuchi">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul (A - Z)</option>
                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul (Z - A)</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="flex: 1;">
                    <i class="bi bi-filter"></i> Filter
                </button>
                <a href="{{ route('public.books.index') }}" class="btn-tzuchi btn-secondary-tzuchi" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Catalog Grid -->
    <div class="book-grid" style="margin-bottom: 2rem;">
        @forelse($books as $key => $book)
            @php
                $colors = [
                    ['#2E7D32', '#1B5E20'],
                    ['#1565C0', '#0D47A1'],
                    ['#6A1B9A', '#4A148C'],
                    ['#C62828', '#8E0000'],
                    ['#D84315', '#BF360C'],
                    ['#00838F', '#006064']
                ];
                $gradient = $colors[$key % count($colors)];
            @endphp
            <div class="book-card">
                @if(!empty($book->cover_image) && file_exists(public_path($book->cover_image)))
                    <div style="width: 100%; height: 230px; overflow: hidden; background: var(--bg-color);">
                        <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s var(--ease-spring);" class="book-cover-img">
                    </div>
                @else
                    <div class="book-cover-placeholder" style="background: linear-gradient(135deg, {{ $gradient[0] }} 0%, {{ $gradient[1] }} 100%);">
                        <i class="bi bi-book book-cover-icon"></i>
                        <div class="book-cover-title">{{ $book->title }}</div>
                    </div>
                @endif

                <div class="book-info">
                    <div style="font-size: 0.75rem; color: var(--primary); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                        {{ $book->category_name ?? 'Umum' }}
                    </div>
                    <h3 class="book-title">{{ $book->title }}</h3>
                    <div class="book-author">{{ $book->author_name ?? 'Penulis Tidak Diketahui' }}</div>
                    
                    <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 0.75rem;">
                        @if(($book->available_copies ?? 0) > 0)
                            <span class="badge-tzuchi badge-success">Tersedia ({{ $book->available_copies }})</span>
                        @elseif(($book->total_copies ?? 0) > 0)
                            <span class="badge-tzuchi badge-warning">Dipinjam</span>
                        @else
                            <span class="badge-tzuchi badge-danger">Tidak Tersedia</span>
                        @endif
                        <a href="{{ route('public.books.show', $book->id) }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm">Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1;" class="empty-state">
                <div class="empty-state-title">Buku tidak ditemukan</div>
                <p>Tidak ada buku yang sesuai dengan pencarian atau filter Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Server-Side Pagination -->
    <div style="display: flex; justify-content: center;">
        {{ $books->links() }}
    </div>
</div>
@endsection
