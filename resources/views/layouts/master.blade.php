<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Library Management System') - Cinta Kasih Tzu Chi Cengkareng</title>
    
    @php
        $faviconLogo = \App\Models\LibrarySetting::get('institution_logo');
        $faviconUrl = ($faviconLogo && file_exists(public_path($faviconLogo))) ? asset($faviconLogo) : asset('img/logo.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <!-- Unified Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/tzuchi-library.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {!! \App\Models\LibrarySetting::getCustomCssVariables() !!}
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

    @php
        $pubAppTitle = \App\Models\LibrarySetting::get('app_title', 'Library Management System');
        $pubAppSubtitle = \App\Models\LibrarySetting::get('app_subtitle', 'Cinta Kasih Tzu Chi Cengkareng');
        $pubCustomLogo = \App\Models\LibrarySetting::get('institution_logo');
        $pubLogoSrc = ($pubCustomLogo && file_exists(public_path($pubCustomLogo))) ? asset($pubCustomLogo) : asset('img/logo.png');
    @endphp

    <!-- Public Navigation Bar -->
    <nav class="tzuchi-nav">
        <div class="container">
            <a href="{{ url('/') }}" class="brand-wrapper">
                <img src="{{ $pubLogoSrc }}" alt="Logo Institusi" style="height: 48px; width: auto; object-fit: contain;">
                <div>
                    <div class="brand-title">{{ $pubAppTitle }}</div>
                    <div class="brand-subtitle">{{ $pubAppSubtitle }}</div>
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

    <!-- Rich Public Footer with Interactive Gallery Links -->
    @php
        $footerDesc = \App\Models\LibrarySetting::get('footer_description', 'Mewujudkan lingkungan perpustakaan berbudaya humanis, terdigitalisasi, dan mendukung keunggulan akademik seluruh siswa & pendidik.');
        $weekdayHours = \App\Models\LibrarySetting::get('operating_hours_weekday', 'Senin - Jumat: 07.00 - 16.00 WIB');
        $weekendHours = \App\Models\LibrarySetting::get('operating_hours_weekend', 'Sabtu - Minggu & Libur: Tutup');
        $libAddress = \App\Models\LibrarySetting::get('library_address', 'Jl. Kamal Raya No.20, Cengkareng, Jakarta Barat');
        $libEmail = \App\Models\LibrarySetting::get('library_email', 'perpustakaan@tzuchi.sch.id');
        $libPhone = \App\Models\LibrarySetting::get('library_phone', '(021) 5439-7462');
        $instName = \App\Models\LibrarySetting::get('institution_name', 'Cinta Kasih Tzu Chi Cengkareng');
        
        $layananTataTertib = \App\Models\LibrarySetting::get('layanan_tata_tertib');
        $layananRuangBaca = \App\Models\LibrarySetting::get('layanan_ruang_baca');
        $layananWifi = \App\Models\LibrarySetting::get('layanan_wifi');
        $layananFaq = \App\Models\LibrarySetting::get('layanan_faq');
    @endphp
    <footer class="tzuchi-footer-rich">
        <div class="footer-grid">
            <!-- Col 1: School Identity -->
            <div class="footer-column">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 42px; width: auto;">
                    <div>
                        <strong style="color: var(--text-main); font-size: 1rem;">{{ $instName }}</strong>
                        <div style="font-size: 0.775rem; color: var(--text-muted);">Sekolah Cengkareng</div>
                    </div>
                </div>
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.6;">
                    {{ $footerDesc }}
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
                    <div><i class="bi bi-clock" style="color: var(--primary);"></i> {{ $weekdayHours }}</div>
                    <div><i class="bi bi-calendar-x" style="color: var(--danger);"></i> {{ $weekendHours }}</div>
                    <div style="margin-top: 0.5rem;"><i class="bi bi-geo-alt" style="color: var(--primary);"></i> {{ $libAddress }}</div>
                    <div><i class="bi bi-envelope" style="color: var(--primary);"></i> {{ $libEmail }}</div>
                    <div><i class="bi bi-telephone" style="color: var(--primary);"></i> {{ $libPhone }}</div>
                </div>
            </div>

            <!-- Col 4: Layanan & Fasilitas -->
            <div class="footer-column">
                <h4>Layanan & Fasilitas</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('public.books.index') }}"><i class="bi bi-book-half" style="color: var(--primary);"></i> Katalog Buku Digital</a></li>
                    <li><a href="javascript:void(0)" onclick="openTzuchiModal('modalTataTertib')"><i class="bi bi-journal-check" style="color: var(--primary);"></i> Tata Tertib Peminjaman</a></li>
                    <li><a href="javascript:void(0)" onclick="openTzuchiModal('modalRuangBaca')"><i class="bi bi-building-check" style="color: var(--primary);"></i> Ruang Baca & Quiet Zone</a></li>
                    <li><a href="javascript:void(0)" onclick="openTzuchiModal('modalWifi')"><i class="bi bi-wifi" style="color: var(--primary);"></i> Layanan Digital & Wi-Fi</a></li>
                    <li><a href="javascript:void(0)" onclick="openTzuchiModal('modalFaq')"><i class="bi bi-headset" style="color: var(--primary);"></i> Bantuan & FAQ Sirkulasi</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                &copy; {{ date('Y') }} <strong>Sekolah Cinta Kasih Tzu Chi Cengkareng</strong>. Hak Cipta Dilindungi.<br>
                Developed by <strong>Credibug Partner Solution</strong>
            </div>
            <div>
                Library Management System v2.0
            </div>
        </div>
    </footer>

    <!-- Interactive Modals for Layanan & Fasilitas -->
    <!-- Modal 1: Tata Tertib Peminjaman -->
    <div class="tzuchi-modal-backdrop" id="modalTataTertib">
        <div class="tzuchi-modal-card">
            <div class="tzuchi-modal-header">
                <h3><i class="bi bi-journal-check" style="color: var(--primary);"></i> Tata Tertib Peminjaman Buku</h3>
                <button class="tzuchi-modal-close" onclick="closeTzuchiModal('modalTataTertib')">&times;</button>
            </div>
            <div class="tzuchi-modal-body">
                <p><strong>Panduan & Ketentuan Peminjaman Perpustakaan Tzu Chi:</strong></p>
                @if(!empty($layananTataTertib))
                    <div style="white-space: pre-line; margin-bottom: 1rem;">{!! e($layananTataTertib) !!}</div>
                @else
                    <ul style="padding-left: 1.25rem; margin-bottom: 1rem;">
                        <li><strong>Batas Maksimal Pinjam:</strong> Siswa (maksimal 3 buku), Guru/Staf (maksimal 5 buku).</li>
                        <li><strong>Durasi Peminjaman:</strong> Masa pinjam buku adalah <strong>7 Hari</strong> dan dapat diperpanjang 1x jika buku tidak sedang dipesan oleh anggota lain.</li>
                        <li><strong>Ketentuan Keterlambatan:</strong> Terlambat mengembalikan buku dikenakan denda sesuai regulasi per hari per buku.</li>
                        <li><strong>Perawatan Buku:</strong> Dilarang mencoret, melipat, atau merusak buku. Kerusakan atau kehilangan wajib diganti dengan buku serupa.</li>
                    </ul>
                @endif
                <div style="background: var(--surface-hover); padding: 0.85rem 1rem; border-radius: var(--radius-md); border-left: 4px solid var(--primary); font-size: 0.85rem;">
                    <i class="bi bi-info-circle-fill" style="color: var(--primary);"></i> Peminjaman dan pengembalian dilakukan secara mandiri melalui petugas sirkulasi atau sistem online.
                </div>
            </div>
            <div class="tzuchi-modal-footer">
                <button class="btn-tzuchi btn-primary-tzuchi btn-sm" onclick="closeTzuchiModal('modalTataTertib')">Mengerti</button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Ruang Baca & Quiet Zone -->
    <div class="tzuchi-modal-backdrop" id="modalRuangBaca">
        <div class="tzuchi-modal-card">
            <div class="tzuchi-modal-header">
                <h3><i class="bi bi-building-check" style="color: var(--primary);"></i> Ruang Baca & Quiet Zone</h3>
                <button class="tzuchi-modal-close" onclick="closeTzuchiModal('modalRuangBaca')">&times;</button>
            </div>
            <div class="tzuchi-modal-body">
                <p><strong>Fasilitas Ruang Baca Perpustakaan Cinta Kasih Tzu Chi:</strong></p>
                @if(!empty($layananRuangBaca))
                    <div style="white-space: pre-line; margin-bottom: 1rem;">{!! e($layananRuangBaca) !!}</div>
                @else
                    <ul style="padding-left: 1.25rem; margin-bottom: 1rem;">
                        <li><strong>Quiet Zone (Area Hening):</strong> Disediakan bagi siswa/guru yang memerlukan ketenangan penuh saat membaca & belajar mandiri.</li>
                        <li><strong>Area Diskusi Kelompok:</strong> Meja kelompok dengan akses port listrik untuk kegiatan belajar atau tugas kelompok.</li>
                        <li><strong>Suhu & Kenyamanan:</strong> Ruangan ber-AC, penerangan standar internasional untuk menjaga kesehatan mata.</li>
                        <li><strong>Etika Ruangan:</strong> Harap menjaga kebersihan, mematikan/mengubah ponsel ke mode hening, dan tidak membawa makanan berat ke dalam perpustakaan.</li>
                    </ul>
                @endif
            </div>
            <div class="tzuchi-modal-footer">
                <button class="btn-tzuchi btn-primary-tzuchi btn-sm" onclick="closeTzuchiModal('modalRuangBaca')">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal 3: Layanan Digital & Wi-Fi -->
    <div class="tzuchi-modal-backdrop" id="modalWifi">
        <div class="tzuchi-modal-card">
            <div class="tzuchi-modal-header">
                <h3><i class="bi bi-wifi" style="color: var(--primary);"></i> Layanan Digital & Akses Wi-Fi</h3>
                <button class="tzuchi-modal-close" onclick="closeTzuchiModal('modalWifi')">&times;</button>
            </div>
            <div class="tzuchi-modal-body">
                <p><strong>Fasilitas Digital & Internet Perpustakaan:</strong></p>
                @if(!empty($layananWifi))
                    <div style="white-space: pre-line; margin-bottom: 1rem;">{!! e($layananWifi) !!}</div>
                @else
                    <ul style="padding-left: 1.25rem; margin-bottom: 1rem;">
                        <li><strong>SSID Wi-Fi Bebas:</strong> <code>TzuChi_Library_HighSpeed</code></li>
                        <li><strong>Katalog Komputer (OPAC):</strong> Disediakan perangkat komputer sirkulasi di area depan perpustakaan untuk pencarian judul/penulis secara cepat.</li>
                        <li><strong>Sistem LMS v2.0:</strong> Anggota dapat mengecek ketersediaan buku, tanggal jatuh tempo, dan riwayat denda kapan saja via laptop atau smartphone.</li>
                    </ul>
                @endif
            </div>
            <div class="tzuchi-modal-footer">
                <button class="btn-tzuchi btn-primary-tzuchi btn-sm" onclick="closeTzuchiModal('modalWifi')">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal 4: Bantuan & FAQ Sirkulasi -->
    <div class="tzuchi-modal-backdrop" id="modalFaq">
        <div class="tzuchi-modal-card">
            <div class="tzuchi-modal-header">
                <h3><i class="bi bi-headset" style="color: var(--primary);"></i> Bantuan & FAQ Sirkulasi</h3>
                <button class="tzuchi-modal-close" onclick="closeTzuchiModal('modalFaq')">&times;</button>
            </div>
            <div class="tzuchi-modal-body">
                @if(!empty($layananFaq))
                    <div style="white-space: pre-line; margin-bottom: 1rem;">{!! e($layananFaq) !!}</div>
                @else
                    <div style="margin-bottom: 0.85rem;">
                        <strong>Q: Bagaimana cara menjadi anggota perpustakaan?</strong>
                        <div style="color: var(--text-muted); font-size: 0.85rem;">A: Seluruh siswa dan guru Sekolah Cinta Kasih Tzu Chi otomatis memiliki akun. Silakan login menggunakan nomor ID / Akun Sekolah.</div>
                    </div>
                    <div style="margin-bottom: 0.85rem;">
                        <strong>Q: Bagaimana jika buku yang ingin dipinjam sedang habis?</strong>
                        <div style="color: var(--text-muted); font-size: 0.85rem;">A: Anda dapat melakukan reservasi buku secara online melalui katalog sistem.</div>
                    </div>
                    <div>
                        <strong>Q: Kontak Petugas Perpustakaan?</strong>
                        <div style="color: var(--text-muted); font-size: 0.85rem;">A: Email: {{ $libEmail }} | Telepon: {{ $libPhone }}</div>
                    </div>
                @endif
            </div>
            <div class="tzuchi-modal-footer">
                <button class="btn-tzuchi btn-primary-tzuchi btn-sm" onclick="closeTzuchiModal('modalFaq')">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Theme Switcher & Modal Script -->
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

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                let theme = document.documentElement.getAttribute('data-theme');
                let newTheme = (theme === 'dark') ? 'light' : 'dark';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('tzuchi_theme', newTheme);
                updateThemeIcon(newTheme);
            });
        }

        function openTzuchiModal(id) {
            const m = document.getElementById(id);
            if(m) m.classList.add('show');
        }

        function closeTzuchiModal(id) {
            const m = document.getElementById(id);
            if(m) m.classList.remove('show');
        }

        // Close modal when clicking outside card
        document.querySelectorAll('.tzuchi-modal-backdrop').forEach(backdrop => {
            backdrop.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('show');
                }
            });
        });

    </script>
    @stack('scripts')
</body>
</html>
