@extends('layouts.admin')

@section('title', $book->title)
@section('header_title', 'Informasi Detail Buku')

@section('content')
<div class="container" style="max-width: 1000px; margin: 0 auto; padding: 2rem 1.25rem;">
    <!-- Breadcrumb -->
    <div style="margin-bottom: 1.5rem; font-size: 0.875rem; color: var(--text-muted);">
        <a href="{{ url('/') }}">Beranda</a> / <a href="{{ route('katalog.index') }}">Katalog</a> / <span>Detail Buku</span>
    </div>

    <div class="card-tzuchi" style="padding: 2rem;">
        <div style="display: grid; grid-template-columns: 240px 1fr; gap: 2rem;">
            <!-- Cover Side -->
            <div>
                @if(!empty($book->cover_image) && file_exists(public_path($book->cover_image)))
                    <div style="width: 100%; height: 320px; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color); background: var(--bg-color);">
                        <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @else
                    <div style="width: 100%; height: 320px; background: linear-gradient(135deg, #2E7D32 0%, #1565C0 100%); border-radius: var(--radius-md); display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; padding: 1.5rem; text-align: center;">
                        <i class="bi bi-journal-text" style="font-size: 4rem; margin-bottom: 0.75rem;"></i>
                        <div style="font-weight: 700; font-size: 0.95rem;">{{ $book->title }}</div>
                    </div>
                @endif

                <div style="margin-top: 1.5rem;">
                    @guest
                        <a href="{{ route('login') }}" class="btn-tzuchi btn-primary-tzuchi" style="width: 100%; text-align: center;">
                            <i class="bi bi-box-arrow-in-right"></i> Login untuk Meminjam
                        </a>
                    @else
                        @if($availableCount > 0)
                            <form action="{{ route('peminjaman.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                
                                <div class="form-group" style="margin-bottom: 0.75rem;">
                                    <label class="form-label required" style="font-size: 0.8rem;">Rencana Pengembalian</label>
                                    <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-control-tzuchi" style="font-size: 0.825rem; padding: 0.45rem 0.65rem;" required>
                                </div>

                                <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="width: 100%;">
                                    <i class="bi bi-hand-index-thumb"></i> Ajukan Peminjaman
                                </button>
                            </form>
                        @else
                            <button disabled class="btn-tzuchi btn-secondary-tzuchi" style="width: 100%; opacity: 0.6; cursor: not-allowed;">
                                Buku Sedang Dipinjam
                            </button>
                        @endif
                    @endguest
                </div>
            </div>

            <!-- Detail Info -->
            <div>
                <div style="margin-bottom: 1rem;">
                    <span class="badge-tzuchi badge-success" style="margin-bottom: 0.5rem;">
                        {{ $book->category_name ?? 'Umum' }}
                    </span>
                    <h1 style="font-size: 1.85rem; margin-top: 0.25rem; margin-bottom: 0.5rem; color: var(--text-main);">
                        {{ $book->title }}
                    </h1>
                    <div style="font-size: 1.05rem; color: var(--text-muted); font-weight: 500;">
                        Oleh: {{ $book->author_name ?? 'Penulis Tidak Diketahui' }}
                    </div>
                </div>

                <!-- Status & Availability -->
                <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: var(--bg-color); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <div>
                        <div style="font-size: 0.775rem; color: var(--text-muted);">Status Buku</div>
                        @if($availableCount > 0)
                            <strong style="color: var(--primary);">Tersedia</strong>
                        @elseif($totalCopies > 0)
                            <strong style="color: var(--warning);">Sedang Dipinjam</strong>
                        @else
                            <strong style="color: var(--danger);">Tidak Tersedia</strong>
                        @endif
                    </div>
                    <div style="border-left: 1px solid var(--border-color); padding-left: 1rem;">
                        <div style="font-size: 0.775rem; color: var(--text-muted);">Jumlah Eksemplar</div>
                        <strong>{{ $totalCopies }} Fisik ({{ $availableCount }} Tersedia)</strong>
                    </div>
                    <div style="border-left: 1px solid var(--border-color); padding-left: 1rem;">
                        <div style="font-size: 0.775rem; color: var(--text-muted);">Lokasi Rak</div>
                        <strong>{{ $book->shelf_code ?? '-' }} ({{ $book->shelf_name ?? 'Rak Utama' }})</strong>
                    </div>
                </div>

                <!-- Metadata Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 2rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <div>
                        <span style="color: var(--text-muted);">ISBN:</span>
                        <strong style="display: block;">{{ $book->isbn ?? '-' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted);">Penerbit:</span>
                        <strong style="display: block;">{{ $book->publisher_name ?? '-' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted);">Tahun Terbit:</span>
                        <strong style="display: block;">{{ $book->publication_year ?? '-' }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted);">Bahasa:</span>
                        <strong style="display: block;">{{ $book->language ?? 'Indonesia' }}</strong>
                    </div>
                </div>

                <!-- Synopsis -->
                <div>
                    <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Sinopsis Buku</h3>
                    <p style="color: var(--text-main); font-size: 0.925rem; line-height: 1.7; white-space: pre-line;">
                        {{ $book->synopsis ?? 'Sinopsis belum tersedia untuk buku ini.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
