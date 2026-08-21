@extends('layouts.admin')

@section('title', 'Edit Siswa')
@section('header_title', 'Edit Data Siswa')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <div class="card-tzuchi">
        <div class="card-header-tzuchi">
            <div>
                <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Edit Profil Siswa</h3>
                <div style="font-size: 0.825rem; color: var(--text-muted);">Perbarui data diri siswa {{ $student->name }}.</div>
            </div>
            <a href="{{ route('siswa.index') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
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

        <form action="{{ route('siswa.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">NIS (Nomor Induk Siswa)</label>
                    <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" class="form-control-tzuchi" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}" class="form-control-tzuchi">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label required">Nama Lengkap Siswa</label>
                <input type="text" name="name" value="{{ old('name', $student->name) }}" class="form-control-tzuchi" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" class="form-control-tzuchi" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control-tzuchi" placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <select name="class_id" id="class-select" class="form-control-tzuchi searchable-select">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $c)
                            @php
                                $cName = strtolower($c->name);
                                $isSmaSmk = str_contains($cName, 'sma') || str_contains($cName, 'smk') || preg_match('/\b(x|xi|xii)\b/i', $cName);
                            @endphp
                            <option value="{{ $c->id }}" data-is-sma-smk="{{ $isSmaSmk ? '1' : '0' }}" {{ old('class_id', $student->class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jurusan / Konsentrasi Keahlian</label>
                    <select name="major" id="major-select" class="form-control-tzuchi searchable-select">
                        <option value="Tidak Ada (Umum)" {{ old('major', $student->major) == 'Tidak Ada (Umum)' ? 'selected' : '' }}>-- Tidak Ada (Siswa SMP) --</option>
                        @foreach($majors as $m)
                            <option value="{{ $m->name }}" {{ old('major', $student->major) == $m->name ? 'selected' : '' }}>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Telepon / WA</label>
                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="form-control-tzuchi">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <a href="{{ route('siswa.index') }}" class="btn-tzuchi btn-secondary-tzuchi">Batal</a>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi"><i class="bi bi-check-lg"></i> Perbarui Siswa</button>
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
        padding: 0.6rem 0.85rem;
        font-family: inherit;
        font-size: 0.95rem;
        box-shadow: none;
        background-color: var(--bg-color);
    }
    .ts-dropdown {
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        font-family: inherit;
        font-size: 0.95rem;
        margin-top: 4px;
        z-index: 9999 !important;
    }
    .ts-dropdown .option:hover, .ts-dropdown .option.active {
        background-color: var(--primary-light);
        color: var(--primary);
    }
    /* Dark mode overrides */
    [data-theme='dark'] .ts-control {
        background-color: var(--bg-color);
        border-color: var(--border-color);
        color: var(--text-main);
    }
    [data-theme='dark'] .ts-dropdown {
        background-color: var(--bg-color);
        border-color: var(--border-color);
        color: var(--text-main);
    }
    [data-theme='dark'] .ts-dropdown .option:hover, [data-theme='dark'] .ts-dropdown .option.active {
        background-color: #2D3748;
        color: var(--primary);
    }
    [data-theme='dark'] .ts-control input {
        color: var(--text-main);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const instances = {};
        document.querySelectorAll('.searchable-select').forEach(function(el) {
            instances[el.id || el.name] = new TomSelect(el, {
                create: false,
                plugins: ['dropdown_input'],
                placeholder: "-- Pilih --",
                dropdownParent: null
            });
        });

        const classSelect = document.getElementById('class-select');
        if (classSelect && instances['class-select'] && instances['major-select']) {
            instances['class-select'].on('change', function(value) {
                const option = classSelect.querySelector(`option[value="${value}"]`);
                if (option) {
                    const isSmaSmk = option.getAttribute('data-is-sma-smk');
                    const className = option.text;
                    
                    if (isSmaSmk === '0' || !value) {
                        instances['major-select'].setValue('Tidak Ada (Umum)');
                        instances['major-select'].disable();
                    } else {
                        instances['major-select'].enable();
                        // Try to auto-select if major name is within class name
                        const majorOptions = Array.from(document.getElementById('major-select').options);
                        let autoSelected = false;
                        for (let i = 0; i < majorOptions.length; i++) {
                            const mName = majorOptions[i].value;
                            if (mName !== 'Tidak Ada (Umum)') {
                                // Extract acronym if it's in parentheses, like "Pengembangan... (PPLG)"
                                const match = mName.match(/\(([^)]+)\)/);
                                const acronym = match ? match[1] : mName;
                                if (className.includes(acronym) || className.includes(mName)) {
                                    instances['major-select'].setValue(mName);
                                    autoSelected = true;
                                    break;
                                }
                            }
                        }
                    }
                }
            });

            // Run once on load to set initial state
            const initialVal = instances['class-select'].getValue();
            if (initialVal) {
                const option = classSelect.querySelector(`option[value="${initialVal}"]`);
                if (option && option.getAttribute('data-is-sma-smk') === '0') {
                    instances['major-select'].disable();
                }
            }
        }
    });
</script>
@endpush
