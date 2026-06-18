@extends('layouts.app')

@section('content')

<style>
    /* Hapus atau comment body background karena sekarang di layout */
    /* body { background: ... } */
    
    /* Sisanya style katalog tetap sama */
    .korean-watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-15deg);
        font-size: 12vw;
        font-weight: 900;
        color: rgba(255, 105, 180, 0.15);
        z-index: 0;
        white-space: nowrap;
        pointer-events: none;
        user-select: none;
    }
    .korean-watermark span { display: block; text-align: center; }
    
    .content-wrapper { position: relative; z-index: 1; }
    
    .product-card {
        border-radius: 20px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(255, 105, 180, 0.1);
        border: 2px solid transparent;
        overflow: hidden;
    }
    .product-card:hover {
        border-color: #ff69b4;
        box-shadow: 0 15px 30px rgba(255, 105, 180, 0.2);
    }
    .product-img {
        height: 200px; 
        width: 100%; 
        object-fit: cover; 
        transition: transform 0.5s ease;
        border-bottom: 2px solid #fff0f5;
    }
    .product-card:hover .product-img { transform: scale(1.05); }
    
    .discount-ribbon {
        background: linear-gradient(45deg, #ff1493, #ff69b4);
        color: white;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(255, 20, 147, 0.3);
    }

    .floating-action-bar {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1050;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        padding: 12px 25px;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(255, 105, 180, 0.25);
        display: flex;
        gap: 15px;
        border: 2px solid #ffe4e1;
    }

    .category-title {
        color: #d81b60;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @media (max-width: 768px) {
        .korean-watermark { font-size: 25vw; }
        .floating-action-bar {
            width: 100%;
            bottom: 0;
            border-radius: 20px 20px 0 0;
            padding: 15px 20px 25px 20px;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.98);
            border-bottom: none;
            border-left: none;
            border-right: none;
            box-shadow: 0 -10px 20px rgba(255, 105, 180, 0.15);
        }
        .floating-action-bar .btn {
            flex: 1;
            padding: 12px 0;
            font-size: 0.9rem;
        }
        .content-wrapper { padding-bottom: 90px !important; }
        .product-img { height: 140px; }
        .product-card .card-body { padding: 1rem; }
        .product-card h5 { font-size: 1rem; }
    }
    
    .form-check-input:checked {
        background-color: #ff69b4;
        border-color: #ff69b4;
    }
</style>

<div class="korean-watermark">
    <span>하우스!</span>
    <span style="font-size: 8vw;">진짜 맛있는</span>
</div>

<div class="content-wrapper pb-5 px-4 pt-4">

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-pill shadow-sm" role="alert" style="background: #ffe5e5; color: #d81b60; border: none;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Peringatan:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Katalog - Tanpa tombol navigasi (udah di sidebar) -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #d81b60;">
            <i class="bi bi-stars me-2"></i>Katalog Produk
        </h3>
        <p class="text-muted mb-0">Pilih menu favoritmu dan tambahkan ke keranjang</p>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-4 mb-5" style="background: rgba(255,255,255,0.9);">
        <div class="card-body p-3">
            <form method="GET" class="mb-0">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill" style="color: #ff69b4;"><i class="bi bi-search"></i></span>
                            <input type="text" name="cari" value="{{ request('cari') }}" class="form-control border-start-0 rounded-end-pill px-3 bg-white" placeholder="Cari minuman kesukaanmu...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="kategori" class="form-select rounded-pill px-3 border-0" style="background: #fff0f5; color: #d81b60; font-weight: bold;">
                            <option value="">Semua Kategori</option>
                            <option value="Choco Series" {{ request('kategori')=='Choco Series' ? 'selected' : '' }}>Choco Series</option>
                            <option value="Tea Series" {{ request('kategori')=='Tea Series' ? 'selected' : '' }}>Tea Series</option>
                            <option value="Classic Series" {{ request('kategori')=='Classic Series' ? 'selected' : '' }}>Classic Series</option>
                            <option value="Pudding" {{ request('kategori')=='Pudding' ? 'selected' : '' }}>Pudding</option>
                            <option value="Hotoppa" {{ request('kategori')=='Hotoppa' ? 'selected' : '' }}>Hotoppa</option>
                            <option value="Sidedish" {{ request('kategori')=='Sidedish' ? 'selected' : '' }}>Sidedish</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn w-100 shadow-sm rounded-pill fw-bold text-white" style="background: #d81b60;">Filter Menu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sisanya sama persis seperti katalog.blade.php sebelumnya -->
    <!-- ... (bestseller, loop produk, compare wrapper, dll) ... -->
    
    @php $bestsellers = $produk->where('bestseller', 1); @endphp
    @if($bestsellers->count() > 0 && !request('kategori') && !request('cari'))
        <div class="p-4 rounded-4 mb-5 shadow-lg position-relative" style="background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%); overflow: hidden;">
            <div class="position-absolute top-0 end-0 opacity-10" style="transform: translate(10%, -30%); pointer-events: none;">
                <i class="bi bi-heart-fill" style="color: #ff69b4; font-size: 15rem;"></i>
            </div>
            <div class="mb-4 position-relative z-1">
                <h4 class="fw-bolder text-white mb-1"><i class="bi bi-fire me-2" style="color: #ff69b4;"></i>Terlaris Minggu Ini</h4>
                <p class="text-white-50 mb-0 small">Menu paling diincar oppa dan eonni!</p>
            </div>
            <div class="d-flex flex-nowrap overflow-auto gap-4 pb-2 position-relative z-1" style="scrollbar-width: none;">
                @foreach($bestsellers as $index => $p)
                <div class="card border-0 flex-shrink-0" style="width: 170px; border-radius: 16px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                    <a href="/detail/{{ $p->id_produk }}" class="text-decoration-none text-white">
                        <div class="p-2 pb-0">
                            <img src="{{ $p->foto ? asset('img/'.$p->foto) : 'https://via.placeholder.com/300x200' }}" class="w-100" style="height: 140px; object-fit: cover; border-radius: 12px;">
                        </div>
                        <div class="card-body text-center p-3">
                            <h6 class="fw-bold mb-1 small text-truncate text-white">{{ $p->nama_produk }}</h6>
                            <span class="fw-bolder small" style="color: #ffb6c1;">Rp {{ number_format($p->harga - $p->diskon, 0, ',', '.') }}</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Wrapper Compare & Produk -->
    <div id="compareWrapper">
        <!-- ... (sama seperti katalog.blade.php sebelumnya) ... -->
        
        @php 
            $semuaKategori = ['Choco Series', 'Tea Series', 'Classic Series', 'Pudding', 'Hotoppa', 'Sidedish'];
        @endphp
        
        @foreach($semuaKategori as $kat)
            @php $produkPerKat = $produk->where('kategori', $kat); @endphp
            @if($produkPerKat->count() > 0)
            <div class="mb-5 pt-3">
                <h4 class="category-title mb-4 border-bottom border-2 pb-2" style="border-color: #ffb6c1 !important; display: inline-block;">{{ $kat }}</h4>
                <div class="row g-4">
                    @foreach($produkPerKat as $p)
                    @php
                        $cart = session('cart', []);
                        $qtyDiKeranjang = isset($cart[$p->id_produk]) ? $cart[$p->id_produk]['qty'] : 0;
                        $sisaStokReal = $p->stok - $qtyDiKeranjang;
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100">
                            <!-- ... (sama persis card produk sebelumnya) ... -->
                            <a href="/detail/{{ $p->id_produk }}" class="text-decoration-none text-dark position-relative d-block img-container">
                                <img src="{{ $p->foto ? asset('img/'.$p->foto) : 'https://via.placeholder.com/300x200' }}" class="product-img">
                                @if($p->diskon > 0)
                                    <span class="badge discount-ribbon position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill">Promo!</span>
                                @endif
                                @if($sisaStokReal <= 0)
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(2px);">
                                        <span class="badge bg-dark px-4 py-2 fs-6 rounded-pill shadow">Sold Out</span>
                                    </div>
                                @endif
                            </a>
                            <div class="card-body d-flex flex-column p-3 p-md-4">
                                <h5 class="fw-bold mb-2 text-truncate" style="color: #2c3e50;">{{ $p->nama_produk }}</h5>
                                <div class="d-flex justify-content-between align-items-end mb-3">
                                    <div>
                                        @if($p->diskon > 0)
                                            <del class="text-muted small d-block" style="font-size: 12px;">Rp {{ number_format($p->harga,0,',','.') }}</del>
                                            <span class="fw-bolder fs-5" style="color: #d81b60;">Rp {{ number_format($p->harga - $p->diskon, 0, ',', '.') }}</span>
                                        @else
                                            <span class="fw-bolder fs-5 d-block mt-3" style="color: #d81b60;">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <span class="badge {{ $sisaStokReal > 0 ? 'bg-light text-dark border' : 'bg-danger text-white' }} rounded-pill px-2 py-1 shadow-sm" style="font-size: 0.75rem;">Sisa {{ $sisaStokReal }}</span>
                                </div>
                                <div class="mt-auto bg-white p-2 rounded-3 text-center mb-3" style="border: 1px solid #ffe4e1;">
                                    <div class="form-check form-check-inline m-0">
                                        <input type="checkbox" name="compare[]" value="{{ $p->id_produk }}" class="form-check-input border-secondary cursor-pointer compare-checkbox" id="comp_{{ $p->id_produk }}"> 
                                        <label class="form-check-label small text-muted cursor-pointer fw-bold" for="comp_{{ $p->id_produk }}">Bandingkan</label>
                                    </div>
                                </div>
                                @if($sisaStokReal > 0)
                                    <form action="/keranjang/tambah/{{ $p->id_produk }}" method="GET" class="w-100">
                                        @php
                                            $minuman = ['Choco Series', 'Tea Series', 'Classic Series'];
                                            $makanan = ['Hotoppa', 'Sidedish']; 
                                        @endphp
                                        @if(in_array($p->kategori, $minuman))
                                            <div class="mb-2 text-start">
                                                <small class="text-muted fw-bold" style="font-size: 0.7rem;">Tingkat Gula:</small>
                                                <select name="sugar_option" class="form-select form-select-sm rounded-pill mt-1" style="font-size: 0.8rem;">
                                                    <option value="Normal">Normal Sugar</option>
                                                    <option value="Less Sugar">Less Sugar</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 text-start">
                                                <small class="text-muted fw-bold" style="font-size: 0.7rem;">Tingkat Es:</small>
                                                <select name="ice_option" class="form-select form-select-sm rounded-pill mt-1" style="font-size: 0.8rem;">
                                                    <option value="Normal">Normal Ice</option>
                                                    <option value="Less Ice">Less Ice</option>
                                                </select>
                                            </div>
                                        @elseif(in_array($p->kategori, $makanan))
                                            <div class="mb-3 text-start">
                                                <small class="text-muted fw-bold" style="font-size: 0.7rem;">Level Pedas:</small>
                                                <select name="spicy_option" class="form-select form-select-sm rounded-pill mt-1" style="font-size: 0.8rem;">
                                                    <option value="Normal">Tidak Pedas</option>
                                                    <option value="Sedang">Pedas Sedang</option>
                                                    <option value="Ekstra Pedas">Ekstra Pedas</option>
                                                </select>
                                            </div>
                                        @endif
                                        <button type="submit" class="btn rounded-pill w-100 fw-bold py-2 shadow-sm text-white" style="background: #ff69b4;">
                                            <i class="bi bi-cart-plus me-1"></i> Tambah
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-secondary rounded-pill w-100 fw-bold py-2 disabled" style="opacity: 0.6;">Habis</button>
                                @endif
                                @if(session('role') == 'admin')
                                <div class="d-flex justify-content-between mt-3 pt-3 border-top" style="border-color: #ffe4e1 !important;">
                                    <a href="/edit/{{ $p->id_produk }}" class="text-warning text-decoration-none small fw-bold"><i class="bi bi-pencil-fill me-1"></i> Kelola</a>
                                    <a href="/hapus/{{ $p->id_produk }}" onclick="return confirm('Yakin hapus produk ini?')" class="text-danger text-decoration-none small fw-bold"><i class="bi bi-trash-fill me-1"></i> Hapus</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

        <!-- Floating Action Bar -->
        <div class="floating-action-bar">
            <button type="button" class="btn btn-outline-dark fw-bold rounded-pill px-4" id="btnBandingkan" onclick="submitCompare()">
                <i class="bi bi-arrow-left-right me-1"></i> Bandingkan
            </button>
            <a href="/keranjang" class="btn fw-bold rounded-pill px-4 shadow-sm text-white" style="background: #d81b60;">
                <i class="bi bi-bag-heart-fill me-1"></i> Keranjang 
                @php $totalQty = session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0; @endphp
                @if($totalQty > 0)
                    <span class="badge bg-white ms-2 rounded-circle shadow-sm" style="color: #d81b60; padding: 6px 9px;">{{ $totalQty }}</span>
                @endif
            </a>
        </div>
    </div>
</div>

<script>
function submitCompare() {
    const checkboxes = document.querySelectorAll('.compare-checkbox:checked');
    
    if (checkboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops!',
            text: 'Pilih minimal 2 produk dulu ya bro!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500
        });
        return;
    }
    
    if (checkboxes.length < 2) {
        Swal.fire({
            icon: 'warning',
            title: 'Kurang!',
            text: 'Pilih minimal 2 produk untuk dibandingkan!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500
        });
        return;
    }
    
    if (checkboxes.length > 4) {
        Swal.fire({
            icon: 'error',
            title: 'Kebanyakan!',
            text: 'Maksimal 4 produk aja ya!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500
        });
        return;
    }
    
    let url = '/compare?';
    checkboxes.forEach((cb, index) => {
        if (index > 0) url += '&';
        url += 'compare[]=' + encodeURIComponent(cb.value);
    });
    
    window.location.href = url;
}
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.compare-checkbox');
        const btnBandingkan = document.getElementById('btnBandingkan');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const checked = document.querySelectorAll('.compare-checkbox:checked').length;
                btnBandingkan.innerHTML = checked > 0 ? '<i class="bi bi-arrow-left-right me-1"></i> Bandingkan (' + checked + ')' : '<i class="bi bi-arrow-left-right me-1"></i> Bandingkan';
            });
        });
    });
</script>

@endsection