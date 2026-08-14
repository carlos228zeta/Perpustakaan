<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Library Management System') - Cinta Kasih Tzu Chi Cengkareng</title>
    
    <!-- Unified Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/tzuchi-library.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @stack('styles')

    <!-- Theme Initialization Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('tzuchi_theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Public Navigation Bar -->
    <nav class="tzuchi-nav">
        <div class="container">
            <a href="{{ url('/') }}" class="brand-wrapper">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Cinta Kasih Tzu Chi" style="height: 38px; width: auto; object-fit: contain;">
                <div>
                    <div class="brand-title">Library Management System</div>
                    <div class="brand-subtitle">Cinta Kasih Tzu Chi Cengkareng</div>
                </div>
            </a>

            <ul class="nav-menu">
                <li><a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('public.books.index') }}" class="nav-link {{ request()->is('books*') ? 'active' : '' }}">Katalog Buku</a></li>
                
                <!-- Dark Mode Switcher Button -->
                <li>
                    <button class="theme-toggle-btn" id="themeToggle" title="Ganti Mode Terang / Gelap">
                        <i class="bi bi-moon-stars" id="themeIcon"></i>
                    </button>
                </li>

                @guest
                    <li><a href="{{ route('login') }}" class="btn-tzuchi btn-primary-tzuchi">Masuk</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}" class="btn-tzuchi btn-primary-tzuchi">Dashboard {{ auth()->user()->role->display_name ?? '' }}</a></li>
                @endguest
            </ul>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Rich Public Footer with Photos & School Info -->
    <footer class="tzuchi-footer-rich">
        <div class="footer-grid">
            <!-- Col 1: School Identity -->
            <div class="footer-column">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 42px; width: auto;">
                    <div>
                        <strong style="color: var(--text-main); font-size: 1rem;">Cinta Kasih Tzu Chi</strong>
                        <div style="font-size: 0.775rem; color: var(--text-muted);">Sekolah Cengkareng</div>
                    </div>
                </div>
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.6;">
                    Mewujudkan lingkungan perpustakaan berbudaya humanis, terdigitalisasi, dan mendukung keunggulan akademik seluruh siswa & pendidik.
                </p>
            </div>

            <!-- Col 2: Navigation Links -->
            <div class="footer-column">
                <h4>Navigasi Perpustakaan</h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}"><i class="bi bi-chevron-right"></i> Beranda Publik</a></li>
                    <li><a href="{{ route('public.books.index') }}"><i class="bi bi-chevron-right"></i> Katalog & Pencarian Buku</a></li>
                    <li><a href="{{ route('login') }}"><i class="bi bi-chevron-right"></i> Portal Anggota & Login</a></li>
                </ul>
            </div>

            <!-- Col 3: Operating Hours & Contact -->
            <div class="footer-column">
                <h4>Jam Operasional</h4>
                <div style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.8;">
                    <div><i class="bi bi-clock" style="color: var(--primary);"></i> Senin - Jumat: 07.00 - 16.00 WIB</div>
                    <div><i class="bi bi-calendar-x" style="color: var(--danger);"></i> Sabtu - Minggu & Libur Nasional: Tutup</div>
                    <div style="margin-top: 0.5rem;"><i class="bi bi-geo-alt" style="color: var(--primary);"></i> Jl. Kamal Raya No.20, Cengkareng, Jakarta Barat</div>
                </div>
            </div>

            <!-- Col 4: Campus & Library Gallery Photos -->
            <div class="footer-column">
                <h4>Galeri Perpustakaan</h4>
                <div class="footer-gallery">
                    <div class="footer-gallery-item" title="Koleksi Buku Utama"><i class="bi bi-book"></i></div>
                    <div class="footer-gallery-item" title="Ruang Baca Quiet Zone"><i class="bi bi-building"></i></div>
                    <div class="footer-gallery-item" title="Area Pustaka Digital"><i class="bi bi-laptop"></i></div>
                    <div class="footer-gallery-item" title="Rak Buku Fiksi & Sains"><i class="bi bi-journal-bookmark"></i></div>
                    <div class="footer-gallery-item" title="Diskusi Kelompok"><i class="bi bi-people"></i></div>
                    <div class="footer-gallery-item" title="Layanan Sirkulasi"><i class="bi bi-box-arrow-in-right"></i></div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                &copy; {{ date('Y') }} <strong>Sekolah Cinta Kasih Tzu Chi Cengkareng</strong>. Hak Cipta Dilindungi.
            </div>
            <div>
                Library Management System v2.0
            </div>
        </div>
    </footer>

    <!-- Theme Switcher Script -->
    <script>
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-sun';
            } else {
                themeIcon.className = 'bi bi-moon-stars';
            }
        }

        const currentTheme = localStorage.getItem('tzuchi_theme') || 'light';
        updateThemeIcon(currentTheme);

        themeToggleBtn.addEventListener('click', function() {
            let theme = document.documentElement.getAttribute('data-theme');
            let newTheme = (theme === 'dark') ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('tzuchi_theme', newTheme);
            updateThemeIcon(newTheme);
        });
    </script>
    @stack('scripts')
</body>
</html>
