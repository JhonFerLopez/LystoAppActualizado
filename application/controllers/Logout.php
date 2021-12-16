<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class logout extends  MY_Controller{
	
	function __construct() {
		parent::__construct();

	}
	

	
	function index(){
	    $explicense = $this->input->get('err');

		$array_sesiones = $this->session->all_userdata();
		$this->session->unset_userdata($array_sesiones);
        $this->session->sess_destroy();
       // $this->session->regenerate_id();
        $url ='inicio';

        if(!empty($explicense)){
            $url.='?err='.$explicense;
        }

		redirect($url, 'location', 301);


	}
	
}