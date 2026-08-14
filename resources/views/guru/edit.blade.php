@extends('layouts.admin')

@section('title', 'Edit Guru')
@section('header_title', 'Edit Data Guru')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Edit Data Guru</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Perbarui profil guru {{ $teacher->name }}.</div>
            </div>
            <a href="{{ route('guru.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
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

        <form action="{{ route('guru.update', $teacher->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">NIP (Nomor Induk Pegawai)</label>
                <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}" class="form-control-tzuchi">
            </div>

            <div class="form-group">
                <label class="form-label required">Nama Lengkap Guru</label>
                <input type="text" name="name" value="{{ old('name', $teacher->name) }}" class="form-control-tzuchi" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $teacher->email) }}" class="form-control-tzuchi" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control-tzuchi" placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" name="subject" value="{{ old('subject', $teacher->subject) }}" class="form-control-tzuchi">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Telepon / WA</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" class="form-control-tzuchi">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <a href="{{ route('guru.index') }}" class="btn-tzuchi btn-secondary-tzuchi">Batal</a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi"><i class="bi bi-check-lg"></i> Perbarui Guru</button>
            </div>
        </form>
    </div>
</div>
@endsection
