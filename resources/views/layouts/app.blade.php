<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<meta name="theme-color" content="#ff69b4">
    <title>E-Catalog Haus!</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
        }
        .card {
            border-radius: 15px;
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        /* Style tambahan untuk badge keranjang agar lebih estetik */
        .badge-cart {
            font-size: 0.65rem;
            vertical-align: top;
            margin-left: -5px;
        }
    </style>
</head>

<body>
@if(Request::path() != 'login')
<nav class="navbar navbar-light bg-white shadow-sm mb-4 py-3">

    <div class="container d-flex flex-column align-items-center text-center">
        
        <a href="/">
            <img src="{{ asset('img/HAUS.png') }}" alt="Logo Haus" style="height: 120px; object-fit: contain;" class="mb-2">
        </a>
        <h4 class="fw-bold mb-3 text-dark">E-Catalog</h4>

        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center">
            @if(session('role') == 'admin')
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill me-2 shadow-sm"><i class="bi bi-person-badge"></i> Admin</span>
            @elseif(session('role') == 'sales')
                <span class="badge bg-info text-dark px-3 py-2 rounded-pill me-2 shadow-sm"><i class="bi bi-person-badge"></i> Sales</span>
            @endif

            <a href="/" class="btn btn-dark btn-sm rounded-pill px-4">Katalog</a>
            
            @if(session('role') == 'admin')
                <li class="nav-item">
            <a href="/pesanan" class="btn btn-outline-dark btn-sm rounded-pill px-4">Data Pesanan</a>
                  </li>
            @endif

            @if(session('role') == 'admin')

@php
    // Ambil data produk yang stoknya kritis (di bawah atau sama dengan 5)
    $produkKritis = \App\Models\Produk::where('stok', '<=', 5)->get();
@endphp

<div class="dropdown me-3 d-inline-block">
    <button class="btn btn-light position-relative rounded-circle p-2 shadow-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell-fill {{ $produkKritis->count() > 0 ? 'text-danger animate-pulse' : 'text-secondary' }}"></i>
        @if($produkKritis->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                {{ $produkKritis->count() }}
            </span>
        @endif
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" style="width: 280px; border-radius: 12px;">
        <li><h6 class="dropdown-header fw-bold text-dark border-bottom pb-2"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Peringatan Stok Kafe</h6></li>
        
        @forelse($produkKritis as $pk)
            <li class="my-1">
                <a href="/edit/{{ $pk->id_produk }}" class="dropdown-item rounded d-flex justify-content-between align-items-center py-2" style="white-space: normal; cursor: pointer; transition: 0.2s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
                    <div>
                        <span class="fw-bold d-block small text-dark">
                            {{ $pk->nama_produk }}
                            <i class="bi bi-pencil-square text-primary ms-1" style="font-size: 11px;" title="Klik untuk edit"></i>
                        </span>
                        <small class="text-muted">{{ $pk->kategori }}</small>
                    </div>
                    <span class="badge bg-danger text-white rounded-pill shadow-sm">Sisa {{ $pk->stok }}</span>
                </a>
            </li>
        @empty
            <li class="text-center py-3 text-muted small">
                <i class="bi bi-check-circle-fill text-success d-block fs-4 mb-1"></i>
                Aman! Semua stok produk aman terpantau.
            </li>
        @endforelse
    </ul>
</div>

@endif

    <style>
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }
        .animate-pulse {
            animation: pulse 1.5s infinite;
            display: inline-block;
        }
                </style>

                <a href="/keranjang" class="btn btn-outline-success btn-sm rounded-pill px-3 position-relative">
                    <i class="bi bi-cart3"></i>
                    @php 
                        $totalQty = session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0; 
                    @endphp
                    @if($totalQty > 0)
                        <span class="badge rounded-pill bg-danger badge-cart">
                            {{ $totalQty }}
                        </span>
                    @endif
                </a>

                <a href="/logout" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('Yakin mau keluar?')">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>
    @endif
<div class="container mt-4 mb-5">
    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Menangkap pesan 'success' dari controller
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Mantap!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            toast: true, // Bikin model toast (ngambang di pojok)
            position: 'top-end'
        });
    @endif

    // Menangkap pesan 'error' dari controller
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Waduh...',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
</script>

<script>
    // Ketika Wi-Fi/LAN mati (Offline)
    window.addEventListener('offline', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Koneksi Terputus!',
            text: 'Mode Offline Aktif. Katalog dimuat dari penyimpanan lokal.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });
    });

    // Ketika Wi-Fi/LAN nyala lagi (Online)
    window.addEventListener('online', function() {
        Swal.fire({
            icon: 'info',
            title: 'Kembali Online!',
            text: 'Mengkoneksikan ke server & mensinkronisasi data pesanan...',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000
        });
    });
</script>

</body>
</html>