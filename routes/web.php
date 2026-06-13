<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController; // JANGAN LUPA PANGGIL INI
use App\Http\Middleware\CekLogin;

// --- RUTE BEBAS (GA DIKUNCI) ---
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login/sales', [AuthController::class, 'loginSales']);
Route::post('/login/admin', [AuthController::class, 'loginAdmin']);
Route::get('/logout', [AuthController::class, 'logout']);

// --- RUTE DIKUNCI (HARUS LOGIN) ---
Route::middleware([CekLogin::class])->group(function () {
    
    // Rute Katalog Produk
    Route::get('/', [ProdukController::class, 'index']);
    Route::get('/tambah', [ProdukController::class, 'create']);
    Route::post('/simpan', [ProdukController::class, 'store']);
    Route::get('/edit/{id}', [ProdukController::class, 'edit']);
    Route::post('/update/{id}', [ProdukController::class, 'update']);
    Route::get('/hapus/{id}', [ProdukController::class, 'destroy']);
    Route::get('/detail/{id}', [ProdukController::class, 'show']);
    Route::get('/compare', [ProdukController::class, 'compare']);

    // Rute Pesanan Lama
    Route::get('/pesan/{id}', [PesananController::class, 'create']);
    Route::post('/pesan/simpan', [PesananController::class, 'store']);
    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::get('/pesanan/update/{id}/{status}', [PesananController::class, 'updateStatus']);
    Route::get('/pesanan/cetak/{id}', [PesananController::class, 'cetakInvoice']);

    // --- RUTE KERANJANG BARU ---
    Route::get('/keranjang', [CartController::class, 'index']);
    Route::patch('/keranjang/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::get('/keranjang/tambah/{id}', [CartController::class, 'add']);
    Route::get('/keranjang/hapus/{id}', [CartController::class, 'remove']);
    Route::post('/checkout', [CartController::class, 'checkout']);
    Route::post('/keranjang/voucher', [CartController::class, 'applyVoucher']);
    Route::get('/keranjang/voucher/hapus', [CartController::class, 'removeVoucher']);
    Route::get('/checkout/sukses/{id}', [PesananController::class, 'sukses']);
});