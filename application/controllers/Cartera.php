<?php use Mike42\Escpos\Printer;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class cartera extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ReceiptPrint');
        $this->load->model('venta/venta_model');
        $this->load->model('local/local_model');
        $this->load->model('cliente/cliente_model');
        $this->load->model('tipo_venta/tipo_venta_model');
        $this->load->model('producto/producto_model', 'pd');
        $this->load->model('cartera/recibo_pago_cliente_model');
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
        $this->load->model('cajas/StatusCajaModel');
        //$this->load->library('Pdf');
        $this->load->library('session');
        //$this->load->library('phpExcel/PHPExcel.php');
        $this->very_sesion();
    }


    function generarrecibos()
    {
        $data = array();
        $data["lstCliente"] = $this->cliente_model->get_all();
        $data["metodos"] = $this->metodos_pago_model->get_all();
        $data['bancos'] = $this->banco_model->get_all();
        $recibo = $this->recibo_pago_cliente_model->get_next_id();
        if (count((array)$recibo) > 0)
            $data['recibo'] = $recibo->recibo_id + 1;
        else
            $data['recibo'] = 1;
        $dataCuerpo['cuerpo'] = $this->load->view('menu/cartera/generarRecibos', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function pagospendientes()
    {
        $data = "";
        $data["lstCliente"] = $this->cliente_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/cartera/pagospendientesVenta', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function lst_reg_pagospendientes()
    {
        if ($this->input->is_ajax_request()) {

            $this->load->view('menu/cartera/tbl_listareg_pagospendiente');
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }


    function lst_reg_pagospendientes_json()
    {
        if ($this->input->is_ajax_request()) {
            $id_cliente = null;
            $fechaDesde = null;
            $fechaHasta = null;
            $nombre_or = false;
            $where_or = false;
            // Pagination Result
            $array = array();
            $array['productosjson'] = array();
            $total = 0;
            $start = 0;
            $limit = false;
            $draw = $this->input->get('draw');
            if (!empty($draw)) {

                $start = $this->input->get('start');
                $limit = $this->input->get('length');
            }


            $where = "`venta_status` ='" . COMPLETADO . "' ";

            if ($this->input->get('cboCliente', true) != -1) {

                $where = $where . " AND venta.id_cliente =" . $this->input->get('cboCliente');
            }
            if ($_GET['fecIni'] != "") {

                $where = $where . " AND date(fecha) >= '" . date('Y-m-d', strtotime($this->input->get('fecIni'))) . "'";
            }
            if ($_GET['fecFin'] != "") {

                $where = $where . " AND  date(fecha) <= '" . date('Y-m-d', strtotime($this->input->get('fecFin'))) . "'";
            }
            //  echo $where;

            $where_in[0] = array(CREDITO_DEBE, CREDITO_ACUENTA);
            $nombre_in[0] = 'var_credito_estado';


            $select = 'venta.venta_id, venta_tipo, venta.id_cliente, nombres,apellidos, fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
            nombre_condiciones, condiciones_pago.dias,venta.id_cliente as clientV, venta.pagado,
            (select SUM(historial_monto) from historial_pagos_clientes where (historial_pagos_clientes.venta_id = venta.venta_id or 
            historial_pagos_clientes.id_credito = credito.credito_id
            )  and historial_estatus="PENDIENTE" ) as confirmar,usuario.nUsuCodigo as vendedor_id, usuario.nombre as vendedor_nombre';
            $from = "venta";
            $join = array('credito', 'cliente', 'documento_venta', 'tipo_venta', 'condiciones_pago', 'usuario');
            $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
                'documento_venta.id_venta=venta.venta_id', 'venta.venta_tipo=tipo_venta.tipo_venta_id', 'condiciones_pago.id_condiciones=tipo_venta.condicion_pago', 'usuario.nUsuCodigo=venta.id_vendedor');
            $tipo_join = array('left', 'left', 'left', 'left', 'left', 'left');


            $where_custom = false;
            $ordenar = $this->input->get('order');
            $order = false;
            $order_dir = 'desc';
            if (!empty($ordenar)) {
                $order_dir = $ordenar[0]['dir'];
                if ($ordenar[0]['column'] == 0) {
                    $order = 'venta.venta_id';
                }
                if ($ordenar[0]['column'] == 1) {
                    $order = 'documento_venta.nombre_tipo_documento';
                }
                if ($ordenar[0]['column'] == 2) {
                    $order = 'documento_venta.nombre_tipo_documento ';
                }
                if ($ordenar[0]['column'] == 3) {
                    $order = 'cliente.nombres';
                }
                if ($ordenar[0]['column'] == 4) {
                    $order = 'venta.fecha';
                }
                if ($ordenar[0]['column'] == 5) {
                    $order = 'total';
                }
                if ($ordenar[0]['column'] == 6) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 7) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 8) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'credito.var_credito_estado';
                }

            }

            $group = false;


            $total = $this->venta_model->traer_by_mejorado('COUNT(venta.venta_id) as total', $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", false, false, $order_dir, false, $where_custom);


            $lstVenta = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

            if (count($lstVenta) > 0) {

                foreach ($lstVenta as $v) {

                    $pendiente = 0;
                    $PRODUCTOjson = array();

                    $PRODUCTOjson[] = $v['venta_id'];
                    $PRODUCTOjson[] = $v['nombre_tipo_documento'];
                    $PRODUCTOjson[] = $v['documento_Numero'];
                    $PRODUCTOjson[] = $v['venta_tipo'];
                    $PRODUCTOjson[] = $v['nombres'] . ' ' . $v['apellidos'];
                    $PRODUCTOjson[] = date("d-m-Y H:i:s", strtotime($v['fecha']));
                    $PRODUCTOjson[] = number_format($v['total'], 2);


                    //$montoancelado = floatval($v["dec_credito_montodebito"]) + floatval($v['pagado'])+floatval($v['confirmacion_monto_cobrado_caja'])+floatval($v['confirmacion_monto_cobrado_bancos']);
                    $montoancelado = floatval($v["dec_credito_montodebito"]);
                    $cancelado = number_format($montoancelado, 2);
                    $PRODUCTOjson[] = $cancelado;


                    $resta = floatval($v['total']) - $montoancelado;
                    $pendiente = number_format($resta, 2);

                    $PRODUCTOjson[] = $pendiente;

                    //    $PRODUCTOjson[] = number_format($v['confirmar'], 2);


                    $days = (strtotime(date('d-m-Y')) - strtotime($v['fecha'])) / (60 * 60 * 24);
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
                    $PRODUCTOjson[] = $label;

                    if ($v['var_credito_estado'] == CREDITO_ACUENTA) {
                        $PRODUCTOjson[] = "A Cuenta";
                    } elseif ($v['var_credito_estado'] == CREDITO_CANCELADO) {
                        $PRODUCTOjson[] = utf8_encode("Cancelado");
                    } elseif ($v['var_credito_estado'] == CREDITO_DEBE) {
                        $PRODUCTOjson[] = "DB";
                    } else {
                        $PRODUCTOjson[] = utf8_encode("Nota de Crédito");
                    }


                    $botonas = '<div class="btn-group"> <a class="btn btn-default btn-xs tip" title="Ver Venta" onclick="visualizar(\'' . $v["venta_id"] . '\')" ><i
                                class="fa fa-search"></i>  Ver</a>';

                    if (floatval($pendiente != 0.00)) {
                        $botonas .= '<a onclick="pagar_venta(\'' . $v['venta_id'] . '\')" class="btn btn-default btn-xs tip" title="Pagar"><i class="fa fa-money"></i> Abonar</a>';

                        //$botonas .=  '<a class="btn btn-default tip" title="Ver Venta"  onclick="nota_credito(\'' . $v['venta_id'] . '\',\'' . $v['documento_Serie'] . '\',\'' . $v['documento_Numero'] . '\')"><i class="fa fa-file-archive-o"></i> Nota de Cr&eacute;dito</a>';
                    }
                    $botonas .= '</div>';
                    $PRODUCTOjson[] = $botonas;
                    $array['productosjson'][] = $PRODUCTOjson;

                }
            }
            $array['data'] = $array['productosjson'];
            $array['draw'] = $draw;//esto debe venir por post
            $array['recordsTotal'] = $total[0]['total'];
            $array['recordsFiltered'] = $total[0]['total']; // esto dbe venir por post

            echo json_encode($array);
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }

    public function verVentaCredito()
    {
        $credito_id = $this->input->post('credito_id');
        if ($credito_id != FALSE) {


            $result['credito'] = $this->venta_model->get_credito_by_id($credito_id);


            $result['ventas'] = array();
            $id_venta = null;
            if (!empty($result['credito']['id_venta'])) {
                $id_venta = $result['credito']['id_venta'];
                $result['ventas'] = $this->venta_model->obtener_venta($id_venta);
            }


            $select = 'historial_pagos_clientes.*,metodos_pago.nombre_metodo, credito.*, recibo_pago_cliente.anulado, 
             recibo_pago_cliente.observaciones_adicionales, usuario.nombre,usuario.username,  recibo_pago_cliente.fecha';
            $from = "historial_pagos_clientes";
            $join = array('credito', 'recibo_pago_cliente', 'usuario', 'metodos_pago');
            $campos_join = array('credito.id_venta=historial_pagos_clientes.venta_id',
                'recibo_pago_cliente.recibo_id=historial_pagos_clientes.recibo_id',
                'usuario.nUsuCodigo=recibo_pago_cliente.usuario',
                'metodos_pago.id_metodo = recibo_pago_cliente.metodo');

            $tipo_join = array('left', 'left', 'left', 'left');
            if (!empty($id_venta)) {
                $where = array('venta_id' => $id_venta);
            } else {
                $where = array('historial_pagos_clientes.id_credito' => $credito_id);
            }
            $result['historial'] = $this->historial_pagos_clientes_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where, false, false, false, false, false, false, "RESULT_ARRAY");
            $this->load->view('menu/ventas/visualizar_venta_credito', $result);
        }
    }


    function estadocuenta()
    {
        $data = array();
        $data["lstCliente"] = $this->cliente_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/cartera/estadocuentaVenta', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function clientesdeuda()
    {
        $data = array();
        $data["lstCliente"] = $this->cliente_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/cartera/clientesdeuda', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    function lst_reg_estadocuenta_json()
    {
        if ($this->input->is_ajax_request()) {
            $id_cliente = null;
            $fechaDesde = null;
            $fechaHasta = null;
            $nombre_or = false;
            $where_or = false;
            // Pagination Result
            $array = array();
            $array['productosjson'] = array();
            $total = 0;
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
            $data = $this->input->get('data');


            $where = "(`venta_status` ='" . COMPLETADO . "' or venta_status IS NULL) ";
            if (isset($data['cboCliente']) && $data['cboCliente'] != -1) {

                $where = $where . " AND (venta.id_cliente =" . $data['cboCliente'] . " OR  credito.id_cliente =" . $data['cboCliente'] . ")";
            }
            if ($data['filtro'] == 'VENTA') {

                if (isset($data['fecIni']) && $data['fecIni'] != "") {

                    $where = $where . " AND (date(venta.fecha) >= '" . date('Y-m-d', strtotime($data['fecIni'])) . "' or venta.fecha IS NULL)";
                }
                if (isset($data['fecFin']) && $data['fecFin'] != "") {

                    $where = $where . " AND  (date(venta.fecha) <= '" . date('Y-m-d', strtotime($data['fecFin'])) . "' or venta.fecha IS NULL)";
                }
            } else {

                $where2 = "";
                if (isset($data['fecIni']) && $data['fecIni'] != "") {

                    $where = $where . " AND date(recibo_pago_cliente.fecha) >= '" . date('Y-m-d', strtotime($data['fecIni'])) . "'";
                }
                if (isset($data['fecFin']) && $data['fecFin'] != "") {

                    $where = $where . " AND  date(recibo_pago_cliente.fecha) <= '" . date('Y-m-d', strtotime($data['fecFin'])) . "'";
                }
            }

            //  echo $where;
            $nombre_in = false;
            $where_in = false;
            $nombre_in[0] = 'var_credito_estado';
            $where_in[0] = array(CREDITO_DEBE, CREDITO_ACUENTA, CREDITO_NOTACREDITO, CREDITO_CANCELADO);

            ///////////////////////
            $select = 'venta.venta_id, venta_tipo,credito.credito_fecha,  venta.id_cliente, credito.credito_id, 
            nombres,apellidos, dec_credito_montodeuda, venta.fecha, total,var_credito_estado,
             dec_credito_montodebito, documento_venta.*,
            nombre_condiciones, condiciones_pago.dias,venta.id_cliente as clientV, venta.pagado,
            usuario.nUsuCodigo as vendedor_id, usuario.nombre as vendedor_nombre,usuario.nombre as vendedor, 
            (select SUM(historial_monto) from historial_pagos_clientes 
             where historial_pagos_clientes.venta_id = venta.venta_id ) as confirmar';

            $from = "credito";
            $join = array('venta', 'cliente', 'documento_venta', 'tipo_venta', 'condiciones_pago', 'usuario', 'historial_pagos_clientes', 'recibo_pago_cliente');
            $campos_join = array(
                'credito.id_venta=venta.venta_id',
                'cliente.id_cliente=venta.id_cliente or cliente.id_cliente=credito.id_cliente',
                'documento_venta.id_venta=venta.venta_id',
                'venta.venta_tipo=tipo_venta.tipo_venta_id',
                'condiciones_pago.id_condiciones=tipo_venta.condicion_pago',
                'usuario.nUsuCodigo=venta.id_vendedor',
                'historial_pagos_clientes.venta_id = venta.venta_id or historial_pagos_clientes.id_credito=credito.credito_id ',
                'recibo_pago_cliente.recibo_id = historial_pagos_clientes.recibo_id');
            $tipo_join = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'left');

            $where_custom = false;
            $ordenar = $this->input->get('order');
            $order = false;
            $order_dir = 'desc';
            if (!empty($ordenar)) {
                $order_dir = $ordenar[0]['dir'];
                if ($ordenar[0]['column'] == 0) {
                    $order = 'credito.credito_id';
                }
                if ($ordenar[0]['column'] == 1) {
                    $order = 'documento_venta.nombre_tipo_documento';
                }
                if ($ordenar[0]['column'] == 2) {
                    $order = 'documento_venta.nombre_tipo_documento ';
                }
                if ($ordenar[0]['column'] == 3) {
                    $order = 'cliente.nombres';
                }
                if ($ordenar[0]['column'] == 4) {
                    $order = 'venta.fecha';
                }
                if ($ordenar[0]['column'] == 5) {
                    $order = 'total';
                }
                if ($ordenar[0]['column'] == 6) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 7) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 8) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'credito.var_credito_estado';
                }

            }

            $group = 'credito.credito_id';


            $total = $this->venta_model->traer_by_mejorado('COUNT(venta.venta_id) as total', $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", false, false, $order_dir, false, $where_custom);


            $lstVenta = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

            /// echo $this->db->last_query();
            if (count($lstVenta) > 0) {

                foreach ($lstVenta as $v) {

                    $days = (strtotime(date('d-m-Y')) - strtotime(!empty($v['fecha']) ? $v['fecha'] : $v['credito_fecha'])) / (60 * 60 * 24);
                    if ($days < 0)
                        $days = 0;

                    $label = "<div><label class='label ";
                    if (floor($days) < 8) {
                        $label .= "label-success";
                    } elseif (floor($days) < 31) {
                        $label .= "label-warning";
                    } else {
                        $label .= "label-danger";
                    }
                    $label .= "'>" . floor($days) . "</label></div>";

                    if (($data['diasvencimiento'] == "-1") ||
                        ($data['diasvencimiento'] == "<8" && floor($days) < 8) ||
                        ($data['diasvencimiento'] == "<31" && (floor($days) >= 8 && floor($days) < 31)) ||
                        ($data['diasvencimiento'] == ">=31" && floor($days) >= 31)) {
                        $pendiente = 0;
                        $PRODUCTOjson = array();

                        $PRODUCTOjson[] = $v['venta_id'];
                        $PRODUCTOjson[] = !empty($v['nombre_tipo_documento']) ? $v['nombre_tipo_documento'] : 'SALDO INICIAL';
                        $PRODUCTOjson[] = !empty($v['documento_Numero']) ? $v['documento_Numero'] : '';

                        $PRODUCTOjson[] = $v['nombres'] . " " . $v['apellidos'];
                        $PRODUCTOjson[] = date("d-m-Y H:i:s", strtotime(!empty($v['fecha']) ? $v['fecha'] : $v['credito_fecha']));
                        $PRODUCTOjson[] = number_format($v['total'], 2);

                        $montoancelado = number_format(floatval($v['dec_credito_montodebito']), 2);
                        $resta = number_format(floatval($v['dec_credito_montodeuda'] - $v['dec_credito_montodebito']), 2);

                        $PRODUCTOjson[] = $montoancelado;
                        $PRODUCTOjson[] = $resta;

                        //Este es liquidacion pero fue cambiado por el nombre del vendedor
                        //$PRODUCTOjson[] = number_format($v['confirmar'], 2);
                        $PRODUCTOjson[] = $v['vendedor'];


                        $PRODUCTOjson[] = $label;

                        if ($v['var_credito_estado'] == CREDITO_ACUENTA) {
                            $PRODUCTOjson[] = "A Cuenta";
                        } elseif ($v['var_credito_estado'] == CREDITO_CANCELADO) {
                            $PRODUCTOjson[] = utf8_encode("Cancelado");
                        } elseif ($v['var_credito_estado'] == CREDITO_DEBE) {
                            $PRODUCTOjson[] = "DB";
                        } else {
                            $PRODUCTOjson[] = utf8_encode("Nota de Crédito");
                        }


                        $botonas = '<div class="btn-group"><a class=\'btn btn-default tip\' title="Ver Venta" onclick="Cartera.visualizar(' . $v["credito_id"] . ')"><i
								class="fa fa-search"></i> Historial</a>';

                        $botonas .= '</div>';
                        $PRODUCTOjson[] = $botonas;
                        $array['productosjson'][] = $PRODUCTOjson;
                    }

                }
            }
            $array['data'] = $array['productosjson'];
            $array['draw'] = $draw;//esto debe venir por post
            $array['recordsTotal'] = sizeof($total);
            $array['recordsFiltered'] = sizeof($total);

            echo json_encode($array);
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }


    function lst_reg_clientesdeuda_json()
    {
        if ($this->input->is_ajax_request()) {
            $id_cliente = null;
            $fechaDesde = null;
            $fechaHasta = null;
            $nombre_or = false;
            $where_or = false;
            // Pagination Result
            $array = array();
            $array['productosjson'] = array();
            $total = 0;
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

            $data = $this->input->get('data');
            $where = "`venta_status` ='" . COMPLETADO . "' ";
            $search = $this->input->get('search');
            $buscar = $search['value'];
            $where_custom = false;
            if (!empty($search['value'])) {
                $where_custom = "(cliente.id_cliente LIKE '%" . $buscar . "%'
                  or nombres LIKE '%" . $buscar . "%' or gravado LIKE '%" . $buscar . "%'
                  or apellidos LIKE '%" . $buscar . "%')";
            }
            /*  if (isset($data['cboCliente']) && $data['cboCliente'] != -1) {

                  $where = $where . " AND venta.id_cliente =" . $data['cboCliente'];
              }
              if (isset($data['fecIni']) && $data['fecIni'] != "") {

                  $where = $where . " AND date(fecha) >= '" . date('Y-m-d', strtotime($data['fecIni'])) . "'";
              }
              if (isset($data['fecFin']) && $data['fecFin'] != "") {

                  $where = $where . " AND  date(fecha) <= '" . date('Y-m-d', strtotime($data['fecFin'])) . "'";
              }*/
            //  echo $where;
            $nombre_in[0] = 'var_credito_estado';
            $where_in[0] = array(CREDITO_DEBE, CREDITO_ACUENTA, CREDITO_NOTACREDITO, CREDITO_CANCELADO);
            $nombre_in[1] = 'venta_status';
            $where_in[1] = array(PEDIDO_ENTREGADO, PEDIDO_DEVUELTO, COMPLETADO, PEDIDO_GENERADO, PEDIDO_ENVIADO);
            ///////////////////////
            $select = 'venta.venta_id, venta_tipo, venta.id_cliente, nombres,apellidos, sum(dec_credito_montodeuda) as dec_credito_montodeuda,
             fecha, total,var_credito_estado,  sum(dec_credito_montodebito) as dec_credito_montodebito, documento_venta.*,
            nombre_condiciones, condiciones_pago.dias,venta.id_cliente as clientV, venta.pagado, cliente.id_cliente, 
            (select SUM(historial_monto) from historial_pagos_clientes where (historial_pagos_clientes.venta_id = venta.venta_id or historial_pagos_clientes.id_credito=credito.credito_id) ) as confirmar,
            usuario.nUsuCodigo as vendedor_id, usuario.nombre as vendedor_nombre,usuario.nombre as vendedor';
            $from = "venta";
            $join = array('credito', 'cliente', 'documento_venta', 'tipo_venta', 'condiciones_pago', 'usuario');
            $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
                'documento_venta.id_venta=venta.venta_id', 'venta.venta_tipo=tipo_venta.tipo_venta_id',
                'condiciones_pago.id_condiciones=tipo_venta.condicion_pago', 'usuario.nUsuCodigo=venta.id_vendedor');
            $tipo_join = array('left', 'left', 'left', 'left', 'left', 'left');


            $ordenar = $this->input->get('order');
            $order = false;
            $order_dir = 'desc';
            if (!empty($ordenar)) {
                $order_dir = $ordenar[0]['dir'];
                if ($ordenar[0]['column'] == 0) {
                    $order = 'venta.venta_id';
                }
                if ($ordenar[0]['column'] == 1) {
                    $order = 'documento_venta.nombre_tipo_documento';
                }
                if ($ordenar[0]['column'] == 2) {
                    $order = 'documento_venta.nombre_tipo_documento ';
                }
                if ($ordenar[0]['column'] == 3) {
                    $order = 'cliente.nombres';
                }
                if ($ordenar[0]['column'] == 4) {
                    $order = 'venta.fecha';
                }
                if ($ordenar[0]['column'] == 5) {
                    $order = 'total';
                }
                if ($ordenar[0]['column'] == 6) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 7) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 8) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'dec_credito_montodebito';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'credito.var_credito_estado';
                }

            }

            $group = 'venta.id_cliente';


            $lstVenta = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit,
                $start, $order_dir, false, $where_custom);

            if (count($lstVenta) > 0) {

                foreach ($lstVenta as $v) {
                    $resta = number_format(floatval($v['dec_credito_montodeuda'] - $v['dec_credito_montodebito']), 2, ',', '.');

                    if ($resta > 0):
                        $pendiente = 0;
                        $PRODUCTOjson = array();

                        // $PRODUCTOjson[] = $v['venta_id'];
                        //  $PRODUCTOjson[] = $v['nombre_tipo_documento'];
                        //  $PRODUCTOjson[] = $v['documento_Numero'];

                        $PRODUCTOjson[] = $v['nombres'] . " " . $v['apellidos'];
                        // $PRODUCTOjson[] = date("d-m-Y H:i:s", strtotime($v['fecha']));
                        //$PRODUCTOjson[] = number_format($v['total'], 2);

                        $montoancelado = number_format(floatval($v['dec_credito_montodebito']), 2, ',', '.');


                        // $PRODUCTOjson[] = $montoancelado;
                        $PRODUCTOjson[] = $resta;

                        //Este es liquidacion pero fue cambiado por el nombre del vendedor
                        //$PRODUCTOjson[] = number_format($v['confirmar'], 2);
                        $PRODUCTOjson[] = $v['vendedor'];

                        $days = (strtotime(date('d-m-Y')) - strtotime($v['fecha'])) / (60 * 60 * 24);
                        if ($days < 0)
                            $days = 0;

                        $label = "<div><label class='label ";
                        if (floor($days) < 8) {
                            $label .= "label-success";
                        } elseif (floor($days) < 31) {
                            $label .= "label-warning";
                        } else {
                            $label .= "label-danger";
                        }
                        $label .= "'>" . floor($days) . "</label></div>";
                        $PRODUCTOjson[] = $label;

                        if ($v['var_credito_estado'] == CREDITO_ACUENTA) {
                            $PRODUCTOjson[] = "A Cuenta";
                        } elseif ($v['var_credito_estado'] == CREDITO_CANCELADO) {
                            $PRODUCTOjson[] = utf8_encode("Cancelado");
                        } elseif ($v['var_credito_estado'] == CREDITO_DEBE) {
                            $PRODUCTOjson[] = "DB";
                        } else {
                            $PRODUCTOjson[] = utf8_encode("Nota de Crédito");
                        }


                        $botonas = '<div class="btn-group"><a class=\'btn btn-default tip\' title="Ver Venta" onclick="Cartera.visualizar(' . $v["venta_id"] . ')"><i
								class="fa fa-search"></i> Historial</a>';

                        $botonas .= '</div>';
                        $PRODUCTOjson[] = $botonas;

                        $PRODUCTOjson['campos_sumar'] = array();
                        $PRODUCTOjson['campos_sumar'][] = 1;

                        $array['productosjson'][] = $PRODUCTOjson;


                    endif;

                }
            }


            $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", false, false, $order_dir, false, $where_custom);


            $array['data'] = $array['productosjson'];
            $array['draw'] = $draw;//esto debe venir por post
            $array['recordsTotal'] = sizeof($total);
            $array['recordsFiltered'] = sizeof($total);// esto dbe venir por post

            echo json_encode($array);
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }


    function lst_reg_estadocuenta()
    {
        if ($this->input->is_ajax_request()) {
            $result = array();

            $this->load->view('menu/cartera/tbl_listareg_estaodcuenta', $result);
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }


    /**
     * esta funcion se llama en cartera, pero cuando la impresion es en la nube
     */
    function getDataToPrintCarteraTiqLocal(){
        $datos = $this->dataToPrintCartera();
        echo json_encode($datos);

    }

    /**
     * @return array
     * esta es la data que se va a imprimir en cartera
     */

    function dataToPrintCartera(){

        $recibo = json_decode($this->input->post('recibo', true));

        $select = 'historial_pagos_clientes.*,recibo_pago_cliente.*, recibo_pago_cliente.fecha as fecha_pago_recibo,
            credito.*,documento_venta.*,venta.*,cliente.*';
        $from = "historial_pagos_clientes";
        $join = array('credito', 'recibo_pago_cliente', 'documento_venta', 'venta', 'cliente');
        $campos_join = array('credito.id_venta=historial_pagos_clientes.venta_id or credito.credito_id=historial_pagos_clientes.id_credito ',
            'recibo_pago_cliente.recibo_id=historial_pagos_clientes.recibo_id',
            'credito.id_venta=documento_venta.id_venta', 'venta.venta_id=credito.id_venta', 'cliente.id_cliente=venta.id_cliente  or cliente.id_cliente=credito.id_cliente');
        $where = array('historial_pagos_clientes.recibo_id' => $recibo);


        $credito = $this->historial_pagos_clientes_model->traer_by($select, $from, $join, $campos_join, array('left', 'left', 'left', 'left', 'left'), $where, false, false,
            false, false, false, false, "RESULT_ARRAY");


        //echo $credito[0]['id_cliente'];

        $where = "(credito.id_cliente=" . $credito[0]['id_cliente'] . " or venta.id_cliente=" . $credito[0]['id_cliente'] . ")
            and (venta_status ='COMPLETADO' or venta_status IS NULL)";
        $join = array('venta');
        $campos_join = array('venta.venta_id=credito.id_venta');
        $tipo_join = array('false', 'false');
        $select = 'sum(dec_credito_montodeuda) as total, sum(dec_credito_montodebito) as pagado ';
        $from = "credito";
        $buscar_restante = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            false, false, false, false, false, false, "ROW_ARRAY");


        $restante = $buscar_restante['total'] - $buscar_restante['pagado'];


        return array('credito'=>$credito, 'restante'=>$restante);
    }

    function directPrint()
    {


        //try {

        $datos = $this->dataToPrintCartera();
        $credito = $datos['credito'];
        $restante = $datos['restante'];

        $printer = $this->receiptprint->connectUsb($this->session->userdata('IMPRESORA'), $this->session->userdata('USUARIO_IMPRESORA'), $this->session->userdata('PASSWORD_IMPRESORA'), $this->session->userdata('WORKGROUP_IMPRESORA'));
        /* Initialize */
        $printer->initialize();

        $printer->feed(1);


        $printer->text("RECIBO DE CAJA Nº:");
        $printer->text(str_pad($credito[0]['recibo_id'], 10));
        $printer->feed(1);

        $printer->text(str_pad("FECHA DE ABONO: ", 12));
        $printer->text(str_pad(date('d/m/Y', strtotime($credito[0]['fecha_pago_recibo'])), 12));

        $printer->feed(2);
        $printer->text($credito[0]['identificacion'] . " \n");
        $printer->text($credito[0]['nombres'] . " " . $credito[0]['apellidos'] . " \n");
        $printer->text($credito[0]['direccion'] . " \n");

        $printer->feed(1);

        $printer->text(str_pad("Fecha", 14));
        $printer->text(str_pad("Abono a", 12));
        $printer->text("Valor");

        // $printer->text(str_pad("Retenc", 5));
        $printer->feed(1);
        $total = 0;
        $totdesc = 0;
        $totalret = 0;
        $printer->text("----------------------------------------\n");

        foreach ($credito as $detalle) {

            $printer->text(str_pad(date('d/m/Y', strtotime($credito[0]['fecha'])), 14));
            $printer->text(str_pad($detalle['documento_Numero'], 12));
            $printer->text(number_format($detalle['historial_monto'], 2, ',', '.'));
            //$printer->text(str_pad(0, 5));
            $total = $total + $detalle['historial_monto'];
            $printer->feed(1);

        }

        $total = number_format($total, 2, ',', '.');
        $printer->text(str_pad("Total", 26));
        $printer->text($total);
        //   $printer->text(str_pad($totalret, 5));
        $printer->feed(1);
        $printer->text("----------------------------------------\n");


        $saldopendiente = number_format($restante, 2, ',', '.');
        $printer->text('SALDO PENDIENTE: ' . $saldopendiente);

        $printer->feed(5);
        $printer->cut(Printer::CUT_FULL, 10); //corta el papel
        $printer->pulse(); // abre la caja registradora
        /* Close printer */
        $printer->close();


        echo json_encode(array('result' => "success"));


        /*} catch (Exception $e) {
            log_message("error", "Error: Could not print. Message " . $e->getMessage());
            echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
            $this->receiptprint->close_after_exception();
        }*/


    }


    function anularRecibo()
    {


        //try {
        $caja = $this->session->userdata('cajapertura'); // se amacena el id de statuscaja  no la caja

        //echo $caja;
        $statuscaja = $this->StatusCajaModel->getBy(array('id' => $caja));

        if (!empty($statuscaja['cierre'])) {
            $json['error'] = "La caja en la que está intentando realizar la operación ya ha sido cerrada por otro usuario. Por favor cierre sessión e ingrese nuevamente para trabajar en otra caja";
            //  echo json_encode($dataresult);
        } else {

            if (($caja == '')) {
                $json['error'] = "Debe aperturar una caja para poder continuar";

            } else {
                $recibo = json_decode($this->input->post('recibo', true));
                $usu_id = $this->session->userdata('nUsuCodigo');
                    $anulado = $this->recibo_pago_cliente_model->anular($recibo, $usu_id, $caja);
             
         if ($anulado) {


                    $select = 'historial_pagos_clientes.*,recibo_pago_cliente.*, recibo_pago_cliente.fecha as fecha_pago_recibo,
            credito.*,documento_venta.*,venta.*,cliente.*';
                    $from = "historial_pagos_clientes";
                    $join = array('credito', 'recibo_pago_cliente', 'documento_venta', 'venta', 'cliente');
                    $campos_join = array('credito.id_venta=historial_pagos_clientes.venta_id or credito.credito_id=historial_pagos_clientes.id_credito ',
                        'recibo_pago_cliente.recibo_id=historial_pagos_clientes.recibo_id',
                        'credito.id_venta=documento_venta.id_venta', 'venta.venta_id=credito.id_venta', 'cliente.id_cliente=venta.id_cliente  or cliente.id_cliente=credito.id_cliente');
                    $where = array('historial_pagos_clientes.recibo_id' => $recibo);


                    $creditos = $this->historial_pagos_clientes_model->traer_by($select, $from, $join, $campos_join,
                        array('left', 'left', 'left', 'left', 'left'), $where, false, false,
                        false, false, 'credito.credito_id', false, "RESULT_ARRAY");


                    foreach ($creditos as $cred) {

                        $select = 'historial_pagos_clientes.*,recibo_pago_cliente.*, recibo_pago_cliente.fecha as fecha_pago_recibo,
            credito.*,documento_venta.*,venta.*,cliente.*';
                        $from = "historial_pagos_clientes";
                        $join = array('credito', 'recibo_pago_cliente', 'documento_venta', 'venta', 'cliente');
                        $campos_join = array('credito.id_venta=historial_pagos_clientes.venta_id or credito.credito_id=historial_pagos_clientes.id_credito ',
                            'recibo_pago_cliente.recibo_id=historial_pagos_clientes.recibo_id',
                            'credito.id_venta=documento_venta.id_venta', 'venta.venta_id=credito.id_venta', 'cliente.id_cliente=venta.id_cliente  or cliente.id_cliente=credito.id_cliente');
                        $where = array('historial_pagos_clientes.recibo_id' => $recibo, 'credito.credito_id' => $cred['id_credito']);


                        $credito = $this->historial_pagos_clientes_model->traer_by($select, $from, $join, $campos_join, array('left', 'left', 'left', 'left', 'left'), $where, false, false,
                            false, false, false, false, "RESULT_ARRAY");



                        $historial_monto = 0;
                        if (isset($credito[0])) {
                            $id_credito = $credito[0]['id_credito'];
                            foreach ($credito as $detalle) {

                                $historial_monto = $historial_monto + $detalle['historial_monto'];

                            }

                            $this->sumCredito($id_credito, $historial_monto);

                        }
                    }

                }

                $json = array('success' => true);
            }


        }
        echo json_encode($json);

    }

    function getCredito($where)
    {
        $this->db->where($where);
        $query = $this->db->get('credito');
        return $query->row_array();
    }


    function sumCredito($credito_id, $montosumar)
    {
        $this->db->trans_start();

        $where = array('credito_id' => $credito_id);


        $queryCredito = $this->getCredito($where);

        $montodebito = $queryCredito['dec_credito_montodebito'] - $montosumar;
        $creditoInsert['dec_credito_montodebito'] = $montodebito;

        if ($montodebito <= 0) {
            $creditoInsert['var_credito_estado'] = CREDITO_DEBE;
        } else {
            $creditoInsert['var_credito_estado'] = CREDITO_ACUENTA;
        }


        // var_dump($creditoInsert);
        $this->db->where($where);
        $this->db->update('credito', $creditoInsert);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
        $this->db->trans_off();
    }


    function toPDF_estadoCuenta()
    {

        $pdf = new Pdf('L', 'mm', 'LETTER', true, 'UTF-8', false, false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetPrintHeader(true);
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->AddPage('L');


        if ($this->input->post('cboCliente2', true) != '-1') {
            $where = array('venta.id_cliente' => $this->input->post('cboCliente2', true));
        }
        if ($_POST['fecIni2'] != "") {
            $where['fecha >= '] = date('Y-m-d', strtotime($this->input->post('fecIni2')));
        }
        if ($_POST['fecFin2'] != "") {
            $where['fecha <= '] = date('Y-m-d', strtotime($this->input->post('fecFin2')));
        }

        if (empty($where)) {
            $where = false;
        }
        ////////////////////////
        $nombre_or = false;
        $where_or = false;
        ///////////////////////
        $nombre_in[0] = 'var_credito_estado';
        $where_in[0] = array(CREDITO_DEBE, CREDITO_ACUENTA, CREDITO_NOTACREDITO, CREDITO_CANCELADO);
        $nombre_in[1] = 'venta_status';
        $where_in[1] = array(PEDIDO_ENTREGADO, PEDIDO_DEVUELTO, COMPLETADO, PEDIDO_GENERADO, PEDIDO_ENVIADO);
        ///////////////////////
        $select = 'venta.venta_id,venta.pagado, venta.id_cliente, venta.pagado, razon_social,fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
            nombre_condiciones, confirmacion_monto_cobrado_caja, confirmacion_monto_cobrado_bancos,(select SUM(historial_monto) from historial_pagos_clientes 
            where (historial_pagos_clientes.venta_id = venta.venta_id or historial_pagos_clientes.id_credito=credito.credito_id) and historial_estatus="PENDIENTE" ) as confirmar';
        $from = "venta";
        $join = array('credito', 'cliente', 'documento_venta', 'condiciones_pago',);
        $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
            'documento_venta.id_venta=venta.venta_id', 'condiciones_pago.id_condiciones=venta.condicion_pago');
        $tipo_join = array('left', null, null, null, 'left');

        $result['lstVenta'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, false, "RESULT_ARRAY");

        // Aqui llamo a la vista html y le paso la data
        $html = $this->load->view('menu/reportes/pdfEstadoCuenta', $result, true);

        // creo el pdf con la vista
        $pdf->WriteHTML($html);
        $nombre_archivo = utf8_decode("EstadoCuenta.pdf");
        $pdf->Output($nombre_archivo, 'I');

    }

    function toExcel_estadoCuenta()
    {
        if ($this->input->post('cboCliente1', true) != '-1') {
            $where = array('venta.id_cliente' => $this->input->post('cboCliente1', true));
        }
        if ($_POST['fecIni1'] != "") {
            $where['fecha >= '] = date('Y-m-d', strtotime($this->input->post('fecIni1')));
        }
        if ($_POST['fecFin1'] != "") {
            $where['fecha <= '] = date('Y-m-d', strtotime($this->input->post('fecFin1')));
        }

        if (empty($where)) {
            $where = false;
        }
        ////////////////////////
        $nombre_or = false;
        $where_or = false;
        ///////////////////////
        $nombre_in[0] = 'var_credito_estado';
        $where_in[0] = array(CREDITO_DEBE, CREDITO_ACUENTA, CREDITO_NOTACREDITO, CREDITO_CANCELADO);
        $nombre_in[1] = 'venta_status';
        $where_in[1] = array(PEDIDO_ENTREGADO, PEDIDO_DEVUELTO, COMPLETADO, PEDIDO_GENERADO, PEDIDO_ENVIADO);
        ///////////////////////
        $select = 'venta.venta_id,venta.pagado, venta.id_cliente, venta.pagado, razon_social,fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
            nombre_condiciones, confirmacion_monto_cobrado_caja, confirmacion_monto_cobrado_bancos,(select SUM(historial_monto) from historial_pagos_clientes where (historial_pagos_clientes.venta_id = credito.credito_id or historial_pagos_clientes.id_credito = venta.venta_id) and historial_estatus="PENDIENTE" ) as confirmar';
        $from = "credito";
        $join = array('venta', 'cliente', 'documento_venta', 'condiciones_pago',);
        $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
            'documento_venta.id_venta=venta.venta_id', 'condiciones_pago.id_condiciones=venta.condicion_pago');
        $tipo_join = array('left', null, null, null);

        $result['lstVenta'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, false, "RESULT_ARRAY");

        // Aqui llamo a la vista html y le paso la data
        $this->load->view('menu/reportes/excelEstadoCuenta', $result);
    }

    function deudaselevadaspdf($fecha_ini = false, $fecha_fin = false, $proveedor = false, $zona = false)
    {

        if ($proveedor != false and $proveedor != -1) {
            $where = array('venta.id_vendedor');
        }
        if ($zona != false and $zona != -1) {
            $where = array('venta.id_vendedor');
        }

        if ($fecha_ini != false and $fecha_ini != "") {
            $where['fecha >= '] = date('Y-m-d', strtotime($fecha_ini));
        }
        if ($fecha_fin != false and $fecha_fin != "") {
            $where['fecha <= '] = date('Y-m-d', strtotime($fecha_fin));
        }
        if (empty($where)) {
            $where = false;
        }
        ////////////////////////
        $nombre_or = false;
        $where_or = false;
        ///////////////////////
        $nombre_in[0] = 'var_credito_estado';
        $where_in[0] = array('DEBE', 'A_CUENTA');
        $nombre_in[1] = 'venta_status';
        $where_in[1] = array('ENTREGADO', 'DEVUELTO PARCIALMENTE', 'COMPLETADO');
        ///////////////////////
        $select = 'venta.venta_id, venta.id_cliente,venta.id_vendedor, razon_social,fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
            nombre_condiciones,usuario.nombre,zonas.zona_nombre';
        $from = "venta";
        $join = array('credito', 'cliente', 'documento_venta', 'condiciones_pago', 'usuario', 'zonas');
        $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
            'documento_venta.id_venta=venta.venta_id', 'condiciones_pago.id_condiciones=venta.condicion_pago',
            'usuario.nUsuCodigo=venta.id_vendedor', 'zonas.zona_id=cliente.id_zona');
        $tipo_join = false;

        $listaventa = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, false, "RESULT_ARRAY");


        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('L');
        // $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Cuentas por Pagar');
        // $pdf->SetSubject('FICHA DE MIEMBROS');
        $pdf->SetPrintHeader(false);
//echo K_PATH_IMAGES;
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "AL.â€¢.G.â€¢.D.â€¢.G.â€¢.A.â€¢.D.â€¢.U.â€¢.<br>Gran Logia de la RepÃºblica de Venezuela", "Gran Logia de la <br> de Venezuela", array(0, 64, 255), array(0, 64, 128));


        $pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));

// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config

// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        //  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        //  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//relaciÃ³n utilizada para ajustar la conversiÃ³n de los pÃ­xeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// ---------------------------------------------------------
// establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);

// Establecer el tipo de letra

//Si tienes que imprimir carÃ¡cteres ASCII estÃ¡ndar, puede utilizar las fuentes bÃ¡sicas como
// Helvetica para reducir el tamaÃ±o del archivo.
        $pdf->SetFont('helvetica', '', 14, '', true);

// AÃ±adir una pÃ¡gina
// Este mÃ©todo tiene varias opciones, consulta la documentaciÃ³n para mÃ¡s informaciÃ³n.
        $pdf->AddPage();

        $pdf->SetFontSize(8);

        $textoheader = "";
        $pdf->writeHTMLCell(
            $w = 0, $h = 0, $x = '60', $y = '',
            $textoheader, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'C', $autopadding = true);

//fijar efecto de sombra en el texto
//        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

        $pdf->SetFontSize(12);

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>Deudas elevadas</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);


        //preparamos y maquetamos el contenido a crear
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #000; font-weight: bold; background-color: #CED6DB; }";
        $html .= "td{color: #222; font-weight: bold; background-color: #fff;}";
        $html .= "table{border:0.2px}";
        $html .= "body{font-size:15px}";
        $html .= "</style>";


        $html .= "<table>";

        $html .= "<tr> <th class='tip' title='Documento'> Nro Venta</th><th>Cliente</th>";
        $html .= "<th class='tip' title='Fecha Registro'>Fecha de venta.</th><th class='tip' >Monto Cred." . MONEDA . "</th>";
        $html .= "<th class='tip' >Monto Canc " . MONEDA . "</th><th class='tip' >Documento</th>";
        $html .= "<th>Trabajador </th>";
        $html .= "<th>Zona </th>";
        $html .= "<th>D&iacute;as de atraso </th>";
        $html .= " <th class='tip' >Estado</th></tr>";
        if (count($listaventa > 0)) {
            foreach ($listaventa as $row) {
                $html .= "<tr><td style='text-align: center;'>" . $row['documento_Numero'] . "</td>";
                $html .= "<td>" . $row['razon_social'] . "</td>";
                $html .= "<td style='text-align: center;'>" . date('d-m-', strtotime($row['fecha'])) . "</td>";
                $html .= "<td style='text-align: center;'>" . $row['total'] . "</td>";
                $html .= "<td style='text-align: center;'>" . $row['dec_credito_montodebito'] . "</td>";
                $html .= "<td style='text-align: center;'>" . $row['nombre_tipo_documento'] . "</td>";
                $html .= "<td style='text-align: center;'>" . $row['nombre'] . "</td>";
                $html .= "<td style='text-align: center;'>" . $row['zona_nombre'] . "</td>";
                $html .= "<td style='text-align: center;'>";
                $days = (strtotime(date('d-m-Y')) - strtotime($row['fecha'])) / (60 * 60 * 24);
                $html .= "<div ";
                if (floor($days) < 8) {
                    $html .= " style='color: #00CC00' ";
                } elseif (floor($days) < 16) {
                    $html .= " style='color: gold'";
                } else {
                    $html .= "style='color: #ff0000'";
                }
                $html .= " >";
                $html .= floor($days);
                $html .= "</div>";
                $html .= " </td>";
                $html .= " <td style='text-align: center;' >";
                if ($row['var_credito_estado'] == CREDITO_ACUENTA) {
                    $html .= "A Cuenta";
                } elseif ($row['var_credito_estado'] == CREDITO_CANCELADO) {
                    $html .= "Canceló";
                } elseif ($row['var_credito_estado'] == CREDITO_DEBE) {
                    $html .= "DB";
                } else {
                    $html .= "Nota de Crédito";
                }
                $html .= "</td>";

                $html .= "</tr>";

            }
        }

        $html .= "</table>";

// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este mÃ©todo tiene varias opciones, consulte la documentaciÃ³n para mÃ¡s informaciÃ³n.
        $nombre_archivo = utf8_decode("DeudasElevadas.pdf");
        $pdf->Output($nombre_archivo, 'D');

    }

    function deudasElevadasexcel($fecha_ini = false, $fecha_fin = false, $proveedor = false, $zona = false)
    {

        if ($proveedor != false and $proveedor != -1) {
            $where = array('venta.id_vendedor');
        }
        if ($zona != false and $zona != -1) {
            $where = array('venta.id_vendedor');
        }

        if ($fecha_ini != false and $fecha_ini != "") {
            $where['fecha >= '] = date('Y-m-d', strtotime($fecha_ini));
        }
        if ($fecha_fin != false and $fecha_fin != "") {
            $where['fecha <= '] = date('Y-m-d', strtotime($fecha_fin));
        }
        if (empty($where)) {
            $where = false;
        }
        ////////////////////////
        $nombre_or = false;
        $where_or = false;
        ///////////////////////
        $nombre_in[0] = 'var_credito_estado';
        $where_in[0] = array('DEBE', 'A_CUENTA');
        $nombre_in[1] = 'venta_status';
        $where_in[1] = array('ENTREGADO', 'DEVUELTO PARCIALMENTE', 'COMPLETADO');
        ///////////////////////
        $select = 'venta.venta_id, venta.id_cliente,venta.id_vendedor, razon_social,fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
            nombre_condiciones,usuario.nombre,zonas.zona_nombre';
        $from = "venta";
        $join = array('credito', 'cliente', 'documento_venta', 'condiciones_pago', 'usuario', 'zonas');
        $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
            'documento_venta.id_venta=venta.venta_id', 'condiciones_pago.id_condiciones=venta.condicion_pago',
            'usuario.nUsuCodigo=venta.id_vendedor', 'zonas.zona_id=cliente.id_zona');

        $tipo_join = false;

        $listaventa = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, false, "RESULT_ARRAY");


        // configuramos las propiedades del documento
        $this->phpexcel->getProperties()
            //->setCreator("Arkos Noem Arenom")
            //->setLastModifiedBy("Arkos Noem Arenom")
            ->setTitle("Reporte de Invetario")
            ->setSubject("Reporte de Invetario")
            ->setDescription("Reporte de Invetario")
            ->setKeywords("Reporte de Invetario")
            ->setCategory("Reporte de Invetario");


        $columna[0] = "Nro Venta";
        $columna[1] = "Cliente";
        $columna[2] = "Fecha de venta.";
        $columna[3] = "Monto Cred ";
        $columna[4] = "MMonto Canc";
        $columna[5] = "Documento";
        $columna[6] = "Trabajador";
        $columna[7] = "Zona";
        $columna[8] = "Dias de atraso";
        $columna[9] = "Estado";


        $col = 0;
        for ($i = 0; $i < count($columna); $i++) {

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 1, $columna[$i]);

        }

        $row = 2;
        if (count($listaventa) > 0) {

            foreach ($listaventa as $fila) {
                $col = 0;

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $fila['documento_Numero']);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $fila['razon_social']);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, date("d-m-Y", strtotime($fila['fecha'])));

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $fila['total']);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $fila['dec_credito_montodebito']);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $fila['nombre_tipo_documento']);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $fila['nombre']);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $fila['zona_nombre']);


                $days = (strtotime(date('d-m-Y')) - strtotime($fila['fecha'])) / (60 * 60 * 24);;

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, floor($days));

                if ($fila['var_credito_estado'] == CREDITO_ACUENTA) {
                    $this->phpexcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($col++, $row, "A CUENTA");
                } elseif ($fila['var_credito_estado'] == CREDITO_CANCELADO) {
                    $this->phpexcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($col++, $row, "CANCELÓ");
                } elseif ($fila['var_credito_estado'] == CREDITO_DEBE) {
                    $this->phpexcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($col++, $row, "DB");
                } else {
                    $this->phpexcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($col++, $row, "NOTA DE CRÉDITO");
                }


                $row++;
            }
        }

// Renombramos la hoja de trabajo
        $this->phpexcel->getActiveSheet()->setTitle('Deudas elevadas');


// configuramos el documento para que la hoja
// de trabajo nÃºmero 0 sera la primera en mostrarse
// al abrir el documento
        $this->phpexcel->setActiveSheetIndex(0);


// redireccionamos la salida al navegador del cliente (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="DeudasElevadas.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');

    }


    function toExcel_pagoPendiente()
    {

        $id_cliente = null;
        $fechaDesde = null;
        $fechaHasta = null;

        $nombre_or = false;
        $where_or = false;

        $where = "((`venta_status` IN ('" . PEDIDO_ENTREGADO . "', '" . PEDIDO_DEVUELTO . "') and venta.venta_tipo='ENTREGA' ) OR (`venta_status` ='" . COMPLETADO . "' and venta.venta_tipo='CAJA')) ";

        if ($this->input->post('cboCliente1', true) != -1) {

            $where = $where . " AND venta.id_cliente =" . $this->input->post('cboCliente1');
        }
        if ($_POST['fecIni1'] != "") {

            $where = $where . " AND date(fecha) >= '" . date('Y-m-d', strtotime($this->input->post('fecIni1'))) . "'";
        }
        if ($_POST['fecFin1'] != "") {

            $where = $where . " AND  date(fecha) <= '" . date('Y-m-d', strtotime($this->input->post('fecFin1'))) . "'";
        }
        // echo $where;

        $where_in[0] = array(CREDITO_DEBE, CREDITO_ACUENTA);
        $nombre_in[0] = 'var_credito_estado';

        /*$nombre_in[1] = 'venta_status';
        $where_in[1] = array(PEDIDO_ENTREGADO, PEDIDO_DEVUELTO, COMPLETADO, PEDIDO_GENERADO, PEDIDO_ENVIADO);*/

        $select = 'venta.venta_id, venta_tipo, venta.id_cliente, razon_social,fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
            nombre_condiciones, condiciones_pago.dias,venta.id_cliente as clientV, venta.pagado,
             (select SUM(historial_monto) from historial_pagos_clientes where (historial_pagos_clientes.venta_id = venta.venta_id or historial_pagos_clientes.id_credito=credito.credito_id) and historial_estatus="PENDIENTE" ) as confirmar';
        $from = "credito";
        $join = array('venta', 'cliente', 'documento_venta', 'condiciones_pago');
        $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
            'documento_venta.id_venta=venta.venta_id', 'condiciones_pago.id_condiciones=venta.condicion_pago');
        $tipo_join = array('left', null, null, null);

        $result['lstVenta'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, false, "RESULT_ARRAY");

        // Aqui llamo a la vista html y le paso la data
        $this->load->view('menu/reportes/excelPagoPendientes', $result);
    }

    function pagospendientepdf()
    {

        $pdf = new Pdf('L', 'mm', 'LETTER', true, 'UTF-8', false, false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetPrintHeader(true);
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->AddPage('L');


        $id_cliente = null;
        $fechaDesde = null;
        $fechaHasta = null;

        $nombre_or = false;
        $where_or = false;

        $where = "((`venta_status` IN ('" . PEDIDO_ENTREGADO . "', '" . PEDIDO_DEVUELTO . "') and venta.venta_tipo='ENTREGA' ) OR (`venta_status` ='" . COMPLETADO . "' and venta.venta_tipo='CAJA')) ";

        if ($this->input->post('cboCliente2', true) != -1) {

            $where = $where . " AND venta.id_cliente =" . $this->input->post('cboCliente2');
        }
        if ($_POST['fecIni2'] != "") {

            $where = $where . " AND date(fecha) >= '" . date('Y-m-d', strtotime($this->input->post('fecIni2'))) . "'";
        }
        if ($_POST['fecFin2'] != "") {

            $where = $where . " AND  date(fecha) <= '" . date('Y-m-d', strtotime($this->input->post('fecFin2'))) . "'";
        }
        // echo $where;

        $where_in[0] = array(CREDITO_DEBE, CREDITO_ACUENTA);
        $nombre_in[0] = 'var_credito_estado';

        /*$nombre_in[1] = 'venta_status';
        $where_in[1] = array(PEDIDO_ENTREGADO, PEDIDO_DEVUELTO, COMPLETADO, PEDIDO_GENERADO, PEDIDO_ENVIADO);*/

        $select = 'venta.venta_id, venta_tipo, venta.id_cliente, razon_social,fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
            nombre_condiciones, condiciones_pago.dias,venta.id_cliente as clientV, venta.pagado,
             (select SUM(historial_monto) from historial_pagos_clientes where historial_pagos_clientes.credito_id = venta.venta_id and historial_estatus="PENDIENTE" ) as confirmar';
        $from = "venta";
        $join = array('credito', 'cliente', 'documento_venta', 'condiciones_pago');
        $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
            'documento_venta.id_venta=venta.venta_id', 'condiciones_pago.id_condiciones=venta.condicion_pago');
        $tipo_join = array('left', null, null, null);

        $result['lstVenta'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, false, "RESULT_ARRAY");


        // Aqui llamo a la vista html y le paso la data
        $html = $this->load->view('menu/reportes/pdfPagoPendiente', $result, true);

        // creo el pdf con la vista
        $pdf->WriteHTML($html);
        $nombre_archivo = utf8_decode("PagosPendiente.pdf");
        $pdf->Output($nombre_archivo, 'I');

    }

    function imprimir_pago_pendiente()
    {

        if ($this->input->is_ajax_request()) {

            $id_historial = json_decode($this->input->post('id_historial', true));
            $id_venta = json_decode($this->input->post('id_venta', true));
            $select = '*';
            $from = "historial_pagos_clientes";
            $join = array('credito', 'recibo_pago_cliente');
            $campos_join = array('credito.id_venta=historial_pagos_clientes.venta_id or credito.credito_id=historial_pagos_clientes.id_dredito',
                'recibo_pago_cliente.recibo_id=historial_pagos_clientes.recibo_id');
            $where = array('historial_id' => $id_historial);
            $result['credito'] = $this->historial_pagos_clientes_model->traer_by($select, $from, $join, $campos_join, array('', 'left'), $where, false, false, false, false, false, false, "RESULT_ARRAY");

            $select = '*';
            $from = "venta";
            $join = array('cliente', 'documento_venta');
            $campos_join = array('cliente.id_cliente=venta.id_cliente', 'venta.venta_id=documento_venta.id_venta');
            $where = array(
                'venta_id' => $id_venta
            );

            $result['cliente'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, false, $where, false, false, false, false, false, false, "ROW_ARRAY");

            $result['metodo_pago'] = $this->metodos_pago_model->get_by('id_metodo', $result['credito'][0]['metodo']);

            $result['cuota'] = $result['credito'][0]['historial_monto'];
            $result['id_historial'] = true;
            $where = array(
                'credito_id' => $result['credito'][0]['id_venta'],
                'historial_id' => $id_historial
            );
            $select = 'monto_restante';
            $from = "historial_pagos_clientes";
            $order = "historial_id desc";
            $buscar_restante = $this->venta_model->traer_by($select, $from, false, false, false, $where, false, false, false, false, false, $order, "RESULT_ARRAY");


            $result['restante'] = $buscar_restante[0]['monto_restante'];
            //var_dump($result);
            $this->load->view('menu/ventas/visualizarCuentaPendiente', $result);
        }


    }


    function editar_historialcobranza()
    {

        $historial = $this->input->post('historial_aeditar');
        $venta_id = $this->input->post('venta_aeditar');
        $montonuevo = $this->input->post('montonuevo');
        $vendedor = $this->input->post('vendedor');

        $guardar_historial_pago = $this->historial_pagos_clientes_model->actualizar_historial_editado($historial, $venta_id, $montonuevo);

        if ($guardar_historial_pago != false) {

            $dataresult['exito'] = true;
        } else {
            $dataresult['error'] = true;
        }
        echo json_encode($dataresult);

    }

    public
    function vercredito()
    {
        $idventa = $this->input->post('idventa');
        if ($idventa != FALSE) {
            $result['metodos'] = $this->metodos_pago_model->get_all();
            $result['credito'] = $this->venta_model->get_credito_by_venta($idventa);
            $result['id_venta'] = $idventa;
            $this->load->view('menu/ventas/tbl_venta_credito_pago', $result);
        }
    }

    public
    function pagosadelantados()
    {
        $data = "";
        //  $where = array('venta_tipo' => "ENTREGA", 'pagado > ' => 0, 'consolidado_carga.status'=>'CERRADO');
        $where = "venta_tipo ='ENTREGA' and venta.pagado>0 and (consolidado_carga.status IN ('CERRADO','ABIERTO','IMPRESO') or consolidado_carga.status IS NULL)";
        $data['pagos'] = $this->venta_model->pagos_adelantados($where);

        $data['ltsVendedores'] = $this->venta_model->get_ventas_user();
        $data['ltsClientes'] = $this->cliente_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/pagosadelantados', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function verPagoAdelantado($id)
    {
        $data = "";
        $where = array('venta_id' => $id);
        $data['pago'] = $this->venta_model->pagos_adelantados($where);

        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/verPagoAdelantado', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function pagoCaja($id)
    {
        $data = "";
        $where = array('venta_id' => $id);
        $data['pago'] = $this->venta_model->pagos_adelantados($where);
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/confirmarPagoCaja', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function pagoBanco($id)
    {
        $data = "";
        $where = array('venta_id' => $id);
        $data['pago'] = $this->venta_model->pagos_adelantados($where);
        $data['banco'] = $this->banco_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/confirmarPagoBanco', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function pagoCajaCobrado()
    {
        $data = "";
        $where = array('venta_tipo' => 'ENTREGA', 'pagado > ' => 0);
        $monto = $this->input->post('monto');
        $id = $this->input->post('id');
        $user = $this->session->userdata('nUsuCodigo');
        $caja = $this->session->userdata('caja');

        if ($caja == '') {
            $json['error'] = 'El usuario no tine caja asignada';
        } else {
            $fecha = date('Y-m-d H:i:s');
            $datos = array(
                'confirmacion_caja' => $caja,
                'confirmacion_fecha' => $fecha,
                'confirmacion_usuario' => $user,
            );

            $result = $data['pago'] = $this->venta_model->pagos_caja_cobrado($datos, $id, $monto);


            if ($result != FALSE) {

                $json['success'] = 'success';


            } else {

                $json['error'] = 'Ha ocurrido un error al confirmar el pago';
            }
        }
        echo json_encode($json);


    }

    function pagoBancoCobrado()
    {
        $data = "";
        $id = $this->input->post('id');
        $monto = $this->input->post('monto');
        $banco = $this->input->post('banco');
        $user = $this->session->userdata('nUsuCodigo');
        $fecha = date('Y-m-d H:i:s');
        $datos = array(
            'confirmacion_banco' => $banco,
            'confirmacion_fecha' => $fecha,
            'confirmacion_usuario' => $user,
        );
        $where = array('venta_tipo' => 'ENTREGA', 'pagado > ' => 0);


        $result = $this->venta_model->pagos_banco_cobrado($datos, $id, $monto);
        if ($result != FALSE) {

            $json['success'] = 'success';


        } else {

            $json['error'] = 'Ha ocurrido un error al confirmar';
        }
        echo json_encode($json);

    }


    public
    function lst_liquidaciones_confirmadas()
    {
        if ($this->input->is_ajax_request()) {

            $where = array(

                'historial_estatus' => "CONFIRMADO"
            );

            if ($this->input->post('cajero', true) != -1) {
                $where['liquidacion_cobranza.liquidacion_cajero'] = $this->input->post('cajero');
            }

            $where['historial_fecha >='] = date('Y-m-d H:i:s', strtotime($this->input->post('fecha_ini') . " 00:00:00"));
            $where['historial_fecha <='] = date('Y-m-d H:i:s', strtotime($this->input->post('fecha_fin') . " 23:59:59"));

            ////////////////////////
            $nombre_or = false;
            $where_or = false;
            ///////////////////////
            $nombre_in = false;
            $where_in = false;
            ///////////////////////
            $select = 'usuario.nUsuCodigo, usuario.nombre, historial_pagos_clientes.*, liquidacion_fecha,
                liquidacion_cobranza.liquidacion_id,cajero.nombre as cajero';
            $from = "historial_pagos_clientes";
            $join = array('liquidacion_cobranza_detalle', 'liquidacion_cobranza', 'usuario', 'usuario as cajero');
            $campos_join = array(
                'liquidacion_cobranza_detalle.pago_id=historial_pagos_clientes.historial_id',
                'liquidacion_cobranza.liquidacion_id=liquidacion_cobranza_detalle.liquidacion_id',
                'usuario.nUsuCodigo=historial_pagos_clientes.historial_usuario', 'cajero.nUsuCodigo=liquidacion_cobranza.liquidacion_cajero');
            $tipo_join = array('', '');

            $group = "liquidacion_cobranza.liquidacion_id";
            $result['lstVenta'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, false, "RESULT_ARRAY");

            for ($i = 0; $i < count($result['lstVenta']); $i++) {
                $suma = $this->db->select_sum('historial_monto', 'suma')
                    ->from('historial_pagos_clientes')
                    ->join('venta', 'historial_pagos_clientes.venta_id=venta.venta_id', 'left')
                    ->join('liquidacion_cobranza_detalle', 'liquidacion_cobranza_detalle.pago_id=historial_pagos_clientes.historial_id')
                    ->where('liquidacion_cobranza_detalle.liquidacion_id', $result['lstVenta'][$i]['liquidacion_id'])
                    ->where('historial_estatus', 'CONFIRMADO')
                    ->get()->row();

                $result['lstVenta'][$i]['suma'] = $suma->suma;
            }

            /// var_dump($result);
            $this->load->view('menu/ventas/tbl_historial_liquidacion', $result);


            //echo json_encode($this->v->select_venta_estadocuenta(date("y-m-d", strtotime($this->input->post('fecIni',true))),date("y-m-d", strtotime($this->input->post('fecFin',true)))));
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }

    function imprimir_historial_liquidacion()
    {

        $historial = $this->input->post('id_historial');
        $venta_id = $this->input->post('id_venta');

        $liquidacion = $this->input->post('id_liquidacion');
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $where = array(

            'liquidacion_cobranza_detalle.liquidacion_id' => $liquidacion,
            'historial_estatus' => "CONFIRMADO"
        );

        $select = 'SUM(historial_monto) AS suma, historial_caja_id, historial_banco_id,historial_estatus,
         cliente.razon_social, direccion,telefono1,
          documento_Numero, usuario.nombre,liquidacion_fecha, ,cajero.nombre as cajero,
          metodos_pago.*';
        $from = "historial_pagos_clientes";
        $join = array('venta', 'cliente', 'documento_venta', 'metodos_pago', 'liquidacion_cobranza_detalle', 'usuario',
            'liquidacion_cobranza', 'usuario as cajero');
        $campos_join = array('historial_pagos_clientes.venta_id=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
            'documento_venta.id_venta=venta.venta_id', 'metodos_pago.id_metodo=historial_pagos_clientes.historial_tipopago',
            'liquidacion_cobranza_detalle.pago_id=historial_pagos_clientes.historial_id',
            'usuario.nUsuCodigo=historial_pagos_clientes.historial_usuario',
            'liquidacion_cobranza.liquidacion_id=liquidacion_cobranza_detalle.liquidacion_id', 'cajero.nUsuCodigo=liquidacion_cobranza.liquidacion_cajero');

        $group_by = "nombre_metodo";
        $order = "nombre_metodo";
        $result['resultado'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, false,
            $where, $nombre_in, $where_in, $nombre_or, $where_or, $group_by, $order, "RESULT_ARRAY");

        $result['historial'] = true;

        $this->load->view('menu/ventas/visualizarHistorialLiquidacion', $result);

    }

    function filtroPagosAdl()
    {
        $condicion = "venta.venta_tipo` ='ENTREGA' and `venta`.`pagado`>0  and (consolidado_carga.status IN ('CERRADO','ABIERTO','IMPRESO') or consolidado_carga.status IS NULL)";
        if ($this->input->post('pedido') != "") {
            $pedido = $this->input->post('pedido');
            $condicion .= " and `venta_id`='$pedido' ";
            $data['pedido'] = $this->input->post('pedido');
        }
        if ($this->input->post('vendedor') != 0) {
            $vendedor = $this->input->post('vendedor');
            $condicion .= " and `id_vendedor`='$vendedor' ";
            $data['vendedor'] = $this->input->post('vendedor');
        }
        if ($this->input->post('cliente') != 0) {
            $cliente = $this->input->post('cliente');
            $condicion .= " and `venta`.`id_cliente`='$cliente' ";

        }
        if ($this->input->post('fecha') != "") {
            $fecha = $this->input->post('fecha');
            $fecha = date('Y-m-d', strtotime($fecha));
            $condicion .= " and `date(fecha)` >=" . $fecha . " AND `date(fecha)` <= " . $fecha;

        }

        if ($this->input->post('estado') != "") {

            if ($this->input->post('estado') == "CONFIRMADO") {
                $condicion .= " and venta.confirmacion_usuario is not null ";
            } else {
                $condicion .= " and venta.confirmacion_usuario is null";
            }
        }

        //var_dump($condicion);

        $data['pagosAdl'] = $this->venta_model->pagos_adelantados($condicion);

        $this->load->view('menu/ventas/lista_pagos_adelantados', $data);

    }

    public
    function lst_liquidaciones()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('vendedor', true) != -1) {
                $where['historial_pagos_clientes.historial_usuario'] = $this->input->post('vendedor');
            }

            $where['historial_estatus'] = "PENDIENTE";

            $where['historial_fecha >='] = date('Y-m-d H:i:s', strtotime($this->input->post('fecha_ini') . " 00:00:00"));
            $where['historial_fecha <='] = date('Y-m-d H:i:s', strtotime($this->input->post('fecha_fin') . " 23:59:59"));

            ////////////////////////
            $nombre_or = false;
            $where_or = false;
            ///////////////////////
            $nombre_in = false;
            $where_in = false;
            ///////////////////////
            $select = 'usuario.nUsuCodigo, usuario.nombre, credito.dec_credito_montodeuda, historial_pagos_clientes.*,
                 metodos_pago.*,  documento_Numero, venta.venta_id';
            $from = "historial_pagos_clientes";
            $join = array('usuario', 'metodos_pago', 'venta', 'documento_venta', 'credito');
            $campos_join = array('usuario.nUsuCodigo=historial_pagos_clientes.historial_usuario',
                'metodos_pago.id_metodo=historial_pagos_clientes.historial_tipopago',
                'venta.venta_id=historial_pagos_clientes.venta_id',
                'venta.venta_id=documento_venta.id_venta',
                'credito.id_venta=historial_pagos_clientes.venta_id');
            $tipo_join = false;

            $result['lstVenta'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, false, false, "RESULT_ARRAY");

            $this->load->view('menu/ventas/tbl_liquidacion_cobranza', $result);


            //echo json_encode($this->v->select_venta_estadocuenta(date("y-m-d", strtotime($this->input->post('fecIni',true))),date("y-m-d", strtotime($this->input->post('fecFin',true)))));
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }

    function guardar_liquidar()
    {

        $id = $this->input->post('historial');
        $vendedor = $this->input->post('vendedor');

        $liquidacion = array(
            'liquidacion_cajero' => $this->session->userdata('nUsuCodigo'),
            'liquidacion_fecha' => date('Y-m-d H:i:s'),
            'liquidacion_vendedor' => $vendedor,
        );
        $id_liquidacion = $this->liquidacion_cobranza_model->guardar_liquidacion($liquidacion);
        $data['resultado'] = $this->historial_pagos_clientes_model->update_historial($id, $id_liquidacion);


        ///////////////////////////////////////////////
        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $where = array(
            'historial_usuario' => $vendedor,
            'liquidacion_id' => $id_liquidacion,
            'historial_estatus' => "CONFIRMADO"
        );

        $select = 'SUM(historial_monto) AS suma, historial_caja_id, historial_banco_id, cliente.razon_social, direccion,telefono1,
        documento_Numero,
          metodos_pago.*';
        $from = "historial_pagos_clientes";
        $join = array('venta', 'cliente', 'documento_venta', 'metodos_pago', 'liquidacion_cobranza_detalle');
        $campos_join = array('historial_pagos_clientes.venta_id=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
            'documento_venta.id_venta=venta.venta_id', 'metodos_pago.id_metodo=historial_pagos_clientes.historial_tipopago',
            'liquidacion_cobranza_detalle.pago_id=historial_pagos_clientes.historial_id');


        $group_by = "nombre_metodo";
        $order = "nombre_metodo";
        $result['resultado'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, false,
            $where, $nombre_in, $where_in, $nombre_or, $where_or, $group_by, $order, "RESULT_ARRAY");

        // var_dump($result);
        ///////////////////////////
        $select = 'nombre';
        $from = "usuario";
        $where = array(
            'nUsuCodigo' => $this->session->userdata('nUsuCodigo')
        );
        $result['cajero'] = $this->usuario_model->traer_by($select, $from, false, false, false,
            $where, false, false, false, false, false, false, "ROW_ARRAY");


        $select = 'nombre';
        $from = "usuario";
        $where = array(
            'nUsuCodigo' => $vendedor
        );
        $result['vendedor'] = $this->usuario_model->traer_by($select, $from, false, false, false,
            $where, false, false, false, false, false, false, "ROW_ARRAY");
        $this->load->view('menu/ventas/visualizarLiquidacion', $result);


    }


    public
    function historial_liquidacion()
    {
        $data = "";
        $data['vendedores'] = $this->usuario_model->select_all_user();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/historial_liquidacion', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    public
    function liquidacion()
    {
        $data = "";
        $data['vendedores'] = $this->usuario_model->select_all_user();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/liquidacion', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


}



