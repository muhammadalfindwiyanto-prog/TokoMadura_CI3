<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) redirect('dashboard');

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_error_delimiters('<small class="field-error">', '</small>');

        if ($this->form_validation->run()) {
            $user = $this->User_model->find_by_username($this->input->post('username', TRUE));
            $valid_role = $user && in_array($user->role, array('admin', 'petugas'), TRUE);
            if ($user && $user->is_active && $valid_role && password_verify($this->input->post('password'), $user->password)) {
                $this->session->sess_regenerate(TRUE);
                $this->session->set_userdata(array(
                    'user_id' => $user->id,
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'role' => $user->role,
                    'logged_in' => TRUE
                ));
                $this->User_model->update_last_login($user->id);
                redirect('dashboard');
            }
            $this->session->set_flashdata('error', 'Username/password salah atau akun bukan admin.');
            redirect('login');
        }

        $this->load->view('auth/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
