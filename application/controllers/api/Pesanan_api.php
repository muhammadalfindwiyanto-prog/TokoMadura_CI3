<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan_api extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pesanan_model');
    }

    public function index()
    {
        $rows = $this->Pesanan_model->get_for_user($this->api_user->id);
        $data = array();
        foreach ($rows as $row) $data[] = $this->transform($row, FALSE);
        $this->respond(array('success' => TRUE, 'data' => $data));
    }

    public function show($id)
    {
        $row = $this->Pesanan_model->find($id);
        if (!$row || (int) $row->user_id !== (int) $this->api_user->id) {
            $this->respond(array('success' => FALSE, 'message' => 'Pesanan tidak ditemukan.'), 404);
        }
        $this->respond(array('success' => TRUE, 'data' => $this->transform($row, TRUE)));
    }

    public function create()
    {
        $data = $this->input_data();
        $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : array();
        try {
            $id = $this->Pesanan_model->create_order(
                $this->api_user->id,
                $items,
                isset($data['catatan']) ? $data['catatan'] : ''
            );
            $row = $this->Pesanan_model->find($id);
            $this->respond(array(
                'success' => TRUE,
                'message' => 'Pesanan berhasil dibuat.',
                'data' => $this->transform($row, TRUE)
            ), 201);
        } catch (Throwable $e) {
            $this->respond(array('success' => FALSE, 'message' => $e->getMessage()), 422);
        }
    }

    private function transform($row, $with_details)
    {
        $result = array(
            'id' => (int) $row->id,
            'kode_pesanan' => $row->kode_pesanan,
            'total' => (float) $row->total,
            'status' => $row->status,
            'metode_pembayaran' => $row->metode_pembayaran,
            'catatan' => $row->catatan,
            'created_at' => $row->created_at,
            'updated_at' => $row->updated_at
        );
        if ($with_details) {
            $result['items'] = array();
            foreach ($this->Pesanan_model->details($row->id) as $item) {
                $result['items'][] = array(
                    'id' => (int) $item->id,
                    'barang_id' => $item->barang_id ? (int) $item->barang_id : null,
                    'nama_barang' => $item->nama_barang,
                    'harga' => (float) $item->harga,
                    'qty' => (int) $item->qty,
                    'subtotal' => (float) $item->subtotal
                );
            }
        }
        return $result;
    }
}
