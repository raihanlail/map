<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Details extends CI_Controller {
    public function index()
    {
        $data = array(
            'judul' => 'data details',
            'content' => 'detail_data'
        );
        $this->load->view('details/viewunion', $data, FALSE);
    }
}
