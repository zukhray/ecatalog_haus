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

    /* Card Perbandingan */
    .compare-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(255, 105, 180, 0.15);
        border: 2px solid #fff;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        min-width: 280px; /* Lebar minimum biar rapi pas dijejer */
        max-width: 350px;
    }

    .compare-img-wrapper {
        position: relative;
        padding: 1rem;
        background: #fff0f5;
        border-bottom: 2px dashed #ffe4e1;
    }

    .compare-img {
        height: 220px;
        width: 100%;
        object-fit: cover;
        border-radius: 16px;
        box-shadow: 0 10px 20px rgba(255, 105, 180, 0.15);
    }

    /* Atribut Perbandingan */
    .compare-attribute {
        padding: 0.8rem 0;
        border-bottom: 1px solid #ffe4e1;
    }
    .compare-attribute:last-child {
        border-bottom: none;
    }
    .attr-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #ffb6c1;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.2rem;
    }
    .attr-value {
        color: #2c3e50;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .btn-cart {
        background: linear-gradient(135deg, #ff1493 0%, #d81b60 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-cart:hover:not(.disabled) {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(216, 27, 96, 0.3);
        color: white;
    }

    /* Scrollbar estetik buat area perbandingan */
    .compare-container {
        scrollbar-width: thin;
        scrollbar-color: #ffb6c1 #fff0f5;
        padding-bottom: 1.5rem;
    }
    .compare-container::-webkit-scrollbar {
        height: 10px;
    }
    .compare-container::-webkit-scrollbar-track {
        background: #fff0f5;
        border-radius: 10px;
    }
    .compare-container::-webkit-scrollbar-thumb {
        background-color: #ffb6c1;
        border-radius: 10px;
    }
</style>

<div class="korean-watermark">
    <span>하우스!</span>
    <span style="font-size: 8vw;">진짜 맛있는</span>
</div>

<div class="content-wrapper pb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bolder mb-0" style="color: #d81b60;">
            <i class="bi bi-arrow-left-right me-2"></i> Bandingkan Menu
        </h3>
        <a href="/" class="btn btn-light rounded-pill px-4 shadow-sm text-dark fw-bold border-0" style="background: rgba(255,255,255,0.8);">
            <i class="bi bi-arrow-left me-1" style="color: #d81b60;"></i> Kembali
        </a>
    </div>

    @if(isset($produk) && count($produk) > 0)
        <div class="d-flex flex-nowrap overflow-auto gap-4 compare-container">
            @foreach($produk as $p)
                
                @php
                    $cart = session('cart', []);
                    $qtyDiKeranjang = isset($cart[$p->id_produk]) ? $cart[$p->id_produk]['qty'] : 0;
                    $sisaStokReal = $p->stok - $qtyDiKeranjang;
                @endphp

                <div class="compare-card flex-shrink-0">
                    <div class="compare-img-wrapper text-center">
                        @if($p->diskon > 0)
                            <span class="badge position-absolute top-0 start-0 m-3 px-3 py-1 fs-6 rounded-pill shadow-sm text-white" style="background: linear-gradient(45deg, #ff1493, #ff69b4); z-index: 2;">
                                Promo!
                            </span>
                        @endif
                        <img src="{{ $p->foto ? asset('img/'.$p->foto) : 'https://via.placeholder.com/300x250' }}" class="compare-img">
                    </div>
                    
                    <div class="card-body p-4 d-flex flex-column">
                        <h4 class="fw-bolder text-center mb-4" style="color: #d81b60;">{{ $p->nama_produk }}</h4>
                        
                        <div class="compare-attribute">
                            <div class="attr-label"><i class="bi bi-cup-straw me-1"></i> Kategori</div>
                            <div class="attr-value">{{ $p->kategori }}</div>
                        </div>

                        <div class="compare-attribute">
                            <div class="attr-label"><i class="bi bi-tag-fill me-1"></i> Harga Normal</div>
                            <div class="attr-value">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                        </div>

                        <div class="compare-attribute">
                            <div class="attr-label"><i class="bi bi-cash-coin me-1"></i> Harga Promo / Akhir</div>
                            @if($p->diskon > 0)
                                <div class="attr-value fw-bolder" style="color: #ff1493;">Rp {{ number_format($p->harga - $p->diskon, 0, ',', '.') }}</div>
                            @else
                                <div class="attr-value fw-bolder text-dark">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                            @endif
                        </div>

                        <div class="compare-attribute">
                            <div class="attr-label"><i class="bi bi-box-seam me-1"></i> Ketersediaan Stok</div>
                            <div class="attr-value">
                                <span class="badge {{ $sisaStokReal > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-1">
                                    {{ $sisaStokReal }} Porsi Tersisa
                                </span>
                            </div>
                        </div>

                        <div class="compare-attribute mb-4 flex-grow-1">
                            <div class="attr-label"><i class="bi bi-info-circle-fill me-1"></i> Deskripsi Singkat</div>
                            <div class="attr-value" style="font-size: 0.85rem; color: #718096; line-height: 1.5;">
                                {{ $p->deskripsi ?: 'Tidak ada deskripsi khusus untuk menu ini.' }}
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top" style="border-color: #ffe4e1 !important;">
                            @if($sisaStokReal > 0)
                                <a href="/keranjang/tambah/{{ $p->id_produk }}" class="btn btn-cart w-100 rounded-pill fw-bold py-2 d-flex justify-content-center align-items-center">
                                    <i class="bi bi-cart-plus-fill me-2"></i> Pilih Menu Ini
                                </a>
                            @else
                                <button class="btn btn-secondary w-100 rounded-pill fw-bold py-2 disabled" style="opacity: 0.6;">
                                    Stok Habis
                                </button>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 rounded-4 shadow-sm text-center py-5" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);">
            <div class="card-body py-5">
                <i class="bi bi-question-circle text-muted mb-3" style="font-size: 4rem; color: #ffb6c1 !important;"></i>
                <h4 class="fw-bold text-dark">Belum Ada Menu yang Dipilih</h4>
                <p class="text-muted">Centang dulu beberapa menu di Katalog buat dibandingin di sini ya.</p>
                <a href="/" class="btn rounded-pill px-4 mt-3 fw-bold text-white shadow-sm" style="background: #ff69b4;">Ke Katalog Sekarang</a>
            </div>
        </div>
    @endif
</div>
@endsection