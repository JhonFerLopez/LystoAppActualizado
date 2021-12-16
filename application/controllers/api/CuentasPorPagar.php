<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class CuentasPorPagar extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();

        $this->load->model('venta/venta_model');
        $this->load->model('metodosdepago/metodos_pago_model');
        $this->load->model('pagos_ingreso/pagos_ingreso_model');
        $this->load->model('ingreso/ingreso_model');
        $this->load->model('cuentas_por_pagar/recibo_pago_proveedor_model');
        $this->load->model('api/api_model', 'api');
		$this->load->model('cajas/StatusCajaModel');
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

        if (!empty($auth_id)) {
            $this->uid = $auth_id;
        } else {
            $this->uid = null;
        }
    }

    function nextRecibo_get()
    {
        $data = array();
        $recibo = $this->recibo_pago_proveedor_model->get_next_id();
        $data['recibo'] = $recibo->recibo_id + 1;
        echo json_encode($data);
    }

    function guardarPago_get()
    {

        $cajero = $this->session->userdata('cajero_id'); //TODO ESTO NOHACE FALTA PORQUE YA ESTA EN LA TABAL STTAUSCAJA, algun dia borrar
        $caja = $this->session->userdata('cajapertura'); // se amacena el id de statuscaja  no la caja

        $statuscaja = $this->StatusCajaModel->getBy(array('id' => $caja));

        if (!empty($statuscaja['cierre'])) {
            $json['error'] = "La caja en la que está intentando realizar la operación ya ha sido cerrada por otro usuario. Por favor cierre sessión e ingrese nuevamente para trabajar en otra caja";
            //  echo json_encode($dataresult);
        } else {

            if (($caja==NULL or $caja == '' or $cajero == '')) {
                $json['error'] = "Debe aperturar una caja para poder continuar";

            } else {
                $json = array();
                //var_dump($post['lst_producto']);
                /*esto lo que hace es convertirlo de objeto a arreglo, ya que con objeto, a veces  arroja error que no se puede hacer foreach
                */
                $pago = preg_replace('/[[:cntrl:]]/', '', $this->input->get('pago'));
                $pago = json_decode($pago, true);

                // esto es apra saber si hay algun error con el json
                switch (json_last_error()) {
                    case JSON_ERROR_NONE:
                        break;
                    case JSON_ERROR_DEPTH:
                        echo ' - Maximum stack depth exceeded';
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        echo ' - Underflow or the modes mismatch';
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        echo ' - Unexpected control character found';
                        break;
                    case JSON_ERROR_SYNTAX:
                        echo ' - Syntax error, malformed JSON';
                        break;
                    case JSON_ERROR_UTF8:
                        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                        break;
                    default:
                        echo ' - Unknown error';
                        break;
                }

                if (is_array($pago['lst_factura'])) {

                    $montoabonado = $pago['cuota']; //lo que estoy abonando

                    foreach ($pago['lst_factura'] as $factura) {

                        if ($montoabonado > 0) {

                            $newpago = $pago;
                            $newpago['id_ingreso'] = $factura['id_ingreso'];

                            //monto_pendiente_nf es el total pendiente, no formateado, es decir sin sin number_format
                            $newpago['cantidad_ingresada'] = ($montoabonado >= $factura['monto_pendiente_nf']) ? $factura['monto_pendiente_nf'] : $montoabonado;

                            $newpago['pagoingreso_restante'] = $factura['pagoingreso_restante'] != null && $factura['pagoingreso_restante'] != "" ?
                                $factura['pagoingreso_restante'] - $newpago['cantidad_ingresada'] : $factura['monto_pendiente_nf'] - $newpago['cantidad_ingresada'];

                            //$montoabonado = $montoabonado - $factura['monto_pendiente_nf'];

                            $id_recibo = $this->recibo_pago_proveedor_model->insertar(
                                array('recibo_id' => null,
                                    'usuario' => $pago['usuario'],
                                    'metodo_pago' => $pago['metodo'],
                                    'observaciones_adicionales' => $pago['observaciones_adicionales'],
                                    'fecha' => date("Y-m-d H:i:s"),
                                    'banco' => !empty($newpago['banco']) ? $newpago['banco'] : null,
                                    'cuadre_caja_id' => $this->session->userdata('cajapertura'),
                                    'fecha_consignacion' => $pago['fecha_consignacion'] != "" ? date('Y-m-d', strtotime($pago['fecha_consignacion'])) : null
                                )
                            );
                            $newpago['recibo_id'] = $id_recibo;

                            $save_historial = $this->pagos_ingreso_model->guardarWithArray($newpago);

                            if ($save_historial != false) {
                                if ($save_historial != false) {
                                    $json['success'] = 'success';
                                    $json['ingreso_id'] = $factura['id_ingreso'];
                                    $json['id_historial'] = $save_historial;
                                } else {
                                    $json['error'] = 'error';
                                }
                            }
                        }
                    }
                }

            }
        }
        $this->response($json, 200);

    }

    /**
     * Este metodo busca un json con los facturas a credito que estan pendientes de pago
     */
    public
    function getComprasCreditoPendienteJson_get()
    {
        if ($this->input->is_ajax_request()) {
            $id_cliente = $this->input->get('cboProveedor');
            $fechaDesde = $this->input->get('fecIni');
            $fechaHasta = $this->input->get('fecFin');
            $nombre_or = false;
            $where_or = false;
            $array = array();
            $start = 0;
            $limit = false;
            $where = "dias > 0";
            $where = $where . " AND ingreso.ingreso_status = '" . COMPLETADO . "'";
            if ($id_cliente != -1) {
                $where = $where . " AND int_Proveedor_id= '" . $id_cliente . "'";
            }
            if ($fechaDesde != "") {
                $where = $where . " AND date(fecha_registro) >= '" . date('Y-m-d', strtotime($fechaDesde)) . "'";
            }
            if ($fechaHasta != "") {
                $where = $where . " AND  date(fecha_registro) <= '" . date('Y-m-d', strtotime($fechaHasta)) . "'";
            }
            $select = 'ingreso.*, pagos_ingreso.*, proveedor.*, sum(pagoingreso_monto) as suma, condiciones_pago.dias';
            $from = "ingreso";
            $join = array('proveedor', 'pagos_ingreso', 'condiciones_pago');
            $campos_join = array('proveedor.id_proveedor=ingreso.int_Proveedor_id', 'pagos_ingreso.pagoingreso_ingreso_id=ingreso.id_ingreso', 'condiciones_pago.id_condiciones=ingreso.condicion_pago');
            $tipo_join[0] = "left";
            $tipo_join[1] = "left";
            $tipo_join[2] = "left";
            $group = "id_ingreso";
            $where_custom = false;
            $order = 'fecha_registro';
            $order_dir = 'desc';
            $nombre_in = false;
            $where_in = false;
            $cuentas = $this->ingreso_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

            if (count($cuentas) > 0) {

                foreach ($cuentas as $v) {

                    $PRODUCTOjson = $v;
                    if ($v['suma'] != null) {
                        $deuda = $v['total_ingreso'] - $v['suma'];
                    } else {
                        $deuda = $v['total_ingreso'];
                    }
                    $PRODUCTOjson['monto_pendiente'] = number_format($deuda, 2, ',', '.');
                    $PRODUCTOjson['monto_pendiente_nf'] = $deuda;
                    $days = (strtotime(date('d-m-Y')) - strtotime($v['fecha_registro'])) / (60 * 60 * 24);
                    if ($days < 0)
                        $days = 0;
                    $label = "<div><label class='label ";
                    if (floor($days) < 8 or $v['suma'] >= $v['total_ingreso']) {
                        $label .= "label-success";
                    } elseif (floor($days) < 16) {
                        $label .= "label-info";
                    } else {
                        $label .= "label-warning";
                    }
                    $label .= "'>" . floor($days) . "</label></div>";
                    $PRODUCTOjson['label_dias'] = $label;
                    $PRODUCTOjson[] = ($v['suma'] >= $v['total_ingreso']) ? PAGO_CANCELADO : INGRESO_PENDIENTE;
                    $botonas = '<div class="btn-group">';
                    if ($v['suma'] < $v['total_ingreso']) {
                        /* $botonas .= '<a onclick="pagar_venta(\'' . $v['id_ingreso'] . '\')" class="btn btn-default tip" title="Pagar"><i
                                 class="fa fa-paypal"></i> Pagar</a>';*/
                    }
                    $botonas .= '
                    </div>';
                    $PRODUCTOjson[] = $botonas;
                    if ($deuda > 0) {
                        $array[] = $PRODUCTOjson;
                    }

                }
            }


            echo json_encode($array);
        } else {

        }
    }

}
