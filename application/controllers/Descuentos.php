<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class descuentos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->very_sesion();
        $this->load->model('descuentos/descuentos_model');
        $this->load->model('producto/producto_model');
        $this->load->model('unidades/unidades_model');


    }

    function index()
    {
        //$data="";

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }
        $data["descuentos"] = $this->descuentos_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/descuentos/descuentos', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        }else{
            $this->load->view('menu/template', $dataCuerpo);
        }
    }
    function verReglaDescuento($id){

        $data['escalas'] = $this->descuentos_model->get_escalas_descuento($id);
        $data['escalas_h'] = $this->descuentos_model->get_escalas_descuento_head($id);
        $this->load->view('menu/descuentos/reglaDescuento',$data);

    }
    function form($id = FALSE)
    {

        $datax = array();
        $group = "producto.producto_id";

        if ($id != FALSE) {
            $datax['descuentos'] = $this->descuentos_model->get_by('descuento_id', $id);


            $datax['escalas'] = $this->descuentos_model->get_escalas_by_descuento($id);
            $where = " where  descuentos.descuento_id='" . $id . "'";
            $datax['productosnoagrupados'] = $this->descuentos_model->edit_descuentos($where, false);
            $datax['sizenoagrupados'] = sizeof($datax['productosnoagrupados']);
            $datax['sizeescalas'] = sizeof($datax['escalas']);


        }
        $datax['productosenreglasdedescuento'] = $this->descuentos_model->edit_descuentos('where descuentos.status=1', $group);


        $datax["lstProducto"] = $this->producto_model->select_all_producto();

        $this->load->view('menu/descuentos/form', $datax);

    }

    function listado($id = FALSE)
    {

        $da['escalas'] = $this->descuentos_model->get_escalas_by_descuento($id);
        $where = " where  descuentos.descuento_id='" . $id . "'";
        $da['prod'] = $this->descuentos_model->edit_descuentos($where, false);

        header('Content-Type: application/json');
        echo json_encode($da);
    }

    function lista_descuento(){

        $id = $this->input->post('desID');
        $condicion = false;
        if ($this->input->post('id_des') != "") {
            $condicion['producto_id'] = $this->input->post('id_des');
        }
        if ($this->input->post('nombre_des') != "") {
            $condicion['producto_nombre'] = $this->input->post('nombre_des');
        }
        $data['escalas_h'] = $this->descuentos_model->get_escalas_descuento_head_list($id,$condicion);
        $data['escalas'] = $this->descuentos_model->get_escalas_descuento_list($id,$condicion);

        $this->load->view('menu/descuentos/lista_descuento', $data);
    }
    function guardar()
    {

        $id = $this->input->post('id');

        $descuento = array(
            'nombre' => $this->input->post('nombre'),
        );

        if (empty($id)) {
            $resultado = $this->descuentos_model->insertar($descuento);

        } else {
            $descuento['descuento_id'] = $id;
            $resultado = $this->descuentos_model->update($descuento);
        }

        if ($resultado == TRUE) {
            $json['success']= 'Solicitud Procesada con exito';
        } else {
            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }

        echo json_encode($json);

    }


    function eliminar()
    {
        $id = $this->input->post('id');

        $descuento = array(
            'descuento_id' => $id,
            'status' => 0

        );

        $data['resultado'] = $this->descuentos_model->delete($descuento);

        if ($data['resultado'] != FALSE) {

            $json['success'] ='Se ha eliminado exitosamente';


        } else {

            $json['error']= 'Ha ocurrido un error al eliminar el descuento';
        }

        echo json_encode($json);
    }

    function get_by_descuento()
    {
        if ($this->input->is_ajax_request()) {
            $descuento_id = $this->input->post('descuento_id');

            $descuento = $this->descuentos_model->get_by('descuento_id', $descuento_id);

            echo json_encode($descuento);
        } else {
            redirect(base_url . 'principal');
        }
    }

    function get_unidades_has_producto(){

        $id_producto=$this->input->post('id_producto');
        $data['unidades']=$this->unidades_model->get_by_producto($id_producto);
        header('Content-Type: application/json');
        echo json_encode( $data );
    }

    function registrar_descuento()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('nombre', 'nombre', 'required');

            if ($this->form_validation->run() == false) {
                $json['error'] = 'Algunos campos son requeridos';
            } else {

                $comp_cab_pie = array(
                    'nombre' => $this->input->post('nombre', true),
                );

                if ($this->input->post('id_de_descuento') == "") {


                    $rs = $this->descuentos_model->insertar_descuento($comp_cab_pie,
                        json_decode($this->input->post('lst_escalas', true)),
                        json_decode($this->input->post('lst_producto', true)),
                        $this->input->post('precio'));

                } else {

                    $comp_cab_pie["descuento_id"] = $this->input->post('id_de_descuento');
                    $rs = $this->descuentos_model->actualizar_descuento($comp_cab_pie,
                        json_decode($this->input->post('lst_escalas', true)),
                        json_decode($this->input->post('lst_producto', true)),
                        $this->input->post('precio'));
                }
                    if ($rs != false) {
                        $json['success'] = 'Solicitud Procesada con exito';
                        $json['id'] = $rs;

                    } else {
                        $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
                    }
            }
        } else {


            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';


        }
        echo json_encode($json);
    }


}
