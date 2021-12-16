<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class documento_inventario extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('documento_inventario/documento_inventario_model');
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
        $data['documentos_inventarios'] = $this->documento_inventario_model->get_all();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/documento_inventario/tabla', $data, true);
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
            $data['documento'] = $this->documento_inventario_model->get_by(array('documento_id' => $id));
        }
        $this->load->view('menu/documento_inventario/form', $data);
    }

    function guardar()
    {
        $id = $this->input->post('documento_id');
        $nombre = $this->input->post('documento_nombre');
        $drogueria_domain = $this->input->post('documento_tipo');
        $datos = array(
            'documento_nombre' => strtoupper($nombre),
            'documento_tipo' => $drogueria_domain,
        );
        if (empty($id)) {
            $data['resultado'] = $this->documento_inventario_model->set($datos);
            $datos['documento_id'] = $data['resultado'];
        } else {
            $datos['documento_id'] = $id;
            $data['resultado'] = $this->documento_inventario_model->update($datos);
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
            'documento_id' => $id,
            'documento_nombre' => $nombre,
            'deleted_at' => date('Y-m-d h:i:s')
        );
        $data['resultado'] = $this->documento_inventario_model->softDelete($datos);
        if ($data['resultado'] != FALSE) {
            $json['success'] = 'Se ha eliminado exitosamente';
        } else {
            $json['error'] = 'Ha ocurrido un error al eliminar la ubicacoin';
        }
        echo json_encode($json);
    }



}