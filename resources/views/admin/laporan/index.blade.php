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
                <i class="bi bi-file-earmark-excel"></i> Ekspor Excel (.xls)
            </a>
            <button onclick="window.print()" class="btn-tzuchi btn-primary-tzuchi">
                <i class="bi bi-printer"></i> Cetak Laporan PDF
            </button>
        </div>
    </div>
</div>

<!-- Category Tabs (Hidden during print) -->
<div class="no-print" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; padding: 0.25rem 0.25rem 0.5rem 0.25rem;">
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
<div class="card-tzuchi" id="printable-area" style="padding: 2.5rem; border-radius: var(--radius-lg);">
    
    <!-- Formal Kop Surat Official School Letterhead (100% Explicit 1-to-1 Match to Reference PDF) -->
    @php
        $customLogo = \App\Models\LibrarySetting::get('institution_logo');
        $logoSrc = ($customLogo && file_exists(public_path($customLogo))) ? asset($customLogo) : asset('img/logo.png');
        $foundationName = \App\Models\LibrarySetting::get('kop_foundation_name', 'YAYASAN BUDDHA TZU CHI WIYATA INDONESIA');
        $rawInstName = \App\Models\LibrarySetting::get('institution_name', 'SEKOLAH CINTA KASIH TZU CHI');
        // Clean institution name to strictly match reference letterhead "SEKOLAH CINTA KASIH TZU CHI"
        $instName = str_ireplace([' CENGKARENG'], '', $rawInstName);
        if (!str_contains(strtoupper($instName), 'SEKOLAH')) {
            $instName = 'SEKOLAH ' . $instName;
        }
        $unitsText = \App\Models\LibrarySetting::get('kop_units_text', '(KB – TK – SD – SMP – SMK – SMA)');
        $letterNoPrefix = \App\Models\LibrarySetting::get('kop_letter_no', 'SMK-CKTC');
        $cityName = \App\Models\LibrarySetting::get('kop_city_name', 'Jakarta');
        $streetAddress = \App\Models\LibrarySetting::get('library_address', 'Jln. Kamal Raya Outer Ring Road No.20. Cengkareng Timur – Jakarta Barat 11730');
        $phone = \App\Models\LibrarySetting::get('library_phone', '021-5439 7565, 5439 7462');
        $email = \App\Models\LibrarySetting::get('library_email', 'sekretariat@cintakasihthuuchi.sch.id');
    @endphp
    
    <div class="print-header-kop" style="display: flex; align-items: center; justify-content: center; position: relative; border-bottom: 4px double #000; padding-bottom: 0.6rem; margin-bottom: 1.25rem;">
        <div style="position: absolute; left: 0; top: 50%; transform: translateY(-50%);">
            <img src="{{ $logoSrc }}" alt="Logo" style="height: 110px; width: auto; max-width: 160px; object-fit: contain;">
        </div>
        <div style="text-align: center; margin: 0 auto; width: 100%; padding-left: 140px; padding-right: 20px;">
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 1.25rem; font-weight: bold; letter-spacing: 0.02em; text-transform: uppercase; color: #000; line-height: 1.25;">
                {{ strtoupper($foundationName) }}
            </div>
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 1.8rem; font-weight: 900; letter-spacing: 0.01em; text-transform: uppercase; color: #000; margin: 0.1rem 0; line-height: 1.15;">
                {{ strtoupper($instName) }}
            </div>
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 1.15rem; font-weight: bold; text-transform: uppercase; color: #000; margin-bottom: 0.2rem; line-height: 1.2;">
                {{ $unitsText }}
            </div>
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 0.88rem; color: #000; line-height: 1.35;">
                {{ $streetAddress }}<br>
                Telp. {{ $phone }}, Fax : 021 – 5439 7573<br>
                email : {{ $email }} // www.cintakasihthuuchi.sch.id
            </div>
        </div>
    </div>

    <!-- Official Header Metadata Block (No, Lamp, Hal, Tanggal - Match Reference PDF) -->
    <div style="display: flex; justify-content: space-between; font-family: 'Times New Roman', Times, serif; font-size: 0.95rem; color: #000; margin-bottom: 1.5rem; line-height: 1.5;">
        <div>
            <div><span style="display: inline-block; width: 60px;">No.</span>: 175.5/{{ $letterNoPrefix }}/VI/{{ date('Y') }}</div>
            <div><span style="display: inline-block; width: 60px;">Lamp.</span>: -</div>
            <div><span style="display: inline-block; width: 60px;">Hal</span>: <u>@if($type === 'books') Laporan Koleksi Buku Perpustakaan @elseif($type === 'fines') Laporan Rekapitulasi Denda Keterlambatan @elseif($type === 'members') Laporan Data Anggota (Siswa & Guru) @else Laporan Sirkulasi Peminjaman Buku @endif</u></div>
        </div>
        <div style="text-align: right; padding-right: 10px;">
            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <!-- Data Table (Clean Responsive Table) -->
    <div class="table-responsive" style="margin-bottom: 2rem;">
        <table class="table-tzuchi print-table-clean">
            <thead>
                @if($type === 'books')
                    <tr>
                        <th width="5%" style="text-align: center;">No</th>
                        <th width="15%">ISBN</th>
                        <th width="35%">Judul Buku</th>
                        <th width="15%">Kategori</th>
                        <th width="15%">Penulis</th>
                        <th width="15%">Penerbit</th>
                    </tr>
                @elseif($type === 'fines')
                    <tr>
                        <th width="5%" style="text-align: center;">No</th>
                        <th width="25%">Nama Anggota</th>
                        <th width="20%">Jumlah Denda</th>
                        <th width="25%">Alasan Keterlambatan</th>
                        <th width="12%">Status</th>
                        <th width="13%">Tanggal</th>
                    </tr>
                @elseif($type === 'members')
                    <tr>
                        <th width="5%" style="text-align: center;">No</th>
                        <th width="20%">NIS / NIP</th>
                        <th width="35%">Nama Pengguna</th>
                        <th width="25%">Email</th>
                        <th width="15%">Peran</th>
                    </tr>
                @else
                    <tr>
                        <th width="5%" style="text-align: center;">No</th>
                        <th width="22%">Peminjam</th>
                        <th width="30%">Judul Buku</th>
                        <th width="15%">Kode Eksemplar</th>
                        <th width="11%">Tgl Pinjam</th>
                        <th width="11%">Jatuh Tempo</th>
                        <th width="6%">Status</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                    @if($type === 'books')
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><code>{{ $row->isbn ?? '-' }}</code></td>
                            <td><strong>{{ $row->title }}</strong></td>
                            <td>{{ $row->category_name ?? '-' }}</td>
                            <td>{{ $row->author_name ?? '-' }}</td>
                            <td>{{ $row->publisher_name ?? '-' }}</td>
                        </tr>
                    @elseif($type === 'fines')
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $row->user_name }}</strong></td>
                            <td style="color: var(--danger); font-weight: 700;">Rp {{ number_format($row->amount, 0, ',', '.') }}</td>
                            <td>{{ $row->reason }}</td>
                            <td><span class="badge-tzuchi {{ $row->status === 'paid' ? 'badge-success' : 'badge-danger' }}">{{ strtoupper($row->status) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                        </tr>
                    @elseif($type === 'members')
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $row->number_id ?? '-' }}</td>
                            <td><strong>{{ $row->name }}</strong></td>
                            <td>{{ $row->email }}</td>
                            <td><span class="badge-tzuchi badge-secondary">{{ $row->role_name }}</span></td>
                        </tr>
                    @else
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $row->user_name }}</strong></td>
                            <td>{{ $row->book_title }}</td>
                            <td><code>{{ $row->copy_code }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($row->borrow_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->due_date)->format('d/m/Y') }}</td>
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
        <div style="width: 240px; position: relative;">
            <p style="margin-bottom: 0.25rem;">Mengetahui,<br><strong>Kepala Perpustakaan</strong></p>
            
            <!-- Signature & Stamp Container -->
            <div style="height: 80px; display: flex; align-items: center; justify-content: center; position: relative; margin: 0.25rem 0;">
                @if(!empty($institutionStampImg))
                    <img src="{{ $institutionStampImg }}" alt="Stempel" style="position: absolute; left: 15px; height: 75px; width: auto; opacity: 0.88; pointer-events: none;">
                @endif

                @if(!empty($librarianSignatureImg))
                    <img src="{{ $librarianSignatureImg }}" alt="TTD Digital" style="height: 70px; width: auto; max-width: 180px; object-fit: contain; z-index: 2;">
                @else
                    <div style="height: 60px;"></div>
                @endif
            </div>

            <p style="font-weight: 700; border-bottom: 1px solid #000; display: inline-block; padding: 0 1rem; margin-bottom: 0.2rem;">( {{ $headLibrarianName }} )</p>
            <div style="font-size: 0.775rem; color: #000;">NIP. {{ $headLibrarianNip }}</div>
        </div>

        <div style="width: 240px;">
            <p style="margin-bottom: 0.25rem;">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br><strong>Petugas Perpustakaan</strong></p>
            <div style="height: 80px;"></div>
            <p style="font-weight: 700; border-bottom: 1px solid #000; display: inline-block; padding: 0 1rem; margin-bottom: 0.2rem;">( {{ auth()->user()->name }} )</p>
            <div style="font-size: 0.775rem; color: #000;">Petugas Sirkulasi</div>
        </div>
    </div>
</div>

<style>
.print-table-clean th {
    background-color: #F1F5F9 !important;
    color: #000 !important;
    font-weight: bold !important;
    text-transform: uppercase;
    font-size: 0.75rem;
    padding: 8px 10px !important;
}
.print-table-clean td {
    padding: 8px 10px !important;
    color: #000 !important;
    background-color: #FFF !important;
    font-size: 0.825rem;
}

@media print {
    /* Hide layout chrome */
    .app-sidebar, .app-topbar, .no-print, header, footer {
        display: none !important;
    }
    .app-main {
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
        overflow: visible !important;
    }
    .app-wrapper {
        padding: 0 !important;
        margin: 0 !important;
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
        display: block !important;
    }
    .app-content {
        padding: 0 !important;
        max-width: 100% !important;
        overflow: visible !important;
        height: auto !important;
    }
    body {
        background: white !important;
        color: black !important;
        -webkit-print-color-adjust: exact;
    }
    #printable-area {
        background-color: white !important;
        color: black !important;
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
    }
    .print-table-clean th {
        background-color: #E2E8F0 !important;
        color: #000 !important;
        border: 1px solid #000 !important;
    }
    .print-table-clean td {
        border: 1px solid #000 !important;
        color: #000 !important;
    }
    @page {
        size: A4 portrait;
        margin: 1.2cm;
    }
}
</style>
@endsection
