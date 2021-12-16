<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class systemLogs extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('system_logs/systemLogsModel');
        $this->load->model('usuario/usuario_model');
        $this->very_sesion();
    }


    function index()
    {


        $data['usuarios'] = $this->usuario_model->select_all_user();//
        $dataCuerpo['cuerpo'] = $this->load->view('menu/system_logs/tabla', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    function get_json()
    {
        $data['lista'] = $this->systemLogsModel->get_all();
    }

    function error()
    {

        $data = array();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/error_logs/tabla', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function showFileError()
    {
        $data = array();
        $fecha = $this->input->post('fecha');
        $texto = file("./application/logs/log-" . $fecha . ".php");
        $data['data'] = $texto;
        echo json_encode($data);
    }


}