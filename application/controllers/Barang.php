<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Barang_model', 'Kategori_model'));
    }

    public function index()
    {
        $keyword = trim((string) $this->input->get('q', TRUE));
        $data = array(
            'title' => 'Data Barang',
            'active_menu' => 'barang',
            'keyword' => $keyword,
            'barang' => $this->Barang_model->get_all($keyword)
        );
        $this->render('barang/index', $data);
    }

    public function show($id)
    {
        $item = $this->Barang_model->find_with_category($id);
        if (!$item) show_404();
        $this->render('barang/show', array('title' => 'Detail Barang', 'active_menu' => 'barang', 'item' => $item));
    }

    public function create()
    {
        $this->rules();
        if ($this->form_validation->run()) {
            if ($this->Barang_model->code_exists($this->input->post('kode', TRUE))) {
                $this->session->set_flashdata('error', 'Kode barang sudah digunakan.');
            } else {
                $foto = $this->upload_foto();
                if ($foto !== FALSE) {
                    $this->Barang_model->insert($this->payload($foto));
                    $this->session->set_flashdata('success', 'Data barang berhasil ditambahkan.');
                    redirect('barang');
                }
            }
        }
        $this->render('barang/form', array(
            'title' => 'Tambah Barang',
            'active_menu' => 'barang',
            'item' => null,
            'kategori' => $this->Kategori_model->get_all()
        ));
    }

    public function edit($id)
    {
        $item = $this->Barang_model->find($id);
        if (!$item) show_404();

        $this->rules();
        if ($this->form_validation->run()) {
            if ($this->Barang_model->code_exists($this->input->post('kode', TRUE), $id)) {
                $this->session->set_flashdata('error', 'Kode barang sudah digunakan.');
            } else {
                $foto = $item->foto;
                if (!empty($_FILES['foto']['name'])) {
                    $uploaded = $this->upload_foto();
                    if ($uploaded === FALSE) {
                        $this->render_form($item);
                        return;
                    }
                    $this->remove_foto($item->foto);
                    $foto = $uploaded;
                }
                $this->Barang_model->update($id, $this->payload($foto));
                $this->session->set_flashdata('success', 'Data barang berhasil diperbarui.');
                redirect('barang');
            }
        }
        $this->render_form($item);
    }

    public function delete($id)
    {
        if ($this->input->method(TRUE) !== 'POST') show_404();
        $item = $this->Barang_model->find($id);
        if (!$item) show_404();
        $this->remove_foto($item->foto);
        $this->Barang_model->delete($id);
        $this->session->set_flashdata('success', 'Data barang berhasil dihapus.');
        redirect('barang');
    }

    private function render_form($item)
    {
        $this->render('barang/form', array(
            'title' => 'Edit Barang',
            'active_menu' => 'barang',
            'item' => $item,
            'kategori' => $this->Kategori_model->get_all()
        ));
    }

    private function payload($foto)
    {
        return array(
            'kode' => strtoupper($this->input->post('kode', TRUE)),
            'nama' => $this->input->post('nama', TRUE),
            'kategori_id' => (int) $this->input->post('kategori_id'),
            'harga_beli' => (float) $this->input->post('harga_beli'),
            'harga_jual' => (float) $this->input->post('harga_jual'),
            'stok' => (int) $this->input->post('stok'),
            'stok_minimum' => (int) $this->input->post('stok_minimum'),
            'satuan' => $this->input->post('satuan', TRUE),
            'lokasi' => $this->input->post('lokasi', TRUE),
            'deskripsi' => $this->input->post('deskripsi', TRUE),
            'foto' => $foto
        );
    }

    private function rules()
    {
        $this->form_validation->set_rules('kode', 'Kode barang', 'required|trim|max_length[30]');
        $this->form_validation->set_rules('nama', 'Nama barang', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|integer');
        $this->form_validation->set_rules('harga_beli', 'Harga beli', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('harga_jual', 'Harga jual', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('stok_minimum', 'Stok minimum', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim|max_length[30]');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|max_length[100]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|max_length[500]');
        $this->form_validation->set_error_delimiters('<small class="field-error">', '</small>');
    }

    private function upload_foto()
    {
        if (empty($_FILES['foto']['name'])) return NULL;

        $config = array(
            'upload_path' => FCPATH . 'uploads/barang/',
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 2048,
            'encrypt_name' => TRUE,
            'remove_spaces' => TRUE
        );
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('foto')) {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors('', '')));
            return FALSE;
        }
        return $this->upload->data('file_name');
    }

    private function remove_foto($filename)
    {
        if ($filename && file_exists(FCPATH . 'uploads/barang/' . $filename)) {
            @unlink(FCPATH . 'uploads/barang/' . $filename);
        }
    }
}
