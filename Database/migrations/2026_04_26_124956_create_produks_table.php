<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('nama_produk');
            $table->string('kategori');
            $table->integer('harga');
            $table->integer('stok')->default(0); // [BARU] Kolom untuk manajemen stok
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('bestseller')->default(0);
            $table->integer('diskon')->default(0);
            $table->timestamps();
            $table->softDeletes(); // [BARU] Kolom 'deleted_at' agar data tidak benar-benar hilang saat dihapus
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk'); // [FIX] Typo dari 'produks' menjadi 'produk'
    }
};