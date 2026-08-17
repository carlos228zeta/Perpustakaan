@extends('layouts.admin')

@section('title', 'Tambah Petugas Baru')
@section('header_title', 'Tambah Petugas Perpustakaan')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Form Akun Petugas Baru</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Buat akun baru untuk pengelola operasional sirkulasi perpustakaan.</div>
            </div>
            <a href="{{ route('petugas.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <form action="{{ route('petugas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label required">Nama Lengkap Petugas</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control-tzuchi" required placeholder="Nama lengkap petugas">
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label required">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control-tzuchi" required placeholder="petugas@tzuchi.sch.id">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">Password</label>
                    <input type="password" name="password" class="form-control-tzuchi" required placeholder="Minimal 8 karakter">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control-tzuchi" required placeholder="Ulangi password">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <a href="{{ route('petugas.index') }}" class="btn-tzuchi btn-secondary-tzuchi">Batal</a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi"><i class="bi bi-check-lg"></i> Simpan Petugas</button>
            </div>
        </form>
    </div>
</div>
@endsection
