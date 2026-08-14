<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('/img/logo.png') }}" rel="icon">
    <title>Library Management System — Cinta Kasih Tzu Chi Cengkareng</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2E7D32;
            --primary-dark: #1b5e20;
            --secondary: #558B2F;
            --background: #F7F8F7;
            --surface: #FFFFFF;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --border: #E5E7EB;
            --success: #2E7D32;
            --warning: #D97706;
            --danger: #DC2626;
        }

        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary) !important;
        }

        .main-content {
            flex-grow: 1;
        }

        .btn-primary-custom {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: white;
        }

        .btn-outline-custom {
            border: 1.5px solid var(--primary);
            color: var(--primary);
            font-weight: 500;
            border-radius: 6px;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-outline-custom:hover {
            background-color: var(--primary);
            color: white;
        }

        .footer-custom {
            background-color: var(--surface);
            border-top: 1px solid var(--border);
            padding: 2.5rem 0 1.5rem 0;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .footer-custom a {
            color: var(--text-muted);
            text-decoration: none;
        }

        .footer-custom a:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top bg-white border-bottom py-2 shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('/img/logo.png') }}" alt="Tzu Chi Logo" height="40" class="me-2" onerror="this.src='https://via.placeholder.com/40?text=TZU+CHI'">
                <div>
                    <div style="font-size: 1.1rem; line-height: 1.2;">Library Management System</div>
                    <div class="small fw-normal text-muted" style="font-size: 0.75rem;">Cinta Kasih Tzu Chi Cengkareng</div>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center fw-medium">
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->is('/') ? 'active fw-bold text-success' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->is('books*') ? 'active fw-bold text-success' : '' }}" href="{{ route('public.books.index') }}">Katalog Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ route('public.books.index') }}#kategori">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ url('/') }}#tentang">Tentang Perpustakaan</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary-custom btn-sm text-white px-3">
                                <i class="fas fa-user-circle me-1"></i> Dashboard ({{ Auth::user()->name }})
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary-custom btn-sm text-white px-4">
                                Masuk
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer-custom mt-auto">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <h6 class="fw-bold text-dark mb-2">Library Management System</h6>
                    <p class="text-muted mb-2">Cinta Kasih Tzu Chi Cengkareng</p>
                    <p class="small text-muted mb-0">Platform perpustakaan sekolah untuk kemudahan telusur koleksi buku, peminjaman, dan reservasi buku secara terintegrasi.</p>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold text-dark mb-2">Navigasi Utama</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><a href="{{ url('/') }}">Beranda</a></li>
                        <li class="mb-1"><a href="{{ route('public.books.index') }}">Katalog Buku</a></li>
                        <li class="mb-1"><a href="{{ url('/') }}#tentang">Tentang Perpustakaan</a></li>
                        <li class="mb-1"><a href="{{ route('login') }}">Login System</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-dark mb-2">Kontak Perpustakaan</h6>
                    <p class="small text-muted mb-1"><i class="fas fa-map-marker-alt me-2 text-success"></i> Jl. Kamal Raya No.20, Cengkareng, Jakarta Barat</p>
                    <p class="small text-muted mb-1"><i class="fas fa-envelope me-2 text-success"></i> perpustakaan@tzuchi.sch.id</p>
                    <p class="small text-muted mb-0"><i class="fas fa-phone me-2 text-success"></i> (021) 5439-7462</p>
                </div>
            </div>
            <div class="border-top pt-3 text-center small text-muted">
                &copy; {{ date('Y') }} Library Management System — Cinta Kasih Tzu Chi Cengkareng. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
