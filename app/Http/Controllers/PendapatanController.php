<?php
// Simpan di: app/Http/Controllers/PendapatanController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        // Default range: 7 hari terakhir
        $tanggalAwal = $request->tanggal_awal 
            ? Carbon::parse($request->tanggal_awal)->startOfDay() 
            : Carbon::now()->subDays(6)->startOfDay();
            
        $tanggalAkhir = $request->tanggal_akhir 
            ? Carbon::parse($request->tanggal_akhir)->endOfDay() 
            : Carbon::now()->endOfDay();

        // ============================================
        // 1. PENDAPATAN HARI INI
        // ============================================
        $hariIni = Carbon::today();
        $pendapatanHariIni = Pesanan::whereDate('created_at', $hariIni)
            ->sum('total');

        $transaksiHariIni = Pesanan::whereDate('created_at', $hariIni)
            ->count();

        $produkTerjualHariIni = Pesanan::whereDate('created_at', $hariIni)
            ->sum('jumlah');

        // ============================================
        // 2. PENDAPATAN MINGGU INI (Senin - Minggu)
        // ============================================
        $awalMinggu = Carbon::now()->startOfWeek();
        $akhirMinggu = Carbon::now()->endOfWeek();
        
        $pendapatanMingguIni = Pesanan::whereBetween('created_at', [$awalMinggu, $akhirMinggu])
            ->sum('total');

        $transaksiMingguIni = Pesanan::whereBetween('created_at', [$awalMinggu, $akhirMinggu])
            ->count();

        // ============================================
        // 3. PENDAPATAN BULAN INI
        // ============================================
        $pendapatanBulanIni = Pesanan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // ============================================
        // 4. DATA HARIAN UNTUK CHART & TABEL (Filter Range)
        // ============================================
        $dataHarian = Pesanan::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as total_pendapatan'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(jumlah) as jumlah_produk')
            )
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Format data untuk chart.js
        $labels = [];
        $dataPendapatan = [];
        $dataTransaksi = [];
        
        // Isi tanggal yang kosong dengan 0
        $current = $tanggalAwal->copy();
        while ($current <= $tanggalAkhir) {
            $tglStr = $current->format('Y-m-d');
            $hariData = $dataHarian->firstWhere('tanggal', $tglStr);
            
            $labels[] = $current->format('d M');
            $dataPendapatan[] = $hariData ? (int)$hariData->total_pendapatan : 0;
            $dataTransaksi[] = $hariData ? (int)$hariData->jumlah_transaksi : 0;
            
            $current->addDay();
        }

        // ============================================
        // 5. TOP PRODUK TERLARIS (Range Filter)
        // ============================================
        $topProduk = Pesanan::select(
                'nama_produk',
                DB::raw('SUM(jumlah) as total_terjual'),
                DB::raw('SUM(total) as total_pendapatan')
            )
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->groupBy('nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Total pendapatan range filter
        $totalRange = $dataHarian->sum('total_pendapatan');
        $totalTransaksiRange = $dataHarian->sum('jumlah_transaksi');

        return view('pendapatan', compact(
            'pendapatanHariIni',
            'transaksiHariIni',
            'produkTerjualHariIni',
            'pendapatanMingguIni',
            'transaksiMingguIni',
            'pendapatanBulanIni',
            'dataHarian',
            'labels',
            'dataPendapatan',
            'dataTransaksi',
            'topProduk',
            'totalRange',
            'totalTransaksiRange',
            'tanggalAwal',
            'tanggalAkhir'
        ));
    }

    // API untuk chart AJAX (optional)
    public function apiChart(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        $data = Pesanan::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as total')
            )
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return response()->json($data);
    }
}