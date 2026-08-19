<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $faviconLogo = \App\Models\LibrarySetting::get('institution_logo');
        $faviconUrl = ($faviconLogo && file_exists(public_path($faviconLogo))) ? asset($faviconLogo) : asset('img/logo.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    <title>Library Management System — Cinta Kasih Tzu Chi Cengkareng</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/tzuchi-library.css') }}">
    
    <style>
        .nav-link-custom {
            color: var(--text-secondary) !important;
            font-weight: 600 !important;
            padding: 0.5rem 0.9rem !important;
            border-radius: var(--radius-md) !important;
            transition: all 0.2s ease !important;
            display: inline-block !important;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--primary) !important;
            background-color: var(--primary-light) !important;
        }

        .footer-custom {
            background-color: var(--surface);
            border-top: 1px solid var(--border-color);
            padding: 2.75rem 0 1.5rem 0;
            color: var(--text-muted);
            font-size: 0.885rem;
        }

        .footer-custom a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-custom a:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <!-- Navbar (Glassmorphism Horizontal Sticky) -->
    <nav class="navbar navbar-expand-lg sticky-top border-bottom py-2.5" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); box-shadow: var(--shadow-sm);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}" style="text-decoration: none;">
                <img src="{{ asset('/img/logo.png') }}" alt="Tzu Chi Logo" height="42" onerror="this.src='https://via.placeholder.com/42?text=TZU+CHI'">
                <div>
                    <div style="font-size: 1.05rem; font-weight: 800; color: var(--primary); line-height: 1.2;">Library Management System</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">Cinta Kasih Tzu Chi Cengkareng</div>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center d-flex flex-column flex-lg-row gap-1 mt-2 mt-lg-0 mb-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->is('books*') ? 'active' : '' }}" href="{{ route('public.books.index') }}">Katalog Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('public.books.index') }}#kategori">Kategori</a>
                    </li>
                    <li class="nav-item me-lg-2">
                        <a class="nav-link nav-link-custom" href="{{ url('/') }}#tentang">Tentang Perpustakaan</a>
                    </li>
                    <li class="nav-item">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-tzuchi btn-primary-tzuchi" style="padding: 0.5rem 1.15rem; font-size: 0.85rem; text-decoration: none; display: inline-flex;">
                                <i class="bi bi-person-circle"></i> Dashboard ({{ Auth::user()->name }})
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-tzuchi btn-primary-tzuchi" style="padding: 0.5rem 1.35rem; font-size: 0.85rem; text-decoration: none; display: inline-flex;">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Footer Section -->
    <footer class="footer-custom mt-auto">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 1rem;">Library Management System</h6>
                    <p class="text-success fw-semibold mb-2" style="font-size: 0.885rem;">Cinta Kasih Tzu Chi Cengkareng</p>
                    <p class="small text-muted mb-0" style="line-height: 1.6;">Platform perpustakaan sekolah untuk kemudahan telusur koleksi buku, peminjaman, dan reservasi buku secara terintegrasi.</p>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;">Navigasi Utama</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="{{ url('/') }}"><i class="bi bi-chevron-right me-1 text-success small"></i> Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('public.books.index') }}"><i class="bi bi-chevron-right me-1 text-success small"></i> Katalog Buku</a></li>
                        <li class="mb-2"><a href="{{ url('/') }}#tentang"><i class="bi bi-chevron-right me-1 text-success small"></i> Tentang Perpustakaan</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}"><i class="bi bi-chevron-right me-1 text-success small"></i> Login System</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;">Kontak Perpustakaan</h6>
                    <p class="small text-muted mb-2"><i class="bi bi-geo-alt-fill me-2 text-success"></i> Jl. Kamal Raya No.20, Cengkareng, Jakarta Barat</p>
                    <p class="small text-muted mb-2"><i class="bi bi-envelope-fill me-2 text-success"></i> perpustakaan@tzuchi.sch.id</p>
                    <p class="small text-muted mb-0"><i class="bi bi-telephone-fill me-2 text-success"></i> (021) 5439-7462</p>
                </div>
            </div>
            <div class="border-top pt-3 text-center small text-muted">
                &copy; {{ date('Y') }} Library Management System — Cinta Kasih Tzu Chi Cengkareng. Hak Cipta Dilindungi.<br>
                Developed by <strong style="color: var(--primary);">Credibug Partner Solution</strong>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
