@extends('layouts.admin')

@section('title', 'Tambah Penerbit Baru')
@section('header_title', 'Tambah Data Penerbit')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card-tzuchi" style="border-top: 4px solid var(--primary);">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 700;">Tambah Data Penerbit Baru</h3>
        
        <form action="{{ route('masterdata.publisher.store') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label required">Nama Penerbit</label>
                <input type="text" name="name" class="form-control-tzuchi" required placeholder="Contoh: PT Gramedia Pustaka Utama" autofocus>
            </div>
            
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 2rem;">
                <a href="{{ route('masterdata.index', ['tab' => 'penerbit']) }}" class="btn-tzuchi btn-secondary-tzuchi">
                    Batal
                </a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">
                    Simpan Penerbit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
