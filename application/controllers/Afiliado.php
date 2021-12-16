<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class afiliado extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('afiliado/afiliado_model');
        $this->load->model('afiliado/afiliado_descuentos');
        $this->load->model('tipo_producto/tipo_producto_model', 'tipo_producto');
        $this->load->model('unidades/unidades_model');
        $this->load->model('condicionespago/condiciones_pago_model');
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

        $data['empresas'] = $this->afiliado_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/afiliado/tabla', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {

        $data = array();
        $data['tipos'] = $this->tipo_producto->get_all();
        $data['unidades'] = $this->unidades_model->get_unidades();
        $data['condiciones_pago'] = $this->condiciones_pago_model->get_all();
        if ($id != FALSE) {
            $data['empresa'] = $this->afiliado_model->get_by(array('afiliado_id' => $id));
            $tipos = $data['tipos'];
            $unidades = $data['unidades'];
            $afiliado_id = $id;
            $afiliado_descuentos = array();
            foreach ($tipos as $tipo) {

                foreach ($unidades as $unidad) {
                    $descuento = $this->afiliado_descuentos->get_by(array('tipo_prod_id' => $tipo['tipo_prod_id'], 'unidad_id' => $unidad['id_unidad'], 'afiliado_id' => $afiliado_id));
                    if (sizeof($descuento) > 0) {


                        array_push($afiliado_descuentos, $descuento);
                    }
                }

            }


            $data['descuentos'] = $afiliado_descuentos;
        }


        $this->load->view('menu/afiliado/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('afiliado_id');
        $codigo = $this->input->post('afiliado_codigo');
        $nombre = $this->input->post('afiliado_nombre');
        $cartera = $this->input->post('afiliado_monto_cartera');

        $tipos = $this->tipo_producto->get_all();
        $unidades = $this->unidades_model->get_unidades();
        $datos = array(
            'afiliado_nombre' => strtoupper($nombre),
            'afiliado_codigo' => strtoupper($codigo),
            'afiliado_monto_cartera' => strtoupper($cartera),

        );
        if (empty($id)) {
            $data['resultado'] = $this->afiliado_model->set($datos);

            $datos['afiliado_id'] = $data['resultado'];
        } else {
            $datos['afiliado_id'] = $id;
            $data['resultado'] = $this->afiliado_model->update($datos);
        }

        if ($datos['afiliado_id'] != false) {
            $afiliado_id = $datos['afiliado_id'];

            $afiliado_descuentos = array();
            foreach ($tipos as $tipo) {

                foreach ($unidades as $unidad) {
                    $porcentaje = $this->input->post('unidad_' . $tipo['tipo_prod_id'] . '_' . $unidad['id_unidad']);
                    $descuento = array('tipo_prod_id' => $tipo['tipo_prod_id'], 'unidad_id' => $unidad['id_unidad'], 'porcentaje' => $porcentaje, 'afiliado_id' => $afiliado_id);
                    array_push($afiliado_descuentos, $descuento);
                }

            }

            $this->afiliado_descuentos->set_batch($afiliado_descuentos);
        }


        if ($data['resultado'] != FALSE) {

            //  $this->session->set_flashdata('success', 'Solicitud Procesada con exito');
            $json['success'] = 'Solicitud Procesada con exito';

        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al procesar la solicitud');
            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }

        if ($data['resultado'] === NOMBRE_EXISTE || $data['resultado'] === CODIGO_EXISTE) {
            //  $this->session->set_flashdata('error', NOMBRE_EXISTE);
            $json['error'] = $data['resultado'];
        }
        echo json_encode($json);
    }

    function eliminar()
    {
        $id = $this->input->post('afiliado_id');
        $nombre = $this->input->post('afiliado_nombre');

        $grupo = array(
            'afiliado_id' => $id,
            'afiliado_nombre' => $nombre,
            'deleted_at' => date('Y-m-d h:i:s')

        );

        $data['resultado'] = $this->afiliado_model->softDelete($grupo);

        if ($data['resultado'] != FALSE) {

            //$this->session->set_flashdata('success', 'Se ha Eliminado exitosamente');
            $json['success'] = 'Se ha eliminado exitosamente';


        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al eliminar la marca');
            $json['error'] = 'Ha ocurrido un error al eliminar la ubicacoin';
        }

        echo json_encode($json);
    }


}