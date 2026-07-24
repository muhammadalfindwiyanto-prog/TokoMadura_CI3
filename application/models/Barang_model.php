<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model
{
    public function get_all($keyword = '')
    {
        $this->db->select('barang.*, kategori.nama AS kategori_nama')
            ->from('barang')->join('kategori', 'kategori.id = barang.kategori_id');
        if ($keyword !== '') {
            $this->db->group_start()->like('barang.kode', $keyword)->or_like('barang.nama', $keyword)->or_like('kategori.nama', $keyword)->group_end();
        }
        return $this->db->order_by('barang.id', 'DESC')->get()->result();
    }

    public function get_api_list($keyword = '', $kategori_id = 0)
    {
        $this->db->select('barang.*, kategori.nama AS kategori_nama')
            ->from('barang')->join('kategori', 'kategori.id = barang.kategori_id');
        if ($keyword !== '') $this->db->group_start()->like('barang.kode', $keyword)->or_like('barang.nama', $keyword)->group_end();
        if ($kategori_id > 0) $this->db->where('barang.kategori_id', $kategori_id);
        return $this->db->order_by('barang.nama', 'ASC')->get()->result();
    }

    public function find($id) { return $this->db->where('id', $id)->get('barang')->row(); }

    public function find_with_category($id)
    {
        return $this->db->select('barang.*, kategori.nama AS kategori_nama')
            ->from('barang')->join('kategori', 'kategori.id = barang.kategori_id')
            ->where('barang.id', $id)->get()->row();
    }

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('barang', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->update('barang', $data);
    }

    public function delete($id) { return $this->db->where('id', $id)->delete('barang'); }
    public function count_all() { return $this->db->count_all('barang'); }

    public function sum_stock()
    {
        $row = $this->db->select_sum('stok')->get('barang')->row();
        return (int) ($row->stok ?: 0);
    }

    public function count_low_stock()
    {
        return $this->db->where('stok <= stok_minimum', NULL, FALSE)->count_all_results('barang');
    }

    public function get_latest($limit = 5)
    {
        return $this->db->select('barang.*, kategori.nama AS kategori_nama')->from('barang')
            ->join('kategori', 'kategori.id = barang.kategori_id')->order_by('barang.id', 'DESC')->limit($limit)->get()->result();
    }

    public function get_low_stock($limit = 5)
    {
        return $this->db->select('barang.*, kategori.nama AS kategori_nama')->from('barang')
            ->join('kategori', 'kategori.id = barang.kategori_id')->where('barang.stok <= barang.stok_minimum', NULL, FALSE)
            ->order_by('barang.stok', 'ASC')->limit($limit)->get()->result();
    }

    public function code_exists($code, $ignore_id = null)
    {
        $this->db->where('UPPER(kode) =', strtoupper(trim($code)));
        if ($ignore_id) $this->db->where('id !=', $ignore_id);
        return $this->db->count_all_results('barang') > 0;
    }
}
