<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Barang_model', 'Kategori_model', 'Pesanan_model'));
    }

    public function index()
    {
        $data = array(
            'title' => 'Dashboard',
            'active_menu' => 'dashboard',
            'total_barang' => $this->Barang_model->count_all(),
            'total_kategori' => $this->Kategori_model->count_all(),
            'total_stok' => $this->Barang_model->sum_stock(),
            'pesanan_baru' => $this->Pesanan_model->count_by_status('baru'),
            'omzet_hari_ini' => $this->Pesanan_model->revenue_today(),
            'pesanan_terbaru' => $this->Pesanan_model->get_all('', '', 6),
            'stok_terendah' => $this->Barang_model->get_low_stock(5)
        );
        $this->render('dashboard/index', $data);
    }
}
