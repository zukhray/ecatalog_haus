@extends('layouts.app')

@section('content')
<style>
    .detail-row { 
        background-color: #fcfcfc; 
    }
    .item-card { 
        border-radius: 12px; 
        border: 1px solid #ffe4e1; 
        background: #fff; 
        transition: 0.2s; 
    }
    .item-card:hover { 
        border-color: #ffb6c1; 
        box-shadow: 0 4px 10px rgba(216, 27, 96, 0.1); 
    }
    .variant-text { 
        font-size: 0.75rem; 
        color: #d81b60; 
        background: linear-gradient(135deg, #fff0f5, #ffe4e1); 
        padding: 3px 10px; 
        border-radius: 10px; 
        display: inline-block; 
        border: 1px solid #ffb6c1;
        font-weight: 600;
    }
    
    /* Toggle Switch Status */
    .status-toggle {
        position: relative;
        display: inline-flex;
        align-items: center;
        background: #fff0f5;
        border-radius: 50px;
        padding: 3px;
        border: 1px solid #ffb6c1;
        cursor: pointer;
        transition: all 0.3s;
        user-select: none;
    }
    .status-toggle:hover {
        box-shadow: 0 2px 8px rgba(216, 27, 96, 0.15);
    }
    .status-toggle .toggle-option {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        transition: all 0.3s;
        color: #a0aec0;
    }
    .status-toggle .toggle-option.active {
        background: linear-gradient(135deg, #ff1493, #d81b60);
        color: white;
        box-shadow: 0 2px 8px rgba(216, 27, 96, 0.3);
    }
    .status-toggle.selesai {
        background: #d1fae5;
        border-color: #059669;
    }
    .status-toggle.selesai .toggle-option.active {
        background: linear-gradient(135deg, #059669, #047857);
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
    }
    
    /* Action buttons */
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    .action-btn.view {
        background: #fff0f5;
        color: #d81b60;
        border: 1px solid #ffb6c1;
    }
    .action-btn.view:hover {
        background: #d81b60;
        color: white;
    }
    .action-btn.print {
        background: #e2e8f0;
        color: #475569;
        border: 1px solid #cbd5e0;
    }
    .action-btn.print:hover {
        background: #475569;
        color: white;
    }
    
    /* Filter chips */
    .filter-chip {
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: white;
        color: #718096;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-chip:hover {
        border-color: #ffb6c1;
        color: #d81b60;
    }
    .filter-chip.active {
        background: linear-gradient(135deg, #ff1493, #d81b60);
        color: white;
        border-color: #d81b60;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-receipt text-secondary"></i> Riwayat Penjualan</h4>
        <p class="text-muted small mb-0">Kelola dan pantau semua transaksi</p>
    </div>
    <a href="/" class="btn btn-outline-secondary shadow-sm rounded-pill px-4">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<!-- Filter & Search -->
<div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold text-muted mb-1">Cari Pesanan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill" style="color: #d81b60;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="cari" value="{{ request('cari') }}" 
                               class="form-control border-start-0 shadow-none rounded-end-pill" 
                               placeholder="Nama pelanggan / ID transaksi...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                           class="form-control shadow-sm rounded-pill">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1">Status</label>
                    <select name="status_filter" class="form-select shadow-sm rounded-pill" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Status</option>
                        <option value="diproses" {{ request('status_filter') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status_filter') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 shadow-sm rounded-pill fw-bold">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 16px; background: linear-gradient(135deg, #fff0f5, #ffe4e1);">
            <div class="card-body d-flex align-items-center p-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: #d81b60;">
                    <i class="bi bi-receipt text-white fs-4"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Total Transaksi</p>
                    <h5 class="fw-bold mb-0" style="color: #d81b60;">{{ $pesanan_grouped->count() }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 16px; background: linear-gradient(135deg, #fef3c7, #fde68a);">
            <div class="card-body d-flex align-items-center p-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: #d97706;">
                    <i class="bi bi-hourglass-split text-white fs-4"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Diproses</p>
                    <h5 class="fw-bold mb-0" style="color: #d97706;">
                        {{ $pesanan_grouped->filter(function($items) { return $items->first()->status == 'diproses'; })->count() }}
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 16px; background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
            <div class="card-body d-flex align-items-center p-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: #059669;">
                    <i class="bi bi-check-circle-fill text-white fs-4"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Selesai</p>
                    <h5 class="fw-bold mb-0" style="color: #059669;">
                        {{ $pesanan_grouped->filter(function($items) { return $items->first()->status == 'selesai'; })->count() }}
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">No. Invoice</th>
                        <th class="py-3">Pelanggan</th>
                        <th class="py-3 text-center">Item</th>
                        <th class="py-3">Total</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($pesanan_grouped as $key => $items)
                        @php
                            $first = $items->first();
                            $totalQty = $items->sum('jumlah');
                            $totalHarga = $items->sum('total');
                            $collapseId = 'detail-struk-' . $first->id;
                            $isSelesai = $first->status == 'selesai';
                        @endphp
                        
                        <tr style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                            <td class="px-4">
                                <span class="fw-bold text-primary">#INV-{{ str_pad($first->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <div class="text-muted small">{{ \Carbon\Carbon::parse($first->created_at)->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $first->nama_customer }}</div>
                                <div class="text-muted small">
                                    <i class="bi bi-geo-alt"></i> {{ $first->tipe_pesanan ?? 'Dine-in' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border" style="font-size: 0.8rem;">{{ $totalQty }} item</span>
                            </td>
                            <td>
                                <span class="fw-bold text-success">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </td>
                            
                            <td class="text-center">
                                <div class="status-toggle {{ $isSelesai ? 'selesai' : '' }}" 
                                     onclick="event.stopPropagation(); toggleStatus({{ $first->id }}, '{{ $isSelesai ? 'diproses' : 'selesai' }}', this)">
                                    <span class="toggle-option {{ !$isSelesai ? 'active' : '' }}">
                                        <i class="bi bi-hourglass-split"></i> Proses
                                    </span>
                                    <span class="toggle-option {{ $isSelesai ? 'active' : '' }}">
                                        <i class="bi bi-check-lg"></i> Selesai
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 text-center">
                                <button class="action-btn view" onclick="event.stopPropagation();" title="Lihat Detail" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <a href="/pesanan/cetak/{{ $first->id }}" target="_blank" class="action-btn print ms-1" onclick="event.stopPropagation();" title="Cetak Invoice">
                                    <i class="bi bi-printer-fill"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Detail Collapse -->
                        <tr class="collapse detail-row" id="{{ $collapseId }}">
                            <td colspan="6" class="p-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="bi bi-receipt-cutoff me-2"></i> Detail Transaksi
                                    </h6>
                                    <div class="d-flex gap-2">
                                        <a href="/pesanan/cetak/{{ $first->id }}" target="_blank" class="btn btn-sm btn-dark rounded-pill px-3">
                                            <i class="bi bi-printer-fill me-1"></i> Cetak Invoice
                                        </a>
                                        <a href="/checkout/sukses/{{ $first->id }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Struk
                                        </a>
                                    </div>
                                </div>
                                
                                @php
                                    $mergedDetail = [];
                                    foreach($items as $item) {
                                        $key = $item->nama_produk . '|' . ($item->catatan ?? '');
                                        if (!isset($mergedDetail[$key])) {
                                            $mergedDetail[$key] = [
                                                'nama_produk' => $item->nama_produk,
                                                'catatan' => $item->catatan,
                                                'jumlah' => 0,
                                                'harga' => $item->harga,
                                                'total' => 0
                                            ];
                                        }
                                        $mergedDetail[$key]['jumlah'] += $item->jumlah;
                                        $mergedDetail[$key]['total'] += $item->total;
                                    }
                                @endphp

                                <div class="row">
                                    @foreach($mergedDetail as $item)
                                        @php
                                            $varian = [];
                                            if (!empty($item['catatan'])) {
                                                $parts = explode(', ', $item['catatan']);
                                                foreach ($parts as $part) {
                                                    if (str_contains($part, 'Sugar')) $varian[] = "Gula: " . $part;
                                                    elseif (str_contains($part, 'Ice')) $varian[] = "Es: " . $part;
                                                    elseif (str_contains($part, 'Pedas') || $part == 'Normal' || $part == 'Sedang' || $part == 'Ekstra Pedas') $varian[] = "Pedas: " . $part;
                                                }
                                            }
                                        @endphp
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card item-card h-100">
                                                <div class="card-body py-3 px-3">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                                                <span class="fw-bold text-dark">{{ $item['nama_produk'] }}</span>
                                                                @if(count($varian) > 0)
                                                                    <span class="variant-text">
                                                                        <i class="bi bi-sliders me-1"></i>{{ implode(' | ', $varian) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <small class="text-secondary">
                                                                {{ $item['jumlah'] }} x Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                                            </small>
                                                        </div>
                                                        <span class="badge bg-success rounded-circle p-2 shadow-sm align-self-center">{{ $item['jumlah'] }}x</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @php
                                    $totalHargaAsli = 0;
                                    foreach($items as $itm) {
                                        $totalHargaAsli += ($itm->harga * $itm->jumlah);
                                    }
                                    $totalDiskon = $totalHargaAsli - $totalHarga;
                                @endphp

                                <div class="mt-3 pt-3 border-top">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3 text-muted small">
                                                <span><i class="bi bi-wallet2 me-1"></i> {{ $first->metode_pembayaran ?? 'Cash/QRIS' }}</span>
                                                <span><i class="bi bi-person me-1"></i> {{ $first->nama_customer }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <div class="mb-1">
                                                <span class="text-muted small">Subtotal:</span>
                                                <span class="fw-bold ms-2">Rp {{ number_format($totalHargaAsli, 0, ',', '.') }}</span>
                                            </div>
                                            @if($totalDiskon > 0)
                                            <div class="mb-1 text-danger">
                                                <span class="small"><i class="bi bi-tags-fill"></i> Diskon:</span>
                                                <span class="fw-bold ms-2">- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</span>
                                            </div>
                                            @endif
                                            <div>
                                                <span class="text-muted small">Total Bayar:</span>
                                                <span class="fw-bold ms-2 text-success fs-5">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <h6>Tidak ada data pesanan</h6>
                                <p class="small">Coba ubah filter atau tambah transaksi baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle Status via AJAX
    function toggleStatus(id, newStatus, element) {
        // Animasi loading
        element.style.opacity = '0.6';
        element.style.pointerEvents = 'none';
        
        fetch(`/pesanan/update/${id}/${newStatus}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                // Reload halaman untuk update tampilan
                window.location.reload();
            } else {
                alert('Gagal mengubah status!');
                element.style.opacity = '1';
                element.style.pointerEvents = 'auto';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan!');
            element.style.opacity = '1';
            element.style.pointerEvents = 'auto';
        });
    }
</script>
@endpush
@endsection