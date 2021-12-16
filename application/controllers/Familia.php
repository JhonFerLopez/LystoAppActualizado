<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class familia extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('familia/familias_model');
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
        $data['familias']=$this->familias_model->get_familias();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/familia/familias',$data, true);
        if($this->input->is_ajax_request()){
            echo $dataCuerpo['cuerpo'] ;
        }
        else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {

        $data = array();
        if ($id != FALSE) {
            $data['familia'] = $this->familias_model->get_by('id_familia', $id);
        }


        $this->load->view('menu/familia/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');
        $grupo = array(
            'nombre_familia' => $nombre
        );
        if (empty($id)) {
            $data['resultado'] = $this->familias_model->set_familias();
        } else {
            $grupo['id_familia'] = $id;
            $data['resultado'] = $this->familias_model->update_familias($grupo);
        }

        if ($data['resultado'] != FALSE) {

           // $this->session->set_flashdata('success', 'Solicitud Procesada con exito');
            $json['success']= 'Solicitud Procesada con exito';

        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al procesar la solicitud');
            $json['error']= 'Ha ocurrido un error al procesar la solicitud';
        }

        if($data['resultado']===NOMBRE_EXISTE){
          //  $this->session->set_flashdata('error', NOMBRE_EXISTE);
            $json['error']= NOMBRE_EXISTE;
        }


        echo json_encode($json);
    }

    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $grupo = array(
            'id_familia' => $id,
            'nombre_familia' => $nombre . time(),
            'estatus_familia' => 0

        );

        $data['resultado'] = $this->familias_model->update_familias($grupo, 'eliminar');

        if ($data['resultado'] != FALSE) {

            $this->session->set_flashdata('success', 'Se ha Eliminado exitosamente');
            $json['success']= 'Se ha Eliminado exitosamente';

        } else {

            $this->session->set_flashdata('error', 'ha ocurrido un error al eliminar la familia');
            $json['error']= 'Ha ocurrido un error al eliminar la familia';
        }

        echo json_encode($json);
    }


}