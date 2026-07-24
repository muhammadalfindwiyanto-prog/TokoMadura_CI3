<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_docs extends Admin_Controller
{
    public function index()
    {
        $this->render('api_docs/index', array('title' => 'Dokumentasi API', 'active_menu' => 'api_docs'));
    }
}
