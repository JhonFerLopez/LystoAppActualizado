<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class creditNote extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('venta/venta_model');

        $this->load->model('cajas/StatusCajaModel');
        $this->load->model('impuesto/Impuestos_model');
        $this->very_sesion();
    }


    function index()
    {

    }

    function save(){

    }



}