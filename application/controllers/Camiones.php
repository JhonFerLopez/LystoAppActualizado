<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class camiones extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('camiones/camiones_model');
        $this->load->model('usuario/usuario_model');
        $this->very_sesion();
    }



    /** carga cuando listas los proveedores*/
    function index()
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }

        $data['camiones'] = $this->camiones_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/camiones/camiones', $data, true);


        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);

        }

    }
    function form($id = FALSE)
    {
        $data = array();
        $data['trabajadores'] = $this->usuario_model->get_all_transportistas();
        if ($id != FALSE) {
            $data['camiones'] = $this->camiones_model->get_by('camiones_id', $id);
        }
        $this->load->view('menu/camiones/form', $data);
    }


    function guardar()
    {
        $id = $this->input->post('id');
        $transporte = array(
            'camiones_placa' => $this->input->post('camiones_placa'),
            'metros_cubicos' => $this->input->post('metros_cubicos'),
            'id_trabajadores' => $this->input->post('id_trabajadores')
        );

        if (empty($id)) {
            $resultado = $this->camiones_model->insertar($transporte);
        } else {
            $transporte['camiones_id'] = $id;
            $resultado = $this->camiones_model->update($transporte);
        }

        if ($resultado != FALSE) {
            if ($resultado === CAMION_EXISTE) {

                $json['error'] = CAMION_EXISTE;
            } else {
                $json['success'] = 'Solicitud Procesada con exito';
            }
        } else {
            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }
        echo json_encode($json);
    }



    function eliminar()
    {
        $id = $this->input->post('id');

        $camiones = array(
            'camiones_id' => $id,
            'deleted' => 1
        );


        $data['resultado'] = $this->camiones_model->update($camiones);

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Se ha eliminado exitosamente';


        } else {

            $json['error'] = 'Ha ocurrido un error al eliminar el Transporte';
        }

        echo json_encode($json);
    }



}