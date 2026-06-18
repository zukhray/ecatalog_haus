<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ str_pad($ref->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            font-size: 10px; 
            line-height: 1.4; 
            color: #2d3748; 
            padding: 30px 35px;
        }
        
        .header { 
            text-align: center;
            border-bottom: 2px solid #d81b60; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
        }
        .logo-img { 
            max-height: 50px; 
            max-width: 140px; 
            display: block;
            margin: 0 auto 6px auto;
        }
        .brand-address { 
            font-size: 8px; 
            color: #a0aec0; 
            margin: 0 0 8px 0;
        }
        .invoice-number { 
            font-size: 16px; 
            font-weight: 800; 
            color: #d81b60; 
            margin: 0;
        }
        .invoice-date { 
            font-size: 9px; 
            color: #718096; 
            margin: 3px 0 0 0;
        }

        .info-section { 
            width: 100%; 
            margin-bottom: 20px; 
        }
        .info-section td { 
            vertical-align: top;
            padding: 0;
        }
        .info-label { 
            font-size: 8px; 
            text-transform: uppercase; 
            color: #ff69b4; 
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .info-value { 
            font-size: 11px; 
            font-weight: 700; 
            color: #2d3748; 
            margin: 0;
        }
        .status-badge { 
            display: inline-block;
            padding: 4px 12px; 
            border-radius: 20px; 
            font-size: 9px; 
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-selesai { background: #d1fae5; color: #059669; }
        .status-pending { background: #fef3c7; color: #d97706; }

        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px;
        }
        .items-table thead th { 
            background: #d81b60; 
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        .items-table thead th:last-child { text-align: right; }
        .items-table tbody td { 
            padding: 8px 10px; 
            border-bottom: 1px solid #ffe4e1;
            vertical-align: top;
        }
        .items-table tbody td:last-child { 
            text-align: right; 
            font-weight: 700;
            white-space: nowrap;
        }
        .items-table tbody td:nth-child(2),
        .items-table tbody td:nth-child(3) { 
            text-align: center; 
            white-space: nowrap;
        }
        
        .item-name { 
            font-weight: 700; 
            font-size: 10px; 
            color: #2d3748; 
        }
        .item-meta { 
            font-size: 8px; 
            color: #a0aec0; 
            margin-top: 2px;
        }
        /* Variant tags in PDF */
        .variant-tag {
            display: inline-block;
            font-size: 7px;
            font-weight: 700;
            color: #d81b60;
            background: #fff0f5;
            padding: 2px 6px;
            border-radius: 6px;
            border: 1px solid #ffb6c1;
            margin-top: 3px;
            margin-right: 3px;
        }

        /* Catatan Umum */
        .catatan-box { 
            background: #fff0f5;
            border-left: 3px solid #d81b60;
            border-radius: 0 8px 8px 0;
            padding: 10px 12px;
            margin-bottom: 15px;
        }
        .catatan-label { 
            font-size: 8px; 
            font-weight: 700; 
            color: #d81b60; 
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .catatan-text { 
            font-size: 9px; 
            color: #4a5568; 
            font-style: italic;
            margin: 0;
            line-height: 1.5;
        }

        .summary-wrapper {
            width: 100%;
            margin-top: 10px;
        }
        .summary-table { 
            width: 100%;
            max-width: 260px; 
            margin-left: auto;
            border-collapse: collapse;
        }
        .summary-table td { 
            padding: 4px 0; 
            font-size: 10px;
        }
        .summary-table td:first-child { 
            text-align: left; 
            color: #718096; 
        }
        .summary-table td:last-child { 
            text-align: right; 
            font-weight: 600; 
            color: #2d3748;
        }
        .summary-divider td { 
            border-top: 1px dashed #ffb6c1; 
            padding-top: 8px;
        }
        .summary-total td { 
            padding-top: 8px;
            font-size: 12px;
            font-weight: 800;
            color: #d81b60;
        }
        .summary-total td:first-child { 
            color: #d81b60; 
            font-weight: 800; 
        }
        .text-discount { color: #ff1493 !important; }

        .footer { 
            margin-top: 40px; 
            text-align: center; 
            border-top: 1px solid #ffe4e1;
            padding-top: 15px;
        }
        .footer .thanks { 
            font-size: 10px; 
            font-weight: 700; 
            color: #d81b60;
            margin-bottom: 4px;
        }
        .footer p { 
            font-size: 8px; 
            color: #a0aec0; 
            margin-bottom: 2px;
        }
        .footer .legal { 
            font-size: 7px; 
            color: #cbd5e0; 
            margin-top: 8px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('img/logo-haus.png') }}" class="logo-img" alt="Haus! Logo">
        <p class="brand-address">Jl. Kebon Jeruk Raya No. 27, Jakarta Barat</p>
        <p class="invoice-number">#INV-{{ str_pad($ref->id, 5, '0', STR_PAD_LEFT) }}</p>
        <p class="invoice-date">{{ \Carbon\Carbon::parse($ref->created_at)->format('d M Y, H:i') }}</p>
    </div>

    <!-- Info Customer & Status -->
    <table class="info-section">
        <tr>
            <td style="width: 50%;">
                <p class="info-label">Tagihan Kepada</p>
                <p class="info-value">{{ $ref->nama_customer }}</p>
            </td>
            <td style="width: 50%; text-align: right;">
                <span class="status-badge {{ $ref->status == 'selesai' ? 'status-selesai' : 'status-pending' }}">
                    {{ strtoupper($ref->status) }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Produk</th>
                <th style="width: 18%;">Harga</th>
                <th style="width: 12%;">Qty</th>
                <th style="width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalHargaAsli = 0;
                $totalBayar = 0;
                $semuaCatatan = [];
            @endphp
            
            @foreach($items as $item)
            @php 
                $subtotalBaris = $item->harga * $item->jumlah;
                $totalHargaAsli += $subtotalBaris;
                $totalBayar += $item->total;
                
                // Kumpulin catatan umum (bukan varian)
                if(!empty($item->catatan) && !in_array($item->catatan, $semuaCatatan)) {
                    $semuaCatatan[] = $item->catatan;
                }
                
                // Build variant tags (skip Normal)
                $variantTags = [];
                if (!empty($item->sugar_option) && $item->sugar_option != 'Normal' && $item->sugar_option != 'Normal Sugar') {
                    $variantTags[] = 'Gula: ' . $item->sugar_option;
                }
                if (!empty($item->ice_option) && $item->ice_option != 'Normal' && $item->ice_option != 'Normal Ice') {
                    $variantTags[] = 'Es: ' . $item->ice_option;
                }
                if (!empty($item->spicy_option) && $item->spicy_option != 'Normal' && $item->spicy_option != 'Tidak Pedas') {
                    $variantTags[] = 'Pedas: ' . $item->spicy_option;
                }
            @endphp
            <tr>
                <td>
                    <div class="item-name">{{ $item->nama_produk }}</div>
                    @if(count($variantTags) > 0)
                        <div>
                            @foreach($variantTags as $tag)
                                <span class="variant-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                    <div class="item-meta">{{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                </td>
                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>Rp {{ number_format($subtotalBaris, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Catatan Pesanan Umum -->
    @if(count($semuaCatatan) > 0)
    <div class="catatan-box">
        <p class="catatan-label">Catatan Tambahan</p>
        <p class="catatan-text">{{ implode(' | ', $semuaCatatan) }}</p>
    </div>
    @endif

    <!-- Ringkasan Biaya -->
    @php
        $totalDiskon = $totalHargaAsli - $totalBayar;
    @endphp

    <div class="summary-wrapper">
        <table class="summary-table">
            <tr>
                <td>Subtotal Normal</td>
                <td>Rp {{ number_format($totalHargaAsli, 0, ',', '.') }}</td>
            </tr>
            @if($totalDiskon > 0)
            <tr>
                <td class="text-discount">Potongan Promo</td>
                <td class="text-discount">- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="summary-divider">
                <td></td>
                <td></td>
            </tr>
            <tr class="summary-total">
                <td>TOTAL PEMBAYARAN</td>
                <td>Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p class="thanks">Terima kasih atas pesanan Anda!</p>
        <p>Dokumen ini adalah bukti transaksi resmi dari E-Catalog Haus!</p>
        <p class="legal">© 2026 - Zukhruf Gharrick Marius | Reni Anggariani | Nabila Innayah</p>
    </div>

</body>
</html>