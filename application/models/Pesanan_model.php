<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan_model extends CI_Model
{
    public function get_all($keyword = '', $status = '', $limit = null)
    {
        $this->db->select('pesanan.*, users.nama AS nama_pelanggan, users.username')
            ->from('pesanan')
            ->join('users', 'users.id = pesanan.user_id', 'left');
        if ($keyword !== '') {
            $this->db->group_start()
                ->like('pesanan.kode_pesanan', $keyword)
                ->or_like('users.nama', $keyword)
                ->or_like('users.username', $keyword)
                ->group_end();
        }
        if ($status !== '') $this->db->where('pesanan.status', $status);
        $this->db->order_by('pesanan.id', 'DESC');
        if ($limit !== null) $this->db->limit((int) $limit);
        return $this->db->get()->result();
    }

    public function get_for_user($user_id)
    {
        return $this->db->where('user_id', $user_id)->order_by('id', 'DESC')->get('pesanan')->result();
    }

    public function find($id)
    {
        return $this->db->select('pesanan.*, users.nama AS nama_pelanggan, users.username')
            ->from('pesanan')->join('users', 'users.id = pesanan.user_id', 'left')
            ->where('pesanan.id', $id)->get()->row();
    }

    public function details($pesanan_id)
    {
        return $this->db->where('pesanan_id', $pesanan_id)->order_by('id', 'ASC')->get('pesanan_detail')->result();
    }

    public function create_order($user_id, array $items, $catatan = '')
    {
        $this->db->trans_begin();
        try {
            $quantities = array();
            foreach ($items as $item) {
                $barang_id = isset($item['barang_id']) ? (int) $item['barang_id'] : 0;
                $qty = isset($item['qty']) ? (int) $item['qty'] : 0;
                if ($barang_id < 1 || $qty < 1) throw new Exception('Barang dan jumlah pesanan tidak valid.');
                if (!isset($quantities[$barang_id])) $quantities[$barang_id] = 0;
                $quantities[$barang_id] += $qty;
            }

            $normalized = array();
            $total = 0;
            foreach ($quantities as $barang_id => $qty) {
                $barang = $this->db->query('SELECT * FROM barang WHERE id = ? FOR UPDATE', array($barang_id))->row();
                if (!$barang) throw new Exception('Salah satu barang tidak ditemukan.');
                if ((int) $barang->stok < $qty) throw new Exception('Stok ' . $barang->nama . ' tidak mencukupi.');

                $subtotal = (float) $barang->harga_jual * $qty;
                $normalized[] = array(
                    'barang' => $barang,
                    'qty' => $qty,
                    'harga' => (float) $barang->harga_jual,
                    'subtotal' => $subtotal
                );
                $total += $subtotal;
            }
            if (!$normalized) throw new Exception('Keranjang masih kosong.');

            $kode = 'TM-' . date('YmdHis') . '-' . random_int(100, 999);
            $now = date('Y-m-d H:i:s');
            $this->db->insert('pesanan', array(
                'kode_pesanan' => $kode,
                'user_id' => $user_id,
                'total' => $total,
                'status' => 'baru',
                'metode_pembayaran' => 'tunai',
                'catatan' => trim((string) $catatan),
                'created_at' => $now,
                'updated_at' => $now
            ));
            $pesanan_id = $this->db->insert_id();

            foreach ($normalized as $row) {
                $this->db->insert('pesanan_detail', array(
                    'pesanan_id' => $pesanan_id,
                    'barang_id' => $row['barang']->id,
                    'nama_barang' => $row['barang']->nama,
                    'harga' => $row['harga'],
                    'qty' => $row['qty'],
                    'subtotal' => $row['subtotal']
                ));
                $this->db->set('stok', 'stok - ' . (int) $row['qty'], FALSE)
                    ->where('id', $row['barang']->id)->update('barang');
            }

            if ($this->db->trans_status() === FALSE) throw new Exception('Gagal menyimpan transaksi.');
            $this->db->trans_commit();
            return $pesanan_id;
        } catch (Throwable $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }

    public function update_status($id, $new_status)
    {
        $allowed = array('baru', 'diproses', 'selesai', 'dibatalkan');
        if (!in_array($new_status, $allowed, TRUE)) return FALSE;

        $this->db->trans_begin();
        $order = $this->db->query('SELECT * FROM pesanan WHERE id = ? FOR UPDATE', array($id))->row();
        if (!$order) {
            $this->db->trans_rollback();
            return FALSE;
        }

        if ($new_status === 'dibatalkan' && $order->status !== 'dibatalkan') {
            foreach ($this->details($id) as $detail) {
                if ($detail->barang_id) {
                    $this->db->set('stok', 'stok + ' . (int) $detail->qty, FALSE)
                        ->where('id', $detail->barang_id)->update('barang');
                }
            }
        }
        if ($order->status === 'dibatalkan' && $new_status !== 'dibatalkan') {
            foreach ($this->details($id) as $detail) {
                if (!$detail->barang_id) continue;
                $barang = $this->db->query('SELECT stok, nama FROM barang WHERE id = ? FOR UPDATE', array($detail->barang_id))->row();
                if (!$barang || (int) $barang->stok < (int) $detail->qty) {
                    $this->db->trans_rollback();
                    return FALSE;
                }
                $this->db->set('stok', 'stok - ' . (int) $detail->qty, FALSE)
                    ->where('id', $detail->barang_id)->update('barang');
            }
        }

        $this->db->where('id', $id)->update('pesanan', array(
            'status' => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        ));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }
        $this->db->trans_commit();
        return TRUE;
    }

    public function count_by_status($status)
    {
        return $this->db->where('status', $status)->count_all_results('pesanan');
    }

    public function revenue_today()
    {
        $row = $this->db->select_sum('total')
            ->where('DATE(created_at) = CURDATE()', NULL, FALSE)
            ->where_in('status', array('baru', 'diproses', 'selesai'))
            ->get('pesanan')->row();
        return (float) ($row->total ?: 0);
    }
}
