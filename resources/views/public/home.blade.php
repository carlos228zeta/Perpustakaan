@extends('layouts.master')

@section('title', 'Beranda Perpustakaan Cinta Kasih Tzu Chi')

@section('content')
<!-- Hero Section -->
<section style="background: linear-gradient(180deg, var(--surface) 0%, var(--bg-color) 100%); padding: 3.5rem 1.25rem 3rem; text-align: center; border-bottom: 1px solid var(--border-color);">
    <div style="max-width: 800px; margin: 0 auto;" class="animate-fade-in">
        <span class="badge-tzuchi badge-success" style="margin-bottom: 1rem; font-size: 0.825rem; padding: 0.4rem 1rem;">
            Perpustakaan Cinta Kasih Tzu Chi Cengkareng
        </span>
        <h1 style="font-size: 2.35rem; margin-bottom: 0.75rem; color: var(--text-main); line-height: 1.25;">
            Temukan & Telusuri Koleksi Buku Perpustakaan
        </h1>
        <p style="font-size: 1.05rem; color: var(--text-muted); margin-bottom: 2rem; max-width: 680px; margin-left: auto; margin-right: auto;">
            Akses ribuan modul pembelajaran, karya ilmiah, sastra, dan pustaka digital dengan cepat, nyaman, dan terstruktur.
        </p>

        <!-- Main Search Form -->
        <form action="{{ route('public.books.index') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 650px; margin: 0 auto; background: var(--surface); padding: 0.6rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-md);">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control-tzuchi" placeholder="Cari judul buku, penulis, atau ISBN..." style="border: none; font-size: 1rem; padding: 0.75rem 1rem;">
            <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="padding: 0.75rem 1.5rem; font-size: 0.95rem;">
                <i class="bi bi-search"></i> Cari Buku
            </button>
        </form>
    </div>
</section>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2.5rem 1.25rem;">
    <!-- Buku Terbaru Section -->
    <div style="margin-bottom: 3.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
            <div>
                <h2 style="font-size: 1.35rem; margin-bottom: 0.25rem;">Buku Terbaru</h2>
                <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0;">Koleksi perpustakaan yang baru saja ditambahkan</p>
            </div>
            <a href="{{ route('public.books.index') }}" style="font-weight: 600; font-size: 0.9rem;">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="book-grid">
            @forelse($latestBooks as $key => $book)
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
                            <i class="bi bi-journal-text book-cover-icon"></i>
                            <div class="book-cover-title">{{ $book->title }}</div>
                        </div>
                    @endif

                    <div class="book-info">
                        <div style="font-size: 0.75rem; color: var(--primary); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                            {{ $book->category_name ?? 'Umum' }}
                        </div>
                        <h3 class="book-title">{{ $book->title }}</h3>
                        <div class="book-author">{{ $book->author_name ?? 'Penulis Tidak Diketahui' }}</div>
                        <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; padding-top: 0.5rem;">
                            <span class="badge-tzuchi badge-success">Tersedia</span>
                            <a href="{{ route('public.books.show', $book->id) }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm">Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1;" class="empty-state">
                    <div class="empty-state-title">Belum ada koleksi buku</div>
                    <p>Koleksi buku baru belum ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Kategori Buku Section -->
    <div style="margin-bottom: 3.5rem;">
        <h2 style="font-size: 1.35rem; margin-bottom: 1.25rem;">Kategori Buku</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem;">
            @foreach($categories as $category)
                <a href="{{ route('public.books.index', ['category_id' => $category->id]) }}" class="category-card">
                    <div class="category-icon-wrapper">
                        <i class="bi bi-bookmark-star"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 0.95rem;">{{ $category->name }}</div>
                    <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.25rem;">{{ $category->total_books }} Buku</div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Informasi Sekolah Cinta Kasih Tzu Chi -->
    <div class="card-tzuchi" style="border-left: 4px solid var(--primary); padding: 2rem;">
        <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 60px; width: auto;">
            <div style="flex: 1;">
                <h3 style="font-size: 1.2rem; margin-bottom: 0.4rem;">Perpustakaan Sekolah Cinta Kasih Tzu Chi Cengkareng</h3>
                <p style="color: var(--text-muted); font-size: 0.925rem; line-height: 1.6; margin: 0;">
                    Berkomitmen menyediakan lingkungan belajar yang kondusif, mendukung literasi akademik, serta membentuk karakter siswa yang berbudaya humanis, disiplin, dan berwawasan luas.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
