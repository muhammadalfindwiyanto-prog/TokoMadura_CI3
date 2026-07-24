<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function find_by_username($username)
    {
        return $this->db->where('username', $username)->get('users')->row();
    }

    public function update_last_login($id)
    {
        return $this->db->where('id', $id)->update('users', array('last_login_at' => date('Y-m-d H:i:s')));
    }
}
