@extends('layouts.admin')

@section('page_title', 'Detail Guru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4" style="background-color: #ffffff;">
            <div class="card-header bg-white py-4 d-flex align-items-center border-bottom" style="border-bottom-color: #f1f5f9 !important;">
                <a href="{{ url('/admin/guru') }}" class="btn btn-sm btn-light text-muted me-3 border-0" style="background: #f8fafc;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h5 class="mb-1 fw-bold text-dark" style="font-size: 1.1rem;">Profil Guru</h5>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Detail informasi tenaga pendidik.</p>
                </div>
            </div>
            
            <div class="card-body p-5 text-center border-bottom" style="border-bottom-color: #f1f5f9 !important;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2E7D32&color=fff&size=120" alt="Avatar" class="rounded-circle mb-3 shadow-sm" style="border: 4px solid #f8fafc;">
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge" style="background-color: #dbeafe; color: #1e3a8a; font-weight: 500; font-size: 0.85rem; padding: 8px 16px;">Guru</span>
                    <span class="badge bg-light text-dark border" style="font-weight: 500; font-size: 0.85rem; padding: 8px 16px;">Mapel: {{ $user->subject ?? '-' }}</span>
                </div>
            </div>

            <div class="card-body p-4">
                <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Informasi Tambahan</h6>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted" style="font-size: 0.9rem;">Jenis Kelamin</div>
                    <div class="col-sm-8 fw-bold text-dark" style="font-size: 0.9rem;">
                        {{ $user->gender == 'L' ? 'Laki-laki' : ($user->gender == 'P' ? 'Perempuan' : 'Belum diatur') }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted" style="font-size: 0.9rem;">Terdaftar Pada</div>
                    <div class="col-sm-8 fw-bold text-dark" style="font-size: 0.9rem;">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d F Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
