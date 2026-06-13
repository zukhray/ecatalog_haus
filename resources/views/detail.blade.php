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

    /* Card Utama */
    .detail-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        border-radius: 30px;
        box-shadow: 0 20px 50px rgba(255, 105, 180, 0.15);
        border: 2px solid #fff;
        overflow: hidden;
    }

    /* Area Foto */
    .img-wrapper {
        background: #fff0f5;
        min-height: 100%;
        padding: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 2px dashed #ffe4e1;
    }
    .detail-img {
        transition: transform 0.5s ease;
        border-radius: 20px;
        object-fit: cover;
        width: 100%;
        max-height: 450px;
        box-shadow: 0 15px 35px rgba(255, 105, 180, 0.2);
    }
    .img-wrapper:hover .detail-img {
        transform: translateY(-10px) scale(1.03);
    }

    /* Area Kotak Deskripsi */
    .desc-box {
        background: #fff0f5;
        border-left: 5px solid #ff69b4;
        border-radius: 0 16px 16px 0;
        padding: 1.5rem;
    }

    .price-tag {
        font-size: 3rem;
        font-weight: 900;
        letter-spacing: -1.5px;
        color: #d81b60;
    }

    .btn-cart {
        background: linear-gradient(135deg, #ff1493 0%, #d81b60 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-cart:hover:not(.disabled) {
        transform: translateY(-4px);
        box-shadow: 0 15px 25px rgba(216, 27, 96, 0.3);
        color: white;
    }
</style>

<div class="korean-watermark">
    <span>하우스!</span>
    <span style="font-size: 8vw;">진짜 맛있는</span>
</div>

<div class="content-wrapper pb-5">
    
    <div class="mb-4">
        <a href="/" class="btn btn-light rounded-pill px-4 shadow-sm text-dark fw-bold border-0" style="background: rgba(255,255,255,0.8);">
            <i class="bi bi-arrow-left me-2" style="color: #d81b60;"></i> Kembali ke Katalog
        </a>
    </div>

    @php
        $cart = session('cart', []);
        $qtyDiKeranjang = isset($cart[$produk->id_produk]) ? $cart[$produk->id_produk]['qty'] : 0;
        $sisaStokReal = $produk->stok - $qtyDiKeranjang;
    @endphp

    <div class="card detail-card border-0">
        <div class="row g-0">
            
            <div class="col-md-5 img-wrapper position-relative">
                @if($produk->diskon > 0)
                    <span class="badge position-absolute top-0 start-0 m-4 px-4 py-2 fs-6 rounded-pill shadow-sm text-white" style="background: linear-gradient(45deg, #ff1493, #ff69b4);">
                        <i class="bi bi-tags-fill me-1"></i> Lagi Promo!
                    </span>
                @endif
                <img src="{{ $produk->foto ? asset('img/'.$produk->foto) : 'https://via.placeholder.com/500x450' }}" class="detail-img">
            </div>
            
            <div class="col-md-7">
                <div class="card-body p-4 p-lg-5 d-flex flex-column h-100">
                    
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="fw-bolder mb-2" style="letter-spacing: -0.5px; color: #2c3e50;">{{ $produk->nama_produk }}</h1>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge px-3 py-2 rounded-pill fw-bold" style="background: #ffe4e1; color: #d81b60;">
                                    <i class="bi bi-cup-straw me-1"></i> {{ $produk->kategori }}
                                </span>
                                @if($produk->bestseller)
                                    <span class="badge px-3 py-2 rounded-pill fw-bold shadow-sm" style="background: linear-gradient(45deg, #FFD700, #FFA500); color: #000;">
                                        <i class="bi bi-star-fill me-1"></i> Best Seller
                                    </span>
                                @endif
                                <span class="badge {{ $sisaStokReal > 0 ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-box-seam me-1"></i> Sisa Stok: {{ $sisaStokReal }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($produk->deskripsi)
                    <div class="desc-box my-4 shadow-sm">
                        <h6 class="fw-bolder mb-2" style="color: #d81b60;"><i class="bi bi-info-circle-fill me-1"></i> Deskripsi Racikan:</h6>
                        <p class="mb-0" style="line-height: 1.7; color: #4a5568;">{{ $produk->deskripsi }}</p>
                    </div>
                    @endif

                    <div class="mt-auto pt-4 border-top" style="border-color: #ffe4e1 !important;">
                        <p class="fw-bold small text-uppercase tracking-wide mb-1" style="color: #ffb6c1;">Investasi Kebahagiaan</p>
                        
                        @if($produk->diskon > 0)
                            <div class="d-flex align-items-end gap-3 mb-4">
                                <h1 class="price-tag mb-0">Rp {{ number_format($produk->harga - $produk->diskon, 0, ',', '.') }}</h1>
                                <div class="pb-1">
                                    <del class="d-block" style="color: #a0aec0; font-size: 1.1rem;">Rp {{ number_format($produk->harga, 0, ',', '.') }}</del>
                                    <span class="badge rounded-pill px-2 py-1 mt-1" style="background: #fff0f5; color: #ff1493; border: 1px solid #ffb6c1;">
                                        Hemat Rp {{ number_format($produk->diskon, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <h1 class="price-tag mb-4">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h1>
                        @endif

                        <div class="d-flex flex-wrap gap-3 mt-2">
                            
                            @if($sisaStokReal > 0)
                                <a href="/keranjang/tambah/{{ $produk->id_produk }}" class="btn btn-cart btn-lg shadow-sm px-5 fw-bold flex-grow-1 rounded-pill d-flex justify-content-center align-items-center">
                                    <i class="bi bi-cart-plus-fill fs-5 me-2"></i> Tambah ke Keranjang
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary btn-lg shadow-sm px-5 fw-bold flex-grow-1 rounded-pill disabled d-flex justify-content-center align-items-center" style="opacity: 0.7;">
                                    <i class="bi bi-x-circle-fill fs-5 me-2"></i> Waduh, Stok Habis!
                                </button>
                            @endif
                            
                            @if(session('role') == 'admin')
                                <a href="/edit/{{ $produk->id_produk }}" class="btn btn-warning btn-lg shadow-sm rounded-pill px-4 fw-bold d-flex justify-content-center align-items-center text-dark" title="Edit Produk">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="/hapus/{{ $produk->id_produk }}" onclick="return confirm('Yakin mau menghapus racikan ini secara permanen?')" class="btn btn-danger btn-lg shadow-sm rounded-pill px-4 fw-bold d-flex justify-content-center align-items-center" title="Hapus Produk">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection