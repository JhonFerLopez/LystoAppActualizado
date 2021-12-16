<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class componentes extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('componentes/componentes_model');

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
        $data['marcas']=$this->componentes_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/componentes/tabla',$data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        }else{
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {

        $data = array();
        if ($id != FALSE) {
            $data['componente'] = $this->componentes_model->get_by(array('componente_id'=> $id));
        }


        $this->load->view('menu/componentes/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');
        $datos = array(
            'componente_nombre' => strtoupper($nombre)
        );
        if (empty($id)) {
            $data['resultado'] = $this->componentes_model->set($datos);
        } else {
            $datos['componente_id'] = $id;
            $data['resultado'] = $this->componentes_model->update($datos);
        }

        if ($data['resultado'] != FALSE) {

          //  $this->session->set_flashdata('success', 'Solicitud Procesada con exito');
            $json['success']= 'Solicitud Procesada con exito';

        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al procesar la solicitud');
            $json['error']= 'Ha ocurrido un error al procesar la solicitud';
        }

        if($data['resultado']===NOMBRE_EXISTE){
          //  $this->session->set_flashdata('error', NOMBRE_EXISTE);
            $json['error']= NOMBRE_EXISTE;
        }

        if(!isset($json['error'])){

            $this->db->cache_delete( 'componentes' ,'index');
        }

        echo json_encode($json);
    }

    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $grupo = array(
            'componente_id' => $id,
            'componente_nombre' => $nombre,
            'deleted_at' =>date('Y-m-d h:i:s')

        );

        $data['resultado'] = $this->componentes_model->update($grupo, 'eliminar');

        if ($data['resultado'] != FALSE) {

            //$this->session->set_flashdata('success', 'Se ha Eliminado exitosamente');
            $json['success']= 'Se ha eliminado exitosamente';


        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al eliminar la marca');
            $json['error']= 'Ha ocurrido un error al eliminar el componente';
        }

        if(!isset($json['error'])){

            $this->db->cache_delete( 'componentes' ,'index');
        }

        echo json_encode($json);
    }

    /*solo retorna los componentes en json*/
    function getComponentesJson()
    {
        $json=$this->componentes_model->get_all();
        echo json_encode($json);
    }

}