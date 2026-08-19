<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($isRegister) && $isRegister ? 'Pendaftaran Anggota' : 'Masuk Akun' }} - {{ \App\Models\LibrarySetting::get('app_title', 'LMS Tzu Chi') }}</title>
    
    @php
        $faviconLogo = \App\Models\LibrarySetting::get('institution_logo');
        $faviconUrl = ($faviconLogo && file_exists(public_path($faviconLogo))) ? asset($faviconLogo) : asset('img/logo.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <link rel="stylesheet" href="{{ asset('css/tzuchi-library.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {!! \App\Models\LibrarySetting::getCustomCssVariables() !!}
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('tzuchi_theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body style="margin: 0; padding: 0; overflow-x: hidden; font-family: 'Plus Jakarta Sans', sans-serif;">

@php
    $customHeroImg = \App\Models\LibrarySetting::get('auth_hero_image');
    $heroBgUrl = ($customHeroImg && file_exists(public_path($customHeroImg))) 
                 ? asset($customHeroImg) 
                 : 'https://images.unsplash.com/photo-1568667256549-094345857637?q=80&w=1600&auto=format&fit=crop';
    
    $institutionName = \App\Models\LibrarySetting::get('institution_name', 'Sekolah Cinta Kasih Tzu Chi');
    $customLogo = \App\Models\LibrarySetting::get('institution_logo');
    $logoSrc = ($customLogo && file_exists(public_path($customLogo))) ? asset($customLogo) : asset('img/logo.png');
    $initialMode = isset($isRegister) && $isRegister ? 'show-signup' : 'show-login';
    $classes = \Illuminate\Support\Facades\DB::table('classes')->get();
@endphp



<!-- 2-in-1 Sliding Auth Card Container -->
<div class="auth-split-wrapper">
    <div class="auth-card-box {{ $initialMode }}" id="authCardBox" style="--hero-bg-url: url('{{ $heroBgUrl }}');">
        
        <!-- Background Form Panels Container -->
        <div class="auth-forms-container">
            
            <!-- LEFT PANEL: Login Form -->
            <div class="auth-form-panel auth-form-login">
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <img src="{{ $logoSrc }}" alt="Logo" style="height: 42px; width: auto; object-fit: contain;">
                        <div>
                            <div style="font-weight: 800; font-size: 1.1rem; color: var(--primary); line-height: 1.2;">LMS Tzu Chi</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $institutionName }}</div>
                        </div>
                    </div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.35rem;">Selamat Datang Kembali</h2>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">Masuk untuk mengelola sirkulasi & peminjaman buku</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="auth-submit-form">
                    @csrf
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="login_email" class="form-label required" style="font-size: 0.825rem; font-weight: 700;">Alamat Email</label>
                        <input id="login_email" type="email" class="form-control-tzuchi @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="email@domain.com">
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <label for="login_password" class="form-label required" style="font-size: 0.825rem; font-weight: 700; margin: 0;">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="font-size: 0.775rem; color: var(--primary); text-decoration: none; font-weight: 600;">Lupa password?</a>
                            @endif
                        </div>
                        <div style="position: relative;">
                            <input id="login_password" type="password" class="form-control-tzuchi @error('password') is-invalid @enderror" name="password" required placeholder="Masukkan kata sandi">
                            <i class="bi bi-eye" id="toggle-icon-login" onclick="togglePasswordVisibility('login_password', 'toggle-icon-login')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-muted);"></i>
                        </div>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.825rem; color: var(--text-muted); font-weight: 600;">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="accent-color: var(--primary); width: 16px; height: 16px;">
                            Ingat Sesi Saya
                        </label>
                    </div>

                    <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="width: 100%; justify-content: center; padding: 0.8rem; font-size: 0.95rem; border-radius: 12px;">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                    </button>
                </form>

                <div class="d-lg-none" style="text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: var(--text-muted);">
                    Belum punya akun? <a href="javascript:void(0)" onclick="toggleAuthCard('signup')" style="color: var(--primary); font-weight: 700;">Daftar di sini</a>
                </div>
            </div>

            <!-- RIGHT PANEL: Register Form -->
            <div class="auth-form-panel auth-form-register">
                <div style="margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <img src="{{ $logoSrc }}" alt="Logo" style="height: 38px; width: auto; object-fit: contain;">
                        <div>
                            <div style="font-weight: 800; font-size: 1rem; color: var(--primary); line-height: 1.2;">LMS Tzu Chi</div>
                            <div style="font-size: 0.725rem; color: var(--text-muted);">Pendaftaran Anggota</div>
                        </div>
                    </div>
                    <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.2rem;">Buat Akun Anggota</h2>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">Lengkapi data diri untuk pendaftaran anggota perpustakaan</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="auth-submit-form">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="role_type" class="form-label required" style="font-size: 0.8rem; font-weight: 700;">Daftar Sebagai</label>
                            <select id="role_type" class="form-control-tzuchi @error('role_type') is-invalid @enderror" name="role_type" required onchange="updateNumberIdLabel()">
                                <option value="student" {{ old('role_type') == 'student' ? 'selected' : '' }}>Siswa / Murid</option>
                                <option value="teacher" {{ old('role_type') == 'teacher' ? 'selected' : '' }}>Guru / Tenaga Pendidik</option>
                                <option value="librarian" {{ old('role_type') == 'librarian' ? 'selected' : '' }}>Petugas Perpustakaan</option>
                            </select>
                        </div>
                        <div class="form-group" id="class_container" style="margin-bottom: 0; display: {{ old('role_type', 'student') == 'student' ? 'block' : 'none' }};">
                            <label for="class_id" class="form-label required" style="font-size: 0.8rem; font-weight: 700;">Pilih Kelas</label>
                            <select id="class_id" class="form-control-tzuchi @error('class_id') is-invalid @enderror" name="class_id">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0.75rem;">
                        <label for="reg_name" class="form-label required" style="font-size: 0.8rem; font-weight: 700;">Nama Lengkap</label>
                        <input id="reg_name" type="text" class="form-control-tzuchi @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Nama lengkap sesuai kartu identitas">
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="number_id" class="form-label" id="number_id_label" style="font-size: 0.8rem; font-weight: 700;">NIS (Nomor Induk)</label>
                            <input id="number_id" type="text" class="form-control-tzuchi" name="number_id" value="{{ old('number_id') }}" placeholder="NIS / NIP">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="phone" class="form-label" style="font-size: 0.8rem; font-weight: 700;">No. WhatsApp</label>
                            <input id="phone" type="text" class="form-control-tzuchi" name="phone" value="{{ old('phone') }}" placeholder="0812XXXXXXXX">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0.75rem;">
                        <label for="reg_email" class="form-label required" style="font-size: 0.8rem; font-weight: 700;">Alamat Email</label>
                        <input id="reg_email" type="email" class="form-control-tzuchi @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="email@domain.com">
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="reg_password" class="form-label required" style="font-size: 0.8rem; font-weight: 700;">Password</label>
                            <input id="reg_password" type="password" class="form-control-tzuchi @error('password') is-invalid @enderror" name="password" required placeholder="Min 8 karakter">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="password_confirmation" class="form-label required" style="font-size: 0.8rem; font-weight: 700;">Konfirmasi</label>
                            <input id="password_confirmation" type="password" class="form-control-tzuchi" name="password_confirmation" required placeholder="Ulangi password">
                        </div>
                    </div>

                    <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="width: 100%; justify-content: center; padding: 0.75rem; font-size: 0.9rem; border-radius: 12px;">
                        <i class="bi bi-person-plus-fill"></i> Daftar Akun Baru
                    </button>
                </form>

                <div class="d-lg-none" style="text-align: center; margin-top: 1.25rem; font-size: 0.85rem; color: var(--text-muted);">
                    Sudah punya akun? <a href="javascript:void(0)" onclick="toggleAuthCard('login')" style="color: var(--primary); font-weight: 700;">Masuk di sini</a>
                </div>
            </div>

        </div>

        <!-- Sliding 3D Overlay Panel -->
        <div class="auth-overlay-panel" id="authOverlayPanel">
            <div class="auth-overlay-content">
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(255, 255, 255, 0.18); backdrop-filter: blur(10px); padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; border: 1px solid rgba(255, 255, 255, 0.25); margin: 0 auto;">
                    <i class="bi bi-book-half"></i> {{ $institutionName }}
                </div>

                <!-- PROMPT FOR REGISTER (Shown when in Login mode) -->
                <div class="overlay-prompt-box {{ $initialMode === 'show-login' ? 'active-prompt' : 'inactive-prompt' }}" id="promptRegisterView">
                    <div style="width: 72px; height: 72px; border-radius: 50%; background: rgba(74, 222, 128, 0.2); border: 1px solid rgba(74, 222, 128, 0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; font-size: 2.2rem; color: #4ADE80;">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h2 style="font-size: 1.85rem; font-weight: 800; color: white; margin-bottom: 0.75rem;">Belum Memiliki Akun?</h2>
                    <p style="font-size: 0.925rem; opacity: 0.9; line-height: 1.6; max-width: 360px; margin-bottom: 1.75rem;">
                        Daftarkan akun siswa, guru, atau petugas Anda untuk mengakses ribuan katalog buku & sirkulasi perpustakaan digital.
                    </p>
                    <button type="button" onclick="toggleAuthCard('signup')" class="btn-tzuchi btn-primary-tzuchi" style="border-radius: 9999px; padding: 0.75rem 2.25rem; font-size: 0.925rem; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
                        <i class="bi bi-arrow-left"></i> Daftar Sekarang
                    </button>
                </div>

                <!-- PROMPT FOR LOGIN (Shown when in Signup mode) -->
                <div class="overlay-prompt-box {{ $initialMode === 'show-signup' ? 'active-prompt' : 'inactive-prompt' }}" id="promptLoginView">
                    <div style="width: 72px; height: 72px; border-radius: 50%; background: rgba(74, 222, 128, 0.2); border: 1px solid rgba(74, 222, 128, 0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; font-size: 2.2rem; color: #4ADE80;">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                    <h2 style="font-size: 1.85rem; font-weight: 800; color: white; margin-bottom: 0.75rem;">Sudah Memiliki Akun?</h2>
                    <p style="font-size: 0.925rem; opacity: 0.9; line-height: 1.6; max-width: 360px; margin-bottom: 1.75rem;">
                        Masuk dengan alamat email dan kata sandi Anda untuk mengakses layanan peminjaman dan riwayat perpustakaan.
                    </p>
                    <button type="button" onclick="toggleAuthCard('login')" class="btn-tzuchi btn-primary-tzuchi" style="border-radius: 9999px; padding: 0.75rem 2.25rem; font-size: 0.925rem; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
                        Masuk ke Akun <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

                <div style="font-size: 0.775rem; opacity: 0.75; border-top: 1px solid rgba(255, 255, 255, 0.15); padding-top: 1rem;">
                    &copy; {{ date('Y') }} LMS Tzu Chi — Perpustakaan Cengkareng
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function togglePasswordVisibility(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    if (passwordInput && toggleIcon) {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
}

function updateNumberIdLabel() {
    const roleSelect = document.getElementById('role_type');
    const label = document.getElementById('number_id_label');
    const input = document.getElementById('number_id');
    const classContainer = document.getElementById('class_container');
    const classInput = document.getElementById('class_id');
    
    if (!roleSelect || !label || !input) return;

    if (roleSelect.value === 'teacher') {
        label.innerText = 'NIP (Nomor Induk Pegawai)';
        input.placeholder = 'NIP Guru';
        if (classContainer) classContainer.style.display = 'none';
        if (classInput) classInput.required = false;
    } else if (roleSelect.value === 'librarian') {
        label.innerText = 'ID Petugas';
        input.placeholder = 'Kode Petugas';
        if (classContainer) classContainer.style.display = 'none';
        if (classInput) classInput.required = false;
    } else {
        label.innerText = 'NIS (Nomor Induk Siswa)';
        input.placeholder = 'NIS Siswa';
        if (classContainer) classContainer.style.display = 'block';
        if (classInput) classInput.required = true;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateNumberIdLabel();
});

function toggleAuthCard(mode) {
    const card = document.getElementById('authCardBox');
    const promptReg = document.getElementById('promptRegisterView');
    const promptLog = document.getElementById('promptLoginView');

    if (!card) return;

    if (mode === 'signup') {
        card.classList.remove('show-login');
        card.classList.add('show-signup');
        history.pushState(null, '', '{{ url("/register") }}');
        document.title = 'Pendaftaran Anggota - {{ \App\Models\LibrarySetting::get("app_title", "LMS Tzu Chi") }}';
        
        if (promptReg) {
            promptReg.classList.remove('active-prompt');
            promptReg.classList.add('inactive-prompt');
        }
        if (promptLog) {
            promptLog.classList.remove('inactive-prompt');
            promptLog.classList.add('active-prompt');
        }

    } else {
        card.classList.remove('show-signup');
        card.classList.add('show-login');
        history.pushState(null, '', '{{ url("/login") }}');
        document.title = 'Login - LMS Cinta Kasih Tzu Chi';

        if (promptLog) {
            promptLog.classList.remove('active-prompt');
            promptLog.classList.add('inactive-prompt');
        }
        if (promptReg) {
            promptReg.classList.remove('inactive-prompt');
            promptReg.classList.add('active-prompt');
        }
    }
}
</script>
</body>
</html>
