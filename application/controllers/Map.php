<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller {
    public function index()
    {
        $data = array(
            'judul' => 'map',
            'content' => 'peta_leaflet'
        );
        $this->load->view('map/viewunion', $data, FALSE);
    }
}
