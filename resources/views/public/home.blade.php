@extends('layouts.master')

@section('title', 'Beranda Perpustakaan Cinta Kasih Tzu Chi')

@section('content')
<!-- Hero Section with Dynamic Ken Burns Zoom & Auto Slider -->
@php
    $customBanner = \App\Models\LibrarySetting::get('banner_image');
    $heroSlidesJson = \App\Models\LibrarySetting::get('hero_slides_list');
    $uploadedSlides = $heroSlidesJson ? json_decode($heroSlidesJson, true) : [];
    
    $slides = [];
    if ($customBanner && file_exists(public_path($customBanner))) {
        $slides[] = asset($customBanner);
    }
    if (!empty($uploadedSlides) && is_array($uploadedSlides)) {
        foreach ($uploadedSlides as $sUrl) {
            if (file_exists(public_path($sUrl)) && count($slides) < 2) {
                $slides[] = asset($sUrl);
            }
        }
    }
    if (count($slides) < 2 && file_exists(public_path('img/hero1.jpg'))) {
        $slides[] = asset('img/hero1.jpg');
    }

    // Strictly limit slides array to exactly 2 items maximum
    $slides = array_slice($slides, 0, 2);
@endphp

<section class="hero-slider-wrapper">
    <!-- Slider Background Images with Subtle Smooth Animation -->
    <div class="hero-slider-bg">
        @foreach($slides as $index => $slideUrl)
            <div class="hero-slide-item {{ $index === 0 ? 'active' : '' }}" style="background-image: url('{{ $slideUrl }}');"></div>
        @endforeach
        <!-- Dark Overlay Gradient for Perfect Contrast -->
        <div class="hero-slider-overlay"></div>
    </div>

    <!-- Hero Floating Glass Card Content (Aligned lower to preserve background photo faces) -->
    <div class="hero-slider-content container">
        <h1 class="hero-title">
            Temukan & Telusuri Koleksi Buku Perpustakaan
        </h1>
        <p class="hero-subtitle">
            Akses ribuan modul pembelajaran, karya ilmiah, sastra, dan pustaka digital dengan cepat, nyaman, dan terstruktur.
        </p>

        <!-- Main Search Form -->
        <form action="{{ route('public.books.index') }}" method="GET" class="hero-search-form">
            <i class="bi bi-search hero-search-icon"></i>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control-tzuchi hero-search-input" placeholder="Cari judul buku, penulis, atau ISBN...">
            <button type="submit" class="btn-tzuchi btn-primary-tzuchi hero-search-btn">
                <span>Cari Buku</span> <i class="bi bi-arrow-right"></i>
            </button>
        </form>
    </div>

    <!-- Indicator Dots Navigation -->
    @if(count($slides) > 1)
        <div class="hero-slider-dots">
            @foreach($slides as $index => $slideUrl)
                <button class="hero-dot {{ $index === 0 ? 'active' : '' }}" onclick="switchHeroSlide({{ $index }})" title="Foto {{ $index + 1 }}"></button>
            @endforeach
        </div>
    @endif
</section>

<style>
/* Seamless Floating Navbar for Homepage to allow 100vh Crisp Hero Photo */
.tzuchi-nav {
  position: absolute !important;
  top: 0;
  left: 0;
  right: 0;
  width: 100%;
  z-index: 1000;
  background: linear-gradient(180deg, rgba(0, 0, 0, 0.50) 0%, rgba(0, 0, 0, 0) 100%) !important;
  backdrop-filter: none !important;
  -webkit-backdrop-filter: none !important;
  border-bottom: none !important;
  box-shadow: none !important;
}

.tzuchi-nav .brand-title {
  color: #FFFFFF !important;
  text-shadow: 0 1px 4px rgba(0,0,0,0.6);
}

.tzuchi-nav .brand-subtitle {
  color: rgba(255, 255, 255, 0.85) !important;
  text-shadow: 0 1px 4px rgba(0,0,0,0.6);
}

.tzuchi-nav .nav-link {
  color: rgba(255, 255, 255, 0.95) !important;
  text-shadow: 0 1px 4px rgba(0,0,0,0.5);
}

.tzuchi-nav .nav-link:hover, 
.tzuchi-nav .nav-link.active {
  color: #FFFFFF !important;
  background-color: rgba(46, 125, 50, 0.85) !important;
}

/* Fullscreen Hero Slider Container (100% Height of Viewport) */
.hero-slider-wrapper {
  position: relative;
  width: 100%;
  height: 100vh;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  border-bottom: 1px solid var(--border-color);
  padding: 4rem 1.25rem 2.5rem;
}

/* Background Slide Item with Crystal Clear Crisp Photo */
.hero-slider-bg {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.hero-slide-item {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
  opacity: 0;
  transition: opacity 1.2s ease-in-out;
}

.hero-slide-item.active {
  opacity: 1;
}

.hero-slider-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.6) 100%);
  z-index: 2;
}

/* Hero Content Styling */
.hero-slider-content {
  position: relative;
  z-index: 3;
  max-width: 820px;
  margin: 0 auto;
  text-align: center;
  color: #FFFFFF;
}

.hero-badge {
  margin-bottom: 1.25rem;
  font-size: 0.85rem;
  padding: 0.45rem 1.1rem;
  background: rgba(46, 125, 50, 0.9) !important;
  color: #FFFFFF !important;
  box-shadow: 0 4px 14px rgba(46, 125, 50, 0.4);
  backdrop-filter: blur(10px);
}

.hero-title {
  font-size: 2.65rem;
  font-weight: 900;
  margin-bottom: 1rem;
  color: #FFFFFF !important;
  line-height: 1.2;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8), 0 4px 15px rgba(0, 0, 0, 0.5);
  letter-spacing: -0.01em;
}

.hero-subtitle {
  font-size: 1.05rem;
  color: rgba(255, 255, 255, 0.95);
  margin-bottom: 2.25rem;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.6;
  text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.8), 0 2px 10px rgba(0, 0, 0, 0.5);
}

.hero-search-form {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  max-width: 680px;
  margin: 0 auto;
  background: rgba(255, 255, 255, 0.96) !important;
  padding: 0.5rem 0.6rem 0.5rem 1.25rem;
  border-radius: var(--radius-xl);
  border: 1px solid rgba(255, 255, 255, 0.5);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(16px);
  transition: all 0.3s ease;
}

.hero-search-form:focus-within {
  box-shadow: 0 16px 40px rgba(46, 125, 50, 0.35);
  transform: translateY(-2px);
}

.hero-search-icon {
  color: var(--primary);
  font-size: 1.2rem;
}

.hero-search-input {
  border: none !important;
  background: transparent !important;
  font-size: 1rem !important;
  padding: 0.75rem 0.5rem !important;
  color: #0F172A !important;
  box-shadow: none !important;
  width: 100%;
}

.hero-search-input::placeholder {
  color: #64748B !important;
}

.hero-search-btn {
  padding: 0.85rem 1.75rem !important;
  font-size: 0.95rem !important;
  border-radius: var(--radius-lg) !important;
  white-space: nowrap;
}

/* Dark Mode Unified Hero Search Styling */
[data-theme="dark"] .hero-search-form {
  background: rgba(15, 23, 42, 0.82) !important;
  border: 1px solid rgba(255, 255, 255, 0.25) !important;
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.65) !important;
}

[data-theme="dark"] .hero-search-input {
  background: transparent !important;
  color: #F8FAFC !important;
}

[data-theme="dark"] .hero-search-input::placeholder {
  color: #94A3B8 !important;
}

[data-theme="dark"] .hero-search-icon {
  color: #4ADE80 !important;
}

/* Dots Navigation Bar */
.hero-slider-dots {
  position: absolute;
  bottom: 2.25rem;
  left: 50%;
  transform: translateX(-50%);
  z-index: 10;
  display: flex;
  gap: 0.6rem;
  align-items: center;
}

.hero-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: 2px solid rgba(255, 255, 255, 0.9);
  background: rgba(255, 255, 255, 0.35);
  cursor: pointer;
  transition: all 0.35s ease;
  padding: 0;
}

.hero-dot.active {
  width: 32px;
  border-radius: 10px;
  background: var(--primary) !important;
  border-color: #FFFFFF !important;
  box-shadow: 0 0 12px rgba(46, 125, 50, 0.8);
}
</style>

<script>
let currentSlideIdx = 0;
let slideTimer = null;

function switchHeroSlide(idx) {
    const slides = document.querySelectorAll('.hero-slide-item');
    const dots = document.querySelectorAll('.hero-dot');
    if (!slides.length) return;

    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));

    currentSlideIdx = idx;
    if (slides[currentSlideIdx]) slides[currentSlideIdx].classList.add('active');
    if (dots[currentSlideIdx]) dots[currentSlideIdx].classList.add('active');
}

document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide-item');
    if (slides.length > 1) {
        slideTimer = setInterval(function() {
            let next = (currentSlideIdx + 1) % slides.length;
            switchHeroSlide(next);
        }, 3500);
    }
});
</script>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2.5rem 1.25rem;">
    <!-- Featured Hero Banner Section (Dynamic from Admin Banner Settings) -->
    @php
        $bannerImg = \App\Models\LibrarySetting::get('banner_image');
        $bannerBadge = \App\Models\LibrarySetting::get('banner_badge', '🌟 Program Literasi & Keunggulan Akademik');
        $bannerTitle = \App\Models\LibrarySetting::get('banner_title', 'Festival Membaca & Eksplorasi Pustaka Cinta Kasih');
        $bannerSubtitle = \App\Models\LibrarySetting::get('banner_subtitle', 'Tingkatkan wawasan, perluas cakrawala, dan kembangkan budi pekerti humanis melalui ribuan koleksi buku pilihan.');
        $bannerBtnText = \App\Models\LibrarySetting::get('banner_button_text', 'Telusuri Koleksi Pilihan');
        $bannerBtnLink = \App\Models\LibrarySetting::get('banner_button_link', '/books');

        $hasBannerImg = ($bannerImg && file_exists(public_path($bannerImg)));
        $bannerImgUrl = $hasBannerImg ? asset($bannerImg) : null;
    @endphp

    <div class="card-tzuchi" style="position: relative; overflow: hidden; border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 0; margin-bottom: 3rem; box-shadow: var(--shadow-md); background: var(--surface);">
        <div style="display: grid; grid-template-columns: {{ $hasBannerImg ? '1.1fr 0.9fr' : '1fr' }}; align-items: center;">
            <div style="padding: 2.5rem 2.25rem;">
                <span class="badge-tzuchi badge-success" style="font-size: 0.8rem; padding: 0.4rem 0.9rem; margin-bottom: 1rem; display: inline-block;">
                    {{ $bannerBadge }}
                </span>
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.75rem; line-height: 1.3;">
                    {{ $bannerTitle }}
                </h2>
                <p style="font-size: 0.975rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 1.75rem;">
                    {{ $bannerSubtitle }}
                </p>
                <a href="{{ $bannerBtnLink }}" class="btn-tzuchi btn-primary-tzuchi" style="padding: 0.75rem 1.6rem; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <span>{{ $bannerBtnText }}</span> <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @if($hasBannerImg)
                <div style="height: 100%; min-height: 280px; position: relative; overflow: hidden; background: var(--bg-color);">
                    <img src="{{ $bannerImgUrl }}" alt="Featured Banner" style="width: 100%; height: 100%; min-height: 280px; object-fit: cover; object-position: center;">
                </div>
            @endif
        </div>
    </div>

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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <div>
                <h2 style="font-size: 1.35rem; margin-bottom: 0.2rem; font-weight: 800;">Eksplorasi Kategori Buku</h2>
                <div style="font-size: 0.85rem; color: var(--text-muted);">Pilih bidang literasi yang ingin Anda pelajari</div>
            </div>
            <a href="{{ route('public.books.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm">Lihat Semua Katalog <i class="bi bi-arrow-right"></i></a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.25rem;">
            @php
                $iconMap = [
                    'Pendidikan' => 'bi-mortarboard-fill',
                    'Teknologi' => 'bi-laptop-fill',
                    'Sains' => 'bi-lightbulb-fill',
                    'Fiksi' => 'bi-stars',
                    'Non-Fiksi' => 'bi-journal-check',
                    'Bahasa' => 'bi-translate',
                    'Sejarah' => 'bi-hourglass-split',
                    'Agama' => 'bi-heart-fill',
                    'Referensi' => 'bi-journal-bookmark-fill',
                    'Komputer' => 'bi-cpu-fill',
                    'Novel' => 'bi-book-half',
                    'Komik' => 'bi-chat-heart-fill',
                    'Majalah' => 'bi-newspaper',
                ];
            @endphp
            @foreach($categories as $category)
                @php
                    $catIcon = $iconMap[$category->name] ?? 'bi-journal-bookmark';
                @endphp
                <a href="{{ route('public.books.index', ['category_id' => $category->id]) }}" class="category-card">
                    <div class="category-icon-wrapper">
                        <i class="bi {{ $catIcon }}"></i>
                    </div>
                    <div style="font-weight: 700; font-size: 1rem; color: var(--text-main);">{{ $category->name }}</div>
                    <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.3rem;">
                        <span class="badge-tzuchi badge-secondary" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">{{ $category->total_books }} Koleksi</span>
                    </div>
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
