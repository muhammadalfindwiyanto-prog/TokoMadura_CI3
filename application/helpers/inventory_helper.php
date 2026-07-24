<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('rupiah')) {
    function rupiah($value) {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }
}

if (!function_exists('stok_badge')) {
    function stok_badge($stok, $minimum = 5) {
        if ((int) $stok <= 0) return '<span class="badge badge-danger">Habis</span>';
        if ((int) $stok <= (int) $minimum) return '<span class="badge badge-warning">Menipis</span>';
        return '<span class="badge badge-success">Tersedia</span>';
    }
}

if (!function_exists('foto_barang_url')) {
    function foto_barang_url($foto = null) {
        if ($foto && file_exists(FCPATH . 'uploads/barang/' . $foto)) {
            return base_url('uploads/barang/' . rawurlencode($foto));
        }
        return base_url('assets/img/no-image.svg');
    }
}


if (!function_exists('status_pesanan_badge')) {
    function status_pesanan_badge($status) {
        $map = array(
            'baru' => array('badge-warning', 'Baru'),
            'diproses' => array('badge-neutral', 'Diproses'),
            'selesai' => array('badge-success', 'Selesai'),
            'dibatalkan' => array('badge-danger', 'Dibatalkan')
        );
        $item = isset($map[$status]) ? $map[$status] : array('badge-neutral', ucfirst($status));
        return '<span class="badge '.$item[0].'">'.$item[1].'</span>';
    }
}
