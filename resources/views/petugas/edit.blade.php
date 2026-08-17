@extends('layouts.admin')

@section('title', 'Edit Petugas')
@section('header_title', 'Edit Data Petugas Perpustakaan')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Edit Akun Petugas</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Perbarui nama, email, atau reset password petugas.</div>
            </div>
            <a href="{{ route('petugas.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <form action="{{ route('petugas.update', $librarian->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label required">Nama Lengkap Petugas</label>
                <input type="text" name="name" value="{{ old('name', $librarian->name) }}" class="form-control-tzuchi" required>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label required">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', $librarian->email) }}" class="form-control-tzuchi" required>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control-tzuchi" placeholder="Kosongkan jika tidak diubah">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control-tzuchi" placeholder="Ulangi password baru">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <a href="{{ route('petugas.index') }}" class="btn-tzuchi btn-secondary-tzuchi">Batal</a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi"><i class="bi bi-check-lg"></i> Perbarui Petugas</button>
            </div>
        </form>
    </div>
</div>
@endsection
