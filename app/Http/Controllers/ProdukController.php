<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        if($request->cari){
            $query->where('nama_produk','like','%'.$request->cari.'%');
        }

        if($request->kategori){
            $query->where('kategori', $request->kategori);
        }

        $produk = $query->get();
        return view('katalog', compact('produk'));
    }

    public function create()
    {
        return view('tambah');
    }

    public function store(Request $request)
    {
        // [BARU] Validasi inputan dari form
        $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric|min:0', // Stok wajib diisi angka, minimal 0
        ]);

        $data = $request->all();
        $data['diskon'] = $request->diskon ?? 0;
        $data['bestseller'] = $request->bestseller ? 1 : 0;

        if($request->hasFile('foto')){
            $file = $request->file('foto');
            $namaFile = time().'.'.$file->extension(); 
            $file->move(public_path('img'), $namaFile);
            $data['foto'] = $namaFile;
        }

        Produk::create($data);
        return redirect('/');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        // [BARU] Validasi juga saat edit data
        $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric|min:0',
        ]);

        $produk = Produk::findOrFail($id);
        $data = $request->all();

        $data['diskon'] = $request->diskon ?? 0;
        $data['bestseller'] = $request->bestseller ? 1 : 0;

        if($request->hasFile('foto')){
            $file = $request->file('foto');
            $namaFile = time().'.'.$file->extension(); 
            $file->move(public_path('img'), $namaFile);
            $data['foto'] = $namaFile;
        }

        $produk->update($data);
        return redirect('/');
    }

    public function destroy($id)
    {
        Produk::findOrFail($id)->delete(); 
        // Karena kita udah pakai SoftDeletes di Model, kode delete() ini 
        // otomatis cuma ngisi kolom 'deleted_at', gak akan ngehapus permanen dari XAMPP!
        return redirect('/');
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('detail', compact('produk'));
    }

    public function compare(Request $request)
    {
        // 1. Ambil data ID produk yang dicentang dari form (name="compare[]")
        $ids = $request->compare;

        // 2. Validasi kalau user iseng milih kurang dari 2 atau malah gak milih sama sekali
        if (!$ids || count($ids) < 2) {
            return redirect('/')->with('error', 'Omo! Pilih minimal 2 menu dulu ya buat dibandingin.');
        }

        // 3. Validasi kalau user maruk milih lebih dari 4 (biar layar HP/Laptop gak kepenuhan)
        if (count($ids) > 4) {
            return redirect('/')->with('error', 'Maksimal 4 menu aja ya yang bisa dibandingin sekaligus!');
        }

        // 4. Tarik data dari database berdasarkan ID yang dicentang
        // Catatan: Pastikan 'id_produk' adalah nama primary key di database lu. 
        // Kalau nama kolom ID-nya cuma 'id', ganti kata 'id_produk' di bawah ini jadi 'id'.
        $produk = \App\Models\Produk::whereIn('id_produk', $ids)->get();

        // 5. Lempar datanya ke halaman compare dengan nama variabel 'produk'
        return view('compare', compact('produk'));
    }

    public function stokKritis()
{
    $produkKritis = Produk::where('stok', '<=', 5)
        ->orderBy('stok', 'asc')
        ->get();
    
    return view('stok_kritis', compact('produkKritis'));
}
}