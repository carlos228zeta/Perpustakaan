<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - LMS Cinta Kasih Tzu Chi</title>
    
    <link rel="stylesheet" href="{{ asset('css/tzuchi-library.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <!-- Floating Sidebar Navigation (Radius 26px, Soft Glass Layer) -->
        <aside class="app-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="brand-wrapper">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Cinta Kasih Tzu Chi" style="height: 38px; width: auto; object-fit: contain;">
                    <div>
                        <div class="brand-title">LMS Tzu Chi</div>
                        <div class="brand-subtitle">Perpustakaan Cengkareng</div>
                    </div>
                </a>
            </div>

            <ul class="sidebar-menu">
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
                    <li class="sidebar-item {{ request()->is('admin/cetaklaporan*') ? 'active' : '' }}">
                        <a href="{{ url('admin/cetaklaporan') }}"><i class="bi bi-file-earmark-bar-graph-fill"></i> <span>Laporan Enterprise</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('admin/pengaturan*') ? 'active' : '' }}">
                        <a href="{{ route('pengaturan.index') }}"><i class="bi bi-gear-wide-connected"></i> <span>Pengaturan Sistem</span></a>
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
                    <li class="sidebar-item {{ request()->is('admin/cetaklaporan*') ? 'active' : '' }}">
                        <a href="{{ url('admin/cetaklaporan') }}"><i class="bi bi-file-earmark-bar-graph-fill"></i> <span>Laporan Enterprise</span></a>
                    </li>

                @elseif($role === 'teacher')
                    <li class="sidebar-item {{ request()->is('teacher/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> <span>Dashboard Guru</span></a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('public.books.index') }}"><i class="bi bi-search"></i> <span>Katalog Buku</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('profile*') ? 'active' : '' }}">
                        <a href="{{ route('profile.index') }}"><i class="bi bi-person-circle"></i> <span>Profil Saya</span></a>
                    </li>

                @elseif($role === 'student')
                    <li class="sidebar-item {{ request()->is('student/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('student.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> <span>Dashboard Siswa</span></a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('public.books.index') }}"><i class="bi bi-search"></i> <span>Katalog Buku</span></a>
                    </li>
                    <li class="sidebar-item {{ request()->is('profile*') ? 'active' : '' }}">
                        <a href="{{ route('profile.index') }}"><i class="bi bi-person-circle"></i> <span>Profil Saya</span></a>
                    </li>
                @endif
            </ul>

            <div style="padding: 1.25rem 1rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('public.books.index') }}" class="btn-tzuchi btn-secondary-tzuchi" style="width: 100%; justify-content: center;">
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

                    <!-- User Profile Avatar & Dropdown -->
                    <div class="user-profile-wrapper">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode(auth()->user()->name) }}" alt="Avatar" class="user-avatar">
                        <div style="text-align: left; font-size: 0.85rem;" class="d-none d-lg-block">
                            <div style="font-weight: 700; color: var(--text-main);">{{ auth()->user()->name }}</div>
                            <div style="color: var(--text-muted); font-size: 0.75rem; text-transform: capitalize;">{{ auth()->user()->role->display_name ?? 'Pengguna' }}</div>
                        </div>
                    </div>

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
    </script>
    @stack('scripts')
</body>
</html>
