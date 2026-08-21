@extends('layouts.admin')

@section('title', 'Kelola Denda & Kas')
@section('header_title', 'Kelola Denda & Kas Perpustakaan')

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
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(46, 125, 50, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
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
                                <span style="font-size: 0.8rem; color: var(--text-muted);"><i class="bi bi-check-all"></i> Selesai</span>
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
            <div class="modal-tzuchi-body">
                <div style="margin-bottom: 1.25rem; text-align: center; background: #F8FAFC; padding: 1rem; border-radius: var(--radius-md); border: 1px dashed var(--border-color);">
                    <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Total Tagihan</div>
                    <strong id="paymentAmountDisplay" style="color: var(--danger); font-size: 1.6rem; font-weight: 800;">Rp0</strong>
                </div>
                <div class="form-group">
                    <label class="form-label required">Metode Pembayaran</label>
                    <select name="payment_method" id="paymentMethodSelect" class="form-control-tzuchi" required onchange="togglePaymentFields()">
                        <option value="cash">Tunai (Cash)</option>
                        <option value="qris">QRIS (E-Wallet / M-Banking)</option>
                        <option value="bca">Transfer Bank BCA</option>
                        <option value="mandiri">Transfer Bank Mandiri</option>
                    </select>
                </div>

                <div id="transferFields" style="display: none; padding: 1rem; background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); margin-bottom: 1.25rem;">
                    <div class="form-group" style="margin-bottom: 0.75rem;">
                        <label class="form-label required">Nomor Referensi / Nomor Rekening</label>
                        <input type="text" name="reference_number" id="referenceInput" class="form-control-tzuchi" placeholder="Misal: TRF-12345 / Nomor VA">
                    </div>
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
function openPaymentModal(id, type, amount) {
    const form = document.getElementById('paymentForm');
    form.action = "{{ url('admin/denda') }}/" + id + "/pay";
    document.getElementById('paymentType').value = type;
    document.getElementById('paymentAmountDisplay').innerText = "Rp" + new Intl.NumberFormat('id-ID').format(amount);
    
    document.getElementById('paymentModal').style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function togglePaymentFields() {
    const method = document.getElementById('paymentMethodSelect').value;
    const transferFields = document.getElementById('transferFields');
    const refInput = document.getElementById('referenceInput');
    const proofInput = document.getElementById('proofInput');
    
    if (method !== 'cash') {
        transferFields.style.display = 'block';
        refInput.setAttribute('required', 'required');
        proofInput.setAttribute('required', 'required');
    } else {
        transferFields.style.display = 'none';
        refInput.removeAttribute('required');
        proofInput.removeAttribute('required');
    }
}
</script>
@endpush
