@extends('layouts.admin')

@section('title', 'Pengaturan Perpustakaan')
@section('header_title', 'Pengaturan Operasional & Website')

@section('content')
<div style="max-width: 850px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.2rem; font-weight: 800;">Pengaturan Sistem Perpustakaan</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Pilih kategori pengaturan pada tab di bawah ini untuk mengelola konfigurasi sistem secara terpisah.</div>
            </div>
        </div>
        @php
            $activeTab = request('tab', 'sirkulasi');
        @endphp

        <!-- Form section -->
        <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" value="{{ $activeTab }}">

            @if($activeTab == 'sirkulasi')
                <!-- Seksi 1: Aturan Operasional Sirkulasi -->
                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--surface);">
                    <h4 style="font-size: 1rem; margin-bottom: 1rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-sliders"></i> Batas Pinjaman & Denda Keterlambatan
                    </h4>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label required">Maksimal Pinjaman Siswa (Buku)</label>
                            <input type="number" name="max_student_borrow" value="{{ old('max_student_borrow', $settings['max_student_borrow'] ?? 3) }}" class="form-control-tzuchi" required min="1" max="10">
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Maksimal Pinjaman Guru (Buku)</label>
                            <input type="number" name="max_teacher_borrow" value="{{ old('max_teacher_borrow', $settings['max_teacher_borrow'] ?? 5) }}" class="form-control-tzuchi" required min="1" max="20">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label required">Durasi Pinjam Siswa (Hari)</label>
                            <input type="number" name="student_borrow_days" value="{{ old('student_borrow_days', $settings['student_borrow_days'] ?? 7) }}" class="form-control-tzuchi" required min="1" max="30">
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Durasi Pinjam Guru (Hari)</label>
                            <input type="number" name="teacher_borrow_days" value="{{ old('teacher_borrow_days', $settings['teacher_borrow_days'] ?? 14) }}" class="form-control-tzuchi" required min="1" max="60">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Denda Keterlambatan per Hari (Rp)</label>
                        <input type="number" name="fine_per_day" value="{{ old('fine_per_day', $settings['fine_per_day'] ?? 1000) }}" class="form-control-tzuchi" required min="0">
                        <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Biaya denda yang otomatis dihitung per hari keterlambatan saat buku dikembalikan.</div>
                    </div>
                </div>

                <!-- Seksi Pembayaran (Dipindahkan ke Sirkulasi) -->
                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--surface);">
                    <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-qr-code"></i> Pengaturan QRIS
                    </h4>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">Unggah gambar/foto QR Code standar nasional (QRIS) sekolah.</p>

                    @php
                        $currentQris = $settings['qris_image'] ?? null;
                        $qrisDisplay = ($currentQris && file_exists(public_path($currentQris))) ? asset($currentQris) : asset('img/no-image.png');
                    @endphp
                    <div style="display: flex; gap: 1.5rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
                        <div style="padding: 0.75rem; background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); text-align: center;">
                            <img src="{{ $qrisDisplay }}" alt="Pratinjau QRIS" style="height: 100px; max-width: 140px; object-fit: contain;">
                            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.35rem;">QRIS Saat Ini</div>
                        </div>

                        <div style="flex: 1; min-width: 220px;">
                            <label class="form-label">Unggah Foto QRIS (PNG / JPG / WebP)</label>
                            <input type="file" name="qris_image" class="form-control-tzuchi" accept="image/*">
                        </div>
                    </div>
                </div>

                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--surface);">
                    <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-bank"></i> Nomor Rekening Bank
                    </h4>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">Atur nomor rekening tujuan transfer pembayaran denda perpustakaan.</p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">No. Rekening BCA</label>
                            <input type="text" name="bca_account" value="{{ old('bca_account', $settings['bca_account'] ?? '') }}" class="form-control-tzuchi" placeholder="Contoh: 1234567890">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Atas Nama BCA</label>
                            <input type="text" name="bca_account_name" value="{{ old('bca_account_name', $settings['bca_account_name'] ?? '') }}" class="form-control-tzuchi" placeholder="Contoh: SMA Tzu Chi">
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">No. Rekening Mandiri</label>
                            <input type="text" name="mandiri_account" value="{{ old('mandiri_account', $settings['mandiri_account'] ?? '') }}" class="form-control-tzuchi" placeholder="Contoh: 0987654321">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Atas Nama Mandiri</label>
                            <input type="text" name="mandiri_account_name" value="{{ old('mandiri_account_name', $settings['mandiri_account_name'] ?? '') }}" class="form-control-tzuchi" placeholder="Contoh: SMA Tzu Chi">
                        </div>
                    </div>
                </div>


            @elseif($activeTab == 'branding')
                <!-- Seksi Branding: Logo, Judul, & Warna Utama Aplikasi -->
                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--surface);">
                    <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-palette-fill"></i> Warna Utama Aplikasi (Primary Theme Color)
                    </h4>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">Atur warna identitas utama untuk seluruh tombol, sidebar, header, dan badge aplikasi.</p>

                    @php
                        $currentColor = $settings['theme_primary_color'] ?? '#2E7D32';
                    @endphp

                    <!-- Unified Color Control Card -->
                    <div style="background: var(--bg-color); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.25rem;">
                        <!-- Row 1: Dual Input (Color Picker & Hex Text) -->
                        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px dashed var(--border-color);">
                            <div style="display: flex; align-items: center; gap: 0.75rem; background: var(--surface); padding: 0.5rem 0.85rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                                <input type="color" id="themePrimaryPicker" value="{{ $currentColor }}" style="width: 44px; height: 38px; padding: 0; cursor: pointer; border: none; background: transparent; border-radius: 6px;" oninput="updateColorInputs(this.value)">
                                <span style="font-size: 0.825rem; font-weight: 700; color: var(--text-main);">Pilih Warna (Color Spectrum)</span>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.5rem; background: var(--surface); padding: 0.35rem 0.85rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); flex: 1; max-width: 240px;">
                                <span style="font-weight: 800; color: var(--text-muted); font-family: monospace;">HEX:</span>
                                <input type="text" name="theme_primary_color" id="themePrimaryText" value="{{ $currentColor }}" style="font-family: monospace; font-weight: 800; font-size: 1rem; text-transform: uppercase; border: none; background: transparent; outline: none; width: 100%; color: var(--text-main);" placeholder="#2E7D32" oninput="updateColorPicker(this.value)">
                            </div>
                        </div>

                        <!-- Row 2: Preset Swatches -->
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.75rem; display: block;">
                                <i class="bi bi-stars"></i> Rekomendasi Palette Warna Cepat:
                            </label>
                            <div style="display: flex; gap: 0.65rem; align-items: center; flex-wrap: wrap;">
                                <button type="button" onclick="setPresetColor('#2E7D32')" title="Hijau Emerald (Default)" style="width: 36px; height: 36px; border-radius: 50%; background: #2E7D32; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                <button type="button" onclick="setPresetColor('#1E40AF')" title="Biru Edukasi (Royal Blue)" style="width: 36px; height: 36px; border-radius: 50%; background: #1E40AF; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                <button type="button" onclick="setPresetColor('#7E22CE')" title="Ungu Elegan (Royal Purple)" style="width: 36px; height: 36px; border-radius: 50%; background: #7E22CE; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                <button type="button" onclick="setPresetColor('#DC2626')" title="Merah Marun (Crimson Red)" style="width: 36px; height: 36px; border-radius: 50%; background: #DC2626; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                <button type="button" onclick="setPresetColor('#0D9488')" title="Teal Ocean (Tosca)" style="width: 36px; height: 36px; border-radius: 50%; background: #0D9488; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                <button type="button" onclick="setPresetColor('#D97706')" title="Amber Gold (Kuning Emas)" style="width: 36px; height: 36px; border-radius: 50%; background: #D97706; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                <button type="button" onclick="setPresetColor('#E11D48')" title="Rose Pink (Merah Muda)" style="width: 36px; height: 36px; border-radius: 50%; background: #E11D48; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                <button type="button" onclick="setPresetColor('#0284C7')" title="Sky Blue (Biru Cerah)" style="width: 36px; height: 36px; border-radius: 50%; background: #0284C7; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                <button type="button" onclick="setPresetColor('#111827')" title="Dark Obsidian (Hitam Modern)" style="width: 36px; height: 36px; border-radius: 50%; background: #111827; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                
                                @php
                                    $customColors = json_decode($settings['custom_theme_colors'] ?? '[]', true);
                                    if (!is_array($customColors)) $customColors = [];
                                @endphp
                                
                                @if(count($customColors) > 0)
                                    <div style="width: 1px; height: 36px; background: var(--border-color); margin: 0 0.25rem;"></div>
                                    @foreach($customColors as $cColor)
                                        <button type="button" onclick="setPresetColor('{{ $cColor }}')" title="Warna Custom Anda ({{ $cColor }})" style="width: 36px; height: 36px; border-radius: 50%; background: {{ $cColor }}; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.18); cursor: pointer; transition: transform 0.2s;"></button>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--bg-color);">
                    <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-image"></i> Logo & Identitas Aplikasi (Whitelabel)
                    </h4>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">Unggah logo institusi Anda dan atur judul aplikasi yang tampil di sidebar & navbar.</p>

                    @php
                        $currentLogo = $settings['institution_logo'] ?? null;
                        $logoDisplay = ($currentLogo && file_exists(public_path($currentLogo))) ? asset($currentLogo) : asset('img/logo.png');
                    @endphp

                    <div style="display: flex; gap: 1.5rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
                        <div style="padding: 0.75rem; background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); text-align: center;">
                            <img src="{{ $logoDisplay }}" alt="Pratinjau Logo" style="height: 60px; max-width: 140px; object-fit: contain;">
                            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.35rem;">Logo Saat Ini</div>
                        </div>

                        <div style="flex: 1; min-width: 220px;">
                            <label class="form-label">Unggah Logo Baru (PNG / JPG / SVG / WebP)</label>
                            <input type="file" name="institution_logo" class="form-control-tzuchi" accept="image/*">
                            <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Rekomendasi logo transparan bertipe PNG/SVG dengan aspek rasio seimbang.</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Judul Utama Aplikasi (Sidebar Baris 1)</label>
                            <input type="text" name="app_title" value="{{ old('app_title', $settings['app_title'] ?? 'LMS Tzu Chi') }}" class="form-control-tzuchi" placeholder="Contoh: LMS SMAN 1 Jakarta">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Subtitle / Tagline (Sidebar Baris 2)</label>
                            <input type="text" name="app_subtitle" value="{{ old('app_subtitle', $settings['app_subtitle'] ?? 'Perpustakaan Cengkareng') }}" class="form-control-tzuchi" placeholder="Contoh: Perpustakaan Digital">
                        </div>
                    </div>

                    <!-- Upload Hero Photo for Login & Register -->
                    @php
                        $currentHero = $settings['auth_hero_image'] ?? null;
                        $heroDisplay = ($currentHero && file_exists(public_path($currentHero))) ? asset($currentHero) : 'https://images.unsplash.com/photo-1568667256549-094345857637?auto=format&fit=crop&w=1600&q=80';
                    @endphp

                    <div style="display: flex; gap: 1.5rem; align-items: center; padding-top: 1.25rem; border-top: 1px dashed var(--border-color); flex-wrap: wrap;">
                        <div style="padding: 0.5rem; background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); text-align: center;">
                            <img src="{{ $heroDisplay }}" alt="Pratinjau Hero" style="height: 70px; width: 120px; object-fit: cover; border-radius: var(--radius-sm);">
                            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.35rem;">Background Auth</div>
                        </div>

                        <div style="flex: 1; min-width: 220px;">
                            <label class="form-label"><i class="bi bi-card-image"></i> Upload Foto Background Halaman Login & Register (Hero Photo)</label>
                            <input type="file" name="auth_hero_image" class="form-control-tzuchi" accept="image/*">
                            <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Ganti foto gedung/ruang perpustakaan sekolah yang tampil di sebelah kiri halaman Login & Register.</div>
                        </div>
                    </div>
                </div>

                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--surface);">
                    <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-chat-quote-fill"></i> Teks Wording Halaman Login & Register
                    </h4>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">Ubah judul dan pesan penyambutan pada formulir login dan banner kiri secara bebas.</p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Judul Formulir Login (Card Header)</label>
                            <input type="text" name="auth_login_title" value="{{ old('auth_login_title', $settings['auth_login_title'] ?? 'Masuk ke Perpustakaan') }}" class="form-control-tzuchi" placeholder="Contoh: Masuk ke Perpustakaan">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Subtitle Formulir Login (Card Subtitle)</label>
                            <input type="text" name="auth_login_subtitle" value="{{ old('auth_login_subtitle', $settings['auth_login_subtitle'] ?? 'Silakan masukkan email/username dan kata sandi Anda') }}" class="form-control-tzuchi" placeholder="Contoh: Silakan masukkan email/username dan kata sandi Anda">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul Banner Hero Kiri (Halaman Login & Register)</label>
                        <input type="text" name="auth_hero_title" value="{{ old('auth_hero_title', $settings['auth_hero_title'] ?? 'Membaca Membuka Jendela Ilmu & Budaya Humanis') }}" class="form-control-tzuchi" placeholder="Contoh: Membaca Membuka Jendela Ilmu & Budaya Humanis">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Deskripsi Banner Hero Kiri (Halaman Login & Register)</label>
                        <textarea name="auth_hero_subtitle" rows="2" class="form-control-tzuchi" placeholder="Contoh: Akses ribuan koleksi buku digital, referensi akademis...">{{ old('auth_hero_subtitle', $settings['auth_hero_subtitle'] ?? 'Akses ribuan koleksi buku digital, referensi akademis, dan manajemen perpustakaan terpadu secara mandiri dan transparan.') }}</textarea>
                    </div>
                </div>

            @elseif($activeTab == 'website')
                <!-- Seksi 2: Identitas & Wording Footer Website Publik -->
                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--surface);">
                    <h4 style="font-size: 1rem; margin-bottom: 1rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-building"></i> Identitas Institusi
                    </h4>
                    
                    <div class="form-group">
                        <label class="form-label required">Nama Institusi / Sekolah</label>
                        <input type="text" name="institution_name" value="{{ old('institution_name', $settings['institution_name'] ?? 'Cinta Kasih Tzu Chi Cengkareng') }}" class="form-control-tzuchi" required>
                    </div>
                </div>

                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--bg-color);">
                    <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-layout-text-window-reverse"></i> Wording & Kontak Footer Website Publik
                    </h4>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">Ubah teks deskripsi visi, jam operasional, alamat, dan kontak yang tampil di footer website.</p>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi / Visi Singkat Footer</label>
                        <textarea name="footer_description" rows="3" class="form-control-tzuchi" placeholder="Contoh: Mewujudkan lingkungan perpustakaan berbudaya humanis...">{{ old('footer_description', $settings['footer_description'] ?? 'Mewujudkan lingkungan perpustakaan berbudaya humanis, terdigitalisasi, dan mendukung keunggulan akademik seluruh siswa & pendidik.') }}</textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label">Jam Operasional Hari Kerja</label>
                            <input type="text" name="operating_hours_weekday" value="{{ old('operating_hours_weekday', $settings['operating_hours_weekday'] ?? 'Senin - Jumat: 07.00 - 16.00 WIB') }}" class="form-control-tzuchi" placeholder="Contoh: Senin - Jumat: 07.00 - 16.00 WIB">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jam Operasional Akhir Pekan</label>
                            <input type="text" name="operating_hours_weekend" value="{{ old('operating_hours_weekend', $settings['operating_hours_weekend'] ?? 'Sabtu - Minggu & Libur: Tutup') }}" class="form-control-tzuchi" placeholder="Contoh: Sabtu - Minggu & Libur: Tutup">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap Sekolah / Perpustakaan</label>
                        <input type="text" name="library_address" value="{{ old('library_address', $settings['library_address'] ?? 'Jl. Kamal Raya No.20, Cengkareng, Jakarta Barat') }}" class="form-control-tzuchi" placeholder="Contoh: Jl. Kamal Raya No.20, Cengkareng, Jakarta Barat">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Email Perpustakaan</label>
                            <input type="email" name="library_email" value="{{ old('library_email', $settings['library_email'] ?? 'perpustakaan@tzuchi.sch.id') }}" class="form-control-tzuchi" placeholder="perpustakaan@tzuchi.sch.id">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Nomor Telepon / Hotline</label>
                            <input type="text" name="library_phone" value="{{ old('library_phone', $settings['library_phone'] ?? '(021) 5439-7462') }}" class="form-control-tzuchi" placeholder="(021) 5439-7462">
                        </div>
                    </div>
                </div>

            @elseif($activeTab == 'layanan')
                <!-- Seksi 3: Informasi Modals Layanan & Fasilitas -->
                @php
                    $defaultTataTertib = "Panduan & Ketentuan Peminjaman Perpustakaan Tzu Chi:\n• Batas Maksimal Pinjam: Siswa (maksimal 3 buku), Guru/Staf (maksimal 5 buku).\n• Durasi Peminjaman: Masa pinjam buku adalah 7 Hari dan dapat diperpanjang 1x jika buku tidak sedang dipesan oleh anggota lain.\n• Ketentuan Keterlambatan: Terlambat mengembalikan buku dikenakan denda sesuai regulasi per hari per buku.\n• Perawatan Buku: Dilarang mencoret, melipat, atau merusak buku. Kerusakan atau kehilangan wajib diganti dengan buku serupa.";

                    $defaultRuangBaca = "Panduan Ruang Baca & Quiet Zone:\n• Jaga Ketenangan: Harap menjaga ketenangan dan menggunakan nada bicara lembut di area ruang baca.\n• Kebersihan & Kerapihan: Dilarang membawa makanan berkuah atau minuman tanpa penutup rapat. Kembalikan kursi ke posisi semula.\n• Pengembalian Buku: Buku yang telah dibaca di tempat dapat diletakkan pada troli pengembalian yang tersedia.";

                    $defaultWifi = "Akses Layanan Digital & Wi-Fi Perpustakaan:\n• Nama Wi-Fi: TzuChi_Perpustakaan_Free (Siswa & Guru)\n• Password Wi-Fi: tzuchiread2026\n• Katalog Online (OPAC): Pencarian koleksi buku digital dan pemesanan mandiri dapat diakses melalui portal LMS Perpustakaan.";

                    $defaultFaq = "Bantuan & FAQ Sirkulasi:\n• Bagaimana jika kartu perpustakaan hilang? Harap segera melapor ke petugas perpustakaan di meja sirkulasi untuk penerbitan ulang.\n• Apakah bisa memperpanjang pinjaman secara online? Ya, perpanjangan dapat dilakukan melalui menu Dashboard Anggota sebelum masa pinjam berakhir.";
                @endphp

                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--surface);">
                    <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-card-checklist"></i> Konten Pop-up Modals Layanan & Fasilitas
                    </h4>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">Atur teks informasi yang tampil saat pengunjung mengklik menu Layanan & Fasilitas di footer.</p>

                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-journal-check" style="color: var(--primary);"></i> Informasi Tata Tertib Peminjaman</label>
                        <textarea name="layanan_tata_tertib" rows="5" class="form-control-tzuchi">{{ old('layanan_tata_tertib', $settings['layanan_tata_tertib'] ?? $defaultTataTertib) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-building-check" style="color: var(--primary);"></i> Informasi Ruang Baca & Quiet Zone</label>
                        <textarea name="layanan_ruang_baca" rows="5" class="form-control-tzuchi">{{ old('layanan_ruang_baca', $settings['layanan_ruang_baca'] ?? $defaultRuangBaca) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-wifi" style="color: var(--primary);"></i> Informasi Layanan Digital & Wi-Fi</label>
                        <textarea name="layanan_wifi" rows="5" class="form-control-tzuchi">{{ old('layanan_wifi', $settings['layanan_wifi'] ?? $defaultWifi) }}</textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label"><i class="bi bi-headset" style="color: var(--primary);"></i> Informasi Bantuan & FAQ Sirkulasi</label>
                        <textarea name="layanan_faq" rows="5" class="form-control-tzuchi">{{ old('layanan_faq', $settings['layanan_faq'] ?? $defaultFaq) }}</textarea>
                    </div>
                </div>

            @endif

            <!-- Retain hidden values for form validation when submitting specific tab -->
            @if($activeTab != 'sirkulasi')
                <input type="hidden" name="max_student_borrow" value="{{ $settings['max_student_borrow'] ?? 3 }}">
                <input type="hidden" name="max_teacher_borrow" value="{{ $settings['max_teacher_borrow'] ?? 5 }}">
                <input type="hidden" name="student_borrow_days" value="{{ $settings['student_borrow_days'] ?? 7 }}">
                <input type="hidden" name="teacher_borrow_days" value="{{ $settings['teacher_borrow_days'] ?? 14 }}">
                <input type="hidden" name="fine_per_day" value="{{ $settings['fine_per_day'] ?? 1000 }}">
            @endif

            @if($activeTab != 'website')
                <input type="hidden" name="institution_name" value="{{ $settings['institution_name'] ?? 'Cinta Kasih Tzu Chi Cengkareng' }}">
            @endif

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="padding: 0.75rem 1.5rem; font-weight: 800;">
                    <i class="bi bi-check-lg"></i> Simpan Pengaturan Tab Ini
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateColorInputs(hex) {
        const text = document.getElementById('themePrimaryText');
        if (text) text.value = hex.toUpperCase();
    }

    function updateColorPicker(hex) {
        if (/^#[0-9A-F]{6}$/i.test(hex)) {
            const picker = document.getElementById('themePrimaryPicker');
            if (picker) picker.value = hex;
        }
    }

    function setPresetColor(color) {
        const picker = document.getElementById('themePrimaryPicker');
        const text = document.getElementById('themePrimaryText');
        if (picker) picker.value = color;
        if (text) text.value = color.toUpperCase();
    }
</script>
@endpush
