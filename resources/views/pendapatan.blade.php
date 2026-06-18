@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%) !important;
        min-height: 100vh;
    }

    .stats-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(255, 105, 180, 0.15);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(255, 105, 180, 0.25);
    }
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #ff1493, #ff69b4);
    }
    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    .bg-pink-soft { background: #fff0f5; color: #d81b60; }
    .bg-purple-soft { background: #f3e5f5; color: #7b1fa2; }
    .bg-orange-soft { background: #fff3e0; color: #e65100; }
    .bg-green-soft { background: #e8f5e9; color: #2e7d32; }

    .chart-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        background: white;
    }

    .table-pendapatan {
        border-radius: 16px;
        overflow: hidden;
    }
    .table-pendapatan thead {
        background: linear-gradient(135deg, #d81b60, #ff69b4);
        color: white;
    }
    .table-pendapatan tbody tr:hover {
        background-color: #fff0f5;
    }
    
    .filter-card {
        border-radius: 16px;
        border: none;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .top-produk-item {
        padding: 12px 16px;
        border-radius: 12px;
        background: #fafafa;
        margin-bottom: 8px;
        transition: all 0.2s;
    }
    .top-produk-item:hover {
        background: #fff0f5;
        transform: translateX(5px);
    }
    .rank-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }
    .rank-1 { background: linear-gradient(135deg, #ffd700, #ffed4a); color: #333; }
    .rank-2 { background: linear-gradient(135deg, #c0c0c0, #e0e0e0); color: #333; }
    .rank-3 { background: linear-gradient(135deg, #cd7f32, #daa520); color: white; }
    .rank-other { background: #f0f0f0; color: #666; }

    .trend-up { color: #2e7d32; }
    .trend-down { color: #c62828; }
</style>

<div class="container-fluid pb-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #d81b60;">
                <i class="bi bi-graph-up-arrow me-2"></i>Laporan Pendapatan
            </h3>
            <p class="text-muted mb-0">Pantau performa penjualan harian & mingguan</p>
        </div>
        <a href="/" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-arrow-left me-1" style="color: #d81b60;"></i> Kembali
        </a>
    </div>

    <!-- Filter Tanggal -->
    <div class="card filter-card mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">Dari Tanggal</label>
                    <input type="date" name="tanggal_awal" value="{{ $tanggalAwal->format('Y-m-d') }}" 
                           class="form-control rounded-pill border-0 shadow-sm" style="background: #fff0f5;">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir->format('Y-m-d') }}" 
                           class="form-control rounded-pill border-0 shadow-sm" style="background: #fff0f5;">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn w-100 rounded-pill fw-bold text-white shadow-sm" style="background: #d81b60;">
                        <i class="bi bi-filter me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Hari Ini -->
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1 fw-bold small">PENDAPATAN HARI INI</p>
                        <h3 class="fw-bolder mb-0" style="color: #d81b60;">
                            Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="stats-icon bg-pink-soft">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-muted small">
                    <i class="bi bi-receipt me-1"></i>
                    <span>{{ $transaksiHariIni }} transaksi</span>
                    <span class="mx-2">•</span>
                    <i class="bi bi-box-seam me-1"></i>
                    <span>{{ $produkTerjualHariIni }} produk</span>
                </div>
            </div>
        </div>

        <!-- Minggu Ini -->
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1 fw-bold small">PENDAPATAN MINGGU INI</p>
                        <h3 class="fw-bolder mb-0" style="color: #7b1fa2;">
                            Rp {{ number_format($pendapatanMingguIni, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="stats-icon bg-purple-soft">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-muted small">
                    <i class="bi bi-receipt me-1"></i>
                    <span>{{ $transaksiMingguIni }} transaksi</span>
                </div>
            </div>
        </div>

        <!-- Bulan Ini -->
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1 fw-bold small">PENDAPATAN BULAN INI</p>
                        <h3 class="fw-bolder mb-0" style="color: #e65100;">
                            Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="stats-icon bg-orange-soft">
                        <i class="bi bi-calendar-month"></i>
                    </div>
                </div>
                <div class="text-muted small">
                    {{ Carbon\Carbon::now()->format('F Y') }}
                </div>
            </div>
        </div>

        <!-- Total Range -->
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1 fw-bold small">TOTAL PERIODE FILTER</p>
                        <h3 class="fw-bolder mb-0" style="color: #2e7d32;">
                            Rp {{ number_format($totalRange, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="stats-icon bg-green-soft">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-muted small">
                    <i class="bi bi-receipt me-1"></i>
                    <span>{{ $totalTransaksiRange }} transaksi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card chart-card p-4">
                <h5 class="fw-bold mb-4" style="color: #d81b60;">
                    <i class="bi bi-bar-chart-line me-2"></i>Grafik Pendapatan Harian
                </h5>
                <canvas id="chartPendapatan" height="100"></canvas>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card chart-card p-4 h-100">
                <h5 class="fw-bold mb-4" style="color: #d81b60;">
                    <i class="bi bi-trophy me-2"></i>Top 5 Produk Terlaris
                </h5>
                
                @forelse($topProduk as $index => $produk)
                <div class="top-produk-item d-flex align-items-center">
                    <div class="rank-badge rank-{{ $index < 3 ? $index + 1 : 'other' }} me-3">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark">{{ $produk->nama_produk }}</div>
                        <div class="small text-muted">{{ $produk->total_terjual }} pcs terjual</div>
                    </div>
                    <div class="fw-bolder" style="color: #d81b60;">
                        Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-1 mb-2 d-block"></i>
                    Belum ada data penjualan
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tabel Detail Harian -->
    <div class="card chart-card p-4">
        <h5 class="fw-bold mb-4" style="color: #d81b60;">
            <i class="bi bi-table me-2"></i>Detail Pendapatan Per Hari
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-pendapatan">
                <thead>
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="py-3 text-center">Hari</th>
                        <th class="py-3 text-center">Jumlah Transaksi</th>
                        <th class="py-3 text-center">Produk Terjual</th>
                        <th class="py-3 text-end px-4">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataHarian as $data)
                    @php
                        $tgl = Carbon\Carbon::parse($data->tanggal);
                        $namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$tgl->dayOfWeek];
                        $isWeekend = in_array($tgl->dayOfWeek, [0, 6]);
                    @endphp
                    <tr>
                        <td class="px-4 fw-bold">{{ $tgl->format('d M Y') }}</td>
                        <td class="text-center">
                            <span class="badge {{ $isWeekend ? 'bg-danger' : 'bg-secondary' }} rounded-pill px-3">
                                {{ $namaHari }}
                            </span>
                        </td>
                        <td class="text-center fw-bold">{{ $data->jumlah_transaksi }}</td>
                        <td class="text-center">{{ $data->jumlah_produk }} pcs</td>
                        <td class="text-end px-4 fw-bolder" style="color: #d81b60;">
                            Rp {{ number_format($data->total_pendapatan, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <span class="fw-bold">Belum ada transaksi di periode ini</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot style="background: #fff0f5;">
                    <tr>
                        <td colspan="2" class="px-4 fw-bold">TOTAL</td>
                        <td class="text-center fw-bold">{{ $totalTransaksiRange }}</td>
                        <td class="text-center fw-bold">{{ $dataHarian->sum('jumlah_produk') }} pcs</td>
                        <td class="text-end px-4 fw-bolder" style="color: #d81b60;">
                            Rp {{ number_format($totalRange, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartPendapatan').getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(255, 105, 180, 0.3)');
    gradient.addColorStop(1, 'rgba(255, 105, 180, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($dataPendapatan) !!},
                borderColor: '#d81b60',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ff69b4',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            }, {
                label: 'Jumlah Transaksi',
                data: {!! json_encode($dataTransaksi) !!},
                borderColor: '#ff9800',
                backgroundColor: 'transparent',
                borderWidth: 2,
                borderDash: [5, 5],
                pointBackgroundColor: '#ff9800',
                pointRadius: 4,
                yAxisID: 'y1',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, weight: 'bold' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(216, 27, 96, 0.9)',
                    titleFont: { size: 13 },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 12,
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.label === 'Pendapatan (Rp)') {
                                return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: 'bold' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000) + 'k';
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { display: false },
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection