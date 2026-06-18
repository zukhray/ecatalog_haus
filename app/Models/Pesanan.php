<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_customer',
        'nama_produk',
        'jumlah',
        'harga',
        'total',
        'status',
        'sugar_option',
        'ice_option',
        'spicy_option',
        'catatan',
        'tipe_pesanan',
        'kode_preorder',
        'metode_pembayaran',
    ];
}