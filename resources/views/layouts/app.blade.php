<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#d81b60">
    <title>E-Catalog Haus!</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            background: #f5f7fa;
            overflow-x: hidden;
        }

        /* ============================================
           SIDEBAR STYLES
           ============================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #d81b60 0%, #ad1457 100%);
            z-index: 1040;
            transition: all 0.35s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 25px rgba(0,0,0,0.15);
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            max-width: 120px;
            height: auto;
            filter: drop-shadow(0 3px 6px rgba(0,0,0,0.2));
        }

        .user-section {
            padding: 15px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-size: 1.4rem;
            color: white;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .user-name {
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        .user-role {
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
        }

        .role-badge-sidebar {
            display: inline-block;
            margin-top: 6px;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .role-admin { background: #ffd700; color: #333; }
        .role-sales { background: rgba(255,255,255,0.2); color: white; }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
        }
        .sidebar-menu::-webkit-scrollbar { width: 4px; }
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
        }

        .menu-label {
            padding: 12px 20px 6px;
            color: rgba(255,255,255,0.5);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .nav-item-sidebar {
            margin: 2px 10px;
        }

        .nav-link-sidebar {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            border-radius: 10px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.85rem;
            position: relative;
        }

        .nav-link-sidebar:hover,
        .nav-link-sidebar.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }

        .nav-link-sidebar i {
            font-size: 1.1rem;
            margin-right: 10px;
            width: 22px;
            text-align: center;
        }

        .nav-badge {
            position: absolute;
            right: 10px;
            background: #ffeb3b;
            color: #d81b60;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 15px;
            min-width: 18px;
            text-align: center;
        }
        .nav-badge.danger {
            background: #ff5252;
            color: white;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .btn-logout {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.25);
            background: rgba(255,255,255,0.08);
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s;
            cursor: pointer;
            font-size: 0.85rem;
        }
        .btn-logout:hover {
            background: rgba(255,255,255,0.2);
        }

        /* ============================================
           TOGGLE BUTTON
           ============================================ */
        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1050;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #d81b60, #ff69b4);
            border: none;
            color: white;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(216, 27, 96, 0.35);
            transition: all 0.3s ease;
        }
        .sidebar-toggle:hover { transform: scale(1.05); }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        .main-content {
            margin-left: 260px;
            transition: all 0.35s ease;
            min-height: 100vh;
        }
        .main-content.expanded {
            margin-left: 0;
        }

        /* ============================================
           MOBILE STYLES
           ============================================ */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 260px; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding-top: 70px; }
            .sidebar-toggle { top: 12px; left: 12px; }
        }

        /* ============================================
           EXISTING STYLES
           ============================================ */
        .card {
            border-radius: 15px;
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>

    @stack('styles')
</head>

<body>

@if(Request::path() != 'login')

<!-- Toggle Button -->
<button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
</button>

<!-- ============================================
     SIDEBAR NAVIGATION
     ============================================ -->
<<nav class="sidebar collapsed" id="sidebar">

    <!-- Logo -->
    <div class="sidebar-header">
        <a href="/">
            <img src="{{ asset('img/logo-haus.png') }}" alt="Haus Logo" class="sidebar-logo"
                 onerror="this.onerror=null; this.src='{{ asset('img/HAUS.png') }}'; this.style.maxWidth='100px';">
        </a>
    </div>

    <!-- User Info -->
    <div class="user-section">
        <div class="user-avatar">
            <i class="bi bi-person-circle"></i>
        </div>
        <div class="user-name">{{ session('nama_user', 'User') }}</div>
        <div class="user-role">{{ ucfirst(session('role', 'Sales')) }}</div>
        <span class="role-badge-sidebar {{ session('role') == 'admin' ? 'role-admin' : 'role-sales' }}">
            {{ session('role') == 'admin' ? 'Administrator' : 'Sales Staff' }}
        </span>
    </div>

    <!-- Menu -->
    <div class="sidebar-menu">

        <div class="menu-label">Menu Utama</div>

        <!-- Katalog -->
        <div class="nav-item-sidebar">
            <a href="/" class="nav-link-sidebar {{ request()->is('/') ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i>
                <span>Katalog</span>
            </a>
        </div>

        <!-- Keranjang -->
        <div class="nav-item-sidebar">
            <a href="/keranjang" class="nav-link-sidebar {{ request()->is('keranjang') ? 'active' : '' }}">
                <i class="bi bi-bag-heart"></i>
                <span>Keranjang</span>
                @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0; @endphp
                @if($cartCount > 0)
                    <span class="nav-badge">{{ $cartCount }}</span>
                @endif
            </a>
        </div>

        <!-- Bandingkan -->
        <div class="nav-item-sidebar">
            <a href="/compare" class="nav-link-sidebar {{ request()->is('compare*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i>
                <span>Bandingkan</span>
            </a>
        </div>

 

          <!-- Data Pesanan - Admin Only -->
          @if(session('role') == 'admin')
          <div class="menu-label">Manajemen</div>
        <div class="nav-item-sidebar">
            <a href="/pesanan" class="nav-link-sidebar {{ request()->is('pesanan*') ? 'active' : '' }}">
                <i class="bi bi-receipt-cutoff"></i>
                <span>Data Pesanan</span>
                @php $newOrders = \App\Models\Pesanan::whereDate('created_at', today())->count(); @endphp
                @if($newOrders > 0)
                    <span class="nav-badge">{{ $newOrders }}</span>
                @endif
            </a>
        </div>
        @endif

        <!-- Laporan Pendapatan - Admin Only -->
        @if(session('role') == 'admin')
        <div class="nav-item-sidebar">
            <a href="/pendapatan" class="nav-link-sidebar {{ request()->is('pendapatan*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Pendapatan</span>
            </a>
        </div>
        @endif

        <!-- Tambah Produk - Admin Only -->
        @if(session('role') == 'admin')
        <div class="nav-item-sidebar">
            <a href="/tambah" class="nav-link-sidebar {{ request()->is('tambah') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Produk</span>
            </a>
        </div>
        @endif

        <!-- Stok Kritis - Admin Only -->
        @if(session('role') == 'admin')
        @php $stokKritis = \App\Models\Produk::where('stok', '<=', 5)->count(); @endphp
        <div class="nav-item-sidebar">
            <a href="/stok-kritis" class="nav-link-sidebar {{ request()->is('stok-kritis') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i>
                <span>Stok Kritis</span>
                @if($stokKritis > 0)
                    <span class="nav-badge danger">{{ $stokKritis }}</span>
                @endif
            </a>
        </div>
        @endif

    </div>

    <!-- Footer / Logout -->
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>

</nav>

@endif

<!-- ============================================
     MAIN CONTENT
     ============================================ -->
<div class="main-content expanded" id="mainContent">
    <div class="container mt-4 mb-5">
        @yield('content')
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ============================================
    // SIDEBAR TOGGLE
    // ============================================
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        const mainContent = document.getElementById('mainContent');

        sidebar.classList.toggle('collapsed');
        sidebar.classList.toggle('show');

        if (window.innerWidth > 768) {
            mainContent.classList.toggle('expanded');
        }
    }

    // Auto-close sidebar on mobile when clicking nav link
    document.querySelectorAll('.nav-link-sidebar').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        });
    });

    // ============================================
    // SWEETALERT
    // ============================================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Mantap!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end'
        });
    @endif

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

    // ============================================
    // OFFLINE / ONLINE
    // ============================================
    window.addEventListener('offline', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Koneksi Terputus!',
            text: 'Mode Offline Aktif.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000
        });
    });

    window.addEventListener('online', function() {
        Swal.fire({
            icon: 'info',
            title: 'Kembali Online!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    });
</script>

</body>
</html>