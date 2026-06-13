<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'nama_customer',
        'nama_produk',
        'harga',
        'jumlah',
        'total',
        'status'
    ];
    //
}
