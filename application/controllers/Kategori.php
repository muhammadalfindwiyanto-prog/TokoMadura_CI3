<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kategori_model');
    }

    public function index()
    {
        $data = array(
            'title' => 'Kategori Produk',
            'active_menu' => 'kategori',
            'kategori' => $this->Kategori_model->get_all()
        );
        $this->render('kategori/index', $data);
    }

    public function create()
    {
        $this->rules();
        if ($this->form_validation->run()) {
            if ($this->Kategori_model->name_exists($this->input->post('nama', TRUE))) {
                $this->session->set_flashdata('error', 'Nama kategori sudah digunakan.');
            } else {
                $this->Kategori_model->insert(array(
                    'nama' => $this->input->post('nama', TRUE),
                    'deskripsi' => $this->input->post('deskripsi', TRUE)
                ));
                $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan.');
                redirect('kategori');
            }
        }
        $this->render('kategori/form', array('title' => 'Tambah Kategori', 'active_menu' => 'kategori', 'item' => null));
    }

    public function edit($id)
    {
        $item = $this->Kategori_model->find($id);
        if (!$item) show_404();

        $this->rules();
        if ($this->form_validation->run()) {
            if ($this->Kategori_model->name_exists($this->input->post('nama', TRUE), $id)) {
                $this->session->set_flashdata('error', 'Nama kategori sudah digunakan.');
            } else {
                $this->Kategori_model->update($id, array(
                    'nama' => $this->input->post('nama', TRUE),
                    'deskripsi' => $this->input->post('deskripsi', TRUE)
                ));
                $this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
                redirect('kategori');
            }
        }
        $this->render('kategori/form', array('title' => 'Edit Kategori', 'active_menu' => 'kategori', 'item' => $item));
    }

    public function delete($id)
    {
        if ($this->input->method(TRUE) !== 'POST') show_404();
        $item = $this->Kategori_model->find($id);
        if (!$item) show_404();

        if ($this->Kategori_model->has_items($id)) {
            $this->session->set_flashdata('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data produk.');
        } else {
            $this->Kategori_model->delete($id);
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        }
        redirect('kategori');
    }

    private function rules()
    {
        $this->form_validation->set_rules('nama', 'Nama kategori', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|max_length[255]');
        $this->form_validation->set_error_delimiters('<small class="field-error">', '</small>');
    }
}
