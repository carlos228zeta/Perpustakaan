@extends('layouts.master')

@section('title', 'Pendaftaran Anggota Perpustakaan')

@section('content')
<div style="min-height: calc(100vh - 180px); display: flex; align-items: center; justify-content: center; padding: 2.5rem 1.25rem;">
    <div class="card-tzuchi" style="width: 100%; max-width: 520px; padding: 2.25rem; background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 56px; width: auto; object-fit: contain; margin-bottom: 0.75rem;">
            <h2 style="font-size: 1.35rem; margin-bottom: 0.25rem;">Pendaftaran Anggota Baru</h2>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Sekolah Cinta Kasih Tzu Chi Cengkareng</div>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="role_type" class="form-label required">Daftar Sebagai (Peran Anggota)</label>
                <select id="role_type" class="form-control-tzuchi @error('role_type') is-invalid @enderror" name="role_type" required onchange="updateNumberIdLabel()">
                    <option value="student" {{ old('role_type') == 'student' ? 'selected' : '' }}>Siswa / Murid</option>
                    <option value="teacher" {{ old('role_type') == 'teacher' ? 'selected' : '' }}>Guru / Tenaga Pendidik</option>
                    <option value="librarian" {{ old('role_type') == 'librarian' ? 'selected' : '' }}>Petugas Perpustakaan</option>
                </select>
                @error('role_type')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name" class="form-label required">Nama Lengkap</label>
                <input id="name" type="text" class="form-control-tzuchi @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama lengkap sesuai kartu identitas">
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="number_id" class="form-label" id="number_id_label">NIS (Nomor Induk Siswa)</label>
                <input id="number_id" type="text" class="form-control-tzuchi @error('number_id') is-invalid @enderror" name="number_id" value="{{ old('number_id') }}" placeholder="Nomor Induk Siswa / NIP">
                @error('number_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label required">Alamat Email</label>
                <input id="email" type="email" class="form-control-tzuchi @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="email@tzuchi.sch.id">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">No. Telepon / WhatsApp</label>
                <input id="phone" type="text" class="form-control-tzuchi @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="0812XXXXXXXX">
                @error('phone')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="password" class="form-label required">Password</label>
                    <input id="password" type="password" class="form-control-tzuchi @error('password') is-invalid @enderror" name="password" required placeholder="Minimal 8 karakter">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label required">Konfirmasi Password</label>
                    <input id="password-confirm" type="password" class="form-control-tzuchi" name="password_confirmation" required placeholder="Ulangi password">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem; text-align: center; font-size: 0.85rem;">
                Sudah Punya Akun? <a href="{{ route('login') }}" style="font-weight: 600;">Masuk Sekarang</a>
            </div>

            <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="width: 100%; padding: 0.75rem; font-size: 0.95rem; font-weight: 600;">
                <i class="bi bi-person-plus-fill"></i> Daftar Anggota Baru
            </button>
        </form>
    </div>
</div>

<script>
function updateNumberIdLabel() {
    const role = document.getElementById('role_type').value;
    const label = document.getElementById('number_id_label');
    if (role === 'student') {
        label.innerText = 'NIS (Nomor Induk Siswa)';
    } else if (role === 'teacher') {
        label.innerText = 'NIP (Nomor Induk Pegawai / Guru)';
    } else {
        label.innerText = 'Nomor Identitas Petugas';
    }
}
</script>
@endsection
