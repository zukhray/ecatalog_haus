@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-3"><a href="/" class="btn btn-outline-secondary shadow-sm rounded-pill px-4"><i class="bi bi-arrow-left"></i> Kembali</a></div>
        
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 15px 15px 0 0;">
                <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle"></i> Tambah Produk Baru</h4>
            </div>
            
            <div class="card-body p-4">
                <form action="/simpan" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="kategori" class="form-control bg-light" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Choco Series">Choco Series</option>
                                <option value="Tea Series">Tea Series</option>
                                <option value="Classic Series">Classic Series</option>
                                <option value="Pudding">Pudding</option>
                                <option value="Hotoppa">Hotoppa</option>
                                <option value="Sidedish">Sidedish</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control bg-light" rows="3" placeholder="Masukkan detail minuman/makanan ini..."></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Harga Asli (Rp)</label>
                            <input type="text" name="harga" class="form-control bg-light input-rupiah" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Diskon (Rp)</label>
                            <input type="text" name="diskon" value="0" class="form-control bg-light input-rupiah">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Stok Awal</label>
                            <input type="number" name="stok" value="0" min="0" class="form-control bg-light" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold d-block">Status Best Seller</label>
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input" type="checkbox" name="bestseller" value="1" id="switchBestSeller">
                            <label class="form-check-label fs-6 mt-1" for="switchBestSeller">Tandai sebagai terlaris</label>
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded border">
                        <label class="form-label fw-bold">Upload Foto Produk</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-pill text-uppercase">
                        <i class="bi bi-save"></i> Simpan Produk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi buat nambahin titik tiap ngetik
    let rupiahInputs = document.querySelectorAll('.input-rupiah');
    
    rupiahInputs.forEach(function(input) {
        input.addEventListener('keyup', function(e) {
            // Hapus karakter selain angka
            let val = this.value.replace(/[^0-9]/g, '');
            // Kasih titik tiap 3 angka
            this.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        });
    });

    // Pas form disubmit, hapus semua titiknya biar database gak error
    document.querySelector('form').addEventListener('submit', function() {
        rupiahInputs.forEach(function(input) {
            input.value = input.value.replace(/\./g, ''); 
        });
    });
</script>
@endsection