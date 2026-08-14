@extends('layouts.admin')

@section('title', 'Pengaturan Perpustakaan')
@section('header_title', 'Pengaturan Perpustakaan')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Aturan Operasional Perpustakaan</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Atur batas jumlah pinjaman, durasi pinjam, dan nominal denda keterlambatan.</div>
            </div>
        </div>

        @if(session('success'))
            <div style="background-color: #E8F5E9; color: var(--primary); border: 1px solid #A5D6A7; padding: 0.85rem 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; font-size: 0.9rem;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('pengaturan.update') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label required">Nama Institusi / Sekolah</label>
                <input type="text" name="institution_name" value="{{ old('institution_name', $settings['institution_name'] ?? 'Cinta Kasih Tzu Chi Cengkareng') }}" class="form-control-tzuchi" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label required">Maksimal Pinjaman Siswa (Buku)</label>
                    <input type="number" name="max_student_borrow" value="{{ old('max_student_borrow', $settings['max_student_borrow'] ?? 3) }}" class="form-control-tzuchi" required min="1" max="10">
                </div>

                <div class="form-group">
                    <label class="form-label required">Maksimal Pinjaman Guru (Buku)</label>
                    <input type="number" name="max_teacher_borrow" value="{{ old('max_teacher_borrow', $settings['max_teacher_borrow'] ?? 5) }}" class="form-control-tzuchi" required min="1" max="20">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label required">Durasi Pinjam Siswa (Hari)</label>
                    <input type="number" name="student_borrow_days" value="{{ old('student_borrow_days', $settings['student_borrow_days'] ?? 7) }}" class="form-control-tzuchi" required min="1" max="30">
                </div>

                <div class="form-group">
                    <label class="form-label required">Durasi Pinjam Guru (Hari)</label>
                    <input type="number" name="teacher_borrow_days" value="{{ old('teacher_borrow_days', $settings['teacher_borrow_days'] ?? 14) }}" class="form-control-tzuchi" required min="1" max="60">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label required">Denda Keterlambatan per Hari (Rp)</label>
                <input type="number" name="fine_per_day" value="{{ old('fine_per_day', $settings['fine_per_day'] ?? 1000) }}" class="form-control-tzuchi" required min="0">
                <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.25rem;">Biaya denda yang otomatis dihitung per hari keterlambatan saat buku dikembalikan.</div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi">
                    <i class="bi bi-check-lg"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
