@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header_title', 'Dashboard Ringkasan Sistem')

@section('content')
<!-- Statistics Cards Grid -->
<div class="stat-grid">
    <div class="stat-card">
        <div>
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                {{ $totalStudents }} Siswa • {{ $totalTeachers }} Guru • {{ $totalLibrarians }} Petugas
            </div>
        </div>
        <div style="width: 44px; height: 44px; background: #E8F5E9; color: var(--primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-people"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">Total Buku</div>
            <div class="stat-value">{{ $totalBooks }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                {{ $totalCopies }} Eksemplar Fisik
            </div>
        </div>
        <div style="width: 44px; height: 44px; background: #E3F2FD; color: #1976D2; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-book"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">Peminjaman Aktif</div>
            <div class="stat-value">{{ $activeBorrowings }}</div>
            <div style="font-size: 0.75rem; color: var(--danger); margin-top: 0.25rem;">
                {{ $overdueBorrowings }} Terlambat
            </div>
        </div>
        <div style="width: 44px; height: 44px; background: #FFF3E0; color: #F57C00; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-arrow-left-right"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">Total Denda</div>
            <div class="stat-value" style="font-size: 1.35rem; color: var(--danger);">
                Rp{{ number_format($totalFines, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                Akumulasi denda
            </div>
        </div>
        <div style="width: 44px; height: 44px; background: #FFEBEE; color: var(--danger); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-cash-stack"></i>
        </div>
    </div>
</div>

<!-- System Activity Log Table -->
<div class="card-tzuchi">
    <div class="card-header-tzuchi">
        <h3 style="font-size: 1.05rem; margin: 0;">Aktivitas Sistem Terbaru</h3>
        <span class="badge-tzuchi badge-secondary">Real-time Log</span>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Modul</th>
                    <th>Aktivitas</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentActivities as $log)
                    <tr>
                        <td style="font-size: 0.8rem; color: var(--text-muted);">
                            {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                        </td>
                        <td><strong>{{ $log->user_name ?? 'System' }}</strong></td>
                        <td><span class="badge-tzuchi badge-secondary">{{ $log->module ?? 'Sistem' }}</span></td>
                        <td>{{ $log->activity }}</td>
                        <td style="font-size: 0.8rem; color: var(--text-muted);">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Belum ada aktivitas yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
