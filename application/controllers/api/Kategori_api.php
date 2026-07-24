<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_api extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kategori_model');
    }

    public function index()
    {
        $rows = $this->Kategori_model->get_all();
        foreach ($rows as $row) {
            $row->id = (int) $row->id;
            $row->jumlah_barang = (int) $row->jumlah_barang;
        }
        $this->respond(array('success' => TRUE, 'message' => 'Data kategori berhasil diambil.', 'data' => $rows));
    }

    public function show($id)
    {
        $row = $this->Kategori_model->find($id);
        if (!$row) $this->respond(array('success' => FALSE, 'message' => 'Kategori tidak ditemukan.'), 404);
        $row->id = (int) $row->id;
        $this->respond(array('success' => TRUE, 'data' => $row));
    }
}
