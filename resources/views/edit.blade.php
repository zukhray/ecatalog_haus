@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-3"><a href="/" class="btn btn-outline-secondary shadow-sm rounded-pill px-4"><i class="bi bi-arrow-left"></i> Kembali ke katalog</a></div>
        
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-warning text-dark text-center py-3" style="border-radius: 15px 15px 0 0;">
                <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square"></i> Kelola Produk</h4>
            </div>
            
            <div class="card-body p-4">
                <form action="/update/{{ $produk->id_produk }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" class="form-control bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="kategori" class="form-control bg-light" required>
                                <option value="Choco Series" {{ $produk->kategori == 'Choco Series' ? 'selected' : '' }}>Choco Series</option>
                                <option value="Tea Series" {{ $produk->kategori == 'Tea Series' ? 'selected' : '' }}>Tea Series</option>
                                <option value="Classic Series" {{ $produk->kategori == 'Classic Series' ? 'selected' : '' }}>Classic Series</option>
                                <option value="Pudding" {{ $produk->kategori == 'Pudding' ? 'selected' : '' }}>Pudding</option>
                                <option value="Hotoppa" {{ $produk->kategori == 'Hotoppa' ? 'selected' : '' }}>Hotoppa</option>
                                <option value="Sidedish" {{ $produk->kategori == 'Sidedish' ? 'selected' : '' }}>Sidedish</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control bg-light" rows="3">{{ $produk->deskripsi }}</textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Harga Asli (Rp)</label>
                            <input type="number" name="harga" value="{{ $produk->harga }}" class="form-control bg-light" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Diskon (Rp)</label>
                            <input type="number" name="diskon" value="{{ $produk->diskon }}" class="form-control bg-light">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Stok Saat Ini</label>
                            <input type="number" name="stok" value="{{ $produk->stok }}" min="0" class="form-control bg-light" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold d-block">Status Best Seller</label>
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input" type="checkbox" name="bestseller" value="1" id="switchBestSeller" {{ $produk->bestseller ? 'checked' : '' }}>
                            <label class="form-check-label fs-6 mt-1" for="switchBestSeller">Tandai sebagai terlaris</label>
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded border">
                        <label class="form-label fw-bold">Ganti Foto <small class="text-muted fw-normal">*Kosongkan jika tidak ingin ganti</small></label>
                        @if($produk->foto)
                            <div class="mb-2"><img src="{{ asset('img/'.$produk->foto) }}" class="rounded shadow-sm" style="height: 100px; object-fit: cover;"></div>
                        @endif
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow-sm rounded-pill text-uppercase">
                        <i class="bi bi-check2-circle"></i> Update Produk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection