@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header_title', 'Ringkasan Eksekutif Sistem')

@section('content')
<!-- Soft 3D Statistics Cards Grid -->
<div class="stat-grid">
    <div class="stat-card" style="cursor: pointer;" onclick="window.location.href='{{ route('siswa.index') }}'" title="Klik untuk lihat data siswa & guru">
        <div class="stat-content">
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-success" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-graph-up-arrow"></i> +12%</span>
                <span>{{ $totalStudents }} Siswa • {{ $totalTeachers }} Guru</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-green">
            <i class="bi bi-people-fill"></i>
        </div>
    </div>

    <div class="stat-card" style="cursor: pointer;" onclick="window.location.href='{{ route('buku.index') }}'" title="Klik untuk kelola katalog buku">
        <div class="stat-content">
            <div class="stat-label">Total Buku & Eksemplar</div>
            <div class="stat-value">{{ $totalBooks }} <span style="font-size: 0.9rem; font-weight: 600; color: var(--text-muted);">Judul</span></div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-success" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-journal-check"></i> {{ $availableCopies ?? $totalCopies }} / {{ $totalCopies }}</span>
                <span>Eksemplar</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-blue">
            <i class="bi bi-journal-bookmark-fill"></i>
        </div>
    </div>

    <div class="stat-card" style="cursor: pointer;" onclick="window.location.href='{{ route('peminjaman.index') }}'" title="Klik untuk lihat transaksi peminjaman">
        <div class="stat-content">
            <div class="stat-label">Peminjaman Aktif</div>
            <div class="stat-value">{{ $activeBorrowings }}</div>
            <div class="stat-meta">
                @if($overdueBorrowings > 0)
                    <span class="badge-tzuchi badge-danger" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-exclamation-circle-fill"></i> {{ $overdueBorrowings }} Terlambat</span>
                @else
                    <span class="badge-tzuchi badge-success" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-check-all"></i> Lancar</span>
                @endif
                <span style="color: var(--text-muted);">Sirkulasi</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-orange">
            <i class="bi bi-arrow-repeat" style="-webkit-text-stroke: 0.8px currentColor; font-size: 1.85rem;"></i>
        </div>
    </div>

    <div class="stat-card" style="cursor: pointer;" onclick="window.location.href='{{ route('denda.index') }}'" title="Klik untuk lihat rincian & kelola denda">
        <div class="stat-content">
            <div class="stat-label">Akumulasi Denda</div>
            <div class="stat-value" style="color: var(--danger);">
                Rp{{ number_format($totalFines, 0, ',', '.') }}
            </div>
            <div class="stat-meta">
                <span class="badge-tzuchi badge-secondary" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"><i class="bi bi-cash-stack"></i> Kas</span>
                <span>Kas Perpustakaan</span>
            </div>
        </div>
        <div class="stat-icon-wrapper stat-icon-red">
            <i class="bi bi-wallet-fill"></i>
        </div>
    </div>
</div>

<!-- Spline Area Chart (S-Curve) -->
<div class="card-tzuchi" style="margin-bottom: 2rem;">
    <div class="card-header-tzuchi">
        <div>
            <h3 style="font-size: 1.1rem; margin: 0; font-weight: 800;">Tren Sirkulasi Peminjaman (7 Hari Terakhir)</h3>
            <div style="font-size: 0.775rem; color: var(--text-muted);">Grafik jumlah peminjaman buku per hari</div>
        </div>
    </div>
    <div class="card-body" style="padding: 1rem 1.5rem 1.5rem 1.5rem;">
        <div id="borrowingChart" style="min-height: 300px;"></div>
    </div>
</div>

<!-- System Activity Log Enterprise Table -->
<div class="card-tzuchi">
    <div class="card-header-tzuchi" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="font-size: 1.1rem; margin: 0; font-weight: 800;">Aktivitas Sistem Real-Time</h3>
            <div style="font-size: 0.775rem; color: var(--text-muted);">Audit log pengguna dan transaksi perpustakaan</div>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <span class="badge-tzuchi badge-success"><i class="bi bi-shield-check"></i> Enterprise Audit</span>
            <form action="{{ route('admin.activities.clear') }}" method="POST" onsubmit="return confirmDeleteModal(event, 'Bersihkan Semua Log?', 'Apakah Anda yakin ingin menghapus SELURUH riwayat aktivitas sistem? (Tindakan ini tidak bisa dibatalkan)')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-tzuchi btn-danger-tzuchi btn-sm" style="background: var(--danger); color: white; border: none; padding: 0.4rem 0.75rem; border-radius: var(--radius-md); font-size: 0.8rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.35rem; transition: background 0.2s;">
                    <i class="bi bi-trash"></i> Bersihkan Log
                </button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-tzuchi">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Modul</th>
                    <th>Aktivitas</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentActivities as $log)
                    <tr>
                        <td style="font-size: 0.825rem; color: var(--text-muted); font-weight: 500;">
                            <i class="bi bi-clock-history"></i> {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                        </td>
                        <td>
                            <strong style="color: var(--text-main);">{{ $log->user_name ?? 'System' }}</strong>
                        </td>
                        <td><span class="badge-tzuchi badge-secondary">{{ $log->module ?? 'Sistem' }}</span></td>
                        <td>{{ $log->activity }}</td>
                        <td style="text-align: center;">
                            <span class="badge-tzuchi badge-success" style="font-size: 0.725rem; padding: 0.2rem 0.6rem;">
                                <i class="bi bi-check-circle-fill"></i> Berhasil
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">
                            <i class="bi bi-inbox" style="font-size: 2rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;"></i>
                            Belum ada aktivitas sistem yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: [{
                name: 'Peminjaman Buku',
                data: {!! json_encode($chartData) !!}
            }],
            chart: {
                height: 320,
                type: 'area',
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            colors: ['{{ \App\Models\LibrarySetting::get("theme_primary_color", "#22C55E") }}'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: {!! json_encode($chartDates) !!},
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: '#94A3B8',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                min: 0,
                labels: {
                    style: {
                        colors: '#94A3B8',
                        fontSize: '12px'
                    },
                    formatter: function(val) {
                        return Math.round(val);
                    }
                }
            },
            grid: {
                borderColor: '#F1F5F9',
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                padding: {
                    top: 15,
                    right: 15,
                    bottom: 0,
                    left: 15
                }
            },
            tooltip: {
                theme: document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light',
                y: {
                    formatter: function (val) {
                        return val + " Transaksi"
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#borrowingChart"), options);
        chart.render();

        const themeToggleBtn = document.getElementById('adminThemeToggle');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                setTimeout(() => {
                    const currentTheme = document.documentElement.getAttribute('data-theme');
                    chart.updateOptions({
                        tooltip: {
                            theme: currentTheme === 'dark' ? 'dark' : 'light'
                        },
                        grid: {
                            borderColor: currentTheme === 'dark' ? '#334155' : '#F1F5F9'
                        }
                    });
                }, 100);
            });
        }
    });
</script>
@endpush
@endsection
