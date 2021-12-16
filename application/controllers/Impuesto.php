<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class impuesto extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('impuesto/impuestos_model');
        $this->load->helper('form');
        $this->very_sesion();
    }


    function index()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }
        $data['lstMovimiento'] = array();
        $data['impuestos'] = $this->impuestos_model->get_impuestos();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/impuesto/impuestos', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {

        $data = array();
        $fe_impuestos = [];
        if (!empty($this->session->userdata('FACT_E_ALLOW') and $this->session->userdata('FACT_E_ALLOW') === '1')) {
            $fe_impuestos =  $this->impuestos_model->get_fe_impuestos();
        }
        $data['fe_impuestos'] = $fe_impuestos;
        if ($id != FALSE) {
            $data['impuesto'] = $this->impuestos_model->get_by('id_impuesto', $id);
        }



        $this->load->view('menu/impuesto/form', $data);
    }

    function guardar()
    {
        $coma = strpos($this->input->post('porcentaje'), ",");
        if ($coma == true) {
            $porcentaje = str_replace(",", ".", $this->input->post('porcentaje'));
        } else {
            $porcentaje = $this->input->post('porcentaje');
        }

        $id = $this->input->post('id');
        $grupo = array(
            'nombre_impuesto' => strtoupper($this->input->post('nombre')),
            'tipo_calculo' => $this->input->post('tipo_calculo'),
            'porcentaje_impuesto' => $porcentaje,
            'fe_impuesto' => $this->input->post('fe_impuesto'),
        );
        if (empty($id)) {
            $data['resultado'] = $this->impuestos_model->set_impuestos($grupo);
        } else {
            $grupo['id_impuesto'] = $id;
            $data['resultado'] = $this->impuestos_model->update_impuestos($grupo);
        }

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Solicitud Procesada con exito';
        } else {

            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }


        if ($data['resultado'] === NOMBRE_EXISTE) {
            //  $this->session->set_flashdata('error', NOMBRE_EXISTE);
            $json['error'] = NOMBRE_EXISTE;
        }

        echo json_encode($json);
    }

    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $grupo = array(
            'id_impuesto' => $id,
            'nombre_impuesto' => $nombre . time(),
            'estatus_impuesto' => 0

        );

        $data['resultado'] = $this->impuestos_model->update_impuestos($grupo);

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Se ha Eliminado exitosamente';
        } else {

            $json['error'] = 'Ha ocurrido un error al eliminar el impuesto';
        }

        echo json_encode($json);
    }

    /*solo retorna los impuestos en json*/
    function getImpuestosJson()
    {
        $json = $this->impuestos_model->get_impuestos();
        echo json_encode($json);
    }
}
