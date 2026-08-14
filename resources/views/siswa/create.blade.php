@extends('layouts.admin')

@section('title', 'Tambah Siswa')
@section('header_title', 'Tambah Siswa Baru')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Formulir Tambah Siswa</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Daftarkan siswa baru ke sistem informasi perpustakaan.</div>
            </div>
            <a href="{{ route('siswa.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        @if ($errors->any())
            <div style="background-color: #FEE2E2; color: var(--danger); border: 1px solid #FCA5A5; padding: 0.85rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; font-size: 0.875rem;">
                <ul style="margin-left: 1.25rem; margin-bottom: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">NIS (Nomor Induk Siswa)</label>
                    <input type="text" name="nis" value="{{ old('nis') }}" class="form-control-tzuchi" required placeholder="Contoh: 260001">
                </div>
                <div class="form-group">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" class="form-control-tzuchi" placeholder="Contoh: 0051234567">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label required">Nama Lengkap Siswa</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control-tzuchi" required placeholder="Nama Siswa">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control-tzuchi" required placeholder="siswa@library.test">
                </div>
                <div class="form-group">
                    <label class="form-label required">Password Login</label>
                    <input type="password" name="password" class="form-control-tzuchi" required placeholder="••••••••">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <select name="class_id" class="form-control-tzuchi">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jurusan</label>
                    <input type="text" name="major" value="{{ old('major', 'PPLG') }}" class="form-control-tzuchi">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Telepon / WA</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control-tzuchi" placeholder="08xxxxxxxxxx">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <a href="{{ route('siswa.index') }}" class="btn-tzuchi btn-secondary-tzuchi">Batal</a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi"><i class="bi bi-check-lg"></i> Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>
@endsection
