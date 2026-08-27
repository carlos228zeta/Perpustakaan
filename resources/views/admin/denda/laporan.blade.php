@extends('layouts.admin')

@section('title', 'Laporan Pembayaran Denda')

@section('content')
<div style="max-width: 1150px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.4rem; margin-bottom: 0.35rem; font-weight: 800; color: var(--text-main);">
                <i class="bi bi-receipt" style="color: var(--primary);"></i> Laporan Pembayaran Denda
            </h2>
            <div style="font-size: 0.875rem; color: var(--text-muted);">
                Rekapitulasi seluruh riwayat transaksi pembayaran denda keterlambatan buku.
            </div>
        </div>
    </div>

<div class="card-tzuchi" style="margin-bottom: 2rem;">
    <div class="card-header-tzuchi">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <h2 class="card-title-tzuchi" style="margin: 0;">Riwayat Transaksi</h2>
            <button type="button" id="btnBulkDelete" class="btn-tzuchi btn-sm" style="display: none; background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; padding: 0.25rem 0.75rem;" onclick="submitBulkDelete()">
                <i class="bi bi-trash"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
            </button>
            <form id="bulkDeleteForm" action="{{ route('denda.bulkDestroyPayment') }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
                <div id="bulkDeleteInputs"></div>
            </form>
        </div>
        <div class="card-actions">
            <form action="{{ route('denda.laporan') }}" method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama siswa atau metode (QRIS/BCA/Mandiri)..." class="form-control-tzuchi" style="width: 320px; font-size: 0.85rem;">
                <button type="submit" class="btn-tzuchi btn-primary-tzuchi btn-sm">
                    <i class="bi bi-search"></i> Cari
                </button>
            </form>
        </div>
    </div>
    
    <div class="card-body" style="padding: 0; overflow-x: auto; width: 100%;">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;"><input type="checkbox" id="selectAll" style="cursor: pointer;"></th>
                    <th style="width: 50px; text-align: center;">NO</th>
                    <th style="width: 130px;">TANGGAL BAYAR</th>
                    <th>NAMA PEMINJAM</th>
                    <th style="width: 140px;">METODE PEMBAYARAN</th>
                    <th>KETERANGAN / BUKTI</th>
                    <th style="width: 140px;">PETUGAS PENERIMA</th>
                    <th style="width: 130px; text-align: center; white-space: nowrap;">NOMINAL</th>
                    <th style="width: 90px; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $index => $payment)
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" class="rowCheckbox" value="{{ $payment->fine_id }}" style="cursor: pointer;">
                        </td>
                        <td style="text-align: center; font-weight: 600;">{{ $payments->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main); white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('H:i') }} WIB
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; white-space: nowrap;">{{ $payment->student_name }}</div>
                            <div style="font-size: 0.775rem; color: var(--text-muted); white-space: nowrap;">ID Transaksi: #{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td>
                            @if($payment->payment_method === 'qris')
                                <span class="badge-tzuchi badge-secondary"><i class="bi bi-qr-code"></i> QRIS</span>
                            @elseif($payment->payment_method === 'bca')
                                <span class="badge-tzuchi badge-secondary"><i class="bi bi-bank"></i> BCA</span>
                            @elseif($payment->payment_method === 'mandiri')
                                <span class="badge-tzuchi badge-secondary"><i class="bi bi-bank"></i> Mandiri</span>
                            @else
                                <span class="badge-tzuchi badge-secondary"><i class="bi bi-cash"></i> Tunai</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->notes)
                                <div style="font-size: 0.8rem; color: var(--text-main); margin-bottom: 0.25rem;">
                                    <strong>Catatan:</strong> {{ Str::limit($payment->notes, 50) }}
                                </div>
                            @endif
                            @if($payment->reference_number)
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                                    Ref: {{ $payment->reference_number }}
                                </div>
                            @endif
                            @if($payment->proof_path)
                                <a href="{{ asset($payment->proof_path) }}" target="_blank" class="btn-tzuchi btn-secondary-tzuchi btn-sm" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                    <i class="bi bi-image"></i> Lihat Bukti
                                </a>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 0.85rem;"><i class="bi bi-person-fill text-muted"></i> {{ $payment->admin_name ?? 'Sistem' }}</div>
                        </td>
                        <td style="text-align: center; font-weight: 800; color: #166534; white-space: nowrap;">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('denda.destroyPayment', $payment->fine_id) }}" method="POST" onsubmit="return confirmDeleteModal(event, 'Hapus Permanen?', 'Hapus riwayat pembayaran ini SELAMANYA? Data yang dihapus tidak dapat dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-tzuchi btn-sm" style="font-size: 0.7rem; background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; padding: 0.2rem 0.5rem; border-radius: 4px;">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                            <i class="bi bi-receipt-cutoff" style="font-size: 2.5rem; color: var(--primary); display: block; margin-bottom: 0.5rem;"></i>
                            Belum ada riwayat transaksi pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($payments->hasPages())
        <div class="card-footer-tzuchi" style="display: flex; justify-content: center;">
            {{ $payments->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.rowCheckbox');
    const btnBulkDelete = document.getElementById('btnBulkDelete');
    const selectedCount = document.getElementById('selectedCount');

    function updateBulkButton() {
        const checked = document.querySelectorAll('.rowCheckbox:checked').length;
        selectedCount.textContent = checked;
        btnBulkDelete.style.display = checked > 0 ? 'inline-block' : 'none';
        
        // Update selectAll indeterminate state if partially selected
        if(checked > 0 && checked < checkboxes.length) {
            selectAll.indeterminate = true;
        } else {
            selectAll.indeterminate = false;
            selectAll.checked = (checked === checkboxes.length && checkboxes.length > 0);
        }
    }

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkButton);
    });
});

function submitBulkDelete() {
    Swal.fire({
        title: 'Hapus Terpilih?',
        text: 'Anda yakin ingin menghapus semua data pembayaran yang dipilih SELAMANYA?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus Sekarang!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const checked = document.querySelectorAll('.rowCheckbox:checked');
            const inputsDiv = document.getElementById('bulkDeleteInputs');
            inputsDiv.innerHTML = '';
            
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                inputsDiv.appendChild(input);
            });
            
            document.getElementById('bulkDeleteForm').submit();
        }
    });
}
</script>
@endpush
