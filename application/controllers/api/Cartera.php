<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class Cartera extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();

        $this->load->model('venta/venta_model');
        $this->load->model('metodosdepago/metodos_pago_model');
        $this->load->model('historial_pagos_clientes/historial_pagos_clientes_model');
        $this->load->model('cartera/recibo_pago_cliente_model');
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
        $recibo = $this->recibo_pago_cliente_model->get_next_id();
        $data['recibo'] = $recibo->recibo_id + 1;
        echo json_encode($data);
    }

    function guardarPago_get()
    {


        $cajero = $this->session->userdata('cajero_id'); //TODO ESTO NOHACE FALTA PORQUE YA ESTA EN LA TABAL STTAUSCAJA, algun dia borrar
        $caja = $this->session->userdata('cajapertura'); // se amacena el id de statuscaja  no la caja

        //echo $caja;
        $statuscaja = $this->StatusCajaModel->getBy(array('id' => $caja));
        if (!empty($statuscaja['cierre'])) {
            $json['error'] = "La caja en la que está intentando realizar la operación ya ha sido cerrada por otro usuario. Por favor cierre sessión e ingrese nuevamente para trabajar en otra caja";
          //  echo json_encode($dataresult);
        } else {

            if (($caja == '' or $cajero == '')) {
                $json['error'] = "Debe aperturar una caja para poder continuar";

            } else {
                $json = array();
                $pago = json_decode($this->input->get('pago', true));


                if (!isset($json['error'])) {
                    if (is_array($pago->lst_factura)) {


                        $newpago = $pago;
                        $id_recibo = $this->recibo_pago_cliente_model->insertar(array
                        ('recibo_id' => null,
                            'usuario' => $newpago->usuario,
                            'banco' => !empty($newpago->banco) ? $newpago->banco : null,
                            'metodo' => $newpago->metodo,
                            'fecha' => date("Y-m-d H:i:s"),
                            'observaciones_adicionales' => $newpago->observaciones_adicionales,
                            'cuadre_caja_id' => $this->session->userdata('cajapertura'),
                        ));

                        $montoabonado = $pago->cuota;
                        foreach ($pago->lst_factura as $factura) {
                            if (floatval($montoabonado) > 0) {

                                $newpago->id_venta = $factura->venta_id;
                                $newpago->credito_id = $factura->credito_id;
                                $newpago->cuota = ($montoabonado >= $factura->monto_pendiente) ? $factura->monto_pendiente : $montoabonado;

                                $newpago->monto_restante = ($montoabonado >= $factura->monto_pendiente) ? 0 : $factura->monto_pendiente - $montoabonado;

                                $montoabonado = $montoabonado - $factura->monto_pendiente;


                                $newpago->recibo_id = $id_recibo;

                                $save_historial = $this->historial_pagos_clientes_model->guardar($newpago);

                                if ($save_historial == true) {
                                    $credito = $this->venta_model->updateCredito($newpago);
                                    $result['credito'] = $this->venta_model->get_credito_by_id($newpago->credito_id);
                                    $newpago->monto_restante = floatval($result['credito'][0]['dec_credito_montodeuda']) - (floatval($result['credito'][0]['dec_credito_montodebito']) + floatval($credito[0]['confirmacion_monto_cobrado_caja']) + floatval($credito[0]['confirmacion_monto_cobrado_bancos']) + floatval($credito[0]['pagado']));

                                    $json['success'] = 'success';


                                } else {
                                    if ($save_historial == false) {
                                        $json['error'] = 'Por favor intente nuevamente';
                                    } else {
                                        $json['error'] = $save_historial;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $json['recibo'] = isset($id_recibo) ? $id_recibo : null;


        $this->response($json, 200);

    }

    /**
     * Este metodo busca un json con los facturas a credito que estan pendientes de pago
     */
    public function getFacturasCreditoPendienteJson_get()
    {
        if ($this->input->is_ajax_request()) {
            $id_cliente = $this->input->get('cboCliente');

            $nombre_or = false;
            $where_or = false;
            $array = array();
            $start = 0;
            $limit = false;

            $where = " (`venta_status` ='" . COMPLETADO . "' or venta_status IS NULL) ";
            $where .= " and (venta.id_cliente =" . $id_cliente . " or credito.id_cliente =" . $id_cliente . ")";


            $where_in[0] = array(CREDITO_DEBE, CREDITO_ACUENTA);
            $nombre_in[0] = 'var_credito_estado';

            $select = 'venta.venta_id,venta.fe_numero, venta.fe_prefijo, venta_tipo, credito.credito_dias, credito.credito_id,  venta.id_cliente, nombres,apellidos, fecha, 
            total,var_credito_estado, dec_credito_montodebito,dec_credito_montodeuda,  documento_venta.*,
            nombre_condiciones, condiciones_pago.dias,venta.id_cliente as clientV, venta.pagado,
            (select SUM(historial_monto) from historial_pagos_clientes where historial_pagos_clientes.venta_id = venta.venta_id )
             as confirmar,usuario.nUsuCodigo as vendedor_id, usuario.nombre as vendedor_nombre';
            $from = "credito";
            $join = array('venta', 'cliente', 'documento_venta', 'tipo_venta', 'condiciones_pago', 'usuario');
            $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente or cliente.id_cliente=credito.id_cliente',
                'documento_venta.id_venta=venta.venta_id', 'venta.venta_tipo=tipo_venta.tipo_venta_id',
                'condiciones_pago.id_condiciones=tipo_venta.condicion_pago', 'usuario.nUsuCodigo=venta.id_vendedor');
            $tipo_join = array('left', 'left', 'left', 'left', 'left', 'left');

            $where_custom = false;
            $order = 'venta.fecha';
            $order_dir = 'asc';
            $group = false;
            $lstVenta = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

            if (count($lstVenta) > 0) {

                foreach ($lstVenta as $v) {

                    $pendiente = 0;
                    $PRODUCTOjson = $v;
                    //$montoancelado = floatval($v["dec_credito_montodebito"]) + floatval($v['pagado'])+floatval($v['confirmacion_monto_cobrado_caja'])+floatval($v['confirmacion_monto_cobrado_bancos']);
                    $montoancelado = floatval($v["dec_credito_montodebito"]);
                    $credito_id = $v["credito_id"];
                    $PRODUCTOjson['credito_id'] = $credito_id;
                    $PRODUCTOjson['monto_cancelado'] = $montoancelado;
                    $resta = floatval($v['dec_credito_montodeuda']) - $montoancelado;
                    $pendiente = $resta;
                    $PRODUCTOjson['monto_pendiente'] = $pendiente;
                    //    $PRODUCTOjson[] = number_format($v['confirmar'], 2);
                    $days = ((strtotime(date('d-m-Y')) - strtotime($v['fecha'])) / (60 * 60 * 24));
                    if ($days < 0)
                        $days = 0;

                    $label = "<div><label class='label ";
                    if (floor($days) < 8) {
                        $label .= "label-success";
                    } elseif (floor($days) < 16) {
                        $label .= "label-info";
                    } else {
                        $label .= "label-warning";
                    }
                    $label .= "'>" . floor($days) . "</label></div>";
                    $PRODUCTOjson['label_dias'] = $label;

                    if ($v['var_credito_estado'] == CREDITO_ACUENTA) {
                        $PRODUCTOjson['estado'] = "A Cuenta";
                    } elseif ($v['var_credito_estado'] == CREDITO_CANCELADO) {
                        $PRODUCTOjson['estado'] = utf8_encode("Cancelado");
                    } elseif ($v['var_credito_estado'] == CREDITO_DEBE) {
                        $PRODUCTOjson['estado'] = "DB";
                    } else {
                        $PRODUCTOjson['estado'] = utf8_encode("Nota de Crédito");
                    }

                    $array[] = $PRODUCTOjson;

                }
            }


            $this->response($array, 200);
        } else {

        }
    }

}