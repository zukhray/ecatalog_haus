<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesanan;
use Carbon\Carbon;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $produkList = [
            ['nama' => 'Choco Hazelnut', 'harga' => 17500],
            ['nama' => 'Choco Avocado', 'harga' => 16000],
            ['nama' => 'Thai Tea', 'harga' => 11500],
            ['nama' => 'Taro', 'harga' => 15000],
            ['nama' => 'Extra Spicy Fried Ramyeon', 'harga' => 19000],
            ['nama' => 'Topokki', 'harga' => 22000],
            ['nama' => 'Choco Pudding', 'harga' => 10000],
            ['nama' => 'Chikawa', 'harga' => 7000],
        ];

        $customers = ['Prabu', 'Oppa Budi', 'Eonni Siti', 'Meja 04', 'Meja 12', 'Pre-Order #001', 'Pre-Order #002'];
        $tipePesanan = ['Dine-in', 'Takeaway', 'Pre-order'];
        $metodeBayar = ['Cash', 'QRIS', 'Debit'];
        $statusList = ['diproses', 'selesai'];
        $catatanList = [
            'Less sugar, no ice',
            'Extra pedas',
            'Hazelnut jgn dipakein air',
            'Thai Tea nya yg satu normal aja gula nya',
            null,
            null,
        ];

        // Generate 50 transaksi (7 hari terakhir)
        for ($i = 0; $i < 50; $i++) {
            $tanggal = Carbon::now()->subDays(rand(0, 6))->subHours(rand(0, 23));
            $customer = $customers[array_rand($customers)];
            $produk = $produkList[array_rand($produkList)];
            $jumlah = rand(1, 5);
            $total = $produk['harga'] * $jumlah;
            $status = rand(1, 100) > 30 ? 'selesai' : 'diproses'; // 70% selesai
            
            // Diskon random 10% untuk sebagian
            $diskon = rand(1, 100) > 70 ? round($total * 0.1) : 0;
            $totalBayar = $total - $diskon;

            Pesanan::create([
                'nama_customer' => $customer,
                'nama_produk' => $produk['nama'],
                'jumlah' => $jumlah,
                'harga' => $produk['harga'],
                'total' => $totalBayar,
                'status' => $status,
                'sugar_option' => rand(0, 1) ? '50% Sugar' : null,
                'ice_option' => rand(0, 1) ? 'Less Ice' : null,
                'spicy_option' => rand(0, 1) ? 'Normal' : null,
                'catatan' => $catatanList[array_rand($catatanList)],
                'tipe_pesanan' => $tipePesanan[array_rand($tipePesanan)],
                'metode_pembayaran' => $metodeBayar[array_rand($metodeBayar)],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);
        }
    }
}