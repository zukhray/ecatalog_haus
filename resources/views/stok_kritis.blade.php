@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%) !important;
        min-height: 100vh;
    }

    .stok-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s;
        overflow: hidden;
    }
    .stok-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }

    .stok-img {
        height: 160px;
        width: 100%;
        object-fit: cover;
    }

    .stok-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.8rem;
        color: white;
    }
    .stok-habis { background: #dc3545; }
    .stok-kritis { background: #fd7e14; }
    .stok-aman { background: #198754; }

    .header-section {
        background: linear-gradient(135deg, #d81b60, #ff69b4);
        border-radius: 20px;
        padding: 25px 30px;
        color: white;
        margin-bottom: 30px;
    }
</style>

<div class="container pb-5">

    <!-- Header -->
    <div class="header-section d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan Stok</h3>
            <p class="mb-0 opacity-75">Produk dengan stok 5 atau kurang</p>
        </div>
        <a href="/" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 text-center shadow-sm" style="background: #fff0f5;">
                <h4 class="fw-bold text-danger mb-1">{{ $produkKritis->where('stok', 0)->count() }}</h4>
                <small class="text-muted">Stok Habis</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 text-center shadow-sm" style="background: #fff3e0;">
                <h4 class="fw-bold text-warning mb-1">{{ $produkKritis->where('stok', '>', 0)->where('stok', '<=', 3)->count() }}</h4>
                <small class="text-muted">Stok Kritis (≤3)</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 text-center shadow-sm" style="background: #e8f5e9;">
                <h4 class="fw-bold text-success mb-1">{{ $produkKritis->where('stok', '>', 3)->where('stok', '<=', 5)->count() }}</h4>
                <small class="text-muted">Stok Menipis (4-5)</small>
            </div>
        </div>
    </div>

    <!-- Grid Produk -->
    @if($produkKritis->count() > 0)
        <div class="row g-4">
            @foreach($produkKritis as $p)
            @php
                $stokClass = $p->stok == 0 ? 'stok-habis' : ($p->stok <= 3 ? 'stok-kritis' : 'stok-aman');
                $stokText = $p->stok == 0 ? 'Habis' : ($p->stok <= 3 ? 'Kritis' : 'Menipis');
            @endphp
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card stok-card h-100">
                    <div class="position-relative">
                        <img src="{{ $p->foto ? asset('img/'.$p->foto) : 'https://via.placeholder.com/300x200' }}" class="stok-img">
                        <span class="stok-indicator {{ $stokClass }}">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>{{ $stokText }} ({{ $p->stok }})
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-2" style="color: #2c3e50;">{{ $p->nama_produk }}</h5>
                        <p class="text-muted small mb-3">{{ $p->kategori }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold" style="color: #d81b60;">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                            <span class="badge bg-light text-dark border">Sisa: {{ $p->stok }}</span>
                        </div>

                        <a href="/edit/{{ $p->id_produk }}" class="btn btn-warning w-100 rounded-pill fw-bold">
                            <i class="bi bi-pencil-square me-1"></i> Kelola Stok
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 rounded-4 shadow-sm text-center py-5">
            <div class="card-body py-5">
                <i class="bi bi-check-circle-fill text-success mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold text-dark">Semua Stok Aman!</h4>
                <p class="text-muted">Tidak ada produk dengan stok kritis saat ini.</p>
                <a href="/" class="btn rounded-pill px-4 mt-2 fw-bold text-white" style="background: #ff69b4;">
                    Ke Katalog
                </a>
            </div>
        </div>
    @endif

</div>
@endsection