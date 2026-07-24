<div class="card api-intro">
<h3>REST API Toko Madura Digital</h3>
<p>Base URL lokal: <code><?= base_url('api') ?></code>. Semua endpoint selain login memakai header <code>Authorization: Bearer TOKEN</code>.</p>
</div>
<div class="api-grid">
    <div class="card endpoint"><span class="method post">POST</span><code>/api/login</code><h4>Login pelanggan</h4><pre>{
  "username": "pelanggan",
  "password": "pelanggan123",
  "device_name": "Android"
}</pre></div>
    <div class="card endpoint"><span class="method get">GET</span><code>/api/profile</code><h4>Profil pengguna</h4><p>Mengambil data akun yang sedang login.</p></div>
    <div class="card endpoint"><span class="method get">GET</span><code>/api/barang</code><h4>Katalog produk</h4><p>Filter opsional: <code>?q=beras&amp;kategori_id=1</code>.</p></div>
    <div class="card endpoint"><span class="method get">GET</span><code>/api/kategori</code><h4>Kategori produk</h4><p>Mengambil semua kategori toko.</p></div>
    <div class="card endpoint"><span class="method post">POST</span><code>/api/pesanan</code><h4>Checkout</h4><pre>{
  "items": [
    {"barang_id": 1, "qty": 2},
    {"barang_id": 4, "qty": 1}
  ],
  "catatan": "Bayar tunai"
}</pre></div>
    <div class="card endpoint"><span class="method get">GET</span><code>/api/pesanan</code><h4>Riwayat pesanan</h4><p>Hanya menampilkan pesanan milik pengguna yang login.</p></div>
    <div class="card endpoint"><span class="method get">GET</span><code>/api/pesanan/{id}</code><h4>Detail pesanan</h4><p>Mengambil rincian item dan total transaksi.</p></div>
    <div class="card endpoint"><span class="method post">POST</span><code>/api/logout</code><h4>Logout</h4><p>Mencabut token pada perangkat.</p></div>
</div>
<div class="card code-card"><h3>Header Retrofit</h3><pre>Authorization: Bearer TOKEN_DARI_LOGIN
Content-Type: application/json
Accept: application/json</pre></div>
