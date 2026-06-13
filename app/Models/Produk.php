<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // [BARU] Panggil class SoftDeletes

class Produk extends Model
{
    use SoftDeletes; // [BARU] Aktifkan fitur SoftDeletes di dalam class

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    // [BARU] Tambahkan 'stok' agar bisa di-save ke database
    protected $fillable = [
        'nama_produk',
        'kategori',
        'harga',
        'stok', 
        'deskripsi',
        'foto',
        'bestseller',
        'diskon'
    ];
}