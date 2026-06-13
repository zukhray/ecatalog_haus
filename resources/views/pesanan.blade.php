@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-receipt text-secondary"></i> Riwayat Penjualan (POS)</h4>
    <a href="/" class="btn btn-outline-secondary shadow-sm rounded-pill px-4">
        <i class="bi bi-arrow-left"></i> Kembali ke Katalog
    </a>
</div>

<form method="GET" class="mb-4">
    <div class="row g-2">
        <div class="col-md-5">
            <input type="text" name="cari" value="{{ request('cari') }}" class="form-control shadow-sm rounded-pill px-4" placeholder="Cari Nama Pelanggan atau ID Transaksi...">
        </div>
        <div class="col-md-4">
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control shadow-sm rounded-pill px-4" title="Filter Tanggal Transaksi">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-dark w-100 shadow-sm rounded-pill fw-bold">
                <i class="bi bi-search"></i> Cari Pesanan
            </button>
        </div>
    </div>
</form>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Nama Pelanggan</th>
                        <th class="py-3 text-center">Kuantitas</th>
                        <th class="py-3">Total Penjualan</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center">Ubah Status</th>
                        <th class="px-4 py-3 text-center">Aksi (Struk)</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($pesanan_grouped as $key => $items)
                        @php
                            $first = $items->first();
                            $totalQty = $items->sum('jumlah');
                            $totalHarga = $items->sum('total');
                            $collapseId = 'detail-struk-' . $first->id; // Pake ID pesanan biar pasti beda
                        @endphp
                        
                        <tr style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" title="Klik untuk lihat detail menu">
                            <td class="px-4 fw-bold text-primary">
                                {{ $first->nama_customer }}
                                <i class="bi bi-chevron-down ms-1 text-muted small"></i>
                            </td>
                            <td class="text-center fw-bold">{{ $totalQty }} Item</td>
                            <td class="fw-bold text-success">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                            
                            <td class="text-center">
                                @if($first->status == 'diproses')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">Diproses</span>
                                @else
                                    <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">Selesai</span>
                                @endif
                            </td>
                            
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <a href="/pesanan/update/{{ $first->id }}/diproses" class="btn btn-sm {{ $first->status == 'diproses' ? 'btn-warning' : 'btn-outline-warning' }}">Proses</a>
                                    <a href="/pesanan/update/{{ $first->id }}/selesai" class="btn btn-sm {{ $first->status == 'selesai' ? 'btn-success' : 'btn-outline-success' }}">Selesai</a>
                                </div>
                            </td>

                            <td class="px-4 text-center">
                                @if($first && $first->id)
                                    <a href="/pesanan/cetak/{{ $first->id }}" class="btn btn-sm btn-dark rounded-pill shadow-sm px-3" title="Cetak Struk">
                                        <i class="bi bi-printer-fill"></i> Cetak
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-secondary rounded-pill px-3" disabled>Data Rusak</button>
                                @endif
                            </td>
                        </tr>

                        <tr class="collapse" id="{{ $collapseId }}" style="background-color: #fcfcfc;">
                            <td colspan="6" class="p-4 border-bottom shadow-inner">
                                <div class="row align-items-center mb-3 pb-2 border-bottom">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-receipt-cutoff me-2"></i> Detail Transaksi</h6>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <span class="text-muted small">Waktu: <span class="fw-bold me-3 text-dark">{{ \Carbon\Carbon::parse($first->created_at)->format('H:i | d-m-Y') }}</span></span>
                                        <span class="text-muted small">No. Transaksi:</span>
                                        <span class="badge bg-dark ms-2 rounded-pill px-3 py-2">#INV-{{ str_pad($first->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-muted small mb-2">Item yang dipesan:</p>
                                    </div>
                                    @foreach($items as $index => $item)
                                        <div class="col-md-4 mb-3">
                                            <div class="card border border-light shadow-sm rounded-3 h-100">
                                                <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="fw-bold text-dark d-block">{{ $item->nama_produk }}</span>
                                                        <span class="text-secondary small">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                                    </div>
                                                    <span class="badge bg-success rounded-circle p-2 shadow-sm">{{ $item->jumlah }}x</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @php
    // [BARU] Logika mencari selisih diskon tanpa ubah database
    $totalHargaAsli = 0;
    foreach($items as $itm) {
        $totalHargaAsli += ($itm->harga * $itm->jumlah);
    }
    $totalDiskon = $totalHargaAsli - $totalHarga;
@endphp

<div class="mt-3 border-top pt-3 text-end">
    <div class="mb-1">
        <span class="text-muted small">Subtotal Normal:</span>
        <span class="fw-bold ms-2 text-dark">Rp {{ number_format($totalHargaAsli, 0, ',', '.') }}</span>
    </div>

    @if($totalDiskon > 0)
    <div class="mb-1 text-danger">
        <span class="small"><i class="bi bi-tags-fill"></i> Potongan Promo:</span>
        <span class="fw-bold ms-2">- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="mb-2">
        <span class="text-muted small">Total Bayar:</span>
        <span class="fw-bold ms-2 text-success fs-5">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
    </div>

    <div class="mt-2">
        <span class="text-muted small">Metode Pembayaran:</span>
        <span class="fw-bold text-dark ms-2"><i class="bi bi-wallet2"></i> Cash/QRIS</span>
    </div>
</div>
                            </td>
                        </tr>
                        
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-search fs-1 d-block mb-2 text-secondary"></i>
                                Tidak ada data pesanan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection