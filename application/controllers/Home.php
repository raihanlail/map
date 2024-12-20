<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Home extends CI_Controller { 
   public function index() 
   { 
       $data = array( 
           'judul' => 'WebGIS Trainning', 
           'content' => 'home_content'
       ); 
       $this->load->view('layout/viewunion', $data, FALSE); 
   } 
}


