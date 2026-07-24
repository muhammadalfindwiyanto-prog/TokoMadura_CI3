<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pesanan_model');
    }

    public function index()
    {
        $keyword = trim((string) $this->input->get('q', TRUE));
        $status = trim((string) $this->input->get('status', TRUE));
        $this->render('pesanan/index', array(
            'title' => 'Pesanan Masuk',
            'active_menu' => 'pesanan',
            'keyword' => $keyword,
            'status' => $status,
            'pesanan' => $this->Pesanan_model->get_all($keyword, $status)
        ));
    }

    public function show($id)
    {
        $order = $this->Pesanan_model->find($id);
        if (!$order) show_404();
        $this->render('pesanan/show', array(
            'title' => 'Detail Pesanan',
            'active_menu' => 'pesanan',
            'order' => $order,
            'details' => $this->Pesanan_model->details($id)
        ));
    }

    public function update_status($id)
    {
        if ($this->input->method(TRUE) !== 'POST') show_404();
        $status = trim((string) $this->input->post('status', TRUE));
        if ($this->Pesanan_model->update_status($id, $status)) {
            $this->session->set_flashdata('success', 'Status pesanan berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Status gagal diperbarui. Periksa stok barang.');
        }
        redirect('pesanan/' . $id);
    }
}
