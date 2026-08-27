@extends('layouts.admin')

@section('title', 'Kelola Denda & Kas')
@section('header_title', 'Kelola Denda & Kas')

@php
    // Workaround to copy user uploaded logos to public/img folder
    $bcaSource = 'C:\Users\carlo\.gemini\antigravity-ide\brain\0013180a-319d-44dd-8d9d-c78cfadfce48\.user_uploaded\media_1787285077280.png';
    $mandiriSource = 'C:\Users\carlo\.gemini\antigravity-ide\brain\0013180a-319d-44dd-8d9d-c78cfadfce48\.user_uploaded\media_1787285094856.png';
    
    if (file_exists($bcaSource) && !file_exists(public_path('img/bca.png'))) {
        copy($bcaSource, public_path('img/bca.png'));
    }
    if (file_exists($mandiriSource) && !file_exists(public_path('img/mandiri.png'))) {
        copy($mandiriSource, public_path('img/mandiri.png'));
    }
@endphp

@section('content')
<div style="max-width: 1150px; margin: 0 auto;">
    <!-- Page Header Title & Subtitle -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.4rem; margin-bottom: 0.35rem; font-weight: 800; color: var(--text-main);">
                <i class="bi bi-wallet2" style="color: var(--primary);"></i> Kelola Denda & Kas Perpustakaan
            </h2>
            <div style="font-size: 0.875rem; color: var(--text-muted);">
                Pantau akumulasi denda keterlambatan, status pengembalian, dan kas denda peminjaman.
            </div>
        </div>
    </div>

    <!-- 4 Stat Cards Grid -->
    <div class="stat-grid" style="margin-bottom: 1.75rem;">
        <div class="stat-card">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.35rem;">AKUMULASI DENDA</div>
                <div style="font-size: 1.65rem; font-weight: 800; color: var(--primary);">Rp {{ number_format($totalAccumulated, 0, ',', '.') }}</div>
                <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Total Kas & Denda Tercatat</div>
            </div>
            <div style="width: 52px; height: 52px; border-radius: 16px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.35rem;">BELUM LUNAS</div>
                <div style="font-size: 1.65rem; font-weight: 800; color: var(--danger);">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
                <div style="font-size: 0.775rem; color: var(--danger); margin-top: 0.35rem; font-weight: 600;">{{ $overdueMembersCount }} Anggota Terlambat</div>
            </div>
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(220, 38, 38, 0.12); color: var(--danger); display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.35rem;">SUDAH LUNAS</div>
                <div style="font-size: 1.65rem; font-weight: 800; color: #166534;">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Denda Masuk Kas</div>
            </div>
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(34, 197, 94, 0.12); color: #166534; display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.35rem;">PEMINJAM TERLAMBAT</div>
                <div style="font-size: 1.65rem; font-weight: 800; color: var(--warning);">{{ $overdueMembersCount }} Orang</div>
                <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 0.35rem;">Perlu Tindakan Pengembalian</div>
            </div>
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(245, 158, 11, 0.12); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('denda.index', ['status' => 'all', 'search' => $search]) }}" class="btn-tzuchi {{ $filterStatus === 'all' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
                <i class="bi bi-grid"></i> Semua Denda
            </a>
            <a href="{{ route('denda.index', ['status' => 'unpaid', 'search' => $search]) }}" class="btn-tzuchi {{ $filterStatus === 'unpaid' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
                <i class="bi bi-exclamation-circle"></i> Belum Lunas
            </a>
            <a href="{{ route('denda.index', ['status' => 'paid', 'search' => $search]) }}" class="btn-tzuchi {{ $filterStatus === 'paid' ? 'btn-primary-tzuchi' : 'btn-secondary-tzuchi' }} btn-sm">
                <i class="bi bi-check-lg"></i> Sudah Lunas
            </a>
        </div>

        <form action="{{ route('denda.index') }}" method="GET" style="display: flex; gap: 0.5rem; width: 100%; max-width: 380px;">
            <input type="hidden" name="status" value="{{ $filterStatus }}">
            <input type="text" name="search" value="{{ $search }}" class="form-control-tzuchi" placeholder="Cari peminjam, judul buku, kode...">
            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <!-- Denda Table Container -->
    <div class="card-tzuchi" style="padding: 0; overflow: hidden;">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">NO</th>
                    <th>PEMINJAM</th>
                    <th>BUKU & EKSEMPLAR</th>
                    <th style="width: 140px;">HARUS KEMBALI</th>
                    <th style="width: 130px; text-align: center;">KETERLAMBATAN</th>
                    <th style="width: 140px;">JUMLAH DENDA</th>
                    <th style="width: 130px; text-align: center;">STATUS</th>
                    <th style="width: 140px; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($finesList as $index => $fine)
                    <tr>
                        <td style="text-align: center; font-weight: 600;">{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $fine->user_name }}</div>
                            @if(!empty($fine->class_name))
                                <div style="font-size: 0.775rem; color: var(--primary); font-weight: 600; margin-top: 0.15rem;">
                                    <i class="bi bi-mortarboard-fill"></i> {{ $fine->class_name }} @if(!empty($fine->student_major)) • {{ $fine->student_major }} @endif
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main);">{{ $fine->book_title }}</div>
                            <div style="font-size: 0.775rem; color: var(--text-muted);">Kode: {{ $fine->copy_code }}</div>
                        </td>
                        <td>{{ $fine->due_date }}</td>
                        <td style="text-align: center;">
                            @if($fine->days_late > 0)
                                <span class="badge-tzuchi badge-danger">{{ $fine->days_late }} Hari</span>
                            @else
                                <span class="badge-tzuchi badge-secondary">Tepat Waktu</span>
                            @endif
                        </td>
                        <td style="font-weight: 800; color: {{ $fine->is_paid ? '#166534' : 'var(--danger)' }};">
                            Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            @if($fine->is_paid)
                                <span class="badge-tzuchi badge-success"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                            @else
                                <span class="badge-tzuchi badge-danger"><i class="bi bi-clock-fill"></i> Belum Lunas</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if(!$fine->is_paid)
                                <button type="button" onclick="openPaymentModal('{{ $fine->id }}', '{{ $fine->type }}', {{ $fine->fine_amount }})" class="btn-tzuchi btn-primary-tzuchi btn-sm" style="font-size: 0.775rem;">
                                    <i class="bi bi-wallet2"></i> Bayar
                                </button>
                            @else
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.25rem;">
                                    <span style="font-size: 0.8rem; color: var(--text-muted);"><i class="bi bi-check-all"></i> Selesai</span>
                                    @if($fine->type == 'fine_record')
                                    <form action="{{ route('denda.archive', $fine->id) }}" method="POST" onsubmit="return confirmDeleteModal(event, 'Arsipkan Denda?', 'Arsipkan denda ini? Denda akan dihapus dari daftar ini namun tetap aman di Laporan Pembayaran.')">
                                        @csrf
                                        <button type="submit" class="btn-tzuchi btn-sm" style="font-size: 0.7rem; background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; padding: 0.15rem 0.5rem; border-radius: 4px;"><i class="bi bi-archive"></i> Hapus</button>
                                    </form>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                            <i class="bi bi-wallet2" style="font-size: 2.5rem; color: var(--primary); display: block; margin-bottom: 0.5rem;"></i>
                            Tidak ada catatan denda peminjaman ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal-tzuchi-backdrop" id="paymentModal" style="display:none;">
    <div class="modal-tzuchi-dialog">
        <div class="modal-tzuchi-header">
            <h3 class="modal-tzuchi-title">Proses Pembayaran Denda</h3>
            <button type="button" class="modal-tzuchi-close" onclick="closeModal('paymentModal')">&times;</button>
        </div>
        <form id="paymentForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" id="paymentType" value="">
            <div class="modal-tzuchi-body" style="max-height: 65vh; overflow-y: auto; padding-right: 0.5rem;">
                <div style="margin-bottom: 1rem; text-align: center; background: #F8FAFC; padding: 0.75rem; border-radius: var(--radius-md); border: 1px dashed var(--border-color);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 0.15rem;">Total Tagihan</div>
                    <strong id="paymentAmountDisplay" style="color: var(--danger); font-size: 1.4rem; font-weight: 800;">Rp0</strong>
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label required">Metode Pembayaran</label>
                    <select name="payment_method" id="paymentMethodSelect" class="form-control-tzuchi" required onchange="togglePaymentFields()">
                        <option value="cash">Tunai (Cash)</option>
                        <option value="qris">QRIS (E-Wallet / M-Banking)</option>
                        <option value="bca">Transfer Bank BCA</option>
                        <option value="mandiri">Transfer Bank Mandiri</option>
                    </select>
                </div>

                <!-- Informasi Pembayaran Dinamis (QRIS / Bank) -->
                <div id="paymentInfoArea" style="display: none; padding: 0.75rem; background: var(--bg-color); border: 1px dashed var(--border-color); border-radius: var(--radius-md); margin-bottom: 1rem; text-align: center;">
                    <div id="qrisInfo" style="display: none;">
                        @php $qrisImg = \App\Models\LibrarySetting::get('qris_image'); @endphp
                        @if($qrisImg && file_exists(public_path($qrisImg)))
                            <img src="{{ asset($qrisImg) }}" alt="QRIS Sekolah" style="max-height: 120px; max-width: 100%; border-radius: 8px; margin-bottom: 0.5rem; border: 1px solid var(--border-color);">
                            <div style="font-size: 0.8rem; color: var(--text-muted);">Silakan scan kode QRIS di atas untuk melakukan pembayaran.</div>
                        @else
                            <div style="padding: 1rem; color: var(--danger);"><i class="bi bi-exclamation-triangle"></i> QRIS belum dikonfigurasi di Pengaturan Sistem.</div>
                        @endif
                    </div>
                    
                    <div id="bcaInfo" style="display: none;">
                        <img src="{{ asset('img/bca.png') }}" alt="BCA" style="height: 40px; margin-bottom: 1rem; object-fit: contain; mix-blend-mode: multiply;">
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">Nomor Rekening BCA</div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: var(--text-main); letter-spacing: 2px;">{{ \App\Models\LibrarySetting::get('bca_account', '-') }}</div>
                        <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-top: 0.25rem;">a.n. {{ \App\Models\LibrarySetting::get('bca_account_name', '-') }}</div>
                    </div>

                    <div id="mandiriInfo" style="display: none;">
                        <img src="{{ asset('img/mandiri.png') }}" alt="Mandiri" style="height: 35px; margin-bottom: 1rem; object-fit: contain; mix-blend-mode: multiply;">
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">Nomor Rekening Mandiri</div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: var(--text-main); letter-spacing: 2px;">{{ \App\Models\LibrarySetting::get('mandiri_account', '-') }}</div>
                        <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-top: 0.25rem;">a.n. {{ \App\Models\LibrarySetting::get('mandiri_account_name', '-') }}</div>
                    </div>
                </div>

                <!-- Input Tunai & Kalkulator Kembalian -->
                <div id="cashFields" style="display: block; padding: 1rem; background: #ECFDF5; border: 1px solid #10B981; border-radius: var(--radius-md); margin-bottom: 1rem;">
                    <div class="form-group" style="margin-bottom: 0.75rem;">
                        <label class="form-label" style="color: #047857;">Nominal Uang Diterima (Rp)</label>
                        <input type="number" id="cashInput" class="form-control-tzuchi" placeholder="Contoh: 50000" onkeyup="calculateChange()" min="0">
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #34D399; padding-top: 0.75rem; margin-top: 0.5rem;">
                        <span style="font-size: 0.85rem; font-weight: 700; color: #047857;">Kembalian:</span>
                        <strong id="changeOutput" style="font-size: 1.25rem; color: #047857; font-weight: 800;">Rp0</strong>
                    </div>
                </div>

                <div id="transferFields" style="display: none; padding: 0.75rem; background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); margin-bottom: 1rem;">
                    <!-- Nomor Referensi Removed -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Bukti Transfer (Gambar)</label>
                        <input type="file" name="proof" id="proofInput" class="form-control-tzuchi" accept="image/*">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Upload screenshot resi atau foto bukti transfer (Max: 2MB).</div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Catatan Pembayaran (Opsional)</label>
                    <textarea name="notes" class="form-control-tzuchi" rows="2" placeholder="Catatan tambahan, contoh: Uang pas atau kembalian"></textarea>
                </div>
            </div>
            <div class="modal-tzuchi-footer">
                <button type="button" onclick="closeModal('paymentModal')" class="btn-tzuchi btn-secondary-tzuchi">Batal</button>
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi" style="background: var(--primary);"><i class="bi bi-check-circle"></i> Konfirmasi Pembayaran</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentFineAmount = 0;

function openPaymentModal(id, type, amount) {
    const form = document.getElementById('paymentForm');
    form.action = "{{ url('admin/denda') }}/" + id + "/pay";
    document.getElementById('paymentType').value = type;
    document.getElementById('paymentAmountDisplay').innerText = "Rp" + new Intl.NumberFormat('id-ID').format(amount);
    currentFineAmount = amount;
    
    // Reset inputs
    document.getElementById('cashInput').value = '';
    document.getElementById('changeOutput').innerText = 'Rp0';
    
    document.getElementById('paymentModal').style.display = 'flex';
    togglePaymentFields(); // Trigger UI state
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function togglePaymentFields() {
    const method = document.getElementById('paymentMethodSelect').value;
    const transferFields = document.getElementById('transferFields');
    const cashFields = document.getElementById('cashFields');
    const paymentInfoArea = document.getElementById('paymentInfoArea');
    const qrisInfo = document.getElementById('qrisInfo');
    const bcaInfo = document.getElementById('bcaInfo');
    const mandiriInfo = document.getElementById('mandiriInfo');
    
    // const refInput = document.getElementById('referenceInput');
    const proofInput = document.getElementById('proofInput');
    
    // Reset displays
    paymentInfoArea.style.display = 'none';
    qrisInfo.style.display = 'none';
    bcaInfo.style.display = 'none';
    mandiriInfo.style.display = 'none';
    
    if (method !== 'cash') {
        transferFields.style.display = 'block';
        cashFields.style.display = 'none';
        // refInput.setAttribute('required', 'required');
        proofInput.setAttribute('required', 'required');
        
        paymentInfoArea.style.display = 'block';
        if (method === 'qris') qrisInfo.style.display = 'block';
        if (method === 'bca') bcaInfo.style.display = 'block';
        if (method === 'mandiri') mandiriInfo.style.display = 'block';
        
    } else {
        transferFields.style.display = 'none';
        cashFields.style.display = 'block';
        // refInput.removeAttribute('required');
        proofInput.removeAttribute('required');
    }
}

function calculateChange() {
    const cashInput = document.getElementById('cashInput').value;
    let change = 0;
    
    if (cashInput && !isNaN(cashInput)) {
        change = parseInt(cashInput) - currentFineAmount;
        if (change < 0) change = 0;
    }
    
    document.getElementById('changeOutput').innerText = "Rp" + new Intl.NumberFormat('id-ID').format(change);
}
</script>
@endpush
