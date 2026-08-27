@extends('layouts.admin')

@section('title', 'Sirkulasi Pengembalian')
@section('header_title', 'Pengembalian & Perhitungan Denda')

@section('content')
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin-bottom: 0.2rem;">Buku Sedang Dipinjam</h3>
            <div style="font-size: 0.825rem; color: var(--text-muted);">Proses pengembalian buku dan hitung otomatis denda keterlambatan.</div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="button" id="btnBulkReturn" class="btn-tzuchi btn-primary-tzuchi btn-sm" style="display: none;" onclick="submitBulkReturn()">
                <i class="bi bi-box-arrow-in-left"></i> Kembalikan Terpilih (<span id="selectedCount">0</span>)
            </button>
            <form id="bulkReturnForm" action="{{ route('pengembalian.bulkReturn') }}" method="POST" style="display: none;">
                @csrf
                <div id="bulkReturnInputs"></div>
            </form>
        </div>
    </div>

    <!-- Search Form -->
    <div style="margin-bottom: 1.25rem;">
        <form action="{{ url('admin/pengembalian') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 420px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control-tzuchi" placeholder="Cari nama peminjam, judul buku, atau kode...">
            <button type="submit" class="btn-tzuchi btn-secondary-tzuchi"><i class="bi bi-search"></i> Cari</button>
            @if(!empty($search))
                <a href="{{ url('admin/pengembalian') }}" class="btn-tzuchi btn-secondary-tzuchi btn-sm" style="color: var(--danger);"><i class="bi bi-x-circle"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th width="5%" style="text-align: center;"><input type="checkbox" id="selectAll" style="cursor: pointer;"></th>
                    <th width="5%">No</th>
                    <th width="20%">Peminjam</th>
                    <th width="25%">Buku & Kode Eksemplar</th>
                    <th width="15%">Tgl Pinjam</th>
                    <th width="15%">Jatuh Tempo</th>
                    <th width="15%" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $key => $b)
                    @php
                        $dueDateObj = \Carbon\Carbon::parse($b->due_date);
                        $isOverdue = \Carbon\Carbon::now()->greaterThan($dueDateObj);
                        $overdueDays = $isOverdue ? \Carbon\Carbon::now()->diffInDays($dueDateObj) : 0;
                    @endphp
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" class="rowCheckbox" value="{{ $b->id }}" style="cursor: pointer;">
                        </td>
                        <td>{{ $borrowings->firstItem() + $key }}</td>
                        <td><strong>{{ $b->user_name }}</strong></td>
                        <td>
                            <strong>{{ $b->book_title }}</strong>
                            <div style="font-size: 0.775rem; color: var(--text-muted);">Kode: <code>{{ $b->copy_code }}</code></div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}</td>
                        <td>
                            <strong style="{{ $isOverdue ? 'color: var(--danger);' : '' }}">
                                {{ $dueDateObj->format('d/m/Y') }}
                            </strong>
                            @if($isOverdue)
                                <div style="font-size: 0.75rem; color: var(--danger); font-weight: 600;">
                                    Terlambat {{ $overdueDays }} Hari (Est. Denda Rp{{ number_format($overdueDays * 1000, 0, ',', '.') }})
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ url('admin/pengembalian/'.$b->id) }}" method="POST" onsubmit="return confirmDeleteModal(event, 'Proses Pengembalian Buku?', 'Apakah Anda yakin ingin memproses pengembalian buku ini?')">
                                @csrf
                                <button type="submit" class="btn-tzuchi btn-primary-tzuchi btn-sm">
                                    <i class="bi bi-box-arrow-in-left"></i> Proses Kembalikan
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Tidak ada transaksi buku dipinjam saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.825rem; color: var(--text-muted);">
            Menampilkan {{ $borrowings->firstItem() ?? 0 }} - {{ $borrowings->lastItem() ?? 0 }} dari {{ $borrowings->total() }} data
        </div>
        <div>
            {{ $borrowings->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
    const btnBulkReturn = document.getElementById('btnBulkReturn');
    const selectedCountSpan = document.getElementById('selectedCount');

    function updateBulkReturnBtn() {
        const checkedCount = document.querySelectorAll('.rowCheckbox:checked').length;
        if (checkedCount > 0) {
            btnBulkReturn.style.display = 'inline-flex';
            selectedCountSpan.textContent = checkedCount;
        } else {
            btnBulkReturn.style.display = 'none';
        }
        
        if (selectAll) {
            selectAll.checked = (checkedCount === rowCheckboxes.length && rowCheckboxes.length > 0);
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkReturnBtn();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkReturnBtn);
    });
});

function submitBulkReturn() {
    const checkedBoxes = document.querySelectorAll('.rowCheckbox:checked');
    if (checkedBoxes.length === 0) return;

    Swal.fire({
        title: 'Proses Pengembalian Masal?',
        text: `Anda akan mengembalikan ${checkedBoxes.length} buku. Denda akan otomatis dihitung jika ada yang terlambat.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'var(--primary)',
        cancelButtonColor: '#94A3B8',
        confirmButtonText: 'Ya, Kembalikan Semua!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('bulkReturnForm');
            const inputsDiv = document.getElementById('bulkReturnInputs');
            inputsDiv.innerHTML = '';
            
            checkedBoxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                inputsDiv.appendChild(input);
            });
            
            form.submit();
        }
    });
}
</script>
@endsection
