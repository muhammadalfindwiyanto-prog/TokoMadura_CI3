# Toko Madura Digital

Proyek tugas sederhana yang terdiri dari website admin CodeIgniter 3, REST API, aplikasi Android Kotlin, dan database MySQL.

## Akun
- Admin web: `admin` / `admin123`
- Client Android: `pelanggan` / `pelanggan123`

## Instalasi Laragon
1. Salin folder `TokoMadura_CI3` ke `C:\laragon\www`.
2. Import `database/toko_madura.sql` lewat phpMyAdmin.
3. Pastikan database pada `application/config/database.php` bernama `toko_madura`.
4. Buka `http://localhost/TokoMadura_CI3/`.
5. Pada emulator Android, base URL bawaan adalah `http://10.0.2.2/TokoMadura_CI3/`.
6. Pada HP fisik, ganti alamat server dari halaman login menjadi IP laptop, contoh `http://192.168.1.10/TokoMadura_CI3/`.

## Alur
Admin mengelola kategori, produk, stok, dan status pesanan di website. Pelanggan login melalui Android, memilih produk, memasukkan ke keranjang, checkout, lalu melihat riwayat pesanan. Stok otomatis berkurang saat checkout dan kembali saat pesanan dibatalkan.
