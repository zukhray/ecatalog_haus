@extends('layouts.app')

@section('content')
<style>
    /* Paksa background full layar */
    body {
        background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%) !important;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    .korean-watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-15deg);
        font-size: 15vw;
        font-weight: 900;
        color: rgba(255, 105, 180, 0.12);
        z-index: 0;
        white-space: nowrap;
        pointer-events: none;
        user-select: none;
    }

    .korean-watermark span { display: block; text-align: center; }

    .login-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
        padding: 2rem 15px;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        border-radius: 30px;
        box-shadow: 0 20px 50px rgba(255, 105, 180, 0.15);
        border: 2px solid #fff;
        width: 100%;
        max-width: 420px;
        overflow: hidden;
    }

    .login-header {
        background: linear-gradient(135deg, #ff1493 0%, #d81b60 100%);
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        text-align: center;
        color: white;
        border-radius: 30px 30px 0 0;
    }

    /* Input Form Estetik */
    .input-group-text {
        background: #fff0f5;
        border: none;
        color: #ff69b4;
        border-radius: 50px 0 0 50px !important;
        padding-left: 1.5rem;
    }
    .form-control-custom {
        background: #fff0f5;
        border: none;
        border-radius: 0 50px 50px 0 !important;
        padding: 0.8rem 1.5rem 0.8rem 0;
        color: #2d3748;
        font-weight: 600;
    }
    .form-control-custom:focus {
        box-shadow: none;
        background: #ffe4e1;
    }
    .input-group:focus-within .input-group-text, 
    .input-group:focus-within .form-control-custom {
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.2);
    }

    .btn-login {
        background: linear-gradient(135deg, #ff1493 0%, #d81b60 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(216, 27, 96, 0.3);
        color: white;
    }

    /* Styling buat Tab Dual-Actor */
    .nav-pills .nav-link {
        color: #ff69b4;
        border-radius: 50px;
        font-weight: bold;
        padding: 10px 0;
        background: #fff0f5;
        border: 1px solid transparent;
        transition: 0.3s;
    }
    .nav-pills .nav-item { margin: 0 5px; }
    .nav-pills .nav-link.active {
        background: #d81b60;
        color: white;
        box-shadow: 0 4px 10px rgba(216, 27, 96, 0.2);
    }
</style>

<div class="korean-watermark">
    <span>하우스!</span>
</div>

<div class="login-container">
    <div class="card login-card border-0">
        
        <!-- Header -->
        <div class="login-header shadow-sm">
            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-3 shadow" style="width: 80px; height: 80px; overflow: hidden; padding: 10px;">
                <img src="{{ asset('img/logo-haus.png') }}" alt="Logo Haus" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h3 class="fw-bolder mb-1" style="letter-spacing: 0.5px;">Annyeong! 👋</h3>
            <p class="small mb-0 opacity-75">Silakan pilih akses masuk kamu.</p>
        </div>

        <div class="card-body p-4">
            
            <!-- PILIHAN TAB (SALES VS ADMIN) -->
            <ul class="nav nav-pills nav-justified mb-4" id="loginTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active w-100" id="sales-tab" data-bs-toggle="pill" data-bs-target="#sales" type="button" role="tab">
                        <i class="bi bi-person-badge me-1"></i> Staff Sales
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link w-100" id="admin-tab" data-bs-toggle="pill" data-bs-target="#admin" type="button" role="tab">
                        <i class="bi bi-person-gear me-1"></i> Admin
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="loginTabsContent">
                
                <!-- ISI TAB 1: FORM LOGIN SALES -->
                <div class="tab-pane fade show active" id="sales" role="tabpanel">
                    
                    @if(session('error_sales'))
                        <div class="alert alert-danger rounded-4 border-0 small shadow-sm text-center mb-3" style="background: #ffe5e5; color: #d81b60;">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error_sales') }}
                        </div>
                    @endif

                    <!-- Action URL dan method disamakan dengan web.php -->
                    <form action="/login/sales" method="POST">
                        @csrf
                        <div class="mb-5 mt-2 text-center">
                            <label class="fw-bold small mb-2" style="color: #d81b60;">Kode PIN Kasir</label>
                            <div class="input-group shadow-sm rounded-pill">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <!-- Name disamakan jadi kode_akses -->
                                <input type="password" name="kode_akses" class="form-control form-control-custom" placeholder="Masukkan 6 digit kode..." required>
                            </div>
                            <small class="text-muted" style="font-size: 11px; display: block; margin-top: 10px;">Hanya masukkan angka PIN kasir Anda.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-login w-100 rounded-pill fw-bold py-3 text-uppercase shadow d-flex justify-content-center align-items-center">
                            Buka Kasir <i class="bi bi-unlock-fill ms-2"></i>
                        </button>
                    </form>
                </div>

                <!-- ISI TAB 2: FORM LOGIN ADMIN -->
                <div class="tab-pane fade" id="admin" role="tabpanel">
                    
                    @if(session('error_admin'))
                        <div class="alert alert-danger rounded-4 border-0 small shadow-sm text-center mb-3" style="background: #ffe5e5; color: #d81b60;">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error_admin') }}
                        </div>
                    @endif

                    <!-- Action URL disamakan dengan web.php -->
                    <form action="/login/admin" method="POST">
                        @csrf
                        <div class="mb-3 mt-2">
                            <label class="fw-bold small mb-2" style="color: #d81b60;">Username Admin</label>
                            <div class="input-group shadow-sm rounded-pill">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <!-- Name disamakan jadi username -->
                                <input type="text" name="username" class="form-control form-control-custom" placeholder="admin@toko.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold small mb-2" style="color: #d81b60;">Password</label>
                            <div class="input-group shadow-sm rounded-pill">
                                <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                                <!-- Name tetap password -->
                                <input type="password" name="password" class="form-control form-control-custom" placeholder="Rahasia..." required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100 rounded-pill fw-bold py-3 text-uppercase shadow d-flex justify-content-center align-items-center">
                            Masuk Dasbor <i class="bi bi-box-arrow-in-right ms-2 fs-5"></i>
                        </button>
                    </form>
                </div>

            </div>
            
        </div>
    </div>
</div>
<div style="margin-top: 50px; text-align: center; color: #888; font-size: 10px;">
        <p style="font-size:12px; margin-top: 20px;">
        © 2026 - Zukhruf Gharrick Marius | Reni Anggariani | Nabila Innayah
        </p>
    </div>
@endsection