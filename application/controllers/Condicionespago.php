<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class condicionespago extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('impuesto/impuestos_model');
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

        $data['condiciones'] = $this->condiciones_pago_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/condicionespago/condicionespago', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        }else{
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {

        $data = array();
        $fe_forms = [];
        if (!empty($this->session->userdata('FACT_E_ALLOW') and $this->session->userdata('FACT_E_ALLOW') === '1')) {
            $fe_forms = $this->impuestos_model->get_fe_payment_forms();
        }
        $data['fe_forms'] = $fe_forms;

        if ($id != FALSE) {
            $data['condicionespago'] = $this->condiciones_pago_model->get_by('id_condiciones', $id);
        }
        $this->load->view('menu/condicionespago/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('id');
        $is_offer = $this->input->post('is_offer');

        $condionespago = array(
            'nombre_condiciones' => $this->input->post('nombre_condiciones'),
            'fe_payment_form_id' => $this->input->post('fe_payment_form_id'),
            'dias' => $this->input->post('dias'),
            'status_condiciones' =>1,
            'is_offer'=>!empty($is_offer)?1:0
        );

        if (empty($id)) {
            $resultado = $this->condiciones_pago_model->insertar($condionespago);
        }
        else{
            $condionespago['id_condiciones'] = $id;
            $resultado = $this->condiciones_pago_model->update($condionespago);
        }

        if ($resultado == TRUE) {
            $json['success'] = 'Solicitud Procesada con exito';
        } else {
            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }

        echo json_encode($json);

    }



    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $condicionespago = array(
            'id_condiciones' => $id,
            'nombre_condiciones' => $nombre . time(),
            'status_condiciones' => 0

        );

        $data['resultado'] = $this->condiciones_pago_model->update($condicionespago);

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Se ha eliminado exitosamente';


        } else {

            $json['error'] ='Ha ocurrido un error al eliminar la Condici&oacute;n';
        }

        echo json_encode($json);
    }


}
