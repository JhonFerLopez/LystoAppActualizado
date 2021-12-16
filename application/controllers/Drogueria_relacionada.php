<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class drogueria_relacionada extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('drogueria_relacionada/drogueria_relacionada_model');
        $this->very_sesion();
    }

    function index()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }
        $data['droguerias_relacionadas'] = $this->drogueria_relacionada_model->get_all();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/drogueria_relacionada/tabla', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {
        $data = array();
        if ($id != FALSE) {
            $data['drogueria'] = $this->drogueria_relacionada_model->get_by(array('drogueria_id' => $id));
        }
        $this->load->view('menu/drogueria_relacionada/form', $data);
    }

    function guardar()
    {
        $id = $this->input->post('drogueria_id');
        $nombre = $this->input->post('drogueria_nombre');
        $drogueria_domain = $this->input->post('drogueria_domain');
        $datos = array(
            'drogueria_nombre' => strtoupper($nombre),
            'drogueria_domain' => $drogueria_domain,
        );
        if (empty($id)) {
            $data['resultado'] = $this->drogueria_relacionada_model->set($datos);
            $datos['drogueria_id'] = $data['resultado'];
        } else {
            $datos['drogueria_id'] = $id;
            $data['resultado'] = $this->drogueria_relacionada_model->update($datos);
        }
        if ($data['resultado'] != FALSE) {
            $json['success'] = 'Solicitud Procesada con exito';
        } else {
            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }
        if ($data['resultado'] === NOMBRE_EXISTE) {
            $json['error'] = $data['resultado'];
        }
        echo json_encode($json);
    }

    function eliminar()
    {
        $id = $this->input->post('drogueria_relacionada_id');
        $nombre = $this->input->post('drogueria_relacionada_nombre');
        $datos = array(
            'drogueria_id' => $id,
            'drogueria_nombre' => $nombre,
            'deleted_at' => date('Y-m-d h:i:s')
        );
        $data['resultado'] = $this->drogueria_relacionada_model->softDelete($datos);
        if ($data['resultado'] != FALSE) {
            $json['success'] = 'Se ha eliminado exitosamente';
        } else {
            $json['error'] = 'Ha ocurrido un error al eliminar la ubicacoin';
        }
        echo json_encode($json);
    }



}