<form action="/pesan/simpan" method="POST">
    @csrf

    <h4>{{ $produk->nama_produk }}</h4>

    <input type="hidden" name="nama_produk" value="{{ $produk->nama_produk }}">
    
    <input type="hidden" name="harga" value="{{ $produk->harga - $produk->diskon }}">

    <p>Harga Satuan: Rp {{ number_format($produk->harga - $produk->diskon, 0, ',', '.') }}</p>

    <input type="text" name="nama_customer" placeholder="Nama Customer" required><br>
    <input type="number" name="jumlah" placeholder="Jumlah" required><br>

    <button type="submit">Pesan</button>
</form>