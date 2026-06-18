<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PendapatanController;
use App\Http\Middleware\CekLogin;
use App\Http\Middleware\CekAdmin;

// --- RUTE BEBAS ---
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login/sales', [AuthController::class, 'loginSales']);
Route::post('/login/admin', [AuthController::class, 'loginAdmin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- RUTE SEMUA USER (LOGIN) ---
Route::middleware([CekLogin::class])->group(function () {
    
    // Katalog & Keranjang (Semua Role)
    Route::get('/', [ProdukController::class, 'index']);
    Route::get('/detail/{id}', [ProdukController::class, 'show']);
    Route::get('/compare', [ProdukController::class, 'compare']);
    
    // Keranjang
    Route::get('/keranjang', [CartController::class, 'index']);
    Route::patch('/keranjang/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('/keranjang/tambah/{id}', [CartController::class, 'add']);
    Route::get('/keranjang/hapus/{id}', [CartController::class, 'remove']);
    Route::post('/checkout', [CartController::class, 'checkout']);
    Route::post('/keranjang/voucher', [CartController::class, 'applyVoucher']);
    Route::get('/keranjang/voucher/hapus', [CartController::class, 'removeVoucher']);
    Route::get('/checkout/sukses/{id}', [PesananController::class, 'sukses']);
    
    // CETAK PDF - SEMUA ROLE BISA (Sales & Admin)
    Route::get('/pesanan/cetak/{id}', [PesananController::class, 'cetakInvoice']);

    // Pesan langsung dari katalog (Sales/Admin)
    Route::get('/pesan/{id}', [PesananController::class, 'create']);
    Route::post('/pesan/simpan', [PesananController::class, 'store']);
});

// --- RUTE ADMIN ONLY ---
Route::middleware([CekLogin::class, CekAdmin::class])->group(function () {
    
    // Produk Management
    Route::get('/tambah', [ProdukController::class, 'create']);
    Route::post('/simpan', [ProdukController::class, 'store']);
    Route::get('/edit/{id}', [ProdukController::class, 'edit']);
    Route::post('/update/{id}', [ProdukController::class, 'update']);
    Route::get('/hapus/{id}', [ProdukController::class, 'destroy']);
    
    // Pesanan Management
    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::get('/pesanan/update/{id}/{status}', [PesananController::class, 'updateStatus']);
    
    // Laporan
    Route::get('/pendapatan', [PendapatanController::class, 'index']);
    
    // Stok Kritis
    Route::get('/stok-kritis', [ProdukController::class, 'stokKritis']);
});