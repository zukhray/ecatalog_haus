<!-- Sidebar Navigation -->
<style>
    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 260px;
        background: linear-gradient(180deg, #d81b60 0%, #ad1457 100%);
        z-index: 1040;
        transition: all 0.3s ease;
        box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
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
        max-width: 140px;
        height: auto;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }
    
    .sidebar-menu {
        flex: 1;
        overflow-y: auto;
        padding: 15px 0;
    }
    
    .sidebar-menu::-webkit-scrollbar {
        width: 5px;
    }
    .sidebar-menu::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3);
        border-radius: 10px;
    }
    
    .nav-item {
        margin: 4px 12px;
    }
    
    .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 12px;
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        transition: all 0.2s;
        font-weight: 500;
        position: relative;
    }
    
    .nav-link:hover, .nav-link.active {
        background: rgba(255,255,255,0.15);
        color: white;
        transform: translateX(5px);
    }
    
    .nav-link i {
        font-size: 1.2rem;
        margin-right: 12px;
        width: 24px;
        text-align: center;
    }
    
    .nav-badge {
        position: absolute;
        right: 12px;
        background: #ffeb3b;
        color: #d81b60;
        font-size: 0.7rem;
        font-weight: bold;
        padding: 2px 8px;
        border-radius: 20px;
        min-width: 20px;
        text-align: center;
    }
    
    .sidebar-footer {
        padding: 15px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    
    .btn-logout {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.1);
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-logout:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.5);
    }
    
    /* Role Badge */
    .role-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
        margin-top: 8px;
        background: rgba(255,255,255,0.2);
        color: white;
    }
    
    /* Toggle Button */
    .sidebar-toggle {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1050;
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: linear-gradient(135deg, #d81b60, #ff69b4);
        border: none;
        color: white;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(216, 27, 96, 0.3);
        transition: all 0.2s;
    }
    
    .sidebar-toggle:hover {
        transform: scale(1.05);
    }
    
    .sidebar-toggle.shifted {
        left: 275px;
    }
    
    /* Overlay untuk mobile */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1035;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }
    
    .sidebar-overlay.show {
        opacity: 1;
        visibility: visible;
    }
    
    /* Main Content Adjustment */
    .main-content {
        margin-left: 260px;
        transition: all 0.3s ease;
        min-height: 100vh;
    }
    
    .main-content.expanded {
        margin-left: 0;
    }
    
    /* Mobile Styles */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0;
            padding-top: 70px;
        }
        
        .sidebar-toggle {
            top: 15px;
            left: 15px;
        }
        
        .sidebar-toggle.shifted {
            left: 15px;
        }
    }
    
    /* User Info Section */
    .user-info {
        padding: 15px 20px;
        text-align: center;
        color: white;
    }
    
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 1.5rem;
    }
    
    .user-name {
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .user-role {
        font-size: 0.8rem;
        opacity: 0.8;
    }
</style>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Toggle Button -->
<button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
</button>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-header">
        <img src="{{ asset('img/logo-haus.png') }}" alt="Haus Logo" class="sidebar-logo" 
             onerror="this.src='https://via.placeholder.com/140x60/d81b60/ffffff?text=HAUS'">
    </div>
    
    <!-- User Info -->
    <div class="user-info">
        <div class="user-avatar">
            <i class="bi bi-person-circle"></i>
        </div>
        <div class="user-name">{{ session('nama_user', 'User') }}</div>
        <div class="user-role">{{ ucfirst(session('role', 'Sales')) }}</div>
        <span class="role-badge">
            <i class="bi bi-shield-check me-1"></i>{{ session('role') == 'admin' ? 'Administrator' : 'Sales Staff' }}
        </span>
    </div>
    
    <!-- Menu -->
    <div class="sidebar-menu">
        
        <!-- Dashboard / Katalog - Semua Role -->
        <div class="nav-item">
            <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>Katalog Produk</span>
            </a>
        </div>
        
        <!-- Keranjang - Semua Role -->
        <div class="nav-item">
            <a href="/keranjang" class="nav-link {{ request()->is('keranjang') ? 'active' : '' }}">
                <i class="bi bi-bag-heart"></i>
                <span>Keranjang</span>
                @php
                    $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0;
                @endphp
                @if($cartCount > 0)
                    <span class="nav-badge">{{ $cartCount }}</span>
                @endif
            </a>
        </div>
        
        <!-- Data Pesanan - Admin Only -->
        @if(session('role') == 'admin')
        <div class="nav-item">
            <a href="/pesanan" class="nav-link {{ request()->is('pesanan*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Data Pesanan</span>
                @php
                    $newOrders = \App\Models\Pesanan::whereDate('created_at', today())->count();
                @endphp
                @if($newOrders > 0)
                    <span class="nav-badge">{{ $newOrders }}</span>
                @endif
            </a>
        </div>
        @endif
        
        <!-- Laporan Pendapatan - Admin Only -->
        @if(session('role') == 'admin')
        <div class="nav-item">
            <a href="/pendapatan" class="nav-link {{ request()->is('pendapatan*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Laporan Pendapatan</span>
            </a>
        </div>
        @endif
        
        <!-- Bandingkan Produk - Semua Role -->
        <div class="nav-item">
            <a href="/compare" class="nav-link {{ request()->is('compare*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i>
                <span>Bandingkan</span>
            </a>
        </div>
        
        <div class="mt-3 mb-2 px-4">
            <small class="text-white-50 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                Manajemen
            </small>
        </div>
        
        <!-- Tambah Produk - Admin Only -->
        @if(session('role') == 'admin')
        <div class="nav-item">
            <a href="/tambah" class="nav-link {{ request()->is('tambah') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Produk</span>
            </a>
        </div>
        @endif
        
        <!-- Peringatan Stok - Admin Only -->
        @if(session('role') == 'admin')
        @php
            $stokHabis = \App\Models\Produk::where('stok', '<=', 5)->count();
        @endphp
        <div class="nav-item">
            <a href="/?filter=stok_habis" class="nav-link {{ request('filter') == 'stok_habis' ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i>
                <span>Peringatan Stok</span>
                @if($stokHabis > 0)
                    <span class="nav-badge" style="background: #ff5252; color: white;">{{ $stokHabis }}</span>
                @endif
            </a>
        </div>
        @endif
        
    </div>
    
    <!-- Footer / Logout -->
    <div class="sidebar-footer">
        <form action="/logout" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</nav>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');
        const mainContent = document.querySelector('.main-content');
        
        sidebar.classList.toggle('show');
        sidebar.classList.toggle('collapsed');
        overlay.classList.toggle('show');
        toggle.classList.toggle('shifted');
        
        if (window.innerWidth > 768) {
            mainContent.classList.toggle('expanded');
        }
    }
    
    // Auto-close sidebar on mobile when clicking nav link
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        });
    });
    
    // Check localStorage for sidebar state (desktop)
    document.addEventListener('DOMContentLoaded', () => {
        if (window.innerWidth > 768) {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const toggle = document.getElementById('sidebarToggle');
            
            // Default: sidebar terbuka
            if (localStorage.getItem('sidebarClosed') === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                toggle.classList.remove('shifted');
            } else {
                toggle.classList.add('shifted');
            }
        }
    });
</script>