@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #fff0f5 0%, #ffe4e1 100%) !important;
        min-height: 100vh;
    }
    .cart-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        background: white;
    }
    .cart-item {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s;
    }
    .cart-item:hover {
        background: #fff0f5;
        border-radius: 12px;
    }
    .cart-img {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
    }
    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: #fff0f5;
        color: #d81b60;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .qty-btn:hover {
        background: #d81b60;
        color: white;
    }
    .summary-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        background: white;
        position: sticky;
        top: 20px;
    }
    .checkout-btn {
        background: linear-gradient(135deg, #d81b60, #ff69b4);
        border: none;
        border-radius: 15px;
        padding: 15px;
        font-weight: bold;
        font-size: 1rem;
        transition: all 0.3s;
        color: white;
        width: 100%;
    }
    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(216, 27, 96, 0.3);
    }
    .checkout-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .tipe-btn {
        padding: 10px;
        border-radius: 12px;
        border: 2px solid #ffe4e1;
        background: white;
        color: #666;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        font-size: 0.85rem;
        flex: 1;
    }
    .tipe-btn.active {
        border-color: #d81b60;
        background: #fff0f5;
        color: #d81b60;
        box-shadow: 0 4px 12px rgba(216, 27, 96, 0.15);
    }
    .tipe-btn:hover {
        border-color: #ff69b4;
    }
    .variant-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 600;
        background: #fff0f5;
        color: #d81b60;
        border: 1px solid #ffe4e1;
    }
    .variant-badge:empty {
        display: none;
    }
    .form-section {
        background: #fafafa;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 12px;
    }
    
    .section-meja { display: block; }
    .section-nama { display: none; }
    .section-preorder { display: none; }
    
    .kode-preorder {
        background: linear-gradient(135deg, #d81b60, #ff69b4);
        color: white;
        padding: 12px 20px;
        border-radius: 15px;
        text-align: center;
        font-size: 1.3rem;
        font-weight: bold;
        letter-spacing: 2px;
        margin-bottom: 15px;
    }
    .payment-option {
        padding: 10px 15px;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        background: white;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        flex: 1;
    }
    .payment-option:hover {
        border-color: #ff69b4;
    }
    .payment-option.selected {
        border-color: #d81b60;
        background: #fff0f5;
    }
    .payment-option input {
        display: none;
    }
    .payment-option i {
        font-size: 1.5rem;
        margin-bottom: 4px;
        display: block;
    }
    .payment-option small {
        display: block;
        font-weight: 600;
    }
    .payment-disabled {
        opacity: 0.4;
        pointer-events: none;
        filter: grayscale(1);
    }
    
    /* QRIS Modal */
    .qris-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(5px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .qris-overlay.show {
        display: flex;
    }
    .qris-box {
        background: white;
        border-radius: 24px;
        padding: 30px;
        max-width: 380px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: popIn 0.3s ease;
    }
    @keyframes popIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .qris-placeholder {
        width: 180px;
        height: 180px;
        background: #fff0f5;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 20px auto;
        border: 2px dashed #ffb6c1;
    }
    .qris-total {
        font-size: 1.5rem;
        font-weight: 800;
        color: #d81b60;
        margin: 10px 0;
    }
    .btn-confirm {
        background: linear-gradient(135deg, #d81b60, #ff69b4);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 12px 40px;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(216, 27, 96, 0.3);
    }
    .btn-batal {
        background: transparent;
        color: #999;
        border: none;
        padding: 10px;
        margin-top: 10px;
        cursor: pointer;
        font-size: 0.9rem;
    }
    .error-shake {
        animation: shake 0.5s;
        border-color: #ff5252 !important;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>

<div class="container pb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #d81b60;">
                <i class="bi bi-bag-heart me-2"></i>Keranjang Belanja
            </h3>
            <p class="text-muted mb-0">{{ count($cart) }} item dalam keranjang</p>
        </div>
        <a href="/" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Lanjutkan Belanja
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card cart-card p-4">
                <h5 class="fw-bold mb-4" style="color: #d81b60;">Menu Pesanan</h5>
                
                @forelse($cart as $id => $item)
                <div class="cart-item d-flex align-items-center gap-3">
                    <img src="{{ $item['foto'] ? asset('img/'.$item['foto']) : 'https://via.placeholder.com/60' }}" class="cart-img" alt="{{ $item['name'] }}">
                    
                    <div class="flex-grow-1">
                        <div class="fw-bold mb-1">{{ $item['name'] }}</div>
                        
                        <div class="mb-1">
                            @php
                                $variantHtml = '';
                                if (isset($item['sugar_option']) && $item['sugar_option'] != 'Normal' && $item['sugar_option'] != 'Normal Sugar') {
                                    $variantHtml .= '<span class="variant-badge me-1"><i class="bi bi-droplet"></i>' . $item['sugar_option'] . '</span>';
                                }
                                if (isset($item['ice_option']) && $item['ice_option'] != 'Normal' && $item['ice_option'] != 'Normal Ice') {
                                    $variantHtml .= '<span class="variant-badge me-1"><i class="bi bi-snow"></i>' . $item['ice_option'] . '</span>';
                                }
                                if (isset($item['spicy_option']) && $item['spicy_option'] != 'Normal' && $item['spicy_option'] != 'Tidak Pedas') {
                                    $variantHtml .= '<span class="variant-badge"><i class="bi bi-fire"></i>' . $item['spicy_option'] . '</span>';
                                }
                            @endphp
                            {!! $variantHtml !!}
                        </div>
                        
                        <div class="text-muted small">Rp {{ number_format($item['price'], 0, ',', '.') }} / pcs</div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2">
                        <a href="/keranjang/hapus/{{ $id }}?action=minus" class="qty-btn">
                            <i class="bi bi-dash"></i>
                        </a>
                        <span class="fw-bold" style="min-width: 30px; text-align: center;">{{ $item['qty'] }}</span>
                        <a href="/keranjang/tambah/{{ $id }}" class="qty-btn">
                            <i class="bi bi-plus"></i>
                        </a>
                    </div>
                    
                    <div class="fw-bold" style="color: #d81b60; min-width: 100px; text-align: right;">
                        Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                    </div>
                    
                    <a href="/keranjang/hapus/{{ $id }}" class="btn btn-link text-danger p-1" onclick="return confirm('Hapus item ini?')">
                        <i class="bi bi-trash-fill"></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                    <p class="text-muted">Keranjang masih kosong</p>
                    <a href="/" class="btn rounded-pill px-4 text-white" style="background: #d81b60;">Mulai Belanja</a>
                </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card summary-card p-4">
                <h5 class="fw-bold mb-4" style="color: #d81b60;">
                    <i class="bi bi-clipboard-check me-2"></i>Ringkasan Pesanan
                </h5>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Pakai Kode Promo?</label>
                    <form action="/keranjang/voucher" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="kode_voucher" class="form-control rounded-pill" placeholder="CTH: HAUSPAS" value="{{ session('voucher.kode') }}">
                        <button type="submit" class="btn rounded-pill px-3 fw-bold text-white" style="background: #d81b60; font-size: 0.8rem;">Klaim</button>
                    </form>
                    @if(session('voucher'))
                        <div class="small text-success mt-1">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            {{ session('voucher.kode') }} aktif 
                            ({{ session('voucher.tipe') == 'persen' ? session('voucher.nominal').'%' : 'Rp '.number_format(session('voucher.nominal'), 0, ',', '.') }})
                            <a href="/keranjang/voucher/hapus" class="text-danger ms-2"><i class="bi bi-x-circle"></i></a>
                        </div>
                    @endif
                </div>

                <hr class="my-3">

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted d-block mb-2">Tipe Pesanan</label>
                    <div class="d-flex gap-2">
                        <button type="button" class="tipe-btn active" onclick="setTipe('dine_in')" id="btnDineIn">
                            <i class="bi bi-shop d-block mb-1 fs-5"></i>
                            <small>Dine-in</small>
                        </button>
                        <button type="button" class="tipe-btn" onclick="setTipe('takeaway')" id="btnTakeaway">
                            <i class="bi bi-bag d-block mb-1 fs-5"></i>
                            <small>Takeaway</small>
                        </button>
                        <button type="button" class="tipe-btn" onclick="setTipe('preorder')" id="btnPreorder">
                            <i class="bi bi-phone d-block mb-1 fs-5"></i>
                            <small>Pre-Order</small>
                        </button>
                    </div>
                </div>

                <form action="/checkout" method="POST" id="checkoutForm">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3 mb-3 shadow-sm p-2 small">
                            <strong class="text-danger d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal Proses (Backend Error):</strong>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <input type="hidden" name="tipe_pesanan" id="tipePesanan" value="dine_in">
                    <input type="hidden" name="total_akhir_diskon" value="{{ $total }}">

                    <div id="mejaSection" class="form-section" style="display: block;">
    <label class="form-label fw-bold small text-muted">
        <i class="bi bi-geo-alt me-1"></i>Nomor Meja <span class="text-danger">*</span>
    </label>
    <select name="no_meja" class="form-select rounded-pill" id="selectMeja">
        <option value="">Pilih Meja</option>
        @for($i = 1; $i <= 20; $i++)
            <option value="Meja {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">Meja {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
        @endfor
    </select>
    <div class="small text-danger mt-1" id="errorMeja" style="display: none;">
        <i class="bi bi-exclamation-circle me-1"></i>Pilih nomor meja dulu!
    </div>
</div>

<div id="namaSection" class="form-section" style="display: none;">
    <label class="form-label fw-bold small text-muted">
        <i class="bi bi-person me-1"></i>Nama Customer <span class="text-danger">*</span>
    </label>
    <input type="text" name="nama_customer" class="form-control rounded-pill" placeholder="Contoh: Budi Santoso" id="inputNama">
    <div class="small text-danger mt-1" id="errorNama" style="display: none;">
        <i class="bi bi-exclamation-circle me-1"></i>Isi nama customer dulu!
    </div>
</div>

                    <div id="preorderSection" class="form-section" style="display: none;">
                        @php
                            $lastOrder = \App\Models\Pesanan::where('tipe_pesanan', 'preorder')
                                ->whereDate('created_at', today())
                                ->latest()
                                ->first();
                            $nextNum = 1;
                            if ($lastOrder && $lastOrder->kode_preorder) {
                                $lastNum = (int) str_replace('#', '', $lastOrder->kode_preorder);
                                $nextNum = $lastNum + 1;
                            }
                            $kodePreorder = '#' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
                        @endphp
                        <div class="kode-preorder">
                            <i class="bi bi-ticket-perforated me-2"></i>
                            <span>{{ $kodePreorder }}</span>
                        </div>
                        <input type="hidden" name="kode_preorder" value="{{ $kodePreorder }}">
                        <input type="hidden" name="nama_customer" value="Pre-Order {{ $kodePreorder }}" id="inputPreorder">
                    </div>

                    <div class="form-section">
                        <label class="form-label fw-bold small text-muted">
                            <i class="bi bi-chat-text me-1"></i>Catatan (Opsional)
                        </label>
                        <textarea name="catatan" class="form-control rounded-3" rows="2" placeholder="Catatan khusus..."></textarea>
                    </div>

                    <div class="form-section" id="paymentSection">
                        <label class="form-label fw-bold small text-muted d-block mb-2" id="labelPayment">
                            <i class="bi bi-wallet2 me-1"></i>Metode Pembayaran
                        </label>
                        
                        <div class="d-flex gap-2" id="normalPayments">
                            <label class="payment-option selected" onclick="selectPay(this, 'cash')">
                                <input type="radio" name="metode_pembayaran" value="cash" checked>
                                <i class="bi bi-cash-coin"></i>
                                <small>Tunai</small>
                            </label>
                            <label class="payment-option" onclick="selectPay(this, 'qris')">
                                <input type="radio" name="metode_pembayaran" value="qris">
                                <i class="bi bi-qr-code"></i>
                                <small>QRIS</small>
                            </label>
                            <label class="payment-option" onclick="selectPay(this, 'debit')">
                                <input type="radio" name="metode_pembayaran" value="debit">
                                <i class="bi bi-credit-card"></i>
                                <small>Debit</small>
                            </label>
                        </div>
                        
                        <div id="qrisOnly" style="display: none;">
                            <input type="hidden" name="metode_pembayaran" value="qris" id="inputQrisOnly">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($diskon > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Diskon</span>
                        <span class="fw-bold">- Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">TOTAL BAYAR</span>
                        <span class="fw-bold fs-4" style="color: #d81b60;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <button type="button" class="checkout-btn" id="btnCheckout" onclick="prosesCheckout()">
                        <i class="bi bi-bag-check-fill me-2"></i>PROSES TRANSAKSI
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<div class="qris-overlay" id="qrisModal">
    <div class="qris-box">
        <h4 class="fw-bold mb-2" style="color: #d81b60;">
            <i class="bi bi-qr-code-scan me-2"></i>Scan QRIS
        </h4>
        <p class="text-muted small">Scan untuk menyelesaikan pembayaran</p>
        
        <div class="qris-placeholder">
            <i class="bi bi-qr-code" style="font-size: 4rem; color: #d81b60;"></i>
        </div>
        
        <div class="text-muted small mb-1">Total Tagihan</div>
        <div class="qris-total">Rp {{ number_format($total, 0, ',', '.') }}</div>
        
        <button class="btn-confirm" onclick="submitForm()">
            <i class="bi bi-check-circle-fill me-2"></i>Konfirmasi Pembayaran
        </button>
        <br>
        <button class="btn-batal" onclick="closeQris()">Batal</button>
    </div>
</div>

<script>
    let currentTipe = 'dine_in';
    let currentMetode = 'cash';
    
    function setTipe(tipe) {
        currentTipe = tipe;
        document.getElementById('tipePesanan').value = tipe;
        
        document.getElementById('btnDineIn').classList.remove('active');
        document.getElementById('btnTakeaway').classList.remove('active');
        document.getElementById('btnPreorder').classList.remove('active');
        
        const mejaSection = document.getElementById('mejaSection');
        const namaSection = document.getElementById('namaSection');
        const preorderSection = document.getElementById('preorderSection');
        const normalPayments = document.getElementById('normalPayments');
        const qrisOnly = document.getElementById('qrisOnly');
        const paymentSection = document.getElementById('paymentSection');
        const labelPayment = document.getElementById('labelPayment');

        const inputMeja = document.getElementById('selectMeja');
        const inputNama = document.getElementById('inputNama');
        const inputPreorder = document.getElementById('inputPreorder');
        const inputQrisOnly = document.getElementById('inputQrisOnly');
        
        mejaSection.style.display = 'none';
        namaSection.style.display = 'none';
        preorderSection.style.display = 'none';
        normalPayments.style.display = 'none';
        qrisOnly.style.display = 'none';

        inputMeja.disabled = true;
        inputNama.disabled = true;
        if(inputPreorder) inputPreorder.disabled = true;
        if(inputQrisOnly) inputQrisOnly.disabled = true;
        
        document.getElementById('errorMeja').style.display = 'none';
        document.getElementById('errorNama').style.display = 'none';
        
        inputMeja.value = '';
        inputNama.value = '';
        
        if (tipe === 'dine_in') {
            document.getElementById('btnDineIn').classList.add('active');
            mejaSection.style.display = 'block';
            inputMeja.disabled = false;

            paymentSection.style.display = 'block';
            normalPayments.style.display = 'flex';
            labelPayment.innerHTML = '<i class="bi bi-wallet2 me-1"></i>Metode Pembayaran';
            currentMetode = 'cash';
            
            document.querySelector('input[name="metode_pembayaran"][value="cash"]').checked = true;
            document.querySelectorAll('#normalPayments .payment-option').forEach(opt => opt.classList.remove('selected'));
            document.querySelector('#normalPayments .payment-option:first-child').classList.add('selected');
            
        } else if (tipe === 'takeaway') {
            document.getElementById('btnTakeaway').classList.add('active');
            namaSection.style.display = 'block';
            inputNama.disabled = false;

            paymentSection.style.display = 'block';
            normalPayments.style.display = 'flex';
            labelPayment.innerHTML = '<i class="bi bi-wallet2 me-1"></i>Metode Pembayaran';
            currentMetode = 'cash';
            
            document.querySelector('input[name="metode_pembayaran"][value="cash"]').checked = true;
            document.querySelectorAll('#normalPayments .payment-option').forEach(opt => opt.classList.remove('selected'));
            document.querySelector('#normalPayments .payment-option:first-child').classList.add('selected');
            
        } else if (tipe === 'preorder') {
            document.getElementById('btnPreorder').classList.add('active');
            preorderSection.style.display = 'block';
            if(inputPreorder) inputPreorder.disabled = false;

            paymentSection.style.display = 'none';
            qrisOnly.style.display = 'block';
            if(inputQrisOnly) inputQrisOnly.disabled = false;
            currentMetode = 'qris';
        }
    }
    
    function selectPay(label, metode) {
        currentMetode = metode;
        document.querySelectorAll('#normalPayments .payment-option').forEach(opt => opt.classList.remove('selected'));
        label.classList.add('selected');
        label.querySelector('input').checked = true;
    }
    
    function validateForm() {
        let isValid = true;
        document.getElementById('errorMeja').style.display = 'none';
        document.getElementById('errorNama').style.display = 'none';
        
        if (currentTipe === 'dine_in') {
            const meja = document.getElementById('selectMeja').value;
            if (!meja) {
                document.getElementById('errorMeja').style.display = 'block';
                isValid = false;
            }
        }
        
        if (currentTipe === 'takeaway') {
            const nama = document.getElementById('inputNama').value.trim();
            if (!nama) {
                document.getElementById('errorNama').style.display = 'block';
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    function prosesCheckout() {
        if (!validateForm()) {
            return;
        }
        
        if (currentTipe === 'preorder') {
            document.getElementById('qrisModal').classList.add('show');
            return;
        }
        
        if (currentMetode === 'qris') {
            document.getElementById('qrisModal').classList.add('show');
        } else {
            submitForm();
        }
    }
    
    function submitForm() {
        document.getElementById('checkoutForm').submit();
    }
    
    function closeQris() {
        document.getElementById('qrisModal').classList.remove('show');
    }
    
    document.getElementById('qrisModal').addEventListener('click', function(e) {
        if (e.target === this) closeQris();
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        setTipe('dine_in');
    });
</script>

@endsection