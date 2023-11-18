<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class cuentasPorPagar extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->very_sesion();

        $this->load->model('proveedor/proveedor_model');
        $this->load->model('ingreso/ingreso_model');
        $this->load->model('tipo_proveedor/tipo_proveedor_model');
        $this->load->model('regimen/regimen_model');
        $this->load->model('pais/pais_model');
        $this->load->model('estado/estado_model');
        $this->load->model('ciudad/ciudad_model');
        $this->load->model('metodosdepago/metodos_pago_model');
        $this->load->model('cuentas_por_pagar/recibo_pago_proveedor_model');
        $this->load->model('pagos_ingreso/pagos_ingreso_model');
        $this->load->model('banco/banco_model');
        //$this->load->library('Pdf');
        //$this->load->library('phpExcel/PHPExcel.php');
    }


    public function cuentas_por_pagar()
    {


        $data = array();
        $data["lstproveedor"] = $this->proveedor_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/proveedor/cuentasporpagar', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }

    }

    public function generar_comprobante()
    {
        $data = array();
        $data["lstproveedor"] = $this->proveedor_model->get_all();
        $data["metodos"] = $this->metodos_pago_model->get_all();
        $data['bancos'] = $this->banco_model->get_all();
        $recibo = $this->recibo_pago_proveedor_model->get_next_id();


        if ($recibo!=NULL && isset($recibo->recibo_id))
            $data['recibo'] = $recibo->recibo_id + 1;
        else
            $data['recibo'] = 1;

        $dataCuerpo['cuerpo'] = $this->load->view('menu/proveedor/generar_comprobante', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }

    }


    function lst_cuentas_porpagar_json()
    {
        if ($this->input->is_ajax_request()) {
            $get = $this->input->get();
			$data = $this->input->get('data');
            $id_cliente = null;
            $fechaDesde = null;
            $fechaHasta = null;
            $where = "dias > 0";
            $where = $where . " AND ingreso_status = '" . COMPLETADO . "'";
            $nombre_or = false;
            $where_or = false;
            // Pagination Result
            $array = array();
            $array['productosjson'] = array();

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

            if (isset($data['proveedor'] ) && $data['proveedor'] != -1) {
                $where = $where . " AND int_Proveedor_id= '" . $data['proveedor']  . "'";
            }

            if (isset($data['fecIni']) && $data['fecIni'] != "") {
                $where = $where . " AND date(fecha_registro) >= '" . date('Y-m-d', strtotime($data['fecIni'])) . "'";
            }
            if (isset($data['fecFin']) && $data['fecFin'] != "") {
                $where = $where . " AND  date(fecha_registro) <= '" . date('Y-m-d', strtotime($data['fecFin'])) . "'";
            }
            $select = 'SQL_CALC_FOUND_ROWS DATE(ingreso.id_ingreso) as total_filas,ingreso.*, pagos_ingreso.*, proveedor.*, 
            sum(pagoingreso_monto) as suma';
            $from = "ingreso";
            $join = array('proveedor', 'pagos_ingreso', 'condiciones_pago');
            $campos_join = array('proveedor.id_proveedor=ingreso.int_Proveedor_id', 'pagos_ingreso.pagoingreso_ingreso_id=ingreso.id_ingreso', 'condiciones_pago.id_condiciones=ingreso.condicion_pago');
            $tipo_join[0] = "left";
            $tipo_join[1] = "left";
            $tipo_join[2] = "left";
            $group = "id_ingreso";
            $where_custom = false;
            $ordenar = $this->input->get('order');
            $order = false;
            $order_dir = 'desc';
            if (!empty($ordenar)) {
                $order_dir = $ordenar[0]['dir'];
                if ($ordenar[0]['column'] == 0) {
                    $order = 'ingreso.id_ingreso';
                }
                if ($ordenar[0]['column'] == 1) {
                    $order = 'tipo_documento';
                }
                if ($ordenar[0]['column'] == 2) {
                    $order = 'documento_numero';
                }
                if ($ordenar[0]['column'] == 3) {
                    $order = 'proveedor_nombre';
                }
                if ($ordenar[0]['column'] == 4) {
                    $order = 'fecha_registro';
                }
                if ($ordenar[0]['column'] == 5) {
                    $order = 'total_ingreso';
                }
                if ($ordenar[0]['column'] == 6) {
                    $order = 'total_ingreso';
                }
                if ($ordenar[0]['column'] == 7) {
                    $order = 'total_ingreso';
                }
            }
            $nombre_in = false;
            $where_in = false;
            $total = $this->ingreso_model->traer_by_mejorado('COUNT(ingreso.id_ingreso) as total', $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", false, false, $order_dir, false, $where_custom);
            $cuentas = $this->ingreso_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);
			$total_resultados=isset($cuentas[0])?$cuentas[0]['total_afectados']:0;
            if (count($cuentas) > 0) {
                foreach ($cuentas as $v) {
                    $PRODUCTOjson = array();
                    $PRODUCTOjson[] = $v['id_ingreso'];
                    $PRODUCTOjson[] = $v['documento_numero'];
                    $PRODUCTOjson[] = $v['proveedor_nombre'];
                    $PRODUCTOjson[] = date("d-m-Y", strtotime($v['fecha_registro']));
                    $PRODUCTOjson[] = number_format($v['total_ingreso'], 2,',', '.');

                    if ($v['suma'] != null) {
                        $abono = $v['suma'];
                    } else {
                        $abono = "0.00";
                    }
                    $PRODUCTOjson[] = $abono;

                    if ($v['suma'] != null) {
                        $deuda = number_format($v['total_ingreso'] - $v['suma'], 2,',', '.');
                    } else {
                        $deuda = number_format($v['total_ingreso'], 2,',', '.');
                    }
                    $PRODUCTOjson[] = $deuda;
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
                    $PRODUCTOjson[] = $label;
                    $PRODUCTOjson[] = ($v['suma'] >= $v['total_ingreso']) ? PAGO_CANCELADO : INGRESO_PENDIENTE;
					$botonas = '<div class="btn-group"><a class=\'btn btn-default tip\' title="Ver Venta" onclick="CuentasPorPagar.visualizar(' . $v['id_ingreso'] . ')"><i
								class="fa fa-search"></i> Historial</a>';
					$botonas .= '</div>';
                    $PRODUCTOjson[] = $botonas;

					$PRODUCTOjson['campos_sumar'] = array();
					$PRODUCTOjson['campos_sumar'][] = 5;
					$PRODUCTOjson['campos_sumar'][] = 6;
                    $array['productosjson'][] = $PRODUCTOjson;
                }
                $total = $total[0]['total'];
            }
            $array['data'] = $array['productosjson'];
            $array['draw'] = $draw;//esto debe venir por post
            $array['recordsTotal'] = $total_resultados;
            $array['recordsFiltered'] = $total_resultados; // esto dbe venir por post
            echo json_encode($array);
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }


    function lst_cuentas_porpagar()
    {
        $data = array();
        if ($this->input->post('proveedor', true) != -1) {


            $data['proveedor'] = $this->input->post('proveedor', true);

        }
        if ($this->input->post('fecIni') != "") {


            $data['fecIni'] = $this->input->post('fecIni', true);

        }

        if ($this->input->post('fecFin') != "") {


            $data['fecFin'] = $this->input->post('fecFin', true);
        }


        if ($this->input->is_ajax_request()) {

            $this->load->view('menu/proveedor/tbl_lst_cuentasporpagar', $data);

        } else {
            redirect(base_url() . 'proveedor/', 'refresh');
        }
    }

    public function vertodoingreso()
    {
        $id_ingreso = $this->input->post('id_ingreso');
        if ($id_ingreso != FALSE) {
            $select = 'ingreso.documento_numero, ingreso.fecha_registro,ingreso.id_ingreso,
                 detalleingreso.total_detalle,
                producto.producto_nombre, proveedor.proveedor_nombre';
            $from = "ingreso";
            $join = array('detalleingreso', 'producto', 'proveedor');
            $campos_join = array('detalleingreso.id_ingreso=ingreso.id_ingreso', 'detalleingreso.id_producto=producto.producto_id',
                'proveedor.id_proveedor=ingreso.int_Proveedor_id');
            /* $tipo_join[0]="";
            $tipo_join[1]="left";*/
            $where = array(
                'ingreso.id_ingreso' => $id_ingreso
            );
            $dataresult['detalle'] = $this->ingreso_model->traer_by($select, $from, $join, $campos_join, false, $where, false, false, false, false, false, false, "RESULT_ARRAY");
            $select = 'sum(pagoingreso_monto) as monto_abonado';
            $from = "pagos_ingreso";
            $where = array(
                'pagoingreso_ingreso_id' => $id_ingreso
            );
            $dataresult['abonado'] = $this->ingreso_model->traer_by($select, $from, false, false, false, $where, false, false, false, false, false, false, "ROW_ARRAY");
            $select = 'sum(total_detalle) as total_ingreso ';
            $from = "ingreso";
            $join = array('detalleingreso');
            $campos_join = array('detalleingreso.id_ingreso=ingreso.id_ingreso');
            $where = array(
                'ingreso.id_ingreso' => $id_ingreso
            );
            $dataresult['total_ingreso'] = $this->ingreso_model->traer_by($select, $from, $join, $campos_join, false, $where, false, false, false, false, false, false, "ROW_ARRAY");
            $select = '*';
            $from = "pagos_ingreso";
            $where = array(
                'pagoingreso_ingreso_id' => $id_ingreso
            );
            $dataresult['cuentas'] = $this->ingreso_model->traer_by($select, $from, false, false, false, $where, false, false, false, false, false, false, "RESULT_ARRAY");
            $this->load->view('menu/ingreso/ingresos', $dataresult);
        }
    }

    public function ver_deuda()
    {
        $id_ingreso = $this->input->post('id_ingreso');
        if ($id_ingreso != FALSE) {
            $select = 'ingreso.*, pagos_ingreso.*, sum(pagoingreso_monto) as suma ';
            $from = "ingreso";
            $join = array('pagos_ingreso', 'condiciones_pago');
            $campos_join = array('pagos_ingreso.pagoingreso_ingreso_id=ingreso.id_ingreso', 'condiciones_pago.id_condiciones=ingreso.condicion_pago');
            $tipo_join = array(0 => "left", 1 => 'left');
            $where = array(
                'id_ingreso' => $id_ingreso,
                'condiciones_pago.dias >' => 0
            );
            $result['cuentas'] = $this->ingreso_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where, false, false, false, false, false, false, "RESULT_ARRAY");
            $result['id_ingreso'] = $id_ingreso;
            $result['fecIni'] = $this->input->post('fecIni');
            $result['proveedor'] = $this->input->post('proveedor');
            $result['fecFin'] = $this->input->post('fecFin');
            $this->load->view('menu/proveedor/form_montoapagar', $result);
        }
    }

    function imprimir_pago_pendiente()
    {

        if ($this->input->is_ajax_request()) {

            $id_historial = json_decode($this->input->post('id_historial', true));
            $id_ingreso = json_decode($this->input->post('ingreso_id', true));

            $where = array(
                'id_ingreso' => $id_ingreso
            );
            $select = 'ingreso.*, proveedor.*, pagos_ingreso.*, sum(pagoingreso_monto) as suma,recibo_pago_proveedor.fecha';
            $from = "ingreso";
            $join = array('pagos_ingreso', 'proveedor','recibo_pago_proveedor');
            $campos_join = array('pagos_ingreso.pagoingreso_ingreso_id=ingreso.id_ingreso', 'proveedor.id_proveedor=int_Proveedor_id','recibo_pago_proveedor.recibo_id=pagos_ingreso.recibo_id');

            $group = " id_ingreso";
            $dataresult['cuentas'] = $this->ingreso_model->traer_by($select, $from, $join, $campos_join, false, $where, false, false, false, false, $group, false, "RESULT_ARRAY");

            $dataresult['id_historial'] = true;
            $dataresult['cuota'] = $dataresult['cuentas'][0]['pagoingreso_monto'];

            $where = array(
                'pagoingreso_ingreso_id' => $id_ingreso,
                'pagoingreso_id' => $id_historial
            );
            $select = 'pagoingreso_restante';
            $from = "pagos_ingreso";
            $order = "pagoingreso_id desc";
            $buscar_restante = $this->pagos_ingreso_model->traer_by($select, $from, false, false, $where, false, $order, "RESULT_ARRAY");

            $dataresult['restante'] = $buscar_restante[0]['pagoingreso_restante'];

            $this->load->view('menu/proveedor/visualizarIngresoPendiente', $dataresult);
        }


    }

    function guardarPago()
    {

        if ($this->input->is_ajax_request()) {

            $detalle = json_decode($this->input->post('lst_producto', true));
// var_dump($detalle);
            $save_historial = $this->pagos_ingreso_model->guardar($detalle);

            $json = array();
            if ($save_historial != false) {


                if ($save_historial != false) {
                    $json['success'] = 'success';
                    $json['ingreso_id'] = $detalle[0]->id_ingreso;
                    $json['id_historial'] = $save_historial;

                } else {
                    $json['error'] = 'error';
                }
            }

            echo json_encode($json);

        }
    }


}
