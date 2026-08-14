@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('header_title', 'Profil Pengguna')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Informasi Akun</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Detail akun dan hak akses Anda di perpustakaan.</div>
            </div>
            <a href="{{ route('profile.edit', $user->id) }}" class="btn-tzuchi btn-primary-tzuchi btn-sm">
                <i class="bi bi-pencil"></i> Edit Profil
            </a>
        </div>

        <div style="display: flex; gap: 1.5rem; align-items: center; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); margin-bottom: 1.5rem;">
            <div style="width: 72px; height: 72px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700;">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h2 style="font-size: 1.35rem; margin-bottom: 0.25rem;">{{ $user->name }}</h2>
                <div style="font-size: 0.875rem; color: var(--text-muted); mb-1;">{{ $user->email }}</div>
                <span class="badge-tzuchi badge-success">{{ $user->role->display_name ?? 'Pengguna' }}</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; font-size: 0.9rem;">
            <div>
                <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Status Akun</span>
                <strong>{{ ucfirst($user->status ?? 'active') }}</strong>
            </div>

            @if($student)
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">NIS</span>
                    <strong>{{ $student->nis }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Jurusan</span>
                    <strong>{{ $student->major ?? 'PPLG' }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Nomor Telepon</span>
                    <strong>{{ $student->phone ?? '-' }}</strong>
                </div>
            @elseif($teacher)
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">NIP</span>
                    <strong>{{ $teacher->nip ?? '-' }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Mata Pelajaran</span>
                    <strong>{{ $teacher->subject ?? 'Umum' }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Nomor Telepon</span>
                    <strong>{{ $teacher->phone ?? '-' }}</strong>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
