<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        return view('keranjang');
    }

    // Ubah bagian atasnya jadi ada Request $request ya bro
    public function add(Request $request, $id)
    {
    $produk = Produk::findOrFail($id);
    $cart = session()->get('cart', []);

    $qtySekarang = isset($cart[$id]) ? $cart[$id]['qty'] : 0;

    if ($qtySekarang >= $produk->stok) {
        return redirect()->back()->with('error', 'Gagal! Stok tersisa hanya ' . $produk->stok);
    }

    $hargaFix = $produk->harga - $produk->diskon;

    if(isset($cart[$id])) {
        $cart[$id]['qty']++;
    } else {
        $cart[$id] = [
            "name" => $produk->nama_produk,
            "qty" => 1,
            "price" => $hargaFix,
            "foto" => $produk->foto
        ];
    }
    
    session()->put('cart', $cart);

    // [LOGIKA BARU] Jika diklik dari halaman compare, langsung lempar ke halaman keranjang
    if ($request->query('from') == 'compare') {
        return redirect('/keranjang')->with('success', 'Produk berhasil dipilih!');
    }

    // Kalau diklik dari katalog biasa, tetap kembali ke halaman sebelumnya
    return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart');
        if(!$cart) {
            return redirect('/keranjang')->with('error', 'Keranjang kosong!');
        }

        // Ambil total akhir yang dikirim dari form (yang udah didiskon)
        $totalAkhirDiskon = $request->total_akhir_diskon ?? 0;
        
        $waktu_sekarang = now();
        $id_referensi = null;

        // [LOGIKA BARU YANG LEBIH KOKOH] 
        // Hitung subtotal asli keranjang pake looping biasa yang lebih aman
        $subtotalAsli = 0;
        foreach($cart as $item) {
            $subtotalAsli += $item['price'] * $item['qty'];
        }

        foreach($cart as $id => $details) {
            $pesanan = new \App\Models\Pesanan();
            $pesanan->nama_customer = $request->nama_customer;
            $pesanan->nama_produk = $details['name'];
            $pesanan->jumlah = $details['qty'];
            
            // Rumus adil biar diskon dibagi rata per barang di database
            $hargaTotalBarangIni = $details['price'] * $details['qty'];
            if($subtotalAsli > 0) {
                // Berapa persen sih kontribusi harga barang ini ke total belanjaan?
                $porsiDiskon = $hargaTotalBarangIni / $subtotalAsli;
                // Kalikan persentase itu dengan total tagihan yang harus dibayar
                $hargaSetelahDiskon = $totalAkhirDiskon * $porsiDiskon;
            } else {
                $hargaSetelahDiskon = 0;
            }
            
            $pesanan->harga = $details['price'];
            $pesanan->total = round($hargaSetelahDiskon); 
            $pesanan->status = 'selesai'; 
            $pesanan->created_at = $waktu_sekarang;
            $pesanan->save();

            // Pengurangan stok di database
            $produkDB = Produk::find($id);
            if($produkDB) {
                $produkDB->stok -= $details['qty'];
                $produkDB->save();
            }

            if(!$id_referensi) $id_referensi = $pesanan->id;
        }

        // Bersihin keranjang dan voucher dari session setelah sukses bayar
        session()->forget('cart');
        session()->forget('voucher');

        return redirect('/checkout/sukses/' . $id_referensi);
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $produk = Produk::find($request->id);
            $cart = session()->get('cart');
            
            // [BARU] Keamanan form update keranjang
            if ($request->quantity > $produk->stok) {
                return response()->json(['success' => false, 'message' => 'Stok tidak cukup']);
            }
            
            $cart[$request->id]["qty"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function applyVoucher(Request $request)
    {
    $kode = strtoupper($request->kode_voucher);

    // Daftar voucher ghaib (bisa lu tambah sendiri kodenya di sini bro)
    $daftar_voucher = [
        'HAUSPUAS' => ['tipe' => 'potongan', 'nominal' => 10000],
        'FARIZIILHAM' => ['tipe' => 'persen', 'nominal' => 10], // Diskon 10%
        'AKHIRBULAN' => ['tipe' => 'potongan', 'nominal' => 5000],
        'HAUSINAJA' => ['tipe' => 'persen', 'nominal' => 50]
    ];

    if (array_key_exists($kode, $daftar_voucher)) {
        // Simpan data voucher ke session
        session()->put('voucher', [
            'kode' => $kode,
            'tipe' => $daftar_voucher[$kode]['tipe'],
            'nominal' => $daftar_voucher[$kode]['nominal']
        ]);
        return redirect()->back()->with('success', 'Voucher ' . $kode . ' berhasil dipasang!');
    }

    return redirect()->back()->with('error', 'Kode voucher tidak valid bro!');
}

    public function removeVoucher()
    {
    session()->forget('voucher');
    return redirect()->back()->with('success', 'Voucher berhasil dilepas.');
    }
}