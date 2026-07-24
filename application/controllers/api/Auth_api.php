<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_api extends Api_Controller
{
    protected $auth_required = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function login()
    {
        $data = $this->input_data();
        $username = isset($data['username']) ? trim($data['username']) : '';
        $password = isset($data['password']) ? (string) $data['password'] : '';

        if ($username === '' || $password === '') {
            $this->respond(array('success' => FALSE, 'message' => 'Username dan password wajib diisi.'), 422);
        }

        $user = $this->User_model->find_by_username($username);
        if (!$user || !$user->is_active || !password_verify($password, $user->password)) {
            $this->respond(array('success' => FALSE, 'message' => 'Username atau password salah.'), 401);
        }

        $plain_token = bin2hex(random_bytes(32));
        $this->db->insert('api_tokens', array(
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plain_token),
            'device_name' => isset($data['device_name']) ? substr($data['device_name'], 0, 100) : 'Android App',
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
            'created_at' => date('Y-m-d H:i:s')
        ));

        $this->respond(array(
            'success' => TRUE,
            'message' => 'Login API berhasil.',
            'data' => array(
                'token' => $plain_token,
                'token_type' => 'Bearer',
                'expires_in_days' => 30,
                'user' => array('id' => (int) $user->id, 'nama' => $user->nama, 'username' => $user->username, 'role' => $user->role)
            )
        ));
    }

    public function logout()
    {
        $this->auth_required = TRUE;
        $this->authenticate_token();
        $this->db->where('id', $this->api_user->token_id)->update('api_tokens', array('revoked_at' => date('Y-m-d H:i:s')));
        $this->respond(array('success' => TRUE, 'message' => 'Logout API berhasil.'));
    }

    public function profile()
    {
        $this->auth_required = TRUE;
        $this->authenticate_token();
        $this->respond(array('success' => TRUE, 'data' => array(
            'id' => (int) $this->api_user->id,
            'nama' => $this->api_user->nama,
            'username' => $this->api_user->username,
            'role' => $this->api_user->role
        )));
    }
}
