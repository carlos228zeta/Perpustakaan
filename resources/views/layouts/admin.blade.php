<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - LMS Cinta Kasih Tzu Chi</title>
    
    @php
        $faviconLogo = \App\Models\LibrarySetting::get('institution_logo');
        $faviconUrl = ($faviconLogo && file_exists(public_path($faviconLogo))) ? asset($faviconLogo) : asset('img/logo.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <link rel="stylesheet" href="{{ asset('css/tzuchi-library.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
<body>
    <div class="app-wrapper">
        @php
            $appTitle = \App\Models\LibrarySetting::get('app_title', 'LMS Tzu Chi');
            $appSubtitle = \App\Models\LibrarySetting::get('app_subtitle', 'Perpustakaan Cengkareng');
            $customLogo = \App\Models\LibrarySetting::get('institution_logo');
            $logoSrc = ($customLogo && file_exists(public_path($customLogo))) ? asset($customLogo) : asset('img/logo.png');
        @endphp
        <!-- Floating Sidebar Navigation (Radius 26px, Soft Glass Layer) -->
        <aside class="app-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="brand-wrapper">
                    <img src="{{ $logoSrc }}" alt="Logo Institusi" style="height: 38px; width: auto; object-fit: contain;">
                    <div>
                        <div class="brand-title">{{ $appTitle }}</div>
                        <div class="brand-subtitle">{{ $appSubtitle }}</div>
                    </div>
                </a>
            </div>

            <ul class="sidebar-menu">
                <script>
                (function(){
                    var s = sessionStorage.getItem('tzuchi_sidebar_scroll');
                    if(s !== null) {
                        var m = document.querySelector('.sidebar-menu');
                        if(m) m.scrollTop = parseInt(s, 10);
                    }
                })();
                </script>
                @php
                    $role = auth()->user()->role->name ?? '';
                @endphp

                @if($role === 'admin')
                    <li class="sidebar-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> <span>Dashboard Admin</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/buku*') ? 'active' : '' }}">
                        <a href="{{ route('buku.index') }}"><i class="bi bi-book-half"></i> <span>Manajemen Buku</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/kategori*') ? 'active' : '' }}">
                        <a href="{{ route('kategori.index') }}"><i class="bi bi-tags-fill"></i> <span>Kategori</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/masterdata*') ? 'active' : '' }}">
                        <a href="{{ route('masterdata.index') }}"><i class="bi bi-database-fill-gear"></i> <span>Data Master
                            
                        </span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/siswa*') ? 'active' : '' }}">
                        <a href="{{ route('siswa.index') }}"><i class="bi bi-people-fill"></i> <span>Data Siswa</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/guru*') ? 'active' : '' }}">
                        <a href="{{ route('guru.index') }}"><i class="bi bi-person-badge-fill"></i> <span>Data Guru</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/petugas*') ? 'active' : '' }}">
                        <a href="{{ route('petugas.index') }}"><i class="bi bi-person-workspace"></i> <span>Data Petugas</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/peminjaman*') ? 'active' : '' }}">
                        <a href="{{ route('peminjaman.index') }}"><i class="bi bi-arrow-left-right"></i> <span>Peminjaman</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/pengembalian*') ? 'active' : '' }}">
                        <a href="{{ url('admin/pengembalian') }}"><i class="bi bi-box-arrow-in-left"></i> <span>Pengembalian</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/denda') ? 'active' : '' }}">
                        <a href="{{ route('denda.index') }}"><i class="bi bi-wallet2"></i> <span>Kelola Denda</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/denda/laporan*') ? 'active' : '' }}">
                        <a href="{{ route('denda.laporan') }}"><i class="bi bi-receipt"></i> <span>Laporan Pembayaran</span></a>
                    </li>
                    @php
                        $isSettingsActive = request()->is('admin/pengaturan*') || request()->is('admin/banner*');
                    @endphp
                    <li class="sidebar-item has-submenu" id="settingsMenuParent">
                        <a href="javascript:void(0)" onclick="toggleSettingsMenu()">
                            <i class="bi bi-gear-wide-connected"></i> 
                            <span>Pengaturan Sistem</span>
                            <i class="bi bi-chevron-down submenu-arrow" style="margin-left: auto; font-size: 0.8rem;"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="{{ request()->is('admin/pengaturan*') && (request('tab') == 'sirkulasi' || !request('tab')) ? 'active-sub' : '' }}">
                                <a href="{{ route('pengaturan.index', ['tab' => 'sirkulasi']) }}">
                                    <i class="bi bi-sliders"></i> <span>Aturan & Denda</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/pengaturan*') && request('tab') == 'branding' ? 'active-sub' : '' }}">
                                <a href="{{ route('pengaturan.index', ['tab' => 'branding']) }}">
                                    <i class="bi bi-palette"></i> <span>Tampilan & Branding</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/pengaturan*') && request('tab') == 'website' ? 'active-sub' : '' }}">
                                <a href="{{ route('pengaturan.index', ['tab' => 'website']) }}">
                                    <i class="bi bi-layout-text-window-reverse"></i> <span>Wording Footer</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/pengaturan*') && request('tab') == 'layanan' ? 'active-sub' : '' }}">
                                <a href="{{ route('pengaturan.index', ['tab' => 'layanan']) }}">
                                    <i class="bi bi-card-checklist"></i> <span>Modals Layanan</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/banner*') ? 'active-sub' : '' }}">
                                <a href="{{ route('banner.index') }}">
                                    <i class="bi bi-images"></i> <span>Banner Beranda</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                @elseif($role === 'librarian')
                    <li class="sidebar-item {{ request()->is('librarian/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('librarian.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> <span>Dashboard Petugas</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/buku*') ? 'active' : '' }}">
                        <a href="{{ route('buku.index') }}"><i class="bi bi-book-half"></i> <span>Kelola Buku</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/kategori*') ? 'active' : '' }}">
                        <a href="{{ route('kategori.index') }}"><i class="bi bi-tags-fill"></i> <span>Kategori</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/peminjaman*') ? 'active' : '' }}">
                        <a href="{{ route('peminjaman.index') }}"><i class="bi bi-arrow-left-right"></i> <span>Transaksi Peminjaman</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/pengembalian*') ? 'active' : '' }}">
                        <a href="{{ url('admin/pengembalian') }}"><i class="bi bi-box-arrow-in-left"></i> <span>Pengembalian</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/denda*') ? 'active' : '' }}">
                        <a href="{{ route('denda.index') }}"><i class="bi bi-wallet2"></i> <span>Kelola Denda</span></a>
                    </li>

                @elseif($role === 'teacher')
                    <li class="sidebar-item {{ request()->is('teacher/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> <span>Dashboard Guru</span></a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('katalog.index') }}"><i class="bi bi-search"></i> <span>Katalog Buku</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('profile*') ? 'active' : '' }}">
                        <a href="{{ route('profile.index') }}"><i class="bi bi-person-circle"></i> <span>Profil Saya</span></a>
                    </li>

                @elseif($role === 'student')
                    <li class="sidebar-item {{ request()->is('student/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('student.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> <span>Dashboard Siswa</span></a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('katalog.index') }}"><i class="bi bi-search"></i> <span>Katalog Buku</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('profile*') ? 'active' : '' }}">
                        <a href="{{ route('profile.index') }}"><i class="bi bi-person-circle"></i> <span>Profil Saya</span></a>
                    </li>
                @endif
            </ul>

            <div style="padding: 1.25rem 1rem; border-top: 1px solid var(--border-color);">
                <a href="{{ url('/') }}" class="btn-tzuchi btn-secondary-tzuchi" style="width: 100%; justify-content: center;">
                    <i class="bi bi-globe2"></i> <span>Website Publik</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="app-main">
            <!-- Floating Navbar (Glassmorphism Blur 20px) -->
            <header class="app-topbar">
                <div class="topbar-welcome">
                    <div class="topbar-title">Selamat Datang, {{ auth()->user()->name }}</div>
                    <div class="topbar-date"><i class="bi bi-calendar3" style="color: var(--primary);"></i> 17 Agustus 2026 • Sekolah Cinta Kasih Tzu Chi</div>
                </div>

                <!-- Modern Search Bar in Navbar -->
                <div class="search-box-modern d-none d-md-block">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Cari buku, anggota, laporan..." onkeydown="if(event.key==='Enter'){ window.location.href='{{ route('buku.index') }}?search=' + this.value; }">
                </div>

                <div class="topbar-actions">
                    @php
                        $now = \Carbon\Carbon::now();
                        $activeReminders = \Illuminate\Support\Facades\DB::table('borrowings')
                            ->join('users', 'borrowings.user_id', '=', 'users.id')
                            ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
                            ->join('book_copies', 'borrowing_items.book_copy_id', '=', 'book_copies.id')
                            ->join('books', 'book_copies.book_id', '=', 'books.id')
                            ->select('borrowings.*', 'users.name as user_name', 'books.title as book_title')
                            ->whereIn('borrowings.status', ['borrowed', 'approved'])
                            ->get()
                            ->filter(function($b) use ($now) {
                                $dueDate = \Carbon\Carbon::parse($b->due_date);
                                return $now->greaterThanOrEqualTo($dueDate) || $now->diffInDays($dueDate, false) <= 3;
                            });
                        $reminderCount = $activeReminders->count();
                    @endphp

                    <!-- Dynamic Notification Bell Button -->
                    <div style="position: relative;">
                        <button class="notification-btn" id="notifBtn" title="Pengingat Jatuh Tempo">
                            <i class="bi bi-bell"></i>
                            @if($reminderCount > 0)
                                <span class="notification-badge">{{ $reminderCount }}</span>
                            @endif
                        </button>
                        
                        <!-- Notification Dropdown Panel -->
                        <div id="notifDropdown" style="display: none; position: absolute; right: 0; top: 52px; width: 350px; background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); z-index: 1200; overflow: hidden;">
                            <div style="padding: 1rem; border-bottom: 1px solid var(--border-color); background: var(--bg-color); font-weight: 700; font-size: 0.885rem; display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="bi bi-clock-history" style="color: var(--primary);"></i> Pengingat Jatuh Tempo</span>
                                @if($reminderCount > 0)
                                    <span class="badge-tzuchi badge-danger">{{ $reminderCount }} Peminjaman</span>
                                @else
                                    <span class="badge-tzuchi badge-success">Tidak Ada</span>
                                @endif
                            </div>

                            <div style="max-height: 320px; overflow-y: auto;">
                                @forelse($activeReminders as $item)
                                    @php
                                        $dueDate = \Carbon\Carbon::parse($item->due_date);
                                        $isOverdue = $now->greaterThan($dueDate);
                                        $daysLeft = $now->diffInDays($dueDate, false);
                                    @endphp
                                    <div style="padding: 0.9rem 1rem; border-bottom: 1px solid var(--border-color); font-size: 0.825rem;">
                                        @if($isOverdue)
                                            <strong style="color: var(--danger); display: block;"><i class="bi bi-exclamation-triangle-fill"></i> Terlambat {{ abs($daysLeft) }} Hari</strong>
                                        @else
                                            <strong style="color: var(--warning); display: block;"><i class="bi bi-clock-fill"></i> Jatuh Tempo {{ $daysLeft == 0 ? 'Hari Ini' : "dalam {$daysLeft} Hari" }}</strong>
                                        @endif
                                        <div style="color: var(--text-main); margin-top: 0.2rem; font-weight: 700;">{{ $item->book_title }}</div>
                                        <div style="color: var(--text-muted); font-size: 0.775rem; margin-top: 0.15rem;">Peminjam: {{ $item->user_name }} • Due: {{ $dueDate->format('d/m/Y') }}</div>
                                    </div>
                                @empty
                                    <div style="padding: 1.75rem 1rem; text-align: center; color: var(--text-muted); font-size: 0.85rem;">
                                        <i class="bi bi-check-circle-fill" style="font-size: 2rem; color: var(--primary); display: block; margin-bottom: 0.5rem;"></i>
                                        Tidak ada peminjaman aktif yang mendekati atau melewati jatuh tempo.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Theme Toggle Switcher -->
                    <button class="theme-toggle-btn" id="adminThemeToggle" title="Ganti Mode Terang / Gelap">
                        <i class="bi bi-moon-stars" id="adminThemeIcon"></i>
                    </button>

                    <!-- User Profile Avatar & Link to Profile -->
                    @php
                        $userAvatar = auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar)) 
                            ? asset(auth()->user()->avatar) 
                            : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode(auth()->user()->name);
                    @endphp
                    <a href="{{ route('profile.index') }}" class="user-profile-wrapper" title="Klik untuk lihat profil & upload foto profil" style="text-decoration: none;">
                        <img src="{{ $userAvatar }}" alt="Avatar" class="user-avatar">
                        <div style="text-align: left; font-size: 0.85rem;" class="d-none d-lg-block">
                            <div style="font-weight: 700; color: var(--text-main);">{{ auth()->user()->name }}</div>
                            <div style="color: var(--text-muted); font-size: 0.75rem; text-transform: capitalize;">{{ auth()->user()->role->display_name ?? 'Pengguna' }}</div>
                        </div>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-tzuchi btn-secondary-tzuchi btn-sm" title="Keluar" style="padding: 0.5rem 0.85rem;">
                            <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Body -->
            <main class="app-content">
                @if(session('success'))
                    <div style="background-color: #E8F5E9; color: var(--primary); border: 1px solid #A5D6A7; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; font-size: 0.9rem; font-weight: 600; box-shadow: var(--shadow-sm);">
                        <i class="bi bi-check-circle-fill" style="margin-right: 0.5rem;"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div style="background-color: #FEE2E2; color: var(--danger); border: 1px solid #FCA5A5; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; font-size: 0.9rem; font-weight: 600; box-shadow: var(--shadow-sm);">
                        <i class="bi bi-exclamation-triangle-fill" style="margin-right: 0.5rem;"></i> {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Notification toggle panel logic
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        if (notifBtn && notifDropdown) {
            notifBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notifDropdown.style.display = (notifDropdown.style.display === 'block') ? 'none' : 'block';
            });
            document.addEventListener('click', function() {
                notifDropdown.style.display = 'none';
            });
        }

        // Dark mode switcher logic for admin layout
        const adminThemeToggle = document.getElementById('adminThemeToggle');
        const adminThemeIcon = document.getElementById('adminThemeIcon');

        function updateAdminThemeIcon(theme) {
            if (adminThemeIcon) {
                if (theme === 'dark') {
                    adminThemeIcon.className = 'bi bi-sun-fill';
                } else {
                    adminThemeIcon.className = 'bi bi-moon-stars-fill';
                }
            }
        }

        const currentTheme = localStorage.getItem('tzuchi_theme') || 'light';
        updateAdminThemeIcon(currentTheme);

        if (adminThemeToggle) {
            adminThemeToggle.addEventListener('click', function() {
                let theme = document.documentElement.getAttribute('data-theme');
                let newTheme = (theme === 'dark') ? 'light' : 'dark';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('tzuchi_theme', newTheme);
                updateAdminThemeIcon(newTheme);
            });
        }

        function toggleSidebarSubmenu(element) {
            const parent = element.closest('.has-submenu');
            if (parent) {
                parent.classList.toggle('open');
            }
        }

        function toggleSettingsMenu() {
            const parent = document.getElementById('settingsMenuParent');
            if (parent) {
                parent.classList.toggle('open');
                localStorage.setItem('tzuchi_settings_open', parent.classList.contains('open') ? '1' : '0');
            }
        }

        // Sidebar scroll position persistence & auto-scroll active item into view
        document.addEventListener('DOMContentLoaded', function() {
            const settingsParent = document.getElementById('settingsMenuParent');
            if (settingsParent) {
                const isSettingsActive = {{ isset($isSettingsActive) && $isSettingsActive ? 'true' : 'false' }};
                const savedState = localStorage.getItem('tzuchi_settings_open');
                
                if (isSettingsActive || savedState === '1') {
                    settingsParent.classList.add('open');
                } else {
                    settingsParent.classList.remove('open');
                }
                
                if (isSettingsActive && savedState !== '1') {
                    localStorage.setItem('tzuchi_settings_open', '1');
                }
            }

            const sidebarMenu = document.querySelector('.sidebar-menu');
            if (sidebarMenu) {
                const savedPos = sessionStorage.getItem('tzuchi_sidebar_scroll');
                if (savedPos !== null) {
                    sidebarMenu.scrollTop = parseInt(savedPos, 10);
                } else {
                    const activeItem = sidebarMenu.querySelector('.sidebar-item.active');
                    if (activeItem) {
                        activeItem.scrollIntoView({ block: 'nearest', behavior: 'instant' });
                    }
                }

                sidebarMenu.addEventListener('scroll', function() {
                    sessionStorage.setItem('tzuchi_sidebar_scroll', sidebarMenu.scrollTop);
                });

                document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"]):not([onclick*="toggleSidebarSubmenu"])').forEach(link => {
                    link.addEventListener('click', function() {
                        if (sidebarMenu) {
                            sessionStorage.setItem('tzuchi_sidebar_scroll', sidebarMenu.scrollTop);
                        }
                    });
                });
            }

            // Pindahkan semua modal ke body agar position fixed tidak terperangkap oleh .app-main
            document.querySelectorAll('.modal-tzuchi-backdrop').forEach(modal => {
                document.body.appendChild(modal);
            });

            // Handle Smooth Login Transition into Dashboard
            if (sessionStorage.getItem('tzuchi_login_success') === '1') {
                sessionStorage.removeItem('tzuchi_login_success');
                
                const welcomeOverlay = document.createElement('div');
                welcomeOverlay.className = 'dashboard-welcome-overlay';
                welcomeOverlay.innerHTML = `
                    <div style="text-align: center; padding: 2rem;">
                        <div style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #15803D, #4ADE80); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; box-shadow: 0 10px 30px rgba(74, 222, 128, 0.4); font-size: 2.2rem; color: white;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h2 style="font-size: 1.65rem; font-weight: 800; margin-bottom: 0.5rem; color: white;">Selamat Datang Kembali!</h2>
                        <p style="font-size: 0.95rem; opacity: 0.85; margin: 0;">Membuka Dashboard Sistem Perpustakaan Tzu Chi...</p>
                    </div>
                `;
                document.body.appendChild(welcomeOverlay);

                setTimeout(() => {
                    welcomeOverlay.classList.add('fade-out');
                    setTimeout(() => {
                        welcomeOverlay.remove();
                    }, 650);
                }, 900);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function confirmDeleteModal(event, titleText = 'Konfirmasi Hapus Data', bodyText = 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.') {
        event.preventDefault();
        const form = event.target.closest('form');
        
        Swal.fire({
            title: titleText,
            text: bodyText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Hapus Sekarang!',
            cancelButtonText: 'Batal',
            borderRadius: '16px',
            customClass: {
                popup: 'tzuchi-swal-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @stack('scripts')
</body>
</html>
