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
        $cart = session()->get('cart', []);
        $subtotal = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }
        
        // Hitung diskon
        $diskon = 0;
        $voucher = session()->get('voucher');
        if ($voucher) {
            if ($voucher['tipe'] == 'potongan') {
                $diskon = $voucher['nominal'];
            } else {
                $diskon = $subtotal * ($voucher['nominal'] / 100);
            }
        }
        
        $total = max(0, $subtotal - $diskon);
        
        return view('keranjang', compact('cart', 'subtotal', 'diskon', 'total'));
    }

    public function add(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $cart = session()->get('cart', []);

        $qtySekarang = isset($cart[$id]) ? $cart[$id]['qty'] : 0;

        if ($qtySekarang >= $produk->stok) {
            return redirect()->back()->with('error', 'Gagal! Stok tersisa hanya ' . $produk->stok);
        }

        $hargaFix = $produk->harga - $produk->diskon;

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                "name" => $produk->nama_produk,
                "qty" => 1,
                "price" => $hargaFix,
                "foto" => $produk->foto,
                // ✅ SIMPAN VARIANT
                "sugar_option" => $request->sugar_option ?? 'Normal',
                "ice_option" => $request->ice_option ?? 'Normal',
                "spicy_option" => $request->spicy_option ?? 'Normal'
            ];
        }
        
        session()->put('cart', $cart);

        if ($request->query('from') == 'compare') {
            return redirect('/keranjang')->with('success', 'Produk berhasil dipilih!');
        }

        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    // ✅ CHECKOUT DENGAN TIPE PESANAN
    public function checkout(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect('/keranjang')->with('error', 'Keranjang kosong!');
        }

        $tipe = $request->tipe_pesanan ?? 'dine_in';

        // ✅ VALIDASI BERDASARKAN TIPE
        if ($tipe == 'dine_in' && empty($request->no_meja)) {
            return redirect()->back()->with('error', 'Pilih nomor meja untuk Dine-in!');
        }
        if ($tipe == 'takeaway' && empty($request->nama_customer)) {
            return redirect()->back()->with('error', 'Isi nama customer untuk Takeaway!');
        }

        // ✅ GENERATE KODE PRE-ORDER OTOMATIS
        $kodePreorder = null;
        $namaCustomer = null;

        if ($tipe == 'preorder') {
            // Cari kode terakhir hari ini
            $lastOrder = Pesanan::where('tipe_pesanan', 'preorder')
                ->whereDate('created_at', today())
                ->latest()
                ->first();
            
            $nextNum = 1;
            if ($lastOrder && $lastOrder->kode_preorder) {
                // Ambil angka dari #001 → 1
                $lastNum = (int) str_replace('#', '', $lastOrder->kode_preorder);
                $nextNum = $lastNum + 1;
            }
            $kodePreorder = '#' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
            $namaCustomer = 'Pre-Order ' . $kodePreorder;
            
        } elseif ($tipe == 'dine_in') {
            $namaCustomer = $request->no_meja;
            
        } else { // takeaway
            $namaCustomer = $request->nama_customer;
        }

        $totalAkhirDiskon = $request->total_akhir_diskon ?? 0;
        $waktu_sekarang = now();
        $id_referensi = null;

        $subtotalAsli = 0;
        foreach ($cart as $item) {
            $subtotalAsli += $item['price'] * $item['qty'];
        }

        foreach ($cart as $id => $details) {
            $pesanan = new Pesanan();
            
            // ✅ DATA CUSTOMER
            $pesanan->nama_customer = $namaCustomer;
            $pesanan->tipe_pesanan = $tipe;
            $pesanan->kode_preorder = $kodePreorder;
            
            $pesanan->nama_produk = $details['name'];
            $pesanan->jumlah = $details['qty'];
            
            // ✅ VARIANT
            $pesanan->sugar_option = $details['sugar_option'] ?? 'Normal';
            $pesanan->ice_option = $details['ice_option'] ?? 'Normal';
            $pesanan->spicy_option = $details['spicy_option'] ?? 'Normal';
            
            // ✅ METODE PEMBAYARAN
            $pesanan->metode_pembayaran = $request->metode_pembayaran ?? 'cash';
            $pesanan->catatan = $request->catatan;

            // Rumus diskon
            $hargaTotalBarangIni = $details['price'] * $details['qty'];
            if ($subtotalAsli > 0) {
                $porsiDiskon = $hargaTotalBarangIni / $subtotalAsli;
                $hargaSetelahDiskon = $totalAkhirDiskon * $porsiDiskon;
            } else {
                $hargaSetelahDiskon = 0;
            }
            
            $pesanan->harga = $details['price'];
            $pesanan->total = round($hargaSetelahDiskon); 
            $pesanan->status = 'selesai'; 
            $pesanan->created_at = $waktu_sekarang;
            $pesanan->save();

            // Kurangi stok
            $produkDB = Produk::find($id);
            if ($produkDB) {
                $produkDB->stok -= $details['qty'];
                $produkDB->save();
            }

            if (!$id_referensi) $id_referensi = $pesanan->id;
        }

        session()->forget('cart');
        session()->forget('voucher');

        return redirect('/checkout/sukses/' . $id_referensi);
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $produk = Produk::find($request->id);
            $cart = session()->get('cart');
            
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

        $daftar_voucher = [
            'HAUSPUAS' => ['tipe' => 'potongan', 'nominal' => 10000],
            'FARIZIILHAM' => ['tipe' => 'persen', 'nominal' => 10],
            'AKHIRBULAN' => ['tipe' => 'potongan', 'nominal' => 5000],
            'HAUSINAJA' => ['tipe' => 'persen', 'nominal' => 50]
        ];

        if (array_key_exists($kode, $daftar_voucher)) {
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