<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class Ingreso extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/api_model', 'api');
        $this->load->model('cajas/StatusCajaModel');
        $this->load->model('cajas/cajas_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('metodosdepago/metodos_pago_model');
        $this->load->model('venta/venta_model');
        $this->load->model('tipo_venta/tipo_venta_model');
        $this->load->model('domicilios/domicilios_model');
        $this->load->model('tipo_anulacion/tipo_anulacion_model');
        $this->load->model('tipo_devolucion/tipo_devolucion_model');
        $this->load->model('opciones/opciones_model');
        $this->load->model('cliente/cliente_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->load->model('producto_componente/producto_componente_model');
        $this->load->model('clasificacion/clasificacion_model');
        $this->load->model('producto/producto_model', 'pd');
        $this->load->model('tipo_producto/tipo_producto_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $this->load->model('ingreso/ingreso_model');
        //$this->very_auth();
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

    function index_get()
    {

    }

    // All
    public function ventas_por_fecha_prueba_get()
    {


        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : $this->input->get('fecha_desde');
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : $this->input->get('fecha_hasta');
        $tipos_venta_select = isset($data['tipos_venta_select']) ? $data['tipos_venta_select'] : $this->input->get('tipos_venta_select');
        $tipos_venta_text = isset($data['tipos_venta_text']) ? $data['tipos_venta_text'] : $this->input->get('tipos_venta_text');
        $where = array('venta_status' => COMPLETADO);


        if ($fechadesde != "") {
            $where['date(fecha) >= '] = date('Y-m-d', strtotime($fechadesde));

        }
        if ($fechahasta != "") {
            $where['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));

        }

        if ($tipos_venta_select != "TODOS") {
            $where['vb.venta_tipo'] = $tipos_venta_select;
        }

        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = false;
        /*  if (!empty($search['value'])) {
              $where_custom = "(id LIKE '%" . $buscar . "%'
              or fecha LIKE '%" . $buscar . "%' or gravado LIKE '%" . $buscar . "%'
              or excluido LIKE '%" . $buscar . "%'
              or total LIKE '%" . $buscar . "%')";
          }*/

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'DATE(fecha)';
        $select = 'SQL_CALC_FOUND_ROWS DATE(fecha) as total_filas, SUM(total) as total, 
        (select SUM(venta_devolucion.total) from venta_devolucion
        join venta on venta.venta_id=venta_devolucion.id_venta
        JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`venta`.`venta_tipo`

where date(fecha_devolucion)=date(vb.fecha) ';
        if ($tipos_venta_select != "TODOS") {
            $select .= ' and venta.venta_tipo=' . $tipos_venta_select;
        }
        $select .= ') as devoluciones,  (select SUM(va.total) from venta va
JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`va`.`venta_tipo`

where date(fecha)=date(vb.fecha) ';
        if ($tipos_venta_select != "TODOS") {
            $select .= ' and va.venta_tipo=' . $tipos_venta_select;
        }

        $select .= ' and va.venta_status  = "' . PEDIDO_ANULADO . '"
) as anulaciones, 
vb.venta_id, 
SUM(total_impuesto) as total_impuesto, SUM(excluido) as excluido, SUM(gravado) as gravado, 
            fecha, SUM(descuento_valor) as descuento_valor, SUM(descuento_porcentaje) as descuento_porcentaje,
            (SELECT SUM(descuento) 
FROM detalle_venta  join venta on venta.venta_id=detalle_venta.id_venta
 WHERE (impuesto = 0  OR impuesto IS NULL ) AND date(venta.fecha)=date(vb.fecha) ';
        if ($tipos_venta_select != "TODOS") {
            $select .= ' and venta.venta_tipo=' . $tipos_venta_select;
        }

        $select .= ')AS descexluido, 
(SELECT SUM(descuento) 
FROM detalle_venta join venta on venta.venta_id=detalle_venta.id_venta
 WHERE (impuesto <> 0  AND  impuesto IS NOT NULL ) AND  date(venta.fecha)=date(vb.fecha) ';
        if ($tipos_venta_select != "TODOS") {
            $select .= ' and venta.venta_tipo=' . $tipos_venta_select;
        }
        $select .= ') 
AS desgravado';


        $from = "venta vb";
        $join = array();
        $campos_join = array();
        $tipo_join = array();

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'fecha';
            }
        }

        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->input->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['clientes'] = $this->StatusCajaModel->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        //  echo $this->db->last_query();

        $array = array();

        $count = 0;


        $graficototal = array();
        $grafcoexluido = array();
        $grafcogravado = array();
        $impuestos = $this->impuestos_model->get_impuestos();

        $total_resultados = isset($datas['clientes'][0]) ? $datas['clientes'][0]['total_afectados'] : 0;
        foreach ($datas['clientes'] as $data) {
            $cont_json = 0;
            $graficototal[$count] = array();
            $grafcoexluido[$count] = array();
            $grafcogravado[$count] = array();

            $d = DateTime::createFromFormat('Y-m-d H:i:s', $data['fecha'], new DateTimeZone('UTC'));

            $graficototal[$count][] = $d->getTimestamp() * 1000;
            $graficototal[$count][] = $data['total'];

            $grafcoexluido[$count][] = $d->getTimestamp() * 1000;
            $grafcoexluido[$count][] = $data['excluido'];

            $grafcogravado[$count][] = $d->getTimestamp() * 1000;
            $grafcogravado[$count][] = $data['gravado'];

            $json = array();

            $json['campos_sumar'] = array();

            $json[$cont_json] = date('d-m-Y', strtotime($data['fecha']));
            $cont_json++;

            if ($tipos_venta_text != "TODOS") {
                $json[$cont_json] = $tipos_venta_text;
                $cont_json++;
            }

            $json[$cont_json] = number_format($data['excluido'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['descexluido'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['gravado'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['desgravado'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format(floatval($data['descuento_valor']) + floatval($data['descuento_porcentaje']), 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;

            foreach ($impuestos as $impuesto) {
                $segundaCondicion = array(
                    'venta_status' => COMPLETADO,
                    'date(fecha) >=' => date('Y-m-d', strtotime($data['fecha'])),
                    'date(fecha) <=' => date('Y-m-d', strtotime($data['fecha'])));
                if ($tipos_venta_select != "TODOS") {
                    $segundaCondicion['venta_tipo'] = $tipos_venta_select;
                }
                $totalimp = $this->venta_model->getTotalesByImpuestos(
                    $impuesto['porcentaje_impuesto'],
                    $segundaCondicion
                );


                if ($impuesto['tipo_calculo'] != 'FIJO') {

                    $totiva = is_numeric($totalimp['iva']) ? $totalimp['iva'] : 0;
                    if ($totiva > 0) {
                        $totdeviva = isset($totalimp['devolucion_iva']) ? $totalimp['devolucion_iva'] : 0;
                        $totaniva = isset($totalimp['anulacion_iva']) ? $totalimp['anulacion_iva'] : 0;
                        $totiva = $totiva - ($totdeviva + $totaniva);
                    }
                    if ($totiva < 0) {
                        $totiva = 0;
                    }

                    $json[$cont_json] = number_format($totiva, 2, ',', '.');
                    $json['campos_sumar'][] = $cont_json;
                    $cont_json++;
                    // $multi = $impuesto['porcentaje_impuesto'] != 0 ? $totalimp['iva'] / $impuesto['porcentaje_impuesto'] : 0;
                    // $gravado = is_numeric($totalimp['iva']) ? ($multi) * 100 : 0;
                    $gravado = is_numeric($totalimp['subtotal']) ? $totalimp['subtotal'] : 0;

                    if ($gravado > 0) {
                        $totdevgrav = isset($totalimp['devolucion_gravado']) ? $totalimp['devolucion_gravado'] : 0;
                        $totangrav = isset($totalimp['anulacion_gravado']) ? $totalimp['anulacion_gravado'] : 0;
                        $gravado = $gravado - ($totdevgrav + $totangrav);
                    }
                    if ($gravado < 0) {
                        $gravado = 0;
                    }


                    $json[$cont_json] = number_format($gravado, 2, ',', '.');
                    $json['campos_sumar'][] = $cont_json;
                    $cont_json++;


                } else {
                    $segundaCondicion = array('DATE(fecha)' => date('Y-m-d', strtotime($data['fecha'])));
                    if ($tipos_venta_select != "TODOS") {
                        $segundaCondicion['venta_tipo'] = $tipos_venta_select;
                    }
                    $totalimpOtro = $this->venta_model->getTotalesByOtroImpuestos(
                        $impuesto['id_impuesto'],
                        $segundaCondicion
                    );

                    $totiva = is_numeric($totalimpOtro['iva']) ? $totalimpOtro['iva'] : 0;
                    if ($totiva > 0) {
                        $totdeviva = isset($totalimpOtro['devolucion_iva']) ? $totalimpOtro['devolucion_iva'] : 0;
                        $totaniva = isset($totalimpOtro['anulacion_iva']) ? $totalimpOtro['anulacion_iva'] : 0;
                        $totiva = $totiva - ($totdeviva + $totaniva);
                    }

                    if ($totiva < 0) {
                        $totiva = 0;
                    }


                    $json[$cont_json] = number_format($totiva, '2', ',', '.');
                    $json['campos_sumar'][] = $cont_json;
                    $cont_json++;
                    // $gravado = is_numeric($totalimpOtro['subtotal']) ? $totalimpOtro['subtotal'] : 0;
                }


            }
            $json[$cont_json] = number_format($data['total_impuesto'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['anulaciones'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['devoluciones'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['excluido'] + $data['gravado'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['total'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;


            $datajson[] = $json;

            $count++;
        }


        $array['graficototal'] = $graficototal;
        $array['grafcoexluido'] = $grafcoexluido;
        $array['grafcogravado'] = $grafcogravado;
        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = $total_resultados;
        $array['recordsFiltered'] = $total_resultados; // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    function index_post()
    {

    }

    // All
    public function compras_por_fecha_get()
    {

        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : $this->input->get('fecha_desde');
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : $this->input->get('fecha_hasta');
        $tipos_venta_select = isset($data['tipos_venta_select']) ? $data['tipos_venta_select'] : $this->input->get('tipos_venta_select');
        $tipos_venta_text = isset($data['tipos_venta_text']) ? $data['tipos_venta_text'] : $this->input->get('tipos_venta_text');
        $where = array('ingreso_status' => COMPLETADO);


        if ($fechadesde != "") {
            $where['date(fecha_registro) >= '] = date('Y-m-d', strtotime($fechadesde));

        }
        if ($fechahasta != "") {
            $where['date(fecha_registro) <='] = date('Y-m-d', strtotime($fechahasta));

        }

        if ($tipos_venta_select != "TODOS") {
            $where['vb.int_Proveedor_id'] = $tipos_venta_select;
        }

        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = false;
        /*  if (!empty($search['value'])) {
              $where_custom = "(id LIKE '%" . $buscar . "%'
              or fecha LIKE '%" . $buscar . "%' or gravado LIKE '%" . $buscar . "%'
              or excluido LIKE '%" . $buscar . "%'
              or total LIKE '%" . $buscar . "%')";
          }*/

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'DATE(fecha_registro)';
        $select = 'SQL_CALC_FOUND_ROWS DATE(fecha_registro) as total_filas, SUM(total_ingreso) as total, 
 
        
SUM(impuesto_ingreso) as total_impuesto,         
SUM(sub_total_ingreso) as gravado, 
            fecha_registro,
            ';


        $select .= '(SELECT SUM(detalleingreso.total_con_descuento) 
FROM detalleingreso  
join ingreso  on ingreso.id_ingreso=detalleingreso.id_ingreso
 WHERE (detalleingreso.total_impuesto >0 ) 
  and date(ingreso.fecha_registro)=date(vb.fecha_registro) and ingreso.ingreso_status  = "' . COMPLETADO . '" ';
        if ($tipos_venta_select != "TODOS") {
            $select .= ' and ingreso.int_Proveedor_id=' . $tipos_venta_select;
        }

        $select .= ')AS gravado,';


        $select .= '(SELECT SUM(detalleingreso.total_con_descuento) 
FROM detalleingreso 
join ingreso  on ingreso.id_ingreso=detalleingreso.id_ingreso 
 WHERE  (detalleingreso.total_impuesto <=0 )and date(ingreso.fecha_registro)=date(vb.fecha_registro) and ingreso.ingreso_status  = "' . COMPLETADO . '"  ';
        if ($tipos_venta_select != "TODOS") {
            $select .= ' and ingreso.int_Proveedor_id=' . $tipos_venta_select;
        }

        $select .= ')AS excluido';


        //echo $select;

        $from = "ingreso vb";
        $join = array();
        $campos_join = array();
        $tipo_join = array();

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'fecha_registro';
            }
        }

        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->input->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['clientes'] = $this->StatusCajaModel->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        //  echo $this->db->last_query();

        $array = array();

        $count = 0;


        $graficototal = array();
        $grafcoexluido = array();
        $grafcogravado = array();
        $impuestos = $this->impuestos_model->get_impuestos();

        $total_resultados = isset($datas['clientes'][0]) ? $datas['clientes'][0]['total_afectados'] : 0;
        foreach ($datas['clientes'] as $data) {
            $cont_json = 0;
            $graficototal[$count] = array();
            $grafcoexluido[$count] = array();
            $grafcogravado[$count] = array();

            $d = DateTime::createFromFormat('Y-m-d H:i:s', $data['fecha_registro'], new DateTimeZone('UTC'));

            $graficototal[$count][] = $d->getTimestamp() * 1000;
            $graficototal[$count][] = $data['total'];

            $grafcoexluido[$count][] = $d->getTimestamp() * 1000;
            $grafcoexluido[$count][] = $data['excluido'];

            $grafcogravado[$count][] = $d->getTimestamp() * 1000;
            $grafcogravado[$count][] = $data['gravado'];

            $json = array();

            $json['campos_sumar'] = array();

            $json[$cont_json] = date('d-m-Y', strtotime($data['fecha_registro']));
            $cont_json++;

            if ($tipos_venta_text != "TODOS") {
              //  $json[$cont_json] = $tipos_venta_text;
               // $cont_json++;
            }




            $totalrealgravdo = 0;
            $totalrealexcluido = 0;
            $totalrealiva = 0;
            foreach ($impuestos as $impuesto) {
                $segundaCondicion = array(
                    'ingreso_status' => COMPLETADO,
                    'date(fecha_registro) >=' => date('Y-m-d', strtotime($data['fecha_registro'])),
                    'date(fecha_registro) <=' => date('Y-m-d', strtotime($data['fecha_registro'])));
                if ($tipos_venta_select != "TODOS") {
                    $segundaCondicion['ingreso.int_Proveedor_id'] = $tipos_venta_select;
                }
                $totalimp = $this->ingreso_model->getTotalesByImpuestos(
                    $impuesto['id_impuesto'],
                    $segundaCondicion
                );


                if ($impuesto['tipo_calculo'] != 'FIJO') {

                    $totiva = is_numeric($totalimp['iva']) ? $totalimp['iva'] : 0;
                    if ($totiva > 0) {
                        $totdeviva = isset($totalimp['devolucion_iva']) ? $totalimp['devolucion_iva'] : 0;
                        $totaniva = isset($totalimp['anulacion_iva']) ? $totalimp['anulacion_iva'] : 0;
                        $totiva = $totiva - ($totdeviva + $totaniva);
                    }
                    if ($totiva < 0) {
                        $totiva = 0;
                    }

                    $json[$cont_json] = number_format($totiva, 2, ',', '.');
                    $json['campos_sumar'][] = $cont_json;
                    $cont_json++;
                    // $multi = $impuesto['porcentaje_impuesto'] != 0 ? $totalimp['iva'] / $impuesto['porcentaje_impuesto'] : 0;
                    // $gravado = is_numeric($totalimp['iva']) ? ($multi) * 100 : 0;
                    $gravado = is_numeric($totalimp['subtotal']) ? $totalimp['subtotal'] : 0;

                    if ($gravado > 0) {
                        $totdevgrav = isset($totalimp['devolucion_gravado']) ? $totalimp['devolucion_gravado'] : 0;
                        $totangrav = isset($totalimp['anulacion_gravado']) ? $totalimp['anulacion_gravado'] : 0;
                        //
                        $gravado = $gravado - ($totdevgrav + $totangrav);
                    }
                    if ($gravado < 0) {
                        $gravado = 0;
                    }


                    $json[$cont_json] = number_format($gravado, 2, ',', '.');
                    $json['campos_sumar'][] = $cont_json;
                    $cont_json++;


                    $excluido = is_numeric($totalimp['subtotal']) ? $totalimp['subtotal'] : 0;

                    if ($gravado > 0) {
                        $totdevgrav = isset($totalimp['devolucion_gravado']) ? $totalimp['devolucion_gravado'] : 0;
                        $totangrav = isset($totalimp['anulacion_gravado']) ? $totalimp['anulacion_gravado'] : 0;
                        //
                        $gravado = $gravado - ($totdevgrav + $totangrav);
                    }
                    if ($gravado < 0) {
                        $gravado = 0;
                    }


                    $totalrealgravdo = $totalrealgravdo + $gravado;
                    $totalrealiva = $totalrealgravdo + $totiva;
                    $totalrealexcluido = $totalrealexcluido + $totiva;

                } else {
                    $segundaCondicion = array('DATE(fecha)' => date('Y-m-d', strtotime($data['fecha'])));
                    if ($tipos_venta_select != "TODOS") {
                        $segundaCondicion['venta_tipo'] = $tipos_venta_select;
                    }
                    $totalimpOtro = $this->venta_model->getTotalesByOtroImpuestos(
                        $impuesto['id_impuesto'],
                        $segundaCondicion
                    );

                    $totiva = is_numeric($totalimpOtro['iva']) ? $totalimpOtro['iva'] : 0;
                    if ($totiva > 0) {
                        $totdeviva = isset($totalimpOtro['devolucion_iva']) ? $totalimpOtro['devolucion_iva'] : 0;
                        $totaniva = isset($totalimpOtro['anulacion_iva']) ? $totalimpOtro['anulacion_iva'] : 0;
                        $totiva = $totiva - ($totdeviva + $totaniva);
                    }

                    if ($totiva < 0) {
                        $totiva = 0;
                    }


                    $json[$cont_json] = number_format($totiva, '2', ',', '.');
                    $json['campos_sumar'][] = $cont_json;
                    $cont_json++;
                    // $gravado = is_numeric($totalimpOtro['subtotal']) ? $totalimpOtro['subtotal'] : 0;
                }


            }
            $json[$cont_json] = number_format($data['total_impuesto'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['gravado'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['excluido'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;

            /* $json[$cont_json] = number_format($data['anulaciones'], 2, ',', '.');
             $json['campos_sumar'][] = $cont_json;
             $cont_json++;
             $json[$cont_json] = number_format($data['devoluciones'], 2, ',', '.');
             $json['campos_sumar'][] = $cont_json;
             $cont_json++;*/
            $json[$cont_json] = number_format($data['excluido'] + $data['gravado'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;
            $json[$cont_json] = number_format($data['total'], 2, ',', '.');
            $json['campos_sumar'][] = $cont_json;
            $cont_json++;


            $datajson[] = $json;

            $count++;
        }


        $array['graficototal'] = $graficototal;
        $array['grafcoexluido'] = $grafcoexluido;
        $array['grafcogravado'] = $grafcogravado;
        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = $total_resultados;
        $array['recordsFiltered'] = $total_resultados; // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }


    public function comparativaVendedoresPorFecha_get()
    {


        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';
        $comparar = isset($data['comparar']) ? $data['comparar'] : '';


        $where = array('venta_status' => COMPLETADO);
        $where2 = "";
        if ($fechadesde != "") {
            $where['fecha >= '] = date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0'));
            $where2 .= ' fecha >= "' . date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0')) . '"';

        }
        if ($fechahasta != "") {
            $where['fecha <='] = date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59'));
            $where2 .= ' AND fecha <="' . date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59')) . '"';

        }


        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = false;
        /*  if (!empty($search['value'])) {
              $where_custom = "(id LIKE '%" . $buscar . "%'
              or fecha LIKE '%" . $buscar . "%' or gravado LIKE '%" . $buscar . "%'
              or excluido LIKE '%" . $buscar . "%'
              or total LIKE '%" . $buscar . "%')";
          }*/

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;


        $group = 'DATE(fecha), id_vendedor ';
        if ($comparar == 'COMISION POR VENTAS') {
            $select = '( SELECT SUM( C.comision ) FROM comision_vendedor AS C join detalle_venta dv on dv.id_detalle = C.id_detalle_venta
join venta D on D.venta_id=dv.id_venta
 where ' . $where2 . '  and C.id_vendedor = v.id_vendedor AND DATE(v.`fecha`)=DATE(D.fecha)
) AS total,id_vendedor,  DATE(fecha) as fecha, (select 0) as porcentaje ';

        } else if ($comparar == 'TOTAL VENDIDO') {
            $select = 'SUM(total) as total, id_vendedor,  DATE(fecha) as fecha,';

        } else if ($comparar == 'VENTA COMISIONADA') {
            $select = '( SELECT SUM( dv.subtotal ) FROM comision_vendedor AS C join detalle_venta dv on dv.id_detalle = C.id_detalle_venta
join venta D on D.venta_id=dv.id_venta
 where ' . $where2 . '  and C.id_vendedor = v.id_vendedor AND DATE(v.`fecha`)=DATE(D.fecha)
) AS total,id_vendedor,  DATE(fecha) as fecha, (select 0) as porcentaje ';

        } else {


            $select = 'id_vendedor,  DATE(fecha) as fecha,  
              ( SELECT SUM(A.total)  * 100 / SUM( v.total )
FROM venta AS A WHERE  ' . $where2 . '   AND DATE(v.`fecha`)=DATE(A.fecha) and v.id_vendedor=A.id_vendedor
) AS total';
        }

        $from = " venta as v";
        $join = array();
        $campos_join = array();
        $tipo_join = array();

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'fecha';
            }

        }

        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['datos'] = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        $array = array();

        $count = 0;


        $vendedores = $this->usuario_model->get_all_vendedores();
        $fechaFlag = '';
        foreach ($datas['datos'] as $data) {
            $json = array();

            if ($data['fecha'] != $fechaFlag || $count == 0) {
                $fechaFlag = $data['fecha'];
                $json[] = date('d-m-Y', strtotime($data['fecha']));
                $json['campos_sumar'] = array();
                foreach ($datas['datos'] as $dv) {
                    if ($dv['fecha'] === $data['fecha']) {


                        foreach ($vendedores as $vendedor) {

                            $json[] = 0;
                        }

                        $i = 1;


                        foreach ($vendedores as $vendedor) {

                            if (intval($dv['id_vendedor']) === intval($vendedor['nUsuCodigo'])) {
                                $json[$i] = number_format($dv['total'], 2, ',', '.');
                                $json['campos_sumar'][] = $i;
                            }
                            $i++;
                        }


                    }
                }
                $datajson[] = $json;
                $count++;
            }
        }

        $datagraficoarray = array();

        foreach ($vendedores as $vendedor) {
            $datagrafico = array();
            $datagrafico['label'] = $vendedor['nombre'];
            $datagrafico['data'] = array();

            foreach ($datas['datos'] as $dv) {
                if (intval($dv['id_vendedor']) === intval($vendedor['nUsuCodigo'])) {
                    $d = DateTime::createFromFormat('Y-m-d', $dv['fecha'], new DateTimeZone('UTC'));
                    $objeto = array();
                    $objeto[] = $d->getTimestamp() * 1000;
                    $objeto[] = $dv['total'];


                    array_push($datagrafico['data'], $objeto);

                }

            }

            array_push($datagraficoarray, $datagrafico);

        }


        $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, $order, "RESULT_ARRAY", false, $start, $order_dir, false, $where_custom);


        $array['graficoarray'] = $datagraficoarray;

        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($total);
        $array['recordsFiltered'] = sizeof($total); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    public function comparativaVendedoresPorVendedores_get()
    {


        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';

        $where = array();
        $where = array('venta_status' => COMPLETADO);
        $where2 = "";
        if ($fechadesde != "") {
            $where['fecha >='] = date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0'));
            $where['fecha <='] = date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59'));
            $where2 .= ' fecha >= "' . date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0')) . '"';
            $where2 .= ' AND fecha <="' . date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59')) . '"';

        }


        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = false;
        /*  if (!empty($search['value'])) {
              $where_custom = "(id LIKE '%" . $buscar . "%'
              or fecha LIKE '%" . $buscar . "%' or gravado LIKE '%" . $buscar . "%'
              or excluido LIKE '%" . $buscar . "%'
              or total LIKE '%" . $buscar . "%')";
          }*/

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'id_vendedor';
        $select = 'id_vendedor, usuario.nombre, SUM(total) as total, 
          ( SELECT SUM( B.total ) FROM venta AS B where ' . $where2 . '  AND DATE(v.`fecha`)=DATE(B.fecha) and v.id_vendedor=B.id_vendedor
) AS totalvendedor, 
( SELECT SUM(A.total)  * 100 / SUM( v.total )
FROM venta AS A WHERE  ' . $where2 . '   AND DATE(v.`fecha`)=DATE(A.fecha) and v.id_vendedor=A.id_vendedor
) AS porcentaje,( SELECT SUM( C.comision ) FROM comision_vendedor AS C join detalle_venta dv on dv.id_detalle = C.id_detalle_venta
join venta D on D.venta_id=dv.id_venta 
 where ' . $where2 . ' and C.id_vendedor = v.id_vendedor AND DATE(v.`fecha`)=DATE(D.fecha)
) AS comision, ( SELECT SUM( dv.subtotal ) FROM comision_vendedor AS C join detalle_venta dv on dv.id_detalle = C.id_detalle_venta
join venta D on D.venta_id=dv.id_venta 
 where ' . $where2 . ' and C.id_vendedor = v.id_vendedor AND DATE(v.`fecha`)=DATE(D.fecha)
) AS ventacomisionada ';

        $from = "venta as v";
        $join = array('usuario');
        $campos_join = array('usuario.nUsuCodigo = v.id_vendedor');
        $tipo_join = array(null);

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'fecha';
            }
        }

        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['resultado'] = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        $array = array();

        $count = 0;


        $graficototal = array();
        $graficoporcentaje = array();
        $graficototalvendedor = array();
        $graficocomision = array();

        foreach ($datas['resultado'] as $data) {

            $graficototal[$count] = array();
            $graficoporcentaje[$count] = array();
            $graficototalvendedor[$count] = array();
            $graficocomision[$count] = array();

            $graficototal[$count][] = $data['nombre'];
            $graficototal[$count][] = $data['total'];

            $graficoporcentaje[$count][] = $data['nombre'];
            $graficoporcentaje[$count][] = number_format($data['porcentaje'], 0, ',', '.');

            $graficototalvendedor[$count][] = $data['nombre'];
            $graficototalvendedor[$count][] = $data['totalvendedor'];

            $graficocomision[$count][] = $data['nombre'];
            $graficocomision[$count][] = ($data['comision'] == null) ? 0 : $data['comision'];

            $json = array();
            $json[] = $data['nombre'];
            //  $json[] = number_format($data['porcentaje'], 0, ',', '.');
            //$json[] = number_format($data['totalvendedor'], 2, ',', '.');
            $json[] = number_format($data['total'], 2, ',', '.');
            $json[] = number_format($data['comision'], 2, ',', '.');
            $json[] = number_format($data['ventacomisionada'], 2, ',', '.');
            $json['campos_sumar'] = array();
            $json['campos_sumar'][] = 1;
            $json['campos_sumar'][] = 2;
            $json['campos_sumar'][] = 3;

            $datajson[] = $json;

            $count++;
        }

        $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, $order, "RESULT_ARRAY", false, $start, $order_dir, false, $where_custom);


        $array['graficototal'] = $graficototal;
        $array['grafcoporcentaje'] = $graficoporcentaje;
        $array['grafcototalvendedor'] = $graficototalvendedor;
        $array['grafcocomision'] = $graficocomision;
        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($total);
        $array['recordsFiltered'] = sizeof($total); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    public function participacion_vendedores_get()
    {


        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';
        $vendedor = !empty($data['vendedor']) ? $data['vendedor'] : 0;


        $where = array('v.venta_status' => COMPLETADO);
        $where2 = "";
        if ($fechadesde != "") {
            $where['date(fecha) >= '] = date('Y-m-d', strtotime($fechadesde));
            $where2 .= ' date(fecha) >= "' . date('Y-m-d', strtotime($fechadesde)) . '"';

        }
        if ($fechahasta != "") {
            $where['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));
            $where2 .= ' AND date(fecha) <="' . date('Y-m-d', strtotime($fechahasta)) . '"';

        }


        if ($vendedor !== 0) {

            $where['v.id_vendedor'] = $vendedor;
            //$where2 .=' AND A.id_vendedor = ' . $vendedor ;

        }

        $where2 .= " and venta_status = '" . COMPLETADO . "'";


        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = false;
        /*  if (!empty($search['value'])) {
              $where_custom = "(id LIKE '%" . $buscar . "%'
              or fecha LIKE '%" . $buscar . "%' or gravado LIKE '%" . $buscar . "%'
              or excluido LIKE '%" . $buscar . "%'
              or total LIKE '%" . $buscar . "%')";
          }*/

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'DATE(fecha), v.id_vendedor';


        $select = 'fecha,  ( SELECT SUM( B.total ) FROM venta AS B where ' . $where2 . 'AND DATE(v.`fecha`)=DATE(B.fecha)
) AS total, v.venta_id,usuario.username,  documento_venta.documento_Numero, ( SELECT SUM(A.total)  * 100 / SUM( v.total )
FROM venta AS A WHERE  ' . $where2 . ' AND A.id_vendedor = v.id_vendedor   AND DATE(v.`fecha`)=DATE(A.fecha)
) AS porcentaje, 
( SELECT SUM( B.total ) FROM venta AS B where ' . $where2 . ' AND B.id_vendedor = v.id_vendedor AND DATE(v.`fecha`)=DATE(B.fecha)
) AS totalvendedor,
( SELECT SUM( B.total_impuesto ) FROM venta AS B where ' . $where2 . ' AND B.id_vendedor = v.id_vendedor AND DATE(v.`fecha`)=DATE(B.fecha)
) AS totalvendedoriva, ( SELECT SUM( B.excluido ) FROM venta AS B where ' . $where2 . ' AND B.id_vendedor = v.id_vendedor AND DATE(v.`fecha`)=DATE(B.fecha)
) AS totalvendedorexcluido, ( SELECT SUM( B.gravado ) FROM venta AS B where ' . $where2 . ' AND B.id_vendedor = v.id_vendedor AND DATE(v.`fecha`)=DATE(B.fecha)
) AS totalvendedorgravado,  ( SELECT SUM(((dv.subtotal-dv.descuento) * C.porcentaje) /100 ) FROM comision_vendedor AS C join detalle_venta dv on dv.id_detalle = C.id_detalle_venta
join venta D on D.venta_id=dv.id_venta
 where ' . $where2 . ' AND (C.id_vendedor = v.id_vendedor OR D.id_vendedor=' . $vendedor . ') AND DATE(v.`fecha`)=DATE(D.fecha)
) AS comision,
( SELECT SUM( dv.subtotal - dv.descuento ) FROM comision_vendedor AS C join detalle_venta dv on dv.id_detalle = C.id_detalle_venta
join venta D on D.venta_id=dv.id_venta
 where ' . $where2 . ' AND (C.id_vendedor = v.id_vendedor OR D.id_vendedor=' . $vendedor . ') AND DATE(v.`fecha`)=DATE(D.fecha)
) AS ventacomisionadavendedor ';


        //echo $select;
        $from = "venta as v";
        $join = array('documento_venta', 'usuario');
        $campos_join = array('documento_venta.id_venta=v.venta_id', 'usuario.nUsuCodigo=v.id_vendedor');
        $tipo_join = array('left', false);

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'fecha';
            }

        }

        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->input->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['clientes'] = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        // echo $this->db->last_query();
        $array = array();

        $count = 0;


        $graficototal = array();
        $graficoporcentaje = array();
        $graficototalvendedor = array();
        $graficocomision = array();
        foreach ($datas['clientes'] as $data) {

            $graficototal[$count] = array();
            $graficoporcentaje[$count] = array();
            $graficototalvendedor[$count] = array();
            $graficocomision[$count] = array();

            $d = DateTime::createFromFormat('Y-m-d H:i:s', $data['fecha'], new DateTimeZone('UTC'));

            $graficototal[$count][] = $d->getTimestamp() * 1000;

            $graficototal[$count][] = $data['total'];

            if ($data['totalvendedor'] > 0) {
                $porcentaje = $data['totalvendedor'] * 100 / $data['total'];
            } else {
                $porcentaje = 0;
            }
            $graficoporcentaje[$count][] = $d->getTimestamp() * 1000;
            $graficoporcentaje[$count][] = number_format($porcentaje, 0, ',', '.');

            $graficototalvendedor[$count][] = $d->getTimestamp() * 1000;
            $graficototalvendedor[$count][] = $data['totalvendedor'];

            $graficocomision[$count][] = $d->getTimestamp() * 1000;
            $graficocomision[$count][] = ($data['comision'] == null) ? 0 : $data['comision'];


            $json = array();
            $json[] = date('d-m-Y', strtotime($data['fecha']));
            $json[] = $data['username'];


            $json[] = number_format($porcentaje, 0, ',', '.');

            $json[] = number_format($data['totalvendedor'], 2, ',', '.');
            $json[] = number_format($data['totalvendedorgravado'], 2, ',', '.');
            $json[] = number_format($data['totalvendedorexcluido'], 2, ',', '.');
            $json[] = number_format($data['totalvendedoriva'], 2, ',', '.');
            $json[] = number_format($data['totalvendedorgravado'] + $data['totalvendedorexcluido'], 2, ',', '.');
            // $json[] = number_format($data['total'], 2, ',', '.');
            $json[] = number_format($data['ventacomisionadavendedor'], 2, ',', '.');
            $json[] = number_format($data['comision'], 2, ',', '.');

            $json['campos_sumar'] = array();
            $json['campos_sumar'][] = 2;
            $json['campos_sumar'][] = 3;
            $json['campos_sumar'][] = 4;
            $json['campos_sumar'][] = 5;
            $json['campos_sumar'][] = 6;
            $json['campos_sumar'][] = 7;
            $json['campos_sumar'][] = 8;
            $json['campos_sumar'][] = 9;

            $datajson[] = $json;

            $count++;
        }

        $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, $order, "RESULT_ARRAY", false, $start, $order_dir, false, $where_custom);


        $array['graficototal'] = $graficototal;
        $array['grafcoporcentaje'] = $graficoporcentaje;
        $array['grafcototalvendedor'] = $graficototalvendedor;
        $array['grafcocomision'] = $graficocomision;
        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($total);
        $array['recordsFiltered'] = sizeof($total); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    public function participacion_vendedoresProducto_get()
    {


        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';
        $vendedor = isset($data['vendedor']) ? $data['vendedor'] : '';
        $subcategoria = isset($data['subcategoria']) ? $data['subcategoria'] : '';
        $categoria = isset($data['categoria']) ? $data['categoria'] : '';
        $comisionados = isset($data['comisionados']) ? $data['comisionados'] : '';


        $where = array('v.venta_status' => COMPLETADO);
        $where2 = "";
        if ($fechadesde != "") {
            $where['fecha >= '] = date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0'));
            $where2 .= ' fecha >= "' . date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0')) . '"';

        }
        if ($fechahasta != "") {
            $where['fecha <='] = date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59'));
            $where2 .= ' AND fecha <="' . date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59')) . '"';

        }

        if ($vendedor != "") {

            $where['v.id_vendedor'] = $vendedor;
            // $where2 .=' AND A.id_vendedor = ' . $vendedor ;

        }

        if ($comisionados == "SI") {

            $where['comision >'] = 0;

        }
        if ($comisionados == "NO") {

            $where['comision'] = NULL;

        }


        if ($subcategoria != "") {


            if ($categoria == "GRUPO") {

                $where['producto.produto_grupo'] = $subcategoria;
            }


            if ($categoria == "CLASIFICACION") {

                $where['producto.producto_clasificacion'] = $subcategoria;
            }

            if ($categoria == "TIPO") {

                $where['producto.producto_tipo'] = $subcategoria;
            }

            if ($categoria == "COMPONENTE") {

                $where['producto_has_componente.componente_id'] = $subcategoria;
            }

            if ($categoria == "UBICACION_FISICA") {

                $where['producto.producto_ubicacion_fisica'] = $subcategoria;
            }

            if ($categoria == "IMPUESTO") {

                $where['producto.producto_impuesto'] = $subcategoria;
            }


            // $where2 .=' AND A.id_vendedor = ' . $vendedor ;

        }
        $where2 .= " and venta_status = '" . COMPLETADO . "'";


        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = false;
        /*  if (!empty($search['value'])) {
              $where_custom = "(id LIKE '%" . $buscar . "%'
              or fecha LIKE '%" . $buscar . "%' or gravado LIKE '%" . $buscar . "%'
              or excluido LIKE '%" . $buscar . "%'
              or total LIKE '%" . $buscar . "%')";
          }*/

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $group = false;


        $select = 'fecha, producto_id,detalle_venta.id_detalle, comision_vendedor.id_detalle_venta, 
        v.venta_id, producto.producto_impuesto, comision_vendedor.porcentaje as porcentaje_comision,
         comision, detalle_venta.impuesto, detalle_venta.subtotal,detalle_venta.descuento,
         producto.producto_codigo_interno,  producto.producto_nombre, usuario.username, documento_venta.documento_Numero ';


        $from = "venta as v";
        $join = array('documento_venta', 'usuario', 'detalle_venta', 'producto', 'comision_vendedor');
        $campos_join = array('documento_venta.id_venta=v.venta_id', 'usuario.nUsuCodigo=v.id_vendedor',
            'detalle_venta.id_venta=v.venta_id',
            'producto.producto_id=detalle_venta.id_producto',
            'comision_vendedor.id_detalle_venta=detalle_venta.id_detalle');
        $tipo_join = array(false, false, false, false, 'left');

        $ordenar = $this->input->get('order');
        $order = 'venta_id, producto_id';
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'fecha';
            }

        }

        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->input->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['clientes'] = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        //echo $this->db->last_query();
        $array = array();

        $count = 0;


        $graficototal = array();
        $graficoporcentaje = array();
        $graficototalvendedor = array();
        $graficocomision = array();
        $impuestos = $this->impuestos_model->get_impuestos();
        $unidades = $this->unidades_model->get_unidades();


        foreach ($datas['clientes'] as $data) {
            if (($data['subtotal'] == 0 && $data['descuento'] == 0) == false):
                $cont_json = 0;
                $graficototal[$count] = array();
                $graficoporcentaje[$count] = array();
                $graficototalvendedor[$count] = array();
                $graficocomision[$count] = array();
                $unidades_has = $this->venta_model->get_detalle_venta_unidad($data['id_detalle']);

                $d = DateTime::createFromFormat('Y-m-d H:i:s', $data['fecha'], new DateTimeZone('UTC'));

                $json['campos_sumar'] = array();

                $json = array();
                $json[$cont_json] = date('d-m-Y', strtotime($data['fecha']));
                $cont_json++;
                $subtotal = $data['subtotal'] - $data['descuento'];
                $json[$cont_json] = $data['documento_Numero'];
                $cont_json++;
                $json[$cont_json] = $data['username'];
                $cont_json++;
                $json[$cont_json] = $data['producto_codigo_interno'];
                $cont_json++;
                $json[$cont_json] = $data['producto_nombre'];
                $cont_json++;


                foreach ($unidades as $unidad) {

                    $existe = false;
                    foreach ($unidades_has as $unidades_ha) {

                        if ($unidades_ha['unidad_id'] == $unidad['id_unidad']) {
                            $json[$cont_json] = $unidades_ha['cantidad'];
                            $cont_json++;
                            $existe = true;
                        }
                    }
                    if (!$existe) {
                        $json[$cont_json] = 0;
                        $cont_json++;
                    }
                }


                $json['campos_sumar'][] = $cont_json;
                $json[$cont_json] = $subtotal;
                $cont_json++;

                ///
                $baseexcluida = 0;
                $basegravada = 0;

                if ($data['impuesto'] > 0) {
                    $basegravada = $subtotal;
                } else {
                    $baseexcluida = $subtotal;
                }

                $json[$cont_json] = number_format($baseexcluida, 2, ',', '.');
                $json['campos_sumar'][] = $cont_json;
                $cont_json++;
                $json[$cont_json] = number_format($basegravada, 2, ',', '.');
                $json['campos_sumar'][] = $cont_json;
                $cont_json++;

                foreach ($impuestos as $impuesto) {

                    if ($impuesto['id_impuesto'] == $data['producto_impuesto'] && $data['impuesto'] > 0) {

                        $json[$cont_json] = $data['impuesto'];
                        $json['campos_sumar'][] = $cont_json;
                        $cont_json++;

                        $json[$cont_json] = $basegravada;
                        $json['campos_sumar'][] = $cont_json;
                        $cont_json++;
                    } else {
                        $json[$cont_json] = number_format(0, '2', ',', '.');
                        $json['campos_sumar'][] = $cont_json;
                        $cont_json++;
                        $json[$cont_json] = number_format(0, '2', ',', '.');
                        $json['campos_sumar'][] = $cont_json;
                        $cont_json++;
                    }


                }

                $json[$cont_json] = number_format($data['impuesto'], 2, ',', '.');
                $json['campos_sumar'][] = $cont_json;
                $cont_json++;
                ////////////////////////////////////


                $json[$cont_json] = ($subtotal * $data['porcentaje_comision']) / 100;

                $json['campos_sumar'][] = $cont_json;
                $cont_json++;
                $json[$cont_json] = $data['porcentaje_comision'];
                $cont_json++;


                $datajson[] = $json;

                $count++;

            endif;
        }

        $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, $order, "RESULT_ARRAY", false, $start, $order_dir, false, $where_custom);


        $array['graficototal'] = $graficototal;
        $array['grafcoporcentaje'] = $graficoporcentaje;
        $array['grafcototalvendedor'] = $graficototalvendedor;
        $array['grafcocomision'] = $graficocomision;
        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($total);
        $array['recordsFiltered'] = sizeof($total); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }


    public function verDomicilio_post()
    {

        $domicilio_id = $this->input->post('domicilio_id');

        $result = array();
        if (!empty($domicilio_id)) {
            $result['domicilio'] = $this->domicilios_model->getDomicilioFull(array('domicilio_id' => $domicilio_id));
            $result['productos'] = $this->venta_model->obtener_venta($domicilio_id);
        }

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array(), 200);
        }

    }


    public function get_data_for_cloud_print_post()
    {

        $domicilio_id = $this->input->post('idventa');

        $id_devolucion = $this->input->post('id_devolucion');

        $result = array();
        if (!empty($id_devolucion)) {


            $result['detalle_devolucion'] = $this->venta_model->detalle_devolucion_venta($id_devolucion);
        }


        if (!empty($domicilio_id)) {

            $result['ventas'] = $this->venta_model->obtener_venta($domicilio_id);
            $result['impuestos'] = $this->impuestos_model->get_impuestos();
        }

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array(), 200);
        }

    }


    public function comparativaVendedoresPorVendedoresGrupo_get()
    {


        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';
        $grupo = isset($data['grupo']) ? $data['grupo'] : '';

        $where = array('venta_status' => COMPLETADO);
        $where2 = "";
        if ($fechadesde != "") {
            $where['fecha >='] = date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0'));
            $where2 .= ' fecha >= "' . date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0')) . '"';

        }

        if ($fechahasta != "") {
            $where['fecha <='] = date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59'));
            $where2 .= ' AND fecha <="' . date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59')) . '"';

        }


        if ($grupo != "") {
            $where['producto.producto_tipo'] = $grupo;
        }


        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = false;
        /*  if (!empty($search['value'])) {
              $where_custom = "(id LIKE '%" . $buscar . "%'
              or fecha LIKE '%" . $buscar . "%' or gravado LIKE '%" . $buscar . "%'
              or excluido LIKE '%" . $buscar . "%'
              or total LIKE '%" . $buscar . "%')";
          }*/

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'DATE(fecha), id_vendedor ';

        $select = 'id_vendedor, DATE(fecha) as fecha , usuario.nombre, SUM(detalle_venta.total) as total';

        $from = "venta as v";
        $join = array('usuario', 'detalle_venta', 'producto');
        $campos_join = array('usuario.nUsuCodigo = v.id_vendedor', 'detalle_venta.id_venta = v.venta_id', 'producto.producto_id = detalle_venta.id_producto');
        $tipo_join = array(null, null, null);

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'fecha';
            }
        }

        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['datos'] = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        $array = array();

        $count = 0;


        $vendedores = $this->usuario_model->get_all_vendedores();
        $fechaFlag = '';
        foreach ($datas['datos'] as $data) {


            $json = array();
            $cont_json = 0;

            if ($data['fecha'] != $fechaFlag || $count == 0) {
                $fechaFlag = $data['fecha'];
                $json[$cont_json] = date('d-m-Y', strtotime($data['fecha']));
                $cont_json++;
                $json['campos_sumar'] = array();
                foreach ($datas['datos'] as $dv) {

                    if ($dv['fecha'] === $data['fecha']) {

                        foreach ($vendedores as $vendedor) {

                            $json[$cont_json] = 0;
                            $json['campos_sumar'][] = $cont_json;
                            $cont_json++;
                        }


                        $i = 1;
                        foreach ($vendedores as $vendedor) {

                            if (intval($dv['id_vendedor']) === intval($vendedor['nUsuCodigo'])) {
                                $json[$i] = number_format($dv['total'], 2, ',', '.');

                                $json['campos_sumar'][] = $i;
                            }
                            $i++;
                        }

                    }
                }

                $datajson[] = $json;
                $count++;
            }


        }

        $datagraficoarray = array();

        foreach ($vendedores as $vendedor) {
            $datagrafico = array();
            $datagrafico['label'] = $vendedor['nombre'];
            $datagrafico['data'] = array();

            foreach ($datas['datos'] as $dv) {

                if (intval($dv['id_vendedor']) === intval($vendedor['nUsuCodigo'])) {
                    $d = DateTime::createFromFormat('Y-m-d', $dv['fecha'], new DateTimeZone('UTC'));
                    $objeto = array();
                    $objeto[] = $d->getTimestamp() * 1000;
                    $objeto[] = $dv['total'];


                    array_push($datagrafico['data'], $objeto);

                }

            }

            array_push($datagraficoarray, $datagrafico);

        }

        $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", null, null, $order_dir, false, $where_custom);


        $array['graficoarray'] = $datagraficoarray;

        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($total);
        $array['recordsFiltered'] = sizeof($total); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    function get_ventas_por_status_post()
    {

        $condicion = array('local_id' => $this->input->post('id_local'));
        $data['local'] = $this->session->userdata('id_local');


        $condicion['venta_status'] = $this->input->post('estatus');
        $data['estatus'] = $this->input->post('estatus');


        $data['ventas'] = $this->venta_model->get_ventas_by($condicion);

        $this->response($data, 200);

    }

    function ventas_devolver_get()
    {


        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';
        $vendedor = isset($data['vendedor']) ? $data['vendedor'] : '';
        $venta_status = isset($data['venta_status']) ? $data['venta_status'] : '';

        $where = array('venta_status' => COMPLETADO);

        $where2 = "";
        if ($fechadesde != "") {
            $where['venta.fecha >='] = date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0'));


        }

        if ($fechahasta != "") {
            $where['venta.fecha <='] = date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59'));

        }


        if ($venta_status != "") {
            $where['venta_status'] = $venta_status;

        }


        if ($vendedor != "") {
            $where['id_vendedor'] = $vendedor;
        }

        //$where['recibo_pago_cliente.recibo_id'] = NULL;
        $where['total >'] = '0';

        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = " (credito.var_credito_estado<>'CANCELADA' OR credito.var_credito_estado IS NULL) ";
        if (!empty($search['value'])) {
            $where_custom = "(venta.venta_id LIKE '%" . $buscar . "%'
              or venta.fecha LIKE '%" . $buscar . "%' 
              or documento_Numero LIKE '%" . $buscar . "%' 
              or total LIKE '%" . $buscar . "%')";
        }

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'venta.venta_id';

        $select = '*, venta.venta_id, venta.fecha';

        $from = "venta";
        $join = array('cliente', 'documento_venta', 'credito', 'historial_pagos_clientes', 'recibo_pago_cliente');
        $campos_join = array(
            'venta.id_cliente = cliente.id_cliente',
            'documento_venta.id_venta=venta.venta_id',
            'credito.id_venta=venta.venta_id',
            'historial_pagos_clientes.venta_id=credito.id_venta',
            'recibo_pago_cliente.recibo_id=historial_pagos_clientes.recibo_id',

        );
        $tipo_join = array('left', 'left', 'left', 'left', 'left');

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'venta.venta_id';
            }
        }

        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['datos'] = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        // echo $this->db->last_query();
        $array = array();

        $count = 0;


        foreach ($datas['datos'] as $data) {


            $json = array();


            $json[] = $data['venta_id'];
            $json[] = $data['documento_Numero'];
            $json[] = $data['nombres'] . " " . $data['apellidos'];
            $json[] = date('d-m-Y', strtotime($data['fecha']));
            $json[] = $data['total'];
            $json[] = $data['venta_status'];
            $json[] = "<div class=\"btn-group\">
                                                <a onclick=\"Venta.devolverventa(" . $data['venta_id'] . ")\"
                                                   class='btn btn-outline btn-default waves-effect waves-light tip'><i
                                                            class=\"fa fa-share\"></i> Devolver</a>

                                                <a style=\"cursor:pointer;\"
                                                   onclick=\"Venta.verVenta(" . $data['venta_id'] . ")\"
                                                   class='btn btn-outline btn-default waves-effect waves-light tip'
                                                   title=\"Ver Venta\">
                                                    <i class=\"fa fa-search\"></i>
                                                </a>

                                            </div>";

            $json['campos_sumar'] = array();
            $json['campos_sumar'][] = 4;

            $datajson[] = $json;
            $count++;


        }


        $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", null, null, $order_dir, false, $where_custom);


        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($total);
        $array['recordsFiltered'] = sizeof($total); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }

    }


    function getDomicilios_post()
    {

        $json = array();

        $condicion = array();

        if ($this->input->post('desde') != "") {
            $condicion['fecha_created >= '] = date('Y-m-d', strtotime($this->input->post('desde'))) . " " . date('H:i:s', strtotime('0:0:0'));
            $data['fecha_created'] = date('Y-m-d', strtotime($this->input->post('desde'))) . " " . date('H:i:s', strtotime('0:0:0'));
        }
        if ($this->input->post('hasta') != "") {
            $condicion['fecha_created <='] = date('Y-m-d', strtotime($this->input->post('hasta'))) . " " . date('H:i:s', strtotime('23:59:59'));
            $data['fecha_created'] = date('Y-m-d', strtotime($this->input->post('hasta'))) . " " . date('H:i:s', strtotime('23:59:59'));
        }
        if ($this->input->post('estatus') != "") {
            $condicion['domicilio_estatus'] = $this->input->post('estatus');
            $data['estatus'] = $this->input->post('estatus');
        }
        if ($this->input->post('vendedor') != "") {
            $condicion['id_vendedor'] = $this->input->post('vendedor');
            $data['vendedor'] = $this->input->post('vendedor');
        }
        /*  if ($this->input->post('listar') == 'pedidos') {
              $condicion['venta_tipo'] = VENTA_ENTREGA;
              $data['listar'] = $this->input->post('listar');
          }
          if ($this->input->post('listar') == 'ventas') {
              $condicion['venta_tipo'] = VENTA_CAJA;
              $data['listar'] = $this->input->post('listar');
          }*/
        if ($this->input->post('client') != "") {
            $condicion['venta.id_cliente'] = $this->input->post('client');
            $data['client'] = $this->input->post('client');
        }
        if ($this->input->post('zona') != "") {
            $condicion['cliente.id_zona'] = $this->input->post('zona');
            $data['zona'] = $this->input->post('zona');
        }

        $condicion['domicilio_estatus <> '] = PEDIDO_ELIMINADO;

        $data['venta'] = $this->domicilios_model->getDomicilios($condicion);
        $json['ventas'] = $data['venta'];


        $this->response($json, 200);

    }

    function salidaOregresoUsuDom_post()
    {
        //aqui va a entrar solo cuando sale de la drogueria a entregar un domicilio y cuando regresa a la drogueria

        $json = array();

        if ($this->input->post('usuario') && $this->input->post('usuario') != "") {
            $estatus = "";
            if ($this->input->post('estatus') == SALIENDO_DOMICILIO) {
                $estatus = SALIENDO_DOMICILIO;
            }
            if ($this->input->post('estatus') == LLEGANDO_DOMICILIO) {
                $estatus = LLEGANDO_DOMICILIO;
            }
            if ($estatus != "") {

                $datoshis = array(
                    'fecha' => date("Y-m-d H:i:s"),
                    'usuario' => $this->input->post('usuario'),
                    'estatus' => $estatus,
                );
                $this->db->insert('domicilio_historial', $datoshis);

                $json['success'] = true;

            } else {
                $json['error'] = "Debe ingresar un estatus valido";
            }

        } else {
            $json['error'] = "Debe ingresar todos los datos";
        }

        $this->response($json, 200);
    }


    function updtPosDomiciliario_post()
    {
        //actualiza la posicion del domiciliario
        $json = array();


        $longitud = $this->input->post('longitud');
        $latitud = $this->input->post('latitud');
        $direccion = $this->input->post('direccion');
        $usuario_id = $this->input->post('usuario_id');


        if (!empty($longitud) && !empty($latitud) && !empty($direccion) && !empty($usuario_id)) {


            $datos = array(
                'latitud' => $latitud,
                'longitud' => $longitud,
                'texto_posicion' => $direccion
            );
            $this->db->where('nUsuCodigo', $usuario_id);
            $update = $this->db->update('usuario', $datos);

            if ($update == true) {
                $json['success'] = "Datos guardados con exito";
            } else {
                $json['error'] = "Ha ocurrido un error al guardar la posicion";
            }

        } else {
            $json['error'] = "Debe ingresar todos los datos";
        }
        $this->response($json, 200);
    }

    function calcularSegundosDiferencia($fecha1, $fecha2)
    {

        $fecha1 = new DateTime($fecha1);
        $fecha2 = new DateTime($fecha2);
        $diferencia = $fecha1->diff($fecha2);

        $promedio_segundos = 0;
        if ($diferencia->s > 0) { //segundos
            $promedio_segundos = $diferencia->s;
        }

        if ($diferencia->i > 0) { //minutos
            $promedio_segundos = $promedio_segundos + ($diferencia->i * 60);
        }

        if ($diferencia->h > 0) { //horas
            $promedio_segundos = $promedio_segundos + ($diferencia->i * 3600);
        }

        if ($diferencia->d > 0) { //dias
            $promedio_segundos = $promedio_segundos + ($diferencia->i * 86400);
        }

        return $promedio_segundos;
    }

    function calcularPromedioDom($cantidadDom, $segundosNuevos, $promSegAnterior)
    {

        $sumapromedio = ($segundosNuevos + $promSegAnterior) / $cantidadDom;

        $promediostring = "";
        $promedioseg = "";
        if ($sumapromedio >= 86400) {
            $division = $sumapromedio / 86400;
            $promediostring = intval($division);

            if (intval($division) > 1) {
                $promediostring .= " días";
            } else {
                $promediostring .= " día";
            }
            if (is_bool($sumapromedio)) {
                $sumapromedio = $sumapromedio - (intval($division) * 86400);
            } else {
                $sumapromedio = 0;
            }
        }

        if ($sumapromedio >= 3600) {
            $division = $sumapromedio / 3600;

            if ($promediostring != "") {
                $promediostring .= ", " . intval($division);
                if (intval($division) > 1) {
                    $promediostring .= " horas";
                } else {
                    $promediostring .= " hora";
                }
            } else {
                $promediostring .= intval($division) . " ";
                if (intval($division) > 1) {
                    $promediostring .= " horas";
                } else {
                    $promediostring .= " hora";
                }
            }
            if (is_bool($sumapromedio)) {
                $sumapromedio = $sumapromedio - (intval($division) * 3600);
            } else {
                $sumapromedio = 0;
            }
        }

        if ($sumapromedio >= 60) {
            $division = $sumapromedio / 60;

            if ($promediostring != "") {
                $promediostring .= ", " . intval($division);
                if (intval($division) > 1) {
                    $promediostring .= " minutos";
                } else {
                    $promediostring .= " minuto";
                }
            } else {
                $promediostring .= intval($division) . " ";
                if (intval($division) > 1) {
                    $promediostring .= " minutos";
                } else {
                    $promediostring .= " minuto";
                }
            }
            if (is_bool($sumapromedio)) {
                $sumapromedio = $sumapromedio - (intval($division) * 60);
            } else {
                $sumapromedio = 0;
            }
        } else {
            $promediostring .= $sumapromedio . " segundos";
        }


        if ($sumapromedio < 60) {
            $sumapromedio = intval($sumapromedio);
            if ($promediostring != "") {
                $promediostring .= ", " . $sumapromedio . " segundos";

            } else {
                $promediostring .= $sumapromedio . " segundos";
            }
        }

        return array('promediostring' => $promediostring);

    }

    function marcarDomicilioComo_post()
    {

        //actualiza el estatus del domicilio
        $json = array();
        if (isset($_POST) && count($_POST) > 0) {
            $datos = array();

            $fechaahora = $this->input->post('fecha') ? $this->input->post('fecha') : date("Y-m-d H:i:s");
            $estatus = $this->input->post('estatus');
            $domicilio_id = $this->input->post('domicilio_id');
            $usuario_id = $this->input->post('usuario_id');

            $latitud_pos = $this->input->post('latitud') ? $this->input->post('latitud') : NULL;
            $longitud_pos = $this->input->post('longitud') ? $this->input->post('longitud') : NULL;
            $texto_pos = $this->input->post('texto_pos') ? $this->input->post('texto_pos') : NULL;

            $datoshis = array(
                'fecha' => $fechaahora,
                'id_domicilio' => $domicilio_id,
                'usuario' => $usuario_id,
                'estatus' => $estatus,
                'latitud_pos' => $latitud_pos,
                'longitud_pos' => $longitud_pos,
                'texto_pos' => $texto_pos,
            );

            if ($this->input->post('comentario') && $this->input->post('comentario') != "") {
                $datoshis['comentario'] = $this->input->post('comentario');
            }

            $where = array(
                'domicilio_id' => $domicilio_id
            );
            //busco la info del domicilio
            $infoDomicilio = $this->domicilios_model->getDomicilio($where);

            //si el domicili que esta llegando es del domiciliario que esta llegando
            //o si estoy asignando el domicilio a un domciliario
            if (($infoDomicilio['usuario_id'] == $usuario_id) ||
                ($this->input->post('usuario_asigna') && $this->input->post('usuario_asigna') != "")
            ) {

                $where = array(
                    'usuario' => $usuario_id
                );
                //busco lo ultimo que haya ingresado este usuario en la tabla historial
                $ultimoHistorial = $this->domicilios_model->onlyLastDomicilioHist($where);

                $segundosdesde = $this->calcularSegundosDiferencia($ultimoHistorial['fecha'], $fechaahora);

                if (!$this->input->post('usuario_asigna')) {

                    //busco el promedio de este domiciliario para este cliente
                    $where = array(
                        'domiciliario' => $usuario_id,
                        'cliente' => $infoDomicilio['cliente_id']
                    );
                    $getpromedio = $this->domicilios_model->getPromedio($where);

                    if (sizeof($getpromedio) < 1) {

                        $calculaPromedio = $this->calcularPromedioDom(1, $segundosdesde, 0);
                        $tablapromedio = array(
                            'domiciliario' => $usuario_id,
                            'cliente' => $infoDomicilio['cliente_id'],
                            'segundos_acumulados' => $segundosdesde, //cantida de segundos acumulados
                            'cantidad_dom' => 1,
                            'promedio_seg' => $segundosdesde, //el promedio de la cantidad de domicilios para este cliente,
                            'promedio_string' => $calculaPromedio['promediostring'],
                        );
                        $guardarPromedio = $this->domicilios_model->savePromedioDom($tablapromedio);

                        //le actualizo el promedio global para cada cliente
                        $wherecli = array(
                            'id_cliente' => $infoDomicilio['cliente_id']
                        );
                        $datacli = array(
                            'cantidad_domic' => 1,
                            'segundos_acum' => $segundosdesde,
                            'segundos_promedio' => $segundosdesde,
                            'string_promedio' => $calculaPromedio['promediostring']
                        );
                        $guardarcli = $this->cliente_model->onlyUpdate($wherecli, $datacli);

                    } else {

                        $promediocantidad = isset($getpromedio['cantidad']) ? $getpromedio['cantidad'] : 0;
                        $calculaPromedio = $this->calcularPromedioDom($promediocantidad + 1,
                            $segundosdesde, $getpromedio['promedio_seg']);

                        //si tiene un registro para este cliente
                        $tablapromedio = array(
                            'segundos_acumulados' => $getpromedio['segundos_acumulados'] + $segundosdesde,
                            'cantidad_dom' => $promediocantidad + 1,
                            'promedio_seg' => ($getpromedio['promedio_seg'] + $segundosdesde) / ($promediocantidad + 1), //el promedio de la cantidad de domicilios para este cliente,
                            'promedio_string' => $calculaPromedio['promediostring'],
                        );
                        $guardarPromedio = $this->domicilios_model->updatePromedioDom($where, $tablapromedio);

                        $wherecli = array(
                            'id_cliente' => $infoDomicilio['cliente_id']
                        );
                        $infoCli = $this->cliente_model->getOnlyClient($wherecli);

                        $calculaPromedio = $this->calcularPromedioDom($infoCli['cantidad_domic'] + 1,
                            $segundosdesde, $infoCli['segundos_promedio']);

                        //le actualizo el promedio global para cada cliente
                        $datacli = array(
                            'cantidad_domic' => $infoCli['cantidad_domic'] + 1,
                            'segundos_acum' => $infoCli['segundos_acum'] + $segundosdesde,
                            'segundos_promedio' => ($infoCli['segundos_promedio'] + $segundosdesde) / ($infoCli['cantidad_domic'] + 1),
                            'string_promedio' => $calculaPromedio['promediostring']
                        );
                        $guardarcli = $this->cliente_model->onlyUpdate($wherecli, $datacli);
                    }

                }

                $datos['domicilio_estatus'] = $estatus;
                if ($estatus == DOMICILIO_ENTREGADO) {
                    $datos['fecha_entregado'] = $fechaahora;
                }

                if ($this->input->post('usuario_asigna')) { //si llega este parametro es que estoy asignando el domicilio a un domiciliario
                    $datos['usuario_asigna'] = $this->input->post('usuario_asigna');
                    $datos['usuario_id'] = $this->input->post('usuario_id');

                    //si estoy asignando a un domiciliario desde el sistema, en el historial queda el que esta asignando
                    $datoshis['usuario'] = $this->input->post('usuario_asigna');
                }

                $this->db->where('domicilio_id', $domicilio_id);
                $update = $this->db->update('domicilios', $datos);


                $datoshis['segundos_tarda'] = $segundosdesde; //los segundos que tardo desde la ultima historia hasta ahorita

                $this->db->insert('domicilio_historial', $datoshis);

                if ($update == true) {
                    $json['success'] = "Domicilio marcado como " . $estatus;
                } else {
                    $json['error'] = "Ha ocurrido un error al guardar la posicion";
                }

            } else {
                $json['error'] = "Usted no ha esta asignado a este domicilio";
            }


        } else {
            $json['error'] = "Debe ingresar todos los datos";
        }
        $this->response($json, 200);
    }


    function misDomicilios_post()
    {
        $json = array();
        $usuario = $this->input->post('usuario');
        $condicion = array();
        $condicion['domicilio_estatus'] = DOMICILIO_ASIGNADO;
        $condicion['usuario_id'] = $usuario;
        $data['venta'] = $this->domicilios_model->getDomicilios($condicion);
        $json['ventas'] = $data['venta'];
        $this->response($json, 200);

    }

    function getHistDomicilio_post()
    {
        //obtengo el historial de un domicilio
        $json = array();
        if ($this->input->post('domicilio_id')) {

            $where = array(
                'domicilios.domicilio_id' => $this->input->post('domicilio_id')
            );
            $json['infodomicilio'] = $this->domicilios_model->getDomicilios($where);
            if (count($json['infodomicilio']) > 0) {
                $where = array(
                    'id_domicilio' => $this->input->post('domicilio_id')
                );
                $json['historial'] = $this->domicilios_model->getHistDom($where);

            } else {
                $json['error'] = "El domicilio no existe";
            }

        } else {
            $json['error'] = "Debe ingresar todos los datos";
        }

        $this->response($json, 200);

    }

    function comentsMostrar_post()
    {


        //comentarios a mostrar cuando de vaya a cancelar el domcilio
        $json = array();

        $comentarios = array();
        $anulaciones = $this->tipo_anulacion_model->get_all();
        $devolucion = $this->tipo_anulacion_model->get_all();
        foreach ($anulaciones as $row) {
            $comentarios[] = $row['tipo_anulacion_nombre'];
        }
        foreach ($devolucion as $row) {
            $comentarios[] = $row['tipo_anulacion_nombre'];
        }
        $json['comentarios'] = $comentarios;
        //   echo json_encode($json);
        $this->response($json, 200, true);
    }

    function geProductosParaPedidoSugerido_get()
    {

        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : $this->input->get('fecha_desde');
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : $this->input->get('fecha_hasta');
        $tipo = isset($data['tipo']) ? $data['tipo'] : $this->input->get('tipo');

        $where_custom = false;
        $wheresub = "venta_status = '" . COMPLETADO . "'";
        $where = array('venta_status' => COMPLETADO);


        if ($fechadesde != "") {
            $where['date(fecha) >= '] = date('Y-m-d', strtotime($fechadesde));
            $wheresub = 'date(fecha) >= \'' . date('Y-m-d', strtotime($fechadesde)) . '\'';
        }
        if ($fechahasta != "") {
            $where['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));
            $wheresub .= ' and date(fecha) <= \'' . date('Y-m-d', strtotime($fechahasta)) . '\'';
        }

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'id_producto';
        $select = ' producto_nombre,  
            vb.venta_id, fecha, id_producto, producto_codigo_interno,
            (select cantidad from inventario where inventario.id_producto 
            = producto.producto_id and inventario.id_unidad =  1 group by producto.producto_id) as cantidad_caja,
            (select cantidad from inventario where inventario.id_producto 
            = producto.producto_id and inventario.id_unidad =  2 group by producto.producto_id) as cantidad_blister, 
            (select cantidad from inventario where inventario.id_producto 
            = producto.producto_id and inventario.id_unidad =  3 group by producto.producto_id) as cantidad_unidad,
            
              (select sum(cantidad) from detalle_venta_unidad 
              join detalle_venta on detalle_venta.id_detalle =  detalle_venta_unidad.detalle_venta_id
              join venta on venta.venta_id=detalle_venta.id_venta and venta_status = \'COMPLETADO\'
               where dvb.id_producto 
            = detalle_venta.id_producto and detalle_venta_unidad.unidad_id =  1 and ' . $wheresub . ') as total_caja,
            
              (select sum(cantidad) from detalle_venta_unidad join detalle_venta on detalle_venta.id_detalle = 
              detalle_venta_unidad.detalle_venta_id
                 join venta on venta.venta_id=detalle_venta.id_venta and venta_status = \'COMPLETADO\'
               where dvb.id_producto 
            = detalle_venta.id_producto and detalle_venta_unidad.unidad_id =  2 and ' . $wheresub . ') as total_blister,
            
              (select sum(cantidad) from detalle_venta_unidad  join detalle_venta on detalle_venta.id_detalle = 
              detalle_venta_unidad.detalle_venta_id
                 join venta on venta.venta_id=detalle_venta.id_venta and venta_status = \'COMPLETADO\'
               where dvb.id_producto 
            = detalle_venta.id_producto  and detalle_venta_unidad.unidad_id =  3 and ' . $wheresub . ') as total_unidad
            ';
        $from = "venta vb";
        $join = array('detalle_venta as dvb', 'producto');
        $campos_join = array(
            'dvb.id_venta = vb.venta_id',
            'producto.producto_id = dvb.id_producto');

        $tipo_join = array(false, false);

        $order = 'total_caja,total_blister, total_unidad';
        $order_dir = 'desc';


        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->input->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['clientes'] = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        // echo $this->db->last_query();

        $array = array();

        $count = 0;


        $graficototal = array();
        $grafcoexluido = array();
        $grafcogravado = array();

        foreach ($datas['clientes'] as $data) {

            $graficototal[$count] = array();
            $grafcoexluido[$count] = array();
            $grafcogravado[$count] = array();


            $json = array();
            $id_prod = $data['id_producto'];
            $json[] = $data['producto_codigo_interno'];
            $json[] = $data['producto_nombre'];

            $json[] = $data['total_caja'];
            $json[] = $data['total_blister'];
            $json[] = $data['total_unidad'];

            $json[] = $data['cantidad_caja'];
            $json[] = $data['cantidad_blister'];
            $json[] = $data['cantidad_unidad'];
            $json[] = "<input class='form-control' name='prod_$id_prod' id='prod_$id_prod'>";

            $json['campos_sumar'] = array();
            $json['campos_sumar'][] = 2;
            $json['campos_sumar'][] = 3;
            $json['campos_sumar'][] = 4;
            $json['campos_sumar'][] = 5;
            $json['campos_sumar'][] = 6;
            $json['campos_sumar'][] = 7;

            $datajson[] = $json;

            $count++;
        }

        $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", false, $start, $order_dir, false, $where_custom);


        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($total);
        $array['recordsFiltered'] = sizeof($total); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    function geProductosParaPedidoSugeridoBytock_get()
    {

        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : $this->input->get('fecha_desde');
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : $this->input->get('fecha_hasta');
        $tipo = isset($data['tipo']) ? $data['tipo'] : $this->input->get('tipo');

        $where_custom = false;
        $wheresub = false;
        $where = array('venta_status' => COMPLETADO);


        if ($fechadesde != "") {
            $where['date(fecha) >= '] = date('Y-m-d', strtotime($fechadesde));
            $wheresub = 'date(fecha) >= \'' . date('Y-m-d', strtotime($fechadesde)) . '\'';
        }
        if ($fechahasta != "") {
            $where['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));
            $wheresub .= ' and date(fecha) <= \'' . date('Y-m-d', strtotime($fechahasta)) . '\'';
        }

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'id_producto';
        $select = ' producto_nombre,  
            vb.venta_id, fecha, id_producto, producto_codigo_interno,
            (select cantidad from inventario where inventario.id_producto 
            = producto.producto_id and inventario.id_unidad =  1 group by producto.producto_id ) as cantidad_caja,
            (select cantidad from inventario where inventario.id_producto 
            = producto.producto_id and inventario.id_unidad =  2 group by producto.producto_id) as cantidad_blister, 
            (select cantidad from inventario where inventario.id_producto 
            = producto.producto_id and inventario.id_unidad =  3 group by producto.producto_id) as cantidad_unidad,
            
              (select sum(cantidad) from detalle_venta_unidad 
              join detalle_venta on detalle_venta.id_detalle =  detalle_venta_unidad.detalle_venta_id
              join venta on venta.venta_id=detalle_venta.id_venta and venta_status = \'COMPLETADO\'
               where dvb.id_producto 
            = detalle_venta.id_producto and detalle_venta_unidad.unidad_id =  1 and ' . $wheresub . ') as total_caja,
            
              (select sum(cantidad) from detalle_venta_unidad join detalle_venta on detalle_venta.id_detalle = 
              detalle_venta_unidad.detalle_venta_id
                 join venta on venta.venta_id=detalle_venta.id_venta and venta_status = \'COMPLETADO\'
               where dvb.id_producto 
            = detalle_venta.id_producto and detalle_venta_unidad.unidad_id =  2 and ' . $wheresub . ') as total_blister,
            
              (select sum(cantidad) from detalle_venta_unidad  join detalle_venta on detalle_venta.id_detalle = 
              detalle_venta_unidad.detalle_venta_id
                 join venta on venta.venta_id=detalle_venta.id_venta and venta_status = \'COMPLETADO\'
               where dvb.id_producto 
            = detalle_venta.id_producto  and detalle_venta_unidad.unidad_id =  3 and ' . $wheresub . ') as total_unidad
            ';
        $from = "venta vb";
        $join = array('detalle_venta as dvb', 'producto');
        $campos_join = array(
            'dvb.id_venta = vb.venta_id',
            'producto.producto_id = dvb.id_producto');

        $tipo_join = array(false, false);

        $order = 'total_caja,total_blister, total_unidad';
        $order_dir = 'desc';


        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->input->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }


        $datas['clientes'] = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        // echo $this->db->last_query();

        $array = array();

        $count = 0;


        $graficototal = array();
        $grafcoexluido = array();
        $grafcogravado = array();

        foreach ($datas['clientes'] as $data) {

            $graficototal[$count] = array();
            $grafcoexluido[$count] = array();
            $grafcogravado[$count] = array();


            $json = array();
            $id_prod = $data['id_producto'];
            $json[] = $data['producto_codigo_interno'];
            $json[] = $data['producto_nombre'];

            $json[] = $data['total_caja'];
            $json[] = $data['total_blister'];
            $json[] = $data['total_unidad'];

            $json[] = $data['cantidad_caja'];
            $json[] = $data['cantidad_blister'];
            $json[] = $data['cantidad_unidad'];
            $json[] = "<input class='form-control' name='prod_$id_prod' id='prod_$id_prod'>";

            $datajson[] = $json;

            $count++;
        }

        $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", false, $start, $order_dir, false, $where_custom);


        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($total);
        $array['recordsFiltered'] = sizeof($total); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    function get_data_printPropProd_post()
    {

        $data = array();
        $data['unidades'] = $this->unidades_model->get_unidades();
        $data['condiciones_pago'] = $this->condiciones_pago_model->get_all();
        if ($this->input->post('categoria') && $this->input->post('subcategoria')) {
            $data['categoria'] = $this->input->post('categoria');

            if ($this->input->post('categoria') == "GRUPO") {
                $where = array("produto_grupo" => $this->input->post('subcategoria'));
                $data['productos'] = $this->pd->get_all_by($where, 'producto_nombre', 'asc');

                $data['subcategoria'] = $this->grupos_model->get_by('id_grupo', $this->input->post('subcategoria'));
                $data['subcategoria'] = $data['subcategoria']['nombre_grupo'];
            }

            if ($this->input->post('categoria') == "CLASIFICACION") {
                $where = array("producto_clasificacion" => $this->input->post('subcategoria'));
                $data['productos'] = $this->pd->get_all_by($where, 'producto_nombre', 'asc');

                $data['subcategoria'] = $this->clasificacion_model->get_by(array('clasificacion_id' => $this->input->post('subcategoria')));
                $data['subcategoria'] = $data['subcategoria']['clasificacion_nombre'];
            }

            if ($this->input->post('categoria') == "TIPO") {
                $where = array("producto_tipo" => $this->input->post('subcategoria'));
                $data['productos'] = $this->pd->get_all_by($where, 'producto_nombre', 'asc');

                $data['subcategoria'] = $this->tipo_producto_model->get_by(array('tipo_prod_id' => $this->input->post('subcategoria')));
                $data['subcategoria'] = $data['subcategoria']['tipo_prod_nombre'];
            }

            if ($this->input->post('categoria') == "COMPONENTE") {
                $where = array("producto_has_componente.componente_id" => $this->input->post('subcategoria'));
                $data['productos'] = $this->producto_componente_model->getProductoByComponent($where, 'producto_nombre', 'asc');

                $data['subcategoria'] = $this->componentes_model->get_by(array('componente_id' => $this->input->post('subcategoria')));
                $data['subcategoria'] = $data['subcategoria']['componente_nombre'];
            }

            if ($this->input->post('categoria') == "UBICACION_FISICA") {
                $where = array("producto_ubicacion_fisica" => $this->input->post('subcategoria'));
                $data['productos'] = $producto = $this->pd->get_all_by($where, 'producto_nombre', 'asc');

                $data['subcategoria'] = $this->ubicacion_fisica_model->get_by(array('ubicacion_id' => $this->input->post('subcategoria')));
                $data['subcategoria'] = $data['subcategoria']['ubicacion_nombre'];
            }

            if ($this->input->post('categoria') == "IMPUESTO") {
                $where = array("producto_impuesto" => $this->input->post('subcategoria'));
                $data['productos'] = $this->pd->get_all_by($where, 'producto_nombre', 'asc');

                $data['subcategoria'] = $this->impuestos_model->get_by('id_impuesto', $this->input->post('subcategoria'));
                $data['subcategoria'] = $data['subcategoria']['nombre_impuesto'];
            }
        }


        for ($cont = 0; $cont < count($data['productos']); $cont++) {

            $where = array('id_producto' => $data['productos'][$cont]['producto_id'], 'id_local' => $this->input->post('id_local'));
            $data['productos'][$cont]['stock'] = array();
            $data['productos'][$cont]['stock'] = $this->inventario_model->get_all_by($where);
            $unidades = $this->unidades_model->get_unidades();

            foreach ($unidades as $unidad) {

                $where = array(
                    'producto_id' => $data['productos'][$cont]['producto_id'],
                    'unidades_has_producto.id_unidad' => $unidad['id_unidad']
                );
                $unidadesprod = $this->unidades_model->solo_unidades_xprod($where);
                $data['productos'][$cont]['unidadesprod'][] = $unidadesprod;

            }

        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(array(), 200);
        }
    }


    function getDataDirectPrintCompra_post()
    {

        $ingreso= IngresoElo::where('id_ingreso', $this->input->post('ingreso_id'))
            ->with(['detalleingreso' => function ($detalleingreso) {
                $detalleingreso->with(['producto','detalleingresounidad' => function ($detalleingresounidad){
                    $detalleingresounidad->with('unidad');
                }]);
            }])->first();

        if ($ingreso) {
            $this->response(array('ingreso'=>$ingreso), 200);
        } else {
            $this->response(array(), 200);
        }
    }


}