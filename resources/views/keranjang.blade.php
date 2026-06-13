@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%) !important;
        position: relative;
        min-height: 100vh;
    }
    
    /* Watermark Korea yang udah ditebelin transparansinya (0.12) */
    .korean-watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-15deg);
        font-size: 12vw;
        font-weight: 900;
        color: rgba(255, 105, 180, 0.12); /* Sekarang pasti keliatan bro! */
        z-index: 0;
        white-space: nowrap;
        pointer-events: none;
        user-select: none;
    }

    .korean-watermark span { display: block; text-align: center; }
    .content-wrapper { position: relative; z-index: 1; }

    .cart-card { 
        border-radius: 24px; 
        background: rgba(255, 255, 255, 0.9); 
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(255, 105, 180, 0.15); 
        border: 2px solid #fff; 
    }
    .summary-card { 
        border-radius: 24px; 
        background: rgba(255, 255, 255, 0.95); 
        backdrop-filter: blur(10px);
        box-shadow: 0 15px 40px rgba(255, 105, 180, 0.15); 
        border: 2px solid #fff; 
        position: sticky; top: 20px; 
    }
    
    .table-cart th { 
        font-weight: 700; color: #d81b60; text-transform: uppercase; 
        font-size: 0.75rem; letter-spacing: 1px; border-bottom: 2px solid #ffe4e1; padding-bottom: 15px; 
    }
    .table-cart td { vertical-align: middle; border-bottom: 1px solid #fff0f5; padding: 1.2rem 0.5rem; }
    .product-img-cart { border-radius: 14px; object-fit: cover; box-shadow: 0 4px 12px rgba(255, 105, 180, 0.15); }
    
    .qty-wrapper { background: #fff0f5; border-radius: 50px; padding: 4px; display: inline-flex; align-items: center; border: 1px solid #ffb6c1; }
    .qty-btn { 
        width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        background: #fff; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: 0.2s; color: #d81b60;
    }
    .qty-btn:hover:not(:disabled) { background: #ff69b4; color: white; }
    .qty-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .qty-input { width: 35px; text-align: center; border: none; background: transparent; font-weight: 700; font-size: 0.9rem; color: #2d3748; outline: none; }
    
    .btn-delete { color: #d81b60; background: #fff0f5; transition: 0.2s; border: 1px solid #ffe4e1; }
    .btn-delete:hover { background: #d81b60; color: #fff; }
    
    .checkout-btn { background: linear-gradient(135deg, #ff1493 0%, #d81b60 100%); color: white; border: none; transition: 0.3s; }
    .checkout-btn:hover:not(:disabled) { box-shadow: 0 15px 25px rgba(216, 27, 96, 0.3); color: white; }
    
    .voucher-box { background: #fff0f5; border: 2px dashed #ffb6c1; border-radius: 16px; transition: 0.3s; }
    .voucher-box:focus-within { border-color: #d81b60; background: #fff; }
</style>

<div class="korean-watermark">
    <span>하우스!</span>
    <span style="font-size: 8vw;">진짜 맛있는</span>
</div>

<div class="content-wrapper pb-5">
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <a href="/" class="btn btn-light rounded-pill px-4 shadow-sm text-dark fw-bold border-0" style="background: rgba(255,255,255,0.8);">
            <i class="bi bi-arrow-left me-2" style="color: #d81b60;"></i> Kembali Belanja
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="d-flex align-items-center mb-4">
                <h3 class="fw-bolder mb-0" style="color: #d81b60;"><i class="bi bi-bag-heart-fill me-2"></i>Keranjang Belanja</h3>
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="badge ms-3 rounded-pill px-3 py-2 text-white shadow-sm" style="background: #ff69b4;">{{ count(session('cart')) }} Pilihan Menu</span>
                @endif
            </div>
            
            <div class="card cart-card p-2 p-md-4">
                <div class="card-body p-0 table-responsive" style="scrollbar-width: thin;">
                    <table class="table table-cart table-borderless mb-0">
                        <thead>
                            <tr>
                                <th class="px-3" style="width: 45%;">Menu Pesanan</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0 @endphp
                            
                            @if(session('cart') && count(session('cart')) > 0)
                                @foreach(session('cart') as $id => $details)
                                    @php 
                                        $total += $details['price'] * $details['qty'];
                                        $stokAsli = \App\Models\Produk::find($id)->stok ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="px-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $details['foto'] ? asset('img/'.$details['foto']) : 'https://via.placeholder.com/50' }}" width="65" height="65" class="product-img-cart me-3">
                                                <div>
                                                    <span class="fw-bolder d-block text-dark" style="font-size: 1.05rem;">{{ $details['name'] }}</span>
                                                    <span class="badge bg-white text-secondary border mt-1" style="font-size: 0.7rem; border-color: #ffe4e1 !important;">
                                                        Sisa: <b style="color: #d81b60;">{{ $stokAsli - $details['qty'] }}</b>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-semibold" style="color: #a0aec0;">
                                            Rp {{ number_format($details['price'], 0, ',', '.') }}
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="qty-wrapper shadow-sm">
                                                <button class="qty-btn update-cart-btn" data-id="{{ $id }}" data-stok="{{ $stokAsli }}" data-type="minus">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="text" class="qty-input" value="{{ $details['qty'] }}" readonly>
                                                <button class="qty-btn update-cart-btn {{ $details['qty'] >= $stokAsli ? 'disabled' : '' }}" data-id="{{ $id }}" data-stok="{{ $stokAsli }}" data-type="plus" {{ $details['qty'] >= $stokAsli ? 'disabled' : '' }}>
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        
                                        <td class="text-end fw-bolder text-dark fs-6">
                                            Rp {{ number_format($details['price'] * $details['qty'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center px-3">
                                            <a href="/keranjang/hapus/{{ $id }}" class="btn btn-sm btn-delete rounded-circle p-2 shadow-sm" title="Hapus Produk">
                                                <i class="bi bi-trash3-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="my-4">
                                            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                                <i class="bi bi-bag-x fs-1 opacity-50" style="color: #ffb6c1;"></i>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-2">Keranjang Masih Kosong</h5>
                                            <p class="text-muted small mb-4">Yuk, tambahin racikan minuman kesukaan oppa dulu!</p>
                                            <a href="/" class="btn rounded-pill px-5 py-2 fw-bold shadow-sm text-white" style="background: #ff69b4;">Lihat Katalog</a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @php
            $diskonVoucher = 0;
            if(session('voucher')) {
                if(session('voucher')['tipe'] == 'potongan') {
                    $diskonVoucher = session('voucher')['nominal'];
                } else {
                    $diskonVoucher = ($total * session('voucher')['nominal']) / 100;
                }
            }
            $totalAkhir = $total - $diskonVoucher;
            if($totalAkhir < 0) $totalAkhir = 0;
        @endphp

        <div class="col-lg-4">
            <div class="card summary-card mt-2 mt-lg-5">
                <div class="card-body p-4 p-xl-5">
                    <h5 class="fw-bolder mb-4" style="color: #d81b60;">Ringkasan Pesanan</h5>

                    <div class="voucher-box p-3 mb-4 shadow-sm">
                        <form action="/keranjang/voucher" method="POST" class="m-0">
                            @csrf
                            <label class="fw-bold mb-2 small" style="color: #d81b60;"><i class="bi bi-ticket-perforated me-1"></i> Pakai Kode Promo?</label>
                            <div class="input-group">
                                <input type="text" name="kode_voucher" class="form-control rounded-start-pill px-4 border-end-0 shadow-none" placeholder="Cth: HAUSPUAS" style="font-weight: 600; text-transform: uppercase;" {{ session('voucher') ? 'disabled' : '' }} value="{{ session('voucher')['kode'] ?? '' }}" required>
                                @if(session('voucher'))
                                    <a href="/keranjang/voucher/hapus" class="btn btn-dark rounded-end-pill px-4 fw-bold">Hapus</a>
                                @else
                                    <button type="submit" class="btn rounded-end-pill px-4 fw-bold text-white" style="background: #d81b60;">Klaim</button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted fw-semibold">Subtotal</span>
                            <span class="fw-bold text-dark">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                        </div>
                        
                        @if(session('voucher'))
                        <div class="d-flex justify-content-between mb-2 align-items-center">
                            <span class="fw-semibold small" style="color: #ff1493;">
                                <i class="bi bi-tags-fill me-1"></i> Promo ({{ session('voucher')['kode'] }})
                            </span>
                            <span class="fw-bolder" style="color: #ff1493;">- Rp {{ number_format($diskonVoucher, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between mb-4 border-top pt-3 align-items-center" style="border-color: #ffe4e1 !important;">
                        <span class="fw-bolder" style="color: #a0aec0;">TOTAL BAYAR</span>
                        <h3 class="fw-bolder mb-0" style="color: #d81b60; letter-spacing: -0.5px;">Rp {{ number_format($totalAkhir, 0, ',', '.') }}</h3>
                    </div>

                    <form action="/checkout" method="POST" id="formCheckout">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="fw-bolder mb-2 small text-dark">Tipe Pesanan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 rounded-start-pill ps-3" style="color: #ffb6c1;"><i class="bi bi-shop-window"></i></span>
                                <select name="tipe_pesanan" id="tipePesanan" class="form-select rounded-end-pill px-3 border-0 shadow-sm fw-semibold text-dark" required>
                                    <option value="Dine-in">Makan di Tempat (Dine-in)</option>
                                    <option value="Takeaway">Bawa Pulang (Takeaway)</option>
                                    <option value="Pre-order">Pre-Order (Ambil di Toko)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bolder mb-2 small text-dark">Nama Pemesan / No. Meja</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 rounded-start-pill ps-3" style="color: #ffb6c1;"><i class="bi bi-person-badge"></i></span>
                                <input type="text" name="nama_customer" class="form-control rounded-end-pill px-3 border-0 shadow-sm fw-semibold text-dark" placeholder="Contoh: Meja 04 / Oppa Budi" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="fw-bolder mb-2 small text-dark">Metode Pembayaran</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 rounded-start-pill ps-3" style="color: #ffb6c1;"><i class="bi bi-wallet2"></i></span>
                                <select name="metode_pembayaran" id="metodePembayaran" class="form-select rounded-end-pill px-3 border-0 shadow-sm fw-semibold text-dark" required>
                                    <option value="Cash">Tunai / Cash di Kasir</option>
                                    <option value="QRIS">QRIS (Gopay/OVO/Dana)</option>
                                    <option value="Debit">Kartu Debit</option>
                                </select>
                            </div>
                        </div>
                        
                        <input type="hidden" name="total_akhir_diskon" value="{{ $totalAkhir }}">
                        
                        <button type="submit" class="btn checkout-btn w-100 rounded-pill fw-bold py-3 text-uppercase d-flex justify-content-center align-items-center" {{ !session('cart') || count(session('cart')) == 0 ? 'disabled' : '' }}>
                            Proses Transaksi
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-center small mt-4 px-3" style="color: #000000;"><i class="bi bi-shield-lock me-1"></i> Transaksi dijamin aman, nyaman, dan estetik.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $(".update-cart-btn").click(function (e) {
            e.preventDefault();
            var ele = $(this);
            var id = ele.attr("data-id");
            var type = ele.attr("data-type");
            var maxStok = parseInt(ele.attr("data-stok"));
            var input = ele.closest('td').find(".qty-input");
            var currentVal = parseInt(input.val());
            var newVal = (type == "plus") ? currentVal + 1 : currentVal - 1;

            if (newVal < 1) return;
            if (newVal > maxStok) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Omo! Stok Terbatas',
                    text: 'Maksimal pembelian buat menu ini cuma ' + maxStok + ' porsi ya.',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                });
                return;
            }

            ele.prop('disabled', true);

            $.ajax({
                url: '{{ route("cart.update") }}',
                method: "patch",
                data: { _token: '{{ csrf_token() }}', id: id, quantity: newVal },
                success: function (response) {
                    if(response.success == false) {
                        alert(response.message);
                        ele.prop('disabled', false);
                    } else {
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Gagal update keranjang!');
                    ele.prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush

<div class="modal fade" id="modalQris" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 20px; border: 4px solid #fff0f5; box-shadow: 0 20px 50px rgba(255, 105, 180, 0.2);">
            <div class="modal-header border-0 pb-0 justify-content-center position-relative mt-3">
                <h5 class="modal-title fw-bolder" style="color: #d81b60;">Scan untuk Bayar</h5>
                <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4 pt-2 pb-4">
                <p class="text-muted small mb-4">Arahkan eonni/oppa untuk scan QR Code menggunakan E-Wallet pilihan mereka.</p>
                <div class="p-2 border rounded-4 mb-4 shadow-sm" style="background: #fff; border-color: #ffe4e1 !important;">
                    <img src="https://via.placeholder.com/250x250/ffb6c1/ffffff?text=QRIS+Toko+Haus" alt="QRIS Toko" class="img-fluid rounded-3">
                </div>
                <p class="fw-semibold small mb-1" style="color: #ff69b4;">Total Tagihan</p>
                <h3 class="fw-bolder mb-4" style="color: #d81b60;">Rp {{ number_format($totalAkhir ?? 0, 0, ',', '.') }}</h3>
                
                <button type="button" class="btn w-100 rounded-pill py-3 fw-bold d-flex justify-content-center align-items-center text-white" id="btnKonfirmasiQris" style="background: #d81b60;">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i> Konfirmasi Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formCheckout').addEventListener('submit', function(e) {
        let metode = document.getElementById('metodePembayaran').value;
        if(metode === 'QRIS') {
            e.preventDefault(); 
            var myModal = new bootstrap.Modal(document.getElementById('modalQris'));
            myModal.show();
        }
    });
    document.getElementById('btnKonfirmasiQris').addEventListener('click', function() {
        document.getElementById('formCheckout').submit();
    });
</script>
@endsection