@extends('layouts.admin')

@section('title', 'Tambah Lokasi Rak Baru')
@section('header_title', 'Tambah Lokasi Rak')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card-tzuchi" style="border-top: 4px solid var(--primary);">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 700;">Tambah Lokasi Rak Baru</h3>
        
        <form action="{{ route('masterdata.shelf.store') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label required">Kode Rak</label>
                <input type="text" name="code" class="form-control-tzuchi" required placeholder="Contoh: RAK-01-A" autofocus>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label required">Nama Lokasi / Deskripsi Rak</label>
                <input type="text" name="name" class="form-control-tzuchi" required placeholder="Contoh: Rak Buku Sains & Matematika">
            </div>
            
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 2rem;">
                <a href="{{ route('masterdata.index', ['tab' => 'rak']) }}" class="btn-tzuchi btn-secondary-tzuchi">
                    Batal
                </a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">
                    Simpan Lokasi Rak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
