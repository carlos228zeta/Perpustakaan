@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('header_title', 'Edit Kategori Buku')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Edit Data Kategori</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Perbarui nama dan deskripsi kategori {{ $category->name }}.</div>
            </div>
            <a href="{{ route('kategori.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
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

        <form action="{{ route('kategori.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label required">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control-tzuchi" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="3" class="form-control-tzuchi">{{ old('description', $category->description) }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <a href="{{ route('kategori.index') }}" class="btn-tzuchi btn-secondary-tzuchi">Batal</a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi"><i class="bi bi-check-lg"></i> Perbarui Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection
