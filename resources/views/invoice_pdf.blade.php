<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $ref->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .table th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .total { font-size: 16px; font-weight: bold; color: #198754; }
        .badge { padding: 4px 10px; border-radius: 10px; font-size: 10px; color: white; }
        .bg-success { background-color: #198754; }
        .bg-warning { background-color: #ffc107; color: black; }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h1 style="margin:0; color: #000;">HAUS! E-CATALOG</h1>
                    <p style="margin:5px 0;">Jl. Kebon Jeruk Raya No. 27, Jakarta Barat</p>
                </td>
                <td class="text-right">
                    <h2 style="margin:0; color: #999;">INVOICE</h2>
                    <p style="margin:5px 0;"><b>#INV-{{ str_pad($ref->id, 5, '0', STR_PAD_LEFT) }}</b></p>
                    <p style="margin:5px 0;">Tanggal: {{ \Carbon\Carbon::parse($ref->created_at)->format('d M Y, H:i') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table style="width: 100%; margin-bottom: 30px;">
        <tr>
            <td style="width: 50%;">
                <p style="margin-bottom: 5px; color: #666;">Tagihan Kepada:</p>
                <h3 style="margin:0;">{{ $ref->nama_customer }}</h3>
            </td>
            <td style="width: 50%;" class="text-right">
                <p style="margin-bottom: 5px; color: #666;">Status:</p>
                <span class="badge {{ $ref->status == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                    {{ strtoupper($ref->status) }}
                </span>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Deskripsi Produk</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalHargaAsli = 0;
                $totalBayar = 0;
            @endphp
            
            @foreach($items as $item)
            @php 
                // Subtotal normal per baris (Harga Asli x Jumlah)
                $subtotalBaris = $item->harga * $item->jumlah;
                $totalHargaAsli += $subtotalBaris;
                $totalBayar += $item->total; 
            @endphp
            <tr>
                <td class="text-left">{{ $item->nama_produk }}</td>
                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>Rp {{ number_format($subtotalBaris, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            
            @php
                // Hitung selisih diskon
                $totalDiskon = $totalHargaAsli - $totalBayar;
            @endphp

            <tr>
                <td colspan="3" class="text-right" style="padding-top: 15px;">Subtotal Normal</td>
                <td style="padding-top: 15px;">Rp {{ number_format($totalHargaAsli, 0, ',', '.') }}</td>
            </tr>
            
            @if($totalDiskon > 0)
            <tr>
                <td colspan="3" class="text-right text-danger"><b>Potongan Promo</b></td>
                <td class="text-danger"><b>- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</b></td>
            </tr>
            @endif
            
            <tr>
                <td colspan="3" class="text-right" style="background-color: #f8f9fa;"><b>TOTAL PEMBAYARAN</b></td>
                <td class="total" style="background-color: #f8f9fa;">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: center; color: #888; font-size: 10px;">
        <p>Terima kasih atas pesanan Anda!</p>
        <p>Dokumen ini adalah bukti transaksi resmi dari E-Catalog Haus!</p>
        <p style="font-size:12px; margin-top: 20px;">
        © 2026 - Zukhruf Gharrick Marius | Reni Anggariani | Nabila Innayah
        </p>
    </div>
</body>
</html>