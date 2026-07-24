<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_api extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Barang_model', 'Kategori_model'));
    }

    public function index()
    {
        $keyword = trim((string) $this->input->get('q', TRUE));
        $kategori_id = (int) $this->input->get('kategori_id', TRUE);
        $rows = $this->Barang_model->get_api_list($keyword, $kategori_id);
        $data = array();
        foreach ($rows as $row) $data[] = $this->transform($row);
        $this->respond(array(
            'success' => TRUE,
            'message' => 'Data barang berhasil diambil.',
            'meta' => array('total' => count($data), 'keyword' => $keyword),
            'data' => $data
        ));
    }

    public function show($id)
    {
        $row = $this->Barang_model->find_with_category($id);
        if (!$row) $this->respond(array('success' => FALSE, 'message' => 'Barang tidak ditemukan.'), 404);
        $this->respond(array('success' => TRUE, 'data' => $this->transform($row)));
    }

    public function create()
    {
        $data = $this->input_data();
        $errors = $this->validate($data);
        if ($errors) $this->respond(array('success' => FALSE, 'message' => 'Validasi gagal.', 'errors' => $errors), 422);
        if ($this->Barang_model->code_exists($data['kode'])) {
            $this->respond(array('success' => FALSE, 'message' => 'Kode barang sudah digunakan.'), 409);
        }

        $id = $this->Barang_model->insert($this->sanitize($data));
        $row = $this->Barang_model->find_with_category($id);
        $this->respond(array('success' => TRUE, 'message' => 'Barang berhasil ditambahkan.', 'data' => $this->transform($row)), 201);
    }

    public function update($id)
    {
        $existing = $this->Barang_model->find($id);
        if (!$existing) $this->respond(array('success' => FALSE, 'message' => 'Barang tidak ditemukan.'), 404);

        $data = array_merge((array) $existing, $this->input_data());
        $errors = $this->validate($data);
        if ($errors) $this->respond(array('success' => FALSE, 'message' => 'Validasi gagal.', 'errors' => $errors), 422);
        if ($this->Barang_model->code_exists($data['kode'], $id)) {
            $this->respond(array('success' => FALSE, 'message' => 'Kode barang sudah digunakan.'), 409);
        }

        $payload = $this->sanitize($data);
        $payload['foto'] = $existing->foto;
        $this->Barang_model->update($id, $payload);
        $row = $this->Barang_model->find_with_category($id);
        $this->respond(array('success' => TRUE, 'message' => 'Barang berhasil diperbarui.', 'data' => $this->transform($row)));
    }

    public function delete($id)
    {
        $existing = $this->Barang_model->find($id);
        if (!$existing) $this->respond(array('success' => FALSE, 'message' => 'Barang tidak ditemukan.'), 404);
        if ($existing->foto && file_exists(FCPATH . 'uploads/barang/' . $existing->foto)) @unlink(FCPATH . 'uploads/barang/' . $existing->foto);
        $this->Barang_model->delete($id);
        $this->respond(array('success' => TRUE, 'message' => 'Barang berhasil dihapus.'));
    }

    private function validate($data)
    {
        $errors = array();
        foreach (array('kode', 'nama', 'kategori_id', 'harga_beli', 'harga_jual', 'stok', 'stok_minimum', 'satuan') as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') $errors[$field] = 'Field ' . $field . ' wajib diisi.';
        }
        if (isset($data['kategori_id']) && !$this->Kategori_model->find((int) $data['kategori_id'])) $errors['kategori_id'] = 'Kategori tidak valid.';
        foreach (array('harga_beli', 'harga_jual', 'stok', 'stok_minimum') as $field) {
            if (isset($data[$field]) && (!is_numeric($data[$field]) || $data[$field] < 0)) $errors[$field] = 'Nilai harus berupa angka minimal 0.';
        }
        return $errors;
    }

    private function sanitize($data)
    {
        return array(
            'kode' => strtoupper(trim($data['kode'])),
            'nama' => trim($data['nama']),
            'kategori_id' => (int) $data['kategori_id'],
            'harga_beli' => (float) $data['harga_beli'],
            'harga_jual' => (float) $data['harga_jual'],
            'stok' => (int) $data['stok'],
            'stok_minimum' => (int) $data['stok_minimum'],
            'satuan' => trim($data['satuan']),
            'lokasi' => isset($data['lokasi']) ? trim($data['lokasi']) : '',
            'deskripsi' => isset($data['deskripsi']) ? trim($data['deskripsi']) : '',
            'foto' => isset($data['foto']) ? $data['foto'] : NULL
        );
    }

    private function transform($row)
    {
        return array(
            'id' => (int) $row->id,
            'kode' => $row->kode,
            'nama' => $row->nama,
            'kategori' => array('id' => (int) $row->kategori_id, 'nama' => isset($row->kategori_nama) ? $row->kategori_nama : null),
            'harga_beli' => (float) $row->harga_beli,
            'harga_jual' => (float) $row->harga_jual,
            'stok' => (int) $row->stok,
            'stok_minimum' => (int) $row->stok_minimum,
            'status_stok' => ((int) $row->stok <= 0 ? 'habis' : ((int) $row->stok <= (int) $row->stok_minimum ? 'menipis' : 'tersedia')),
            'satuan' => $row->satuan,
            'lokasi' => $row->lokasi,
            'deskripsi' => $row->deskripsi,
            'foto_url' => foto_barang_url($row->foto),
            'created_at' => $row->created_at,
            'updated_at' => $row->updated_at
        );
    }
}
