<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class StatusCaja extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/api_model', 'api');
        $this->load->model('cajas/StatusCajaModel');
        $this->load->model('cajas/cajas_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('metodosdepago/metodos_pago_model');
        $this->load->model('venta/ComprobanteDiarioVentas');
        $this->load->model('regimen/regimen_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('historial_pagos_clientes/historial_pagos_clientes_model');
        $this->load->model('venta/venta_model');

        $this->load->model('grupos/grupos_model');
        $this->very_auth();
    }

    function very_auth()
    {
        // Request Header
        $reqHeader = $this->input->request_headers();

        // Key
        $key = null;
        if (isset($reqHeader['X-api-key'])) {
            $key = $reqHeader['X-api-key'];
        } else if ($key_get = $this->get('x-api-key')) {
            $key = $key_get;
        } else if ($key_post = $this->post('x-api-key')) {
            $key = $key_post;
        } else {
            $key = null;
        }

        // Auth ID
        $auth_id = $this->api->getAuth($key);

        // ID ?
        if (!empty($auth_id)) {
            $this->uid = $auth_id;
        } else {
            $this->uid = null;
        }
    }


    // All
    public function index_get()
    {

        $datajson = array();

        $caja = $this->input->get('caja');
        $cajero = $this->input->get('cajero');

        $cajas = $this->StatusCajaModel->getAlBy(
            array(
                'apertura IS NOT null' => NULL,
                'cierre IS NOT null ' => NULL)
        );

        // Pagination Result
        $total = sizeof($cajas);
        $datas = array();
        $where = array();
        $where = array(
            'apertura IS NOT null' => NULL,
            'cierre IS NOT null ' => NULL);
        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = false;
        if (!empty($search['value'])) {
            $where_custom = "(id LIKE '%" . $buscar . "%' or nombre LIKE '%" . $buscar . "%'
            
             or apertura LIKE '%" . $buscar . "%' or cierre LIKE '%" . $buscar . "%'
             or monto_cierre LIKE '%" . $buscar . "%'
             or alias LIKE '%" . $buscar . "%')";
        }

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $select = 'status_caja.apertura, status_caja.cierre, status_caja.id, status_caja.monto_cierre, caja.alias, usuario.nombre';
        $from = "status_caja";
        $join = array('caja', 'usuario');
        $campos_join = array('caja.caja_id=status_caja.caja_id', 'usuario.nUsuCodigo=status_caja.cajero');
        $tipo_join = array(null, null, null, null, 'left');

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'id';
            }

        }

        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->get('length');
        }
        $datas['clientes'] = $this->StatusCajaModel->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        $array = array();

        $count = 0;
        foreach ($datas['clientes'] as $data) {
            $arr = $data;
            $json = array();
            $json[] = $data['id'];
            $json[] = $data['alias'];
            $json[] = $data['nombre'];
            $json[] = $data['apertura'];
            $json[] = $data['cierre'];
            $json[] = $data['monto_cierre'];
            $json[] = '<div class="btn-group"> <a class="btn btn-default" data-toggle="tooltip"
                title="Editar" data-original-title="fa fa-search"
                href="#" onclick="StatusCaja.preview(' . $data['id'] . ');"> <i class="fa fa-search"></i>
                </a><a class="btn btn-default" data-toggle="tooltip"  title="Imprimir" data-original-title="fa fa-comment-o"
                onclick="StatusCaja.imprimir(' . $data['id'] . ');">   <i class="fa fa-print"></i> </a> </div>';


            $datajson[] = $json;

            $count++;
        }

        $datas['clientes'] = $this->StatusCajaModel->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, $order, "RESULT_ARRAY", false, $start, $order_dir, false, $where_custom);


        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($datas['clientes']);
        $array['recordsFiltered'] = sizeof($datas['clientes']); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    public function data_print_get()
    {

        $id = $this->input->get('id');
        $cierrecaja = $this->StatusCajaModel->getBy(array('id' => $id));
        $last_venta = $this->venta_model->get_last(array('caja_id' => $id));
        $first_venta = $this->venta_model->get_first(array('caja_id' => $id));
        $formaspago_o = $this->metodos_pago_model->get_all_by(array('incluye_cuadre_caja' => 1));
        $abonosacarteraresult = $this->historial_pagos_clientes_model->getIngresosByCierreCaja($id);

        $credito = $this->venta_model->get_total_solocredito(array('caja_id' => $id));
        $calculodevoluciones = $this->venta_model->getSalidasByDevoluciones($id);
        $calculodevoluciones_credito = $this->venta_model->getSalidasByDevoluciones($id, false, true);
        $calculoanulaciones = $this->venta_model->getSalidasByAnulaciones($id);
        $calculoanulaciones_credito = $this->venta_model->getSalidasByAnulaciones($id, false, true);
        $formaspago = array();
        foreach ($formaspago_o as $formapago) {
            $formapago['totales'] = $this->StatusCajaModel->getTotalsIngresosByMetodoPago($formapago['id_metodo'], $cierrecaja['apertura'],
                $cierrecaja['cierre'], $formapago['suma_total_ingreso'], $formapago['nombre_metodo'], $id);
            array_push($formaspago, $formapago);
        }

        $totalventascondescuento = $this->venta_model->get_total_ventas_con_descuentos(array('venta_backup.caja_id=' => $id));


        $array = array(
            'cierrecaja' => $cierrecaja,
            'last_venta' => $last_venta,
            'first_venta' => $first_venta,
            'formaspago' => $formaspago,
            'calculodevoluciones' => $calculodevoluciones,
            'calculodevoluciones_credito' => $calculodevoluciones_credito,
            'abonosacarteraresult' => $abonosacarteraresult,
            'credito' => $credito,
            'calculoanulaciones' => $calculoanulaciones,
            'calculoanulaciones_credito' => $calculoanulaciones_credito,
            'totalventascondescuento' =>$totalventascondescuento
        );

        $this->response($array, 200);

    }

    public function data_print_comprobante_diario_get()
    {

        $fechadesde = $this->input->get('fecha_Desde');


        $id = $this->input->get('id');
        $insert = '';
        if(!is_numeric($id)) {
            $insert = $this->ComprobanteDiarioVentas->insert(array(
                'fecha_reporte' => date('Y-m-d', strtotime($fechadesde)),
                'fecha_generado' => date('Y-m-d H:i:s'),
                'usuario_genero_reporte' => $this->input->get('USUARIO_SESSION'),
            ));
        }

        $fecha_impreso = date('d-m-Y h:i A');
        $fecha_generado = date('d-m-Y h:i A');

        $condicionVenta = array();
        $condicionVenta['date(fecha) >='] = date('Y-m-d', strtotime($fechadesde));
        $condicionVenta['date(fecha) <='] = date('Y-m-d', strtotime($fechadesde));


        $totalventascondescuento = $this->venta_model->get_total_ventas_con_descuentos($condicionVenta);


        $fechaSinModificar=$fechadesde;

        if (is_numeric($id)) {

            $reporte = $this->ComprobanteDiarioVentas->get_where(array('id_reporte' => $id));
            $fechadesde=$reporte['fecha_reporte'];
            $fecha_generado=$reporte['fecha_generado'];

            //$fecha_impreso = $reporte['fecha_generado'];
        }else{


        }
        $REGIMEN_CONTRIBUTIVO = $this->regimen_model->get_by(array('regimen_id' => $this->input->get('REGIMEN_CONTRIBUTIVO')));


        $id = $this->input->get('id');
        $cierrecaja = $this->StatusCajaModel->getBy(array('id' => $id));

        $last_venta = $this->venta_model->get_last($condicionVenta);
        $first_venta = $this->venta_model->get_first($condicionVenta);


        $formaspago_o = $this->metodos_pago_model->get_all();
        $abonosacarteraresult = $this->historial_pagos_clientes_model->getIngresosByCierreCaja(false, $fechadesde);

       
        $credito = $this->venta_model->get_total_solocredito($condicionVenta);
       

        $custom_query = " and date(venta_devolucion.fecha_devolucion) >= '" . date('Y-m-d', strtotime($fechadesde))
            . "' and date(venta_devolucion.fecha_devolucion) <='" . date('Y-m-d', strtotime($fechadesde)) . "'";
        $calculodevoluciones = $this->venta_model->getSalidasByDevoluciones(false, $custom_query);
        $calculodevoluciones_credito = $this->venta_model->getSalidasByDevoluciones(false, $custom_query, true);
        $custom_query = " and date(dat_fecha_registro) >= '" . date('Y-m-d', strtotime($fechadesde)) .
            "' and date(dat_fecha_registro) <='" . date('Y-m-d', strtotime($fechadesde)) . "'";
        $calculoanulaciones = $this->venta_model->getSalidasByAnulaciones(false, $custom_query);
        $calculoanulaciones_credito = $this->venta_model->getSalidasByAnulaciones(false, $custom_query, true);
        $formaspago = array();
        foreach ($formaspago_o as $formapago) {

            $formapago['totales'] = $totales = $this->StatusCajaModel->getTotalsIngresosByMetodoPago($formapago['id_metodo'], $fechaSinModificar,
                $fechaSinModificar, $formapago['suma_total_ingreso'], $formapago['nombre_metodo'], false);

            array_push($formaspago, $formapago);
        }
        $impuestos_o = $this->impuestos_model->get_impuestos();
        $impuestos=array();

        $condicionVenta = array(
            'venta_status' => COMPLETADO
        );
        $condicionVenta['date(fecha) >='] = date('Y-m-d', strtotime($fechaSinModificar));
        $condicionVenta['date(fecha) <='] = date('Y-m-d', strtotime($fechaSinModificar));
        
        foreach ($impuestos_o as $impuesto) {

            $total = $this->venta_model->getTotalesByImpuestos($impuesto['porcentaje_impuesto'], $condicionVenta);
            $total_otros = $this->venta_model->getTotalesByOtroImpuestos($impuesto['id_impuesto'], $condicionVenta);
            $impuesto['totales'] = $total;
            $impuesto['totales_otros'] = $total_otros;
            array_push($impuestos, $impuesto);
        }
        $grupos_o = $this->grupos_model->get_grupos();
        $grupos = array();
        foreach ($grupos_o as $grupo) {

            $total = $this->venta_model->getTotalesByGrupo($grupo['id_grupo'], $condicionVenta);
            $grupo['totales'] = $total;
            array_push($grupos, $grupo);
        }

        $condicionVenta['date(fecha) >='] = date('Y-m-d',strtotime($fechaSinModificar));
        $condicionVenta['date(fecha) <='] = date('Y-m-d',strtotime($fechaSinModificar));

        $totales_reales_backup = $this->venta_model->get_totales_reales_backup($condicionVenta);

        $array = array(

            'REGIMEN_CONTRIBUTIVO' => $REGIMEN_CONTRIBUTIVO,
            'grupos' => $grupos,
            'cierrecaja' => $cierrecaja,
            'last_venta' => $last_venta,
            'first_venta' => $first_venta,
            'formaspago' => $formaspago,
            'calculodevoluciones' => $calculodevoluciones,
            'calculodevoluciones_credito' => $calculodevoluciones_credito,
            'credito' => $credito,
            'abonosacarteraresult' => $abonosacarteraresult,
            'calculoanulaciones' => $calculoanulaciones,
            'calculoanulaciones_credito' => $calculoanulaciones_credito,
            'impuestos' => $impuestos,
            'fecha_Desde' => $fechadesde,
            'insert' => $insert,
            'fecha_impreso' => $fecha_impreso,
            'fecha_generado' => $fecha_generado,
            'totalesreales' => $totales_reales_backup,
            'totalventascondescuento' =>$totalventascondescuento

        );

        $this->response($array, 200);

    }

}