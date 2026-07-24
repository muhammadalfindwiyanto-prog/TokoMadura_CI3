# Penjelasan Sistem Toko Madura Digital

## Konsep
Sistem ini adalah kasir/pemesanan sederhana untuk tugas UAS. Website CodeIgniter 3 digunakan admin toko, sedangkan aplikasi Android digunakan pelanggan sebagai client.

## Fitur Website Admin
- Login admin
- Dashboard ringkas
- CRUD kategori
- CRUD produk dan stok
- Daftar pesanan dari Android
- Detail item pesanan
- Perubahan status: Baru, Diproses, Selesai, Dibatalkan
- Stok otomatis kembali jika pesanan dibatalkan
- Dokumentasi REST API

## Fitur Android
- Login pelanggan
- Katalog produk
- Keranjang belanja
- Perubahan jumlah item
- Checkout pembayaran tunai
- Riwayat dan status pesanan
- Pengaturan alamat server API

## Integrasi
Android mengirim dan mengambil data JSON melalui Retrofit. API CI3 menggunakan Bearer Token. Harga dan stok selalu dihitung ulang di server untuk mencegah manipulasi dari client.

## Database
Tabel utama: users, kategori, barang, pesanan, pesanan_detail, api_tokens.
