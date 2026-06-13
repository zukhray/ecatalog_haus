<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::orderBy('created_at', 'desc');

        // [LOGIKA BARU] Pencarian Nama Customer atau ID Transaksi
        if ($request->cari) {
            $keyword = $request->cari;
            
            // Bersihkan tulisan "INV", "#", atau angka nol di depan (Misal: #INV-00015 jadi angka 15 aja)
            $id_clean = preg_replace('/[^0-9]/', '', $keyword);
            $id_clean = (int) $id_clean; 

            $query->where(function($q) use ($keyword, $id_clean) {
                // Cari berdasarkan nama
                $q->where('nama_customer', 'like', '%' . $keyword . '%');
                
                // Cari berdasarkan ID (kalau ada unsur angkanya di pencarian)
                if ($id_clean > 0) {
                    $q->orWhere('id', $id_clean);
                    // Catatan: Kalau primary key pesanan lu bukan 'id' (misal: 'id_pesanan'), 
                    // ganti kata 'id' di atas jadi 'id_pesanan' ya bro!
                }
            });
        }

        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $pesanan_raw = $query->get();
        
        $pesanan_grouped = $pesanan_raw->groupBy(function($item) {
            return $item->nama_customer . '_' . \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i');
        });

        return view('pesanan', compact('pesanan_grouped'));
    }

    // 2. Fungsi Ubah Status (Otomatis ngubah semua item di 1 struk)
    public function updateStatus($id, $status)
    {
        $ref = Pesanan::findOrFail($id);
        
        // Cek kalau created_at ada isinya
        if ($ref->created_at) {
            $minTime = Carbon::parse($ref->created_at)->subSeconds(10);
            $maxTime = Carbon::parse($ref->created_at)->addSeconds(10);

            Pesanan::where('nama_customer', $ref->nama_customer)
                   ->whereBetween('created_at', [$minTime, $maxTime])
                   ->update(['status' => $status]);
        } else {
            // Rencana cadangan: update yang ID nya sama persis aja
            $ref->update(['status' => $status]);
        }

        return redirect()->back()->with('success', 'Status 1 struk berhasil diperbarui!');
    }

    public function cetakInvoice($id)
    {
        $ref = Pesanan::findOrFail($id);
        
        // Cek kalau created_at ada isinya
        if ($ref->created_at) {
            $minTime = Carbon::parse($ref->created_at)->subSeconds(10);
            $maxTime = Carbon::parse($ref->created_at)->addSeconds(10);

            $items = Pesanan::where('nama_customer', $ref->nama_customer)
                            ->whereBetween('created_at', [$minTime, $maxTime])
                            ->get();
        } else {
            // Rencana cadangan: ambil yang ID nya sama persis aja (jadi array biar bisa diloop di PDF)
            $items = Pesanan::where('id', $ref->id)->get();
        }
        
        $pdf = Pdf::loadView('invoice_pdf', compact('items', 'ref'));
        return $pdf->download('Invoice-'.$ref->nama_customer.'.pdf');
    }

    public function sukses($id)
    {
        // Cari data pesanan berdasarkan ID biar bisa nampilin nama customer di setruk
        $ref = \App\Models\Pesanan::findOrFail($id);
        
        // Ambil semua item dalam 1 struk yang dibeli di waktu yang sama
        $minTime = \Carbon\Carbon::parse($ref->created_at)->subSeconds(10);
        $maxTime = \Carbon\Carbon::parse($ref->created_at)->addSeconds(10);

        $items = \App\Models\Pesanan::where('nama_customer', $ref->nama_customer)
                        ->whereBetween('created_at', [$minTime, $maxTime])
                        ->get();
        
        // Hitung subtotal asli dan total akhir
        $subtotalAsli = 0;
        $totalBayar = 0;
        
        foreach($items as $item) {
            $subtotalAsli += ($item->harga * $item->jumlah); // Pakai 'jumlah', bukan 'qty'
            $totalBayar += $item->total; // Kolom 'total' udah nyimpen harga setelah diskon
        }
        
        $totalDiskon = $subtotalAsli - $totalBayar;

        return view('sukses', compact('ref', 'items', 'subtotalAsli', 'totalBayar', 'totalDiskon', 'id'));
    }
}