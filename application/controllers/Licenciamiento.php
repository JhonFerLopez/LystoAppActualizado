<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class licenciamiento extends MY_Controller {

    function __construct() {
        parent::__construct();


        $this->very_sesion();
    }



    function index(){
        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }
        $data['lstMovimiento'] = array();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/licencia/index',$data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        }else{
            $this->load->view('menu/template', $dataCuerpo);
        }
    }






}