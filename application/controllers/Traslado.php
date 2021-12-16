<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class traslado extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('traslado/traslado_model');
        $this->load->model('local/local_model');
        $this->load->model('unidades/unidades_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('producto/producto_model');
        $this->very_sesion();
    }

    function index()
    {

        $data['locales'] = $this->local_model->get_all();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/traslado/traslado', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($traslado_id,$localselec)
    {
        //$localselec es para colocar un local por defecto

        $data = array();

        $header = '';
        $data['detalle']=array();
        $data['productosDetalle']=array();
        if ($traslado_id == false || $traslado_id == 'false') {

            $header = '<div class="row bg-title"><div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Traslado</h4></div>
             <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
             <ol class="breadcrumb">
                <li><a href="">SID</a></li>
            <li class="active">Nuevo Traslado</li></ol></div></div>';
        }else{

            $where=array(
                'id_traslado'=>$traslado_id
            );
            $data['detalle']=$this->traslado_model->getDetalleTraslado($where);
            $where=array(
                'traslado_id'=>$traslado_id
            );
            $data['productosDetalle']=$this->traslado_model->productosDetalle($where);

        }

        $data['header']=$header;
        $data['locales'] = $this->local_model->get_all();
        $data['unidades_medida'] = $this->unidades_model->get_unidades();

        $data['local_select'] =$localselec;

        $this->load->view('menu/traslado/form', $data);
    }

    function registrarTraslado()
    {

        $json = array();
        if ($this->input->is_ajax_request()) {

            $lst_producto=json_decode($this->input->post('lst_producto'));

            if (count($lst_producto)>0) {

                $rs = $this->traslado_model->procesarTraslado($lst_producto);


                if(isset($rs['error'])){

                    $json["error"]=$rs['error'];
                }elseif ($rs == false) {

                    $json['error'] = "Ha ocurrido un error al procesar el Traslado";
                } else {

                    $json['success'] = 'Solicitud Procesada con exito';
                    $json['id'] = $rs;

                }

            } else {
                $json['error'] = "Debe seleccionar al menos un producto";

            }
        } else {

            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }
        echo json_encode($json);
    }

    function buscarTraslados()
    {
        $condicion = array();
        $data=array();
        if ($this->input->post('local_salida') != "TODOS") {
            $condicion = array('local_salida' => $this->input->post('local_salida'));
            $data['local_salida'] = $this->input->post('local_salida');
        }
        if ($this->input->post('local_destino') != "TODOS") {
            $condicion['local_destino'] = $this->input->post('local_destino');
            $data['local_destino'] = $this->input->post('local_destino');
        }

        $tring="'%d-%m-%Y %H:%i:%s'"; // esto formatea de una vez las fechas
        if ($this->input->post('fecIni') != "") {

            $condicion['DATE_FORMAT(fecha, '.$tring.') >= '] = date('d-m-Y H:i:s', strtotime($this->input->post('fecIni') . " 00:00:00"));
            $data['fecha_desde'] = date('Y-m-d H:i:s', strtotime($this->input->post('fecIni') . " 00:00:00"));
        }
        if ($this->input->post('fecFin') != "") {

            $condicion['DATE_FORMAT(fecha, '.$tring.') <='] = date('d-m-Y H:i:s', strtotime($this->input->post('fecFin') . " 23:59:59"));
            $data['fecha_hasta'] = date('Y-m-d H:i:s', strtotime($this->input->post('fecFin') . " 23:59:59"));
        }

        $data['traslados'] = $this->traslado_model->buscarTraslados($condicion);

        echo json_encode($data);

    }

}