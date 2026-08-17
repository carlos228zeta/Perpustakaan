@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('header_title', 'Profil & Foto Pengguna')

@section('content')
<div style="max-width: 750px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.2rem; font-weight: 800;">Informasi Akun & Foto Profil</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Kelola data personal, hak akses, dan foto profil Anda.</div>
            </div>
            <a href="{{ route('profile.edit', $user->id) }}" class="btn-tzuchi btn-primary-tzuchi btn-sm">
                <i class="bi bi-pencil-square"></i> Edit Profil & Foto
            </a>
        </div>

        @php
            $avatarUrl = $user->avatar && file_exists(public_path($user->avatar)) 
                ? asset($user->avatar) 
                : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($user->name);
        @endphp

        <!-- Profile Header with Avatar -->
        <div style="display: flex; gap: 1.75rem; align-items: center; padding-bottom: 1.75rem; border-bottom: 1px solid var(--border-color); margin-bottom: 1.75rem; flex-wrap: wrap;">
            <div style="position: relative;">
                <img src="{{ $avatarUrl }}" alt="Foto Profil" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary); box-shadow: var(--shadow-md);">
                <a href="{{ route('profile.edit', $user->id) }}" style="position: absolute; bottom: 0; right: 0; background: var(--primary); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; border: 2px solid white; box-shadow: var(--shadow-sm);" title="Ganti Foto Profil">
                    <i class="bi bi-camera-fill"></i>
                </a>
            </div>
            <div>
                <h2 style="font-size: 1.45rem; font-weight: 800; margin-bottom: 0.25rem; color: var(--text-main);">{{ $user->name }}</h2>
                <div style="font-size: 0.885rem; color: var(--text-muted); margin-bottom: 0.65rem;"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <span class="badge-tzuchi badge-success"><i class="bi bi-shield-check"></i> {{ $user->role->display_name ?? 'Pengguna' }}</span>
                    <span class="badge-tzuchi badge-secondary"><i class="bi bi-person-check"></i> {{ ucfirst($user->status ?? 'active') }}</span>
                </div>
            </div>
        </div>

        <!-- Detailed Metadata Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; font-size: 0.9rem;">
            <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <span style="color: var(--text-muted); display: block; font-size: 0.775rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Peran Sistem</span>
                <strong style="color: var(--text-main);">
                    {{ $user->role->display_name ?? 'Pengguna' }}
                    @if($student && !empty($student->class_name))
                        ({{ $student->class_name }})
                    @endif
                </strong>
            </div>

            <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <span style="color: var(--text-muted); display: block; font-size: 0.775rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Status Akun</span>
                <strong style="color: var(--primary);"><i class="bi bi-check-circle-fill"></i> Aktif</strong>
            </div>

            @if($student)
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <span style="color: var(--text-muted); display: block; font-size: 0.775rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Kelas Siswa</span>
                    <strong style="color: var(--primary); font-size: 1.05rem;"><i class="bi bi-mortarboard-fill me-1"></i> {{ $student->class_name ?? 'Belum Diatur' }}</strong>
                </div>
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <span style="color: var(--text-muted); display: block; font-size: 0.775rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Nomor Induk Siswa (NIS)</span>
                    <code>{{ $student->nis }}</code>
                </div>
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <span style="color: var(--text-muted); display: block; font-size: 0.775rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Konsentrasi Keahlian / Jurusan</span>
                    <strong style="color: var(--text-main);">{{ $student->major ?? 'PPLG' }}</strong>
                </div>
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <span style="color: var(--text-muted); display: block; font-size: 0.775rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Nomor Telepon / WhatsApp</span>
                    <strong style="color: var(--text-main);">{{ $student->phone ?? '-' }}</strong>
                </div>
            @elseif($teacher)
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <span style="color: var(--text-muted); display: block; font-size: 0.775rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Nomor Induk Pegawai (NIP)</span>
                    <code>{{ $teacher->nip ?? '-' }}</code>
                </div>
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <span style="color: var(--text-muted); display: block; font-size: 0.775rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Bidang Studi / Mata Pelajaran</span>
                    <strong style="color: var(--text-main);">{{ $teacher->subject ?? 'Umum' }}</strong>
                </div>
                <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <span style="color: var(--text-muted); display: block; font-size: 0.775rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Nomor Telepon / WhatsApp</span>
                    <strong style="color: var(--text-main);">{{ $teacher->phone ?? '-' }}</strong>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
