<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Cek dulu kalau belum ada, baru tambah
            if (!Schema::hasColumn('pesanans', 'tipe_pesanan')) {
                $table->string('tipe_pesanan')->default('dine_in');
            }
            if (!Schema::hasColumn('pesanans', 'kode_preorder')) {
                $table->string('kode_preorder')->nullable();
            }
            if (!Schema::hasColumn('pesanans', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->default('cash');
            }
            if (!Schema::hasColumn('pesanans', 'sugar_option')) {
                $table->string('sugar_option')->default('Normal');
            }
            if (!Schema::hasColumn('pesanans', 'ice_option')) {
                $table->string('ice_option')->default('Normal');
            }
            if (!Schema::hasColumn('pesanans', 'spicy_option')) {
                $table->string('spicy_option')->default('Normal');
            }
        });
    }

    public function down(): void
    {
        // Biarin kosong, gak usah dihapus
    }
};