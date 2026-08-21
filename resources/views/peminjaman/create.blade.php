@extends('layouts.admin')

@section('title', 'Peminjaman Baru')
@section('header_title', 'Catat Peminjaman Baru')

@section('content')
<div style="max-width: 650px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Form Peminjaman Buku</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Pilih peminjam (Siswa/Guru), judul buku, dan rencana tanggal pengembalian.</div>
            </div>
            <a href="{{ route('peminjaman.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        @if(session('error'))
            <div style="background-color: #FEE2E2; color: var(--danger); border: 1px solid #FCA5A5; padding: 0.85rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; font-size: 0.875rem;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label required">Peminjam (Siswa / Guru)</label>
                <select name="user_id" class="form-control-tzuchi searchable-select" required>
                    <option value="">-- Pilih Peminjam --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->role_name }}) - {{ $u->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label required">Pilih Buku</label>
                <select name="book_id" class="form-control-tzuchi searchable-select" required>
                    <option value="">-- Pilih Buku --</option>
                    @foreach($books as $b)
                        <option value="{{ $b->id }}" {{ old('book_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->title }} (Oleh: {{ $b->author_name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">Tanggal Peminjaman</label>
                    <input type="date" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" class="form-control-tzuchi" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Rencana Tanggal Pengembalian</label>
                    <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-control-tzuchi" required>
                </div>
            </div>

            <div style="font-size: 0.775rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                * Peminjam dapat menentukan rencana tanggal pengembalian buku (Maksimal 7 hari untuk Siswa / 14 hari untuk Guru).
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <a href="{{ route('peminjaman.index') }}" class="btn-tzuchi btn-secondary-tzuchi">Batal</a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi"><i class="bi bi-check-lg"></i> Proses Peminjaman</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control {
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        padding: 0.65rem 1rem;
        background-color: rgba(255, 255, 255, 0.95);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.9rem;
        color: var(--text-main);
        min-height: 42px;
        box-shadow: none;
    }
    .ts-control.focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-glow);
    }
    .ts-dropdown {
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-lg);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.9rem;
    }
    .ts-dropdown .option {
        padding: 0.5rem 1rem;
    }
    .ts-dropdown .option.active {
        background-color: var(--primary-light);
        color: var(--primary);
    }
    [data-theme="dark"] .ts-control {
        background-color: #0F172A;
        border-color: #334155;
        color: #F8FAFC;
    }
    [data-theme="dark"] .ts-dropdown {
        background-color: #1E293B;
        border-color: #334155;
        color: #F8FAFC;
    }
    [data-theme="dark"] .ts-dropdown .option.active {
        background-color: var(--primary);
        color: white;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.searchable-select').forEach(function(el) {
            new TomSelect(el, {
                create: false,
                plugins: ['dropdown_input'],
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    });
</script>
@endpush
