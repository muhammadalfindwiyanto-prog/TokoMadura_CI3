-- TOKO MADURA DIGITAL - CI3 + ANDROID
-- Import file ini melalui phpMyAdmin.
-- Admin web: admin / admin123
-- Client Android: pelanggan / pelanggan123

CREATE DATABASE IF NOT EXISTS `toko_madura` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `api_tokens`;
DROP TABLE IF EXISTS `pesanan_detail`;
DROP TABLE IF EXISTS `pesanan`;
DROP TABLE IF EXISTS `barang`;
DROP TABLE IF EXISTS `kategori`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `username` VARCHAR(60) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','petugas','pelanggan') NOT NULL DEFAULT 'pelanggan',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login_at` DATETIME NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`), UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `kategori` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `deskripsi` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`), UNIQUE KEY `kategori_nama_unique` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `barang` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode` VARCHAR(30) NOT NULL,
  `nama` VARCHAR(150) NOT NULL,
  `kategori_id` INT UNSIGNED NOT NULL,
  `harga_beli` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `harga_jual` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `stok` INT NOT NULL DEFAULT 0,
  `stok_minimum` INT NOT NULL DEFAULT 5,
  `satuan` VARCHAR(30) NOT NULL DEFAULT 'pcs',
  `lokasi` VARCHAR(100) NULL,
  `deskripsi` TEXT NULL,
  `foto` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `barang_kode_unique` (`kode`),
  KEY `barang_kategori_index` (`kategori_id`),
  CONSTRAINT `barang_kategori_fk` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pesanan` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_pesanan` VARCHAR(40) NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `total` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `status` ENUM('baru','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'baru',
  `metode_pembayaran` VARCHAR(30) NOT NULL DEFAULT 'tunai',
  `catatan` VARCHAR(500) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `pesanan_kode_unique` (`kode_pesanan`),
  KEY `pesanan_user_index` (`user_id`),
  CONSTRAINT `pesanan_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pesanan_detail` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pesanan_id` BIGINT UNSIGNED NOT NULL,
  `barang_id` INT UNSIGNED NULL,
  `nama_barang` VARCHAR(150) NOT NULL,
  `harga` DECIMAL(15,2) NOT NULL,
  `qty` INT NOT NULL,
  `subtotal` DECIMAL(15,2) NOT NULL,
  PRIMARY KEY (`id`), KEY `detail_pesanan_index` (`pesanan_id`), KEY `detail_barang_index` (`barang_id`),
  CONSTRAINT `detail_pesanan_fk` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `detail_barang_fk` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `api_tokens` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `token_hash` CHAR(64) NOT NULL,
  `device_name` VARCHAR(100) NULL,
  `last_used_at` DATETIME NULL,
  `expires_at` DATETIME NOT NULL,
  `revoked_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `api_tokens_hash_unique` (`token_hash`),
  KEY `api_tokens_user_index` (`user_id`),
  CONSTRAINT `api_tokens_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`,`nama`,`username`,`password`,`role`,`is_active`) VALUES
(1,'Administrator Toko','admin','$2y$12$NbXGsLZKRHR0/ixLgiWb2OGot9yDxyRbVRoDm3qX7FgzBhCmOlFlO','admin',1),
(2,'Pelanggan Demo','pelanggan','$2y$12$2ciEcgVGaONFIFgxLTnXc.SHxb17VFJ0pwU/mq03Q/vitNp9zC0hK','pelanggan',1);

INSERT INTO `kategori` (`id`,`nama`,`deskripsi`) VALUES
(1,'Sembako','Kebutuhan pokok harian'),
(2,'Minuman','Minuman kemasan dan seduh'),
(3,'Makanan Ringan','Camilan dan makanan instan'),
(4,'Kebutuhan Rumah','Sabun, deterjen, dan kebutuhan rumah');

INSERT INTO `barang` (`id`,`kode`,`nama`,`kategori_id`,`harga_beli`,`harga_jual`,`stok`,`stok_minimum`,`satuan`,`lokasi`,`deskripsi`,`foto`,`created_at`,`updated_at`) VALUES
(1,'SMB-001','Beras Premium 5 Kg',1,68000,75000,20,5,'pack','Rak A1','Beras premium kemasan 5 kilogram.',NULL,NOW(),NOW()),
(2,'SMB-002','Minyak Goreng 1 Liter',1,16500,19000,35,8,'pcs','Rak A2','Minyak goreng kemasan satu liter.',NULL,NOW(),NOW()),
(3,'SMB-003','Gula Pasir 1 Kg',1,15000,17500,24,6,'pack','Rak A3','Gula pasir putih kemasan satu kilogram.',NULL,NOW(),NOW()),
(4,'MNM-001','Air Mineral 600 ml',2,2500,4000,48,12,'botol','Kulkas B1','Air mineral dingin 600 ml.',NULL,NOW(),NOW()),
(5,'MNM-002','Kopi Sachet',2,1500,2500,60,15,'sachet','Rak B2','Kopi instan berbagai rasa.',NULL,NOW(),NOW()),
(6,'MKN-001','Mi Instan Goreng',3,2800,3500,50,10,'pcs','Rak C1','Mi instan rasa goreng.',NULL,NOW(),NOW()),
(7,'MKN-002','Biskuit Cokelat',3,7000,9000,18,5,'pack','Rak C2','Biskuit isi krim cokelat.',NULL,NOW(),NOW()),
(8,'RMH-001','Sabun Mandi Batang',4,3500,5000,25,6,'pcs','Rak D1','Sabun mandi batang.',NULL,NOW(),NOW()),
(9,'RMH-002','Deterjen Bubuk 800 g',4,17000,20500,16,5,'pack','Rak D2','Deterjen bubuk kemasan 800 gram.',NULL,NOW(),NOW()),
(10,'RMH-003','Tisu Gulung',4,7500,10000,14,4,'roll','Rak D3','Tisu gulung untuk kebutuhan rumah.',NULL,NOW(),NOW());

SET FOREIGN_KEY_CHECKS = 1;
