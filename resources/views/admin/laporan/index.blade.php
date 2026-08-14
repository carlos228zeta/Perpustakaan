@extends('layouts.admin')

@section('title', 'Laporan Perpustakaan')
@section('header_title', 'Laporan & Rekapitulasi Sistem')

@section('content')
<!-- Header Controls Card (Hidden during print) -->
<div class="card-tzuchi no-print" style="margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Laporan Resmi Perpustakaan</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Cetak dan ekspor rekapitulasi data sekolah Cinta Kasih Tzu Chi Cengkareng.</div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('cetaklaporan.export', ['type' => $type]) }}" class="btn-tzuchi btn-secondary-tzuchi">
                <i class="bi bi-file-earmark-excel"></i> Ekspor Excel (CSV)
            </a>
            <button onclick="window.print()" class="btn-tzuchi btn-primary-tzuchi">
                <i class="bi bi-printer"></i> Cetak Laporan PDF
            </button>
        </div>
    </div>
</div>

<!-- Category Tabs (Hidden during print) -->
<div class="no-print" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <a href="{{ route('cetaklaporan', ['type' => 'borrowing']) }}" class="btn-tzuchi {{ $type === 'borrowing' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
        <i class="bi bi-arrow-left-right"></i> Laporan Peminjaman
    </a>
    <a href="{{ route('cetaklaporan', ['type' => 'books']) }}" class="btn-tzuchi {{ $type === 'books' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
        <i class="bi bi-book"></i> Laporan Koleksi Buku
    </a>
    <a href="{{ route('cetaklaporan', ['type' => 'fines']) }}" class="btn-tzuchi {{ $type === 'fines' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
        <i class="bi bi-cash-stack"></i> Laporan Denda
    </a>
    <a href="{{ route('cetaklaporan', ['type' => 'members']) }}" class="btn-tzuchi {{ $type === 'members' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
        <i class="bi bi-people"></i> Laporan Anggota
    </a>
</div>

<!-- Printable Formal Report Document -->
<div class="card-tzuchi" id="printable-area" style="background: white; padding: 2.5rem; border-radius: var(--radius-lg);">
    
    <!-- Formal Kop Surat -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 4px double var(--primary); padding-bottom: 1.25rem; margin-bottom: 1.75rem;">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 72px; width: auto; object-fit: contain;">
            <div>
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; text-transform: uppercase;">YAYASAN BUDDHA TZU CHI INDONESIA</div>
                <h2 style="font-size: 1.35rem; color: var(--primary); font-weight: 700; margin: 0.1rem 0; text-transform: uppercase;">SEKOLAH CINTA KASIH TZU CHI CENGKARENG</h2>
                <div style="font-size: 0.9rem; font-weight: 600; color: var(--text-main);">UNIT PERPUSTAKAAN SEKOLAH</div>
                <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.2rem;">
                    Jl. Kamal Raya No. 20, Cengkareng, Jakarta Barat 11730 | Telp: (021) 5439-7462 | Email: perpustakaan@tzuchi.sch.id
                </div>
            </div>
        </div>
    </div>

    <!-- Title & Date -->
    <div style="text-align: center; margin-bottom: 1.75rem;">
        <h3 style="font-size: 1.15rem; font-weight: 700; text-transform: uppercase; text-decoration: underline; color: var(--text-main); margin-bottom: 0.25rem;">
            @if($type === 'books') LAPORAN KOLEKSI BUKU PERPUSTAKAAN
            @elseif($type === 'fines') LAPORAN REKAPITULASI DENDA KETERLAMBATAN
            @elseif($type === 'members') LAPORAN DATA ANGGOTA (SISWA & GURU)
            @else LAPORAN SIRKULASI PEMINJAMAN BUKU
            @endif
        </h3>
        <div style="font-size: 0.825rem; color: var(--text-muted);">
            Periode Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-responsive" style="margin-bottom: 2rem;">
        <table class="table-tzuchi" style="border: 1px solid var(--border-color);">
            <thead>
                @if($type === 'books')
                    <tr style="background: #F3F4F6;">
                        <th width="5%" style="border-right: 1px solid var(--border-color);">No</th>
                        <th width="15%" style="border-right: 1px solid var(--border-color);">ISBN</th>
                        <th width="35%" style="border-right: 1px solid var(--border-color);">Judul Buku</th>
                        <th width="15%" style="border-right: 1px solid var(--border-color);">Kategori</th>
                        <th width="15%" style="border-right: 1px solid var(--border-color);">Penulis</th>
                        <th width="15%">Penerbit</th>
                    </tr>
                @elseif($type === 'fines')
                    <tr style="background: #F3F4F6;">
                        <th width="5%" style="border-right: 1px solid var(--border-color);">No</th>
                        <th width="25%" style="border-right: 1px solid var(--border-color);">Nama Anggota</th>
                        <th width="20%" style="border-right: 1px solid var(--border-color);">Jumlah Denda</th>
                        <th width="25%" style="border-right: 1px solid var(--border-color);">Alasan Keterlambatan</th>
                        <th width="12%" style="border-right: 1px solid var(--border-color);">Status</th>
                        <th width="13%">Tanggal</th>
                    </tr>
                @elseif($type === 'members')
                    <tr style="background: #F3F4F6;">
                        <th width="5%" style="border-right: 1px solid var(--border-color);">No</th>
                        <th width="20%" style="border-right: 1px solid var(--border-color);">NIS / NIP</th>
                        <th width="35%" style="border-right: 1px solid var(--border-color);">Nama Pengguna</th>
                        <th width="25%" style="border-right: 1px solid var(--border-color);">Email</th>
                        <th width="15%">Peran</th>
                    </tr>
                @else
                    <tr style="background: #F3F4F6;">
                        <th width="5%" style="border-right: 1px solid var(--border-color);">No</th>
                        <th width="22%" style="border-right: 1px solid var(--border-color);">Peminjam</th>
                        <th width="30%" style="border-right: 1px solid var(--border-color);">Judul Buku</th>
                        <th width="15%" style="border-right: 1px solid var(--border-color);">Kode Eksemplar</th>
                        <th width="11%" style="border-right: 1px solid var(--border-color);">Tgl Pinjam</th>
                        <th width="11%" style="border-right: 1px solid var(--border-color);">Jatuh Tempo</th>
                        <th width="6%">Status</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                    @if($type === 'books')
                        <tr>
                            <td style="border-right: 1px solid var(--border-color); text-align: center;">{{ $index + 1 }}</td>
                            <td style="border-right: 1px solid var(--border-color);"><code>{{ $row->isbn ?? '-' }}</code></td>
                            <td style="border-right: 1px solid var(--border-color);"><strong>{{ $row->title }}</strong></td>
                            <td style="border-right: 1px solid var(--border-color);">{{ $row->category_name ?? '-' }}</td>
                            <td style="border-right: 1px solid var(--border-color);">{{ $row->author_name ?? '-' }}</td>
                            <td>{{ $row->publisher_name ?? '-' }}</td>
                        </tr>
                    @elseif($type === 'fines')
                        <tr>
                            <td style="border-right: 1px solid var(--border-color); text-align: center;">{{ $index + 1 }}</td>
                            <td style="border-right: 1px solid var(--border-color);"><strong>{{ $row->user_name }}</strong></td>
                            <td style="border-right: 1px solid var(--border-color); color: var(--danger); font-weight: 700;">Rp {{ number_format($row->amount, 0, ',', '.') }}</td>
                            <td style="border-right: 1px solid var(--border-color);">{{ $row->reason }}</td>
                            <td style="border-right: 1px solid var(--border-color);"><span class="badge-tzuchi {{ $row->status === 'paid' ? 'badge-success' : 'badge-danger' }}">{{ strtoupper($row->status) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                        </tr>
                    @elseif($type === 'members')
                        <tr>
                            <td style="border-right: 1px solid var(--border-color); text-align: center;">{{ $index + 1 }}</td>
                            <td style="border-right: 1px solid var(--border-color);"><code>{{ $row->nis ?? $row->nip ?? '-' }}</code></td>
                            <td style="border-right: 1px solid var(--border-color);"><strong>{{ $row->name }}</strong></td>
                            <td style="border-right: 1px solid var(--border-color);">{{ $row->email }}</td>
                            <td><span class="badge-tzuchi badge-secondary">{{ $row->role_name }}</span></td>
                        </tr>
                    @else
                        <tr>
                            <td style="border-right: 1px solid var(--border-color); text-align: center;">{{ $index + 1 }}</td>
                            <td style="border-right: 1px solid var(--border-color);"><strong>{{ $row->user_name }}</strong></td>
                            <td style="border-right: 1px solid var(--border-color);">{{ $row->book_title }}</td>
                            <td style="border-right: 1px solid var(--border-color);"><code>{{ $row->copy_code }}</code></td>
                            <td style="border-right: 1px solid var(--border-color);">{{ \Carbon\Carbon::parse($row->borrow_date)->format('d/m/Y') }}</td>
                            <td style="border-right: 1px solid var(--border-color);">{{ \Carbon\Carbon::parse($row->due_date)->format('d/m/Y') }}</td>
                            <td><span class="badge-tzuchi {{ $row->status === 'returned' ? 'badge-secondary' : 'badge-success' }}">{{ strtoupper($row->status) }}</span></td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">Belum ada data untuk laporan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Formal Signature Section -->
    <div style="display: flex; justify-content: space-between; margin-top: 3.5rem; text-align: center; font-size: 0.875rem; page-break-inside: avoid;">
        <div style="width: 220px;">
            <p style="margin-bottom: 4rem;">Mengetahui,<br><strong>Kepala Perpustakaan</strong></p>
            <p style="font-weight: 700; border-bottom: 1px solid var(--text-main); display: inline-block; padding: 0 1rem;">( Dra. Ratna Wijaya, M.Pd )</p>
            <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.2rem;">NIP. 19780412 200312 2 001</div>
        </div>
        <div style="width: 220px;">
            <p style="margin-bottom: 4rem;">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br><strong>Petugas Perpustakaan</strong></p>
            <p style="font-weight: 700; border-bottom: 1px solid var(--text-main); display: inline-block; padding: 0 1rem;">( {{ auth()->user()->name }} )</p>
            <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.2rem;">Petugas Sirkulasi</div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide layout chrome */
    .app-sidebar, .app-topbar, .no-print, header, footer {
        display: none !important;
    }
    .app-main {
        margin: 0 !important;
        padding: 0 !important;
    }
    .app-content {
        padding: 0 !important;
        max-width: 100% !important;
    }
    body {
        background: white !important;
        color: black !important;
        -webkit-print-color-adjust: exact;
    }
    #printable-area {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
    }
    @page {
        size: A4 portrait;
        margin: 1.5cm;
    }
}
</style>
@endsection
