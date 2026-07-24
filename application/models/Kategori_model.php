<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model
{
    public function get_all()
    {
        return $this->db
            ->select('kategori.*, COUNT(barang.id) AS jumlah_barang')
            ->from('kategori')
            ->join('barang', 'barang.kategori_id = kategori.id', 'left')
            ->group_by('kategori.id')
            ->order_by('kategori.nama', 'ASC')
            ->get()->result();
    }

    public function count_all() { return $this->db->count_all('kategori'); }
    public function find($id) { return $this->db->where('id', $id)->get('kategori')->row(); }
    public function insert($data) { $this->db->insert('kategori', $data); return $this->db->insert_id(); }
    public function update($id, $data) { return $this->db->where('id', $id)->update('kategori', $data); }
    public function delete($id) { return $this->db->where('id', $id)->delete('kategori'); }
    public function has_items($id) { return $this->db->where('kategori_id', $id)->count_all_results('barang') > 0; }

    public function name_exists($name, $ignore_id = null)
    {
        $this->db->where('LOWER(nama) =', strtolower(trim($name)));
        if ($ignore_id) $this->db->where('id !=', $ignore_id);
        return $this->db->count_all_results('kategori') > 0;
    }
}
