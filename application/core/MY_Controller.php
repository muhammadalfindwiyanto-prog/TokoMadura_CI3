<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    protected function render($view, $data = array())
    {
        $data['content_view'] = $view;
        $this->load->view('layouts/app', $data);
    }
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }
        if (!in_array($this->session->userdata('role'), array('admin', 'petugas'), TRUE)) {
            $this->session->sess_destroy();
            redirect('login');
        }
    }
}

class Api_Controller extends MY_Controller
{
    protected $auth_required = TRUE;
    protected $api_user = null;
    protected $json_input = array();

    public function __construct()
    {
        parent::__construct();
        $this->output->set_content_type('application/json', 'utf-8');
        $this->set_cors_headers();

        if ($this->input->method(TRUE) === 'OPTIONS') {
            $this->output->set_status_header(204)->set_output('');
            $this->output->_display();
            exit;
        }

        $raw = trim((string) $this->input->raw_input_stream);
        $decoded = json_decode($raw, TRUE);
        $this->json_input = is_array($decoded) ? $decoded : array();

        if ($this->auth_required) $this->authenticate_token();
    }

    protected function set_cors_headers()
    {
        $this->output
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With')
            ->set_header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    }

    protected function authenticate_token()
    {
        $header = $this->input->get_request_header('Authorization', TRUE);
        if (!$header || !preg_match('/Bearer\s+(\S+)/i', $header, $matches)) {
            $this->respond(array('success' => FALSE, 'message' => 'Token API tidak ditemukan.'), 401);
        }

        $token = hash('sha256', $matches[1]);
        $row = $this->db
            ->select('api_tokens.id AS token_id, users.id, users.nama, users.username, users.role, users.is_active')
            ->from('api_tokens')
            ->join('users', 'users.id = api_tokens.user_id')
            ->where('api_tokens.token_hash', $token)
            ->where('api_tokens.revoked_at IS NULL', NULL, FALSE)
            ->where('api_tokens.expires_at >', date('Y-m-d H:i:s'))
            ->get()->row();

        if (!$row || !$row->is_active) {
            $this->respond(array('success' => FALSE, 'message' => 'Token tidak valid atau kedaluwarsa.'), 401);
        }

        $this->api_user = $row;
        $this->db->where('id', $row->token_id)->update('api_tokens', array('last_used_at' => date('Y-m-d H:i:s')));
    }

    protected function input_data()
    {
        return !empty($this->json_input) ? $this->json_input : $this->input->post(NULL, TRUE);
    }

    protected function respond($payload, $status = 200)
    {
        $this->output
            ->set_status_header($status)
            ->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->output->_display();
        exit;
    }
}
