<?php
use Mike42\Escpos\Printer;
class Soporte extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('venta/venta_model');
        $this->load->model('local/local_model');
        $this->load->model('regimen/regimen_model');
        $this->load->model('producto/producto_model');
        $this->load->model('cliente/cliente_model');
        $this->load->model('tipo_venta/tipo_venta_model');
        $this->load->model('producto/producto_model', 'pd');
        $this->load->library('ReceiptPrint');
        $this->load->model('proveedor/proveedor_model', 'pv');
        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('metodosdepago/metodos_pago_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('zona/zona_model');
        $this->load->model('camiones/camiones_model');
        $this->load->model('venta/venta_estatus_model', 'venta_estatus');
        $this->load->model('historial_pagos_clientes/historial_pagos_clientes_model');
        $this->load->model('consolidadodecargas/consolidado_model');
        $this->load->model('banco/banco_model');
        $this->load->model('liquidacioncobranza/liquidacion_cobranza_model');
        $this->load->model('ingreso/ingreso_model');
        $this->load->model('gastos/gastos_model');
        $this->load->model('unidades/unidades_model');
        $this->load->model('resolucion/resolucion_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('drogueria_relacionada/drogueria_relacionada_model');
        $this->load->model('tipo_anulacion/tipo_anulacion_model');
        $this->load->model('tipo_devolucion/tipo_devolucion_model');
        $this->load->model('venta/ComprobanteDiarioVentas');
        $this->load->model('grupos/grupos_model');
        $this->load->model('domicilios/domicilios_model');
        //   $this->load->library('phpword');
        $this->load->model('system_logs/systemLogsModel');
        $this->load->model('cajas/StatusCajaModel');
        //$this->load->library('Pdf');
        $this->load->library('session');
        //$this->load->library('phpExcel/PHPExcel.php');
        $this->load->library("NuSoap_lib");
        $this->very_sesion();

    }

    function updatefechaventa(){
        $data=array();
        $this->load->view('menu/soporte/ventas/updatefechaventa/index', $data);
    }

    public
    function accionupdatefechaventa()
    {


        $id_desde = $this->input->post('id_desde');
        $id_hasta = $this->input->post('id_hasta');
        $fecha_actualiza = $this->input->post('fecha_actualiza');
        $fecha_actualiza = date('Y-m-d', strtotime($fecha_actualiza)). ' 09:00:00';

        $this->venta_model->update_venta('venta_id BETWEEN '.$id_desde. ' and '.$id_hasta,
            array('fecha' => $fecha_actualiza));

        $this->venta_model->update_venta_backup('venta_id BETWEEN '.$id_desde. ' and '.$id_hasta,
            array('fecha' => $fecha_actualiza));

        echo json_encode(array('success'=>1));
    }

}