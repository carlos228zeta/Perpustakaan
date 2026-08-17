@extends('layouts.admin')

@section('title', 'Manajemen Banner')
@section('header_title', 'Manajemen Banner & Konten Publik')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.2rem; font-weight: 800;">Manajemen Banner & Wording Website</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Kelola banner promo beranda dan teks informasi kontak yang tampil di website publik.</div>
            </div>
        </div>



        @if($errors->any())
            <div style="background-color: #FEE2E2; color: var(--danger); border: 1px solid #FCA5A5; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 0.875rem;">
                <ul style="margin-left: 1.25rem; margin-bottom: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('banner.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Seksi 1: Banner Beranda Utama -->
            <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--bg-color);">
                <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-image-fill"></i> Banner Utama Beranda Publik (Featured Banner)
                </h4>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">Atur foto/poster promosi, teks ajakan, dan tombol aksi yang tampil di beranda depan.</p>

                <div class="form-group">
                    <label class="form-label">Upload Gambar Banner (Bebas Ukuran / Rasio Foto)</label>
                    <input type="file" name="banner_image" accept="image/*" class="form-control-tzuchi" id="bannerInput" onchange="previewBanner(event)">
                    <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Format: JPG, PNG, WEBP (Maksimal 5MB). Bebas upload foto lanskap, portret, atau persegi. Sistem secara otomatis melakukan <i>smart-crop & centering</i> yang presisi tanpa membuat foto peyot/terdistorsi.</div>
                    
                    @if(!empty($settings['banner_image']) && file_exists(public_path($settings['banner_image'])))
                        <div style="margin-top: 0.75rem;">
                            <div style="font-size: 0.775rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.35rem;">Preview Banner Saat Ini:</div>
                            <img id="bannerPreview" src="{{ asset($settings['banner_image']) }}" alt="Banner Preview" style="width: 100%; max-height: 220px; object-fit: cover; object-position: center; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                        </div>
                    @else
                        <div style="margin-top: 0.75rem; display: none;" id="bannerPreviewContainer">
                            <img id="bannerPreview" src="#" alt="Banner Preview" style="width: 100%; max-height: 220px; object-fit: cover; object-position: center; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                        </div>
                    @endif
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label">Teks Badge / Kategori Banner</label>
                        <input type="text" name="banner_badge" value="{{ old('banner_badge', $settings['banner_badge'] ?? '🌟 Program Literasi & Keunggulan Akademik') }}" class="form-control-tzuchi" placeholder="Contoh: 🌟 Program Literasi & Keunggulan Akademik">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul Utama Banner</label>
                        <input type="text" name="banner_title" value="{{ old('banner_title', $settings['banner_title'] ?? 'Festival Membaca & Eksplorasi Pustaka Cinta Kasih 2026') }}" class="form-control-tzuchi" placeholder="Contoh: Festival Membaca & Eksplorasi Pustaka 2026">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Subjudul / Deskripsi Singkat Banner</label>
                    <textarea name="banner_subtitle" rows="2" class="form-control-tzuchi" placeholder="Tuliskan pesan ajakan atau informasi penting...">{{ old('banner_subtitle', $settings['banner_subtitle'] ?? 'Tingkatkan wawasan, perluas cakrawala, dan kembangkan budi pekerti humanis melalui ribuan koleksi buku pilihan.') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Teks Tombol Aksi</label>
                        <input type="text" name="banner_button_text" value="{{ old('banner_button_text', $settings['banner_button_text'] ?? 'Telusuri Koleksi Pilihan') }}" class="form-control-tzuchi" placeholder="Contoh: Telusuri Koleksi Pilihan">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Tautan / URL Tombol Aksi</label>
                        <input type="text" name="banner_button_link" value="{{ old('banner_button_link', $settings['banner_button_link'] ?? '/books') }}" class="form-control-tzuchi" placeholder="Contoh: /books atau https://...">
                    </div>
                </div>
            </div>

            <!-- Seksi 1B: Foto-Foto Latar Belakang Hero Slider (Ken Burns Carousel) -->
            <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--surface);">
                <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-images"></i> Galeri Foto Latar Belakang Hero Slider (Animasi Zoom In & Out)
                </h4>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">Upload beberapa foto perpustakaan/kegiatan sekaligus untuk dijadikan latar belakang bergerak (slider sinematik) pada Hero Beranda Utama.</p>

                <div class="form-group">
                    <label class="form-label">Upload Foto Tambahan Hero Slider (Bisa pilih banyak sekaligus)</label>
                    <input type="file" name="hero_slides[]" multiple accept="image/*" class="form-control-tzuchi">
                    <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Format: JPG, PNG, WEBP. Anda dapat memilih beberapa foto sekaligus untuk dimasukkan ke dalam rotasi slider.</div>
                </div>

                @if(!empty($heroSlides) && count($heroSlides) > 0)
                    <div style="margin-top: 1.25rem;">
                        <label class="form-label" style="margin-bottom: 0.75rem;">Daftar Foto Hero Slider Aktif Saat Ini (Total: {{ count($heroSlides) }} Foto):</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem;">
                            @foreach($heroSlides as $idx => $slidePath)
                                <div style="position: relative; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color); background: var(--bg-color);">
                                    <img src="{{ asset($slidePath) }}" alt="Hero Slide" style="width: 100%; height: 110px; object-fit: cover;">
                                    <div style="padding: 0.4rem; background: var(--surface); text-align: center; border-top: 1px solid var(--border-color);">
                                        <button type="submit" form="deleteSlideForm_{{ $idx }}" onclick="return confirmDeleteModal(event, 'Hapus Foto Slider?', 'Apakah Anda yakin ingin menghapus foto slider ini?')" class="btn-tzuchi btn-danger-tzuchi btn-sm" style="width: 100%; padding: 0.25rem 0.5rem; font-size: 0.75rem; justify-content: center;">
                                            <i class="bi bi-trash"></i> Hapus Foto
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Seksi 2: Wording & Informasi Footer Website Publik -->
            <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem; margin-bottom: 1.5rem; background: var(--surface);">
                <h4 style="font-size: 1rem; margin-bottom: 0.35rem; color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-layout-text-window-reverse"></i> Wording & Kontak Footer Website Publik
                </h4>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">Ubah teks visi singkat, jam operasional, alamat, dan kontak yang tampil di footer halaman utama.</p>
                
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

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="padding: 0.75rem 1.5rem; font-weight: 800;">
                    <i class="bi bi-check-lg"></i> Simpan Banner & Konten Publik
                </button>
        </form>

        @if(!empty($heroSlides))
            @foreach($heroSlides as $idx => $slidePath)
                <form id="deleteSlideForm_{{ $idx }}" action="{{ route('banner.deleteSlide', $idx) }}" method="POST" style="display:none;">
                    @csrf
                </form>
            @endforeach
        @endif
    </div>
</div>

<script>
function previewBanner(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('bannerPreview');
            preview.src = e.target.result;
            const container = document.getElementById('bannerPreviewContainer');
            if (container) container.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
