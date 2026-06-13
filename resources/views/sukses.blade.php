@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%) !important;
        position: relative;
        min-height: 100vh;
    }
    
    /* Watermark Korea */
    .korean-watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-15deg);
        font-size: 12vw;
        font-weight: 900;
        color: rgba(255, 105, 180, 0.12);
        z-index: 0;
        white-space: nowrap;
        pointer-events: none;
        user-select: none;
    }
    .korean-watermark span { display: block; text-align: center; }
    
    .content-wrapper { position: relative; z-index: 1; }

    .receipt-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(255, 105, 180, 0.15);
        border-top: 8px solid #ff69b4; /* Border atas diganti pink */
        position: relative;
    }
    
    /* Efek bolongan di pinggir struk */
    .receipt-card::before, .receipt-card::after {
        content: '';
        position: absolute;
        top: 25px;
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%);
        border-radius: 50%;
        box-shadow: inset 0 3px 5px rgba(0,0,0,0.05);
    }
    .receipt-card::before { left: -12px; }
    .receipt-card::after { right: -12px; }
    
    .dashed-line {
        border-top: 2px dashed #ffb6c1; /* Garis putus-putus pink */
        margin: 20px 0;
    }
</style>

<!-- Background Watermark -->
<div class="korean-watermark">
    <span>하우스!</span>
    <span style="font-size: 8vw;">진짜 맛있는</span>
</div>

<div class="content-wrapper row justify-content-center mt-4 mb-5">
    <div class="col-md-6 col-lg-5">
        
        <!-- Header Sukses -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px; background: linear-gradient(135deg, #ff1493 0%, #d81b60 100%);">
                 <i class="bi bi-check-lg" style="font-size: 3.5rem;"></i>
            </div>
            <h2 class="fw-bolder mb-1" style="color: #d81b60;">Gomawo! Pesanan Sukses!</h2>
            <p class="text-muted">Terima kasih, <strong style="color: #d81b60;">{{ $ref->nama_customer }}</strong></p>
        </div>

        <!-- Struk Digital -->
        <div class="card receipt-card p-4 p-md-5 border-0">
            <div class="text-center mb-2">
                <h5 class="fw-bold mb-1" style="color: #d81b60;"><i class="bi bi-shop me-1"></i> E-Catalog Haus!</h5>
                <p class="small mb-0" style="color: #ff69b4;">Order ID: #INV-{{ str_pad($id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-muted small">{{ \Carbon\Carbon::parse($ref->created_at)->format('d M Y, H:i') }}</p>
            </div>

            <div class="dashed-line"></div>

            <!-- List Barang -->
            <table class="table table-borderless table-sm mb-0">
                @foreach($items as $item)
                <tr>
                    <td class="ps-0 py-2">
                        <span class="fw-bold text-dark d-block">{{ $item->nama_produk }}</span>
                        <small class="text-muted">{{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</small>
                    </td>
                    <td class="text-end pe-0 align-middle fw-bold" style="color: #2c3e50;">
                        Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </table>

            <div class="dashed-line"></div>

            <!-- Ringkasan Biaya -->
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Subtotal Normal</span>
                <span class="text-dark fw-bold small">Rp {{ number_format($subtotalAsli, 0, ',', '.') }}</span>
            </div>

            @if($totalDiskon > 0)
            <div class="d-flex justify-content-between mb-2">
                <span class="small fw-semibold" style="color: #ff1493;"><i class="bi bi-tags-fill me-1"></i> Potongan Promo</span>
                <span class="fw-bolder small" style="color: #ff1493;">- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</span>
            </div>
            @endif

            <div class="dashed-line"></div>

            <!-- Total Bayar -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bolder" style="color: #a0aec0; letter-spacing: 0.5px;">TOTAL BAYAR</span>
                <h2 class="fw-bolder mb-0" style="color: #d81b60; letter-spacing: -1px;">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h2>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-grid gap-3 mt-5">
                <a href="/pesanan/cetak/{{ $id }}" target="_blank" class="btn rounded-pill fw-bold py-3 shadow-sm d-flex justify-content-center align-items-center text-white" style="background: #2d3748;" id="btnCetakPDF" onclick="loadingPDF(this)">
                    <i class="bi bi-printer-fill me-2 fs-5"></i> Cetak Struk PDF
                </a>
                <a href="/" class="btn rounded-pill fw-bold py-3 d-flex justify-content-center align-items-center" style="border: 2px solid #ffb6c1; color: #d81b60; background: #fff0f5;">
                    <i class="bi bi-arrow-left-circle-fill me-2 fs-5"></i> Kembali ke Katalog
                </a>
            </div>
        </div>
        
    </div>
</div>

<script>
    function loadingPDF(btnElement) {
        let originalText = btnElement.innerHTML;
        btnElement.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses PDF...';
        btnElement.classList.add('disabled'); 
        setTimeout(() => {
            btnElement.innerHTML = originalText;
            btnElement.classList.remove('disabled');
        }, 3000);
    }
</script>
@endsection