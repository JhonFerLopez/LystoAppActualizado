<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//require_once(APPPATH . 'controllers/facturacionElectronica.php'); //include controller
use Mike42\Escpos\Printer;

class venta extends MY_Controller
{

    //   private $facturcionelectronicaController;

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
        $this->load->library('Pdf');
        $this->load->library('session');
        $this->load->library('phpExcel/PHPExcel.php');
        $this->load->library("NuSoap_lib");

        $this->very_sesion();
    }

    function facturaPdf()
    {

        $idventa = $this->input->get('idventa');

        $ventas = array();
        if ($idventa != FALSE) {
            $ventas = $this->venta_model->obtener_venta($idventa);
        }

        $resolucion_prefijo = isset($ventas[0]['resolucion_prefijo']) ? $ventas[0]['resolucion_prefijo'] . "-" : '';
        $numero_factura = isset($ventas[0]['numero']) ? $ventas[0]['numero'] : '';
        $data = array(
            'invoice_title' => 'Factura de venta',
            'user_company' => isset($ventas[0]['RazonSocialEmpresa']) ? strtoupper($ventas[0]['RazonSocialEmpresa']) : '',
            'user_address' => isset($ventas[0]['DireccionEmpresa']) ? strtoupper($ventas[0]['DireccionEmpresa']) : '',
            'rep_legal' => isset($ventas[0]['REPRESENTANTE_LEGAL']) ? strtoupper($ventas[0]['REPRESENTANTE_LEGAL']) : '',
            'nit' => isset($ventas[0]['NIT']) ? strtoupper($ventas[0]['NIT']) : '',
            'telf_empresa' => isset($ventas[0]['TelefonoEmpresa']) ? $ventas[0]['TelefonoEmpresa'] : '',

            'regimen' => isset($ventas[0]['REGIMEN_CONTRIBUTIVO']) ? strtoupper($ventas[0]['REGIMEN_CONTRIBUTIVO']) : '',

            'date' => date('d-m-Y', strtotime($ventas[0]['fechaemision'])),
            'hora' => date('H:i:d', strtotime($ventas[0]['fechaemision'])),
            'due_date' => date('d-m-Y', strtotime($ventas[0]['fechaemision'])),

            'current_date' => date('d-m-Y'),
            'invoice_numb' => $resolucion_prefijo . $numero_factura,
            'client_cedula' => isset($ventas[0]['documento_cliente']) ? strtoupper($ventas[0]['documento_cliente']) : '',
            'client_name' => $ventas[0]['cliente'] . " " . $ventas[0]['apellidos'],
            'address1' => isset($ventas[0]['direccion_cliente']) ? strtoupper($ventas[0]['direccion_cliente']) : '',
            'ventas' => $ventas,
            'cajero_id' => isset($ventas[0]['cajero_id']) ? $ventas[0]['cajero_id'] : '',
            'vendedor' => $this->session->userdata('VENDEDOR_EN_FACTURA') == "CODIGO" ? $ventas[0]['id_vendedor'] :
                $this->usuario_model->getUserReturnName($ventas[0]['id_vendedor']),
            'mensaje_factura' => $this->session->userdata('MENSAJE_FACTURA'),
        );
        $data['page_title'] = 'FACTURA';

        if (!empty($ventas[0]['celular'])) {
            $data['cel_client '] = $ventas[0]['celular'];
        }
        if (!empty($ventas[0]['telefonoC1'])) {
            $data['fijo_client '] = $ventas[0]['telefonoC1'];
        }

        if (isset($ventas[0]['dias']) && $ventas[0]['dias'] != 0) {
            $data['page_title'] = 'VENTA A CREDITO';
        }

        if (isset($ventas[0]['genera_control_domicilios']) && $ventas[0]['genera_control_domicilios'] == 1) {
            //$printer->text("VENTA A DOMICILIO");
            $data['page_title'] = 'VENTA A DOMICILIO';
        }


        if (
            isset($ventas[0]['documento_cliente']) && $ventas[0]['documento_cliente'] != ''
            && $ventas[0]['documento_cliente'] != null && !empty($ventas[0]['documento_cliente'])
        ) {
            $data['client'] = true;
        } else {
            $data['client'] = false;
        }

        $this->load->view('reportesPdf/factura_tpl', $data);
    }


    function validar_deuda($id_cliente)
    {
        $where = array('venta.id_cliente' => $id_cliente);
        ////////////////////////
        $nombre_or = false;
        $where_or = false;
        ///////////////////////
        $nombre_in[0] = 'var_credito_estado';
        $where_in[0] = array('DEBE', 'A_CUENTA');
        $nombre_in[1] = 'venta_status';
        $where_in[1] = array('ENTREGADO', 'DEVUELTO PARCIALMENTE', 'COMPLETADO');
        ///////////////////////
        $select = 'venta.venta_id, venta.id_cliente, nombres, apellidos,fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
        nombre_condiciones';
        $from = "venta";
        $join = array('credito', 'cliente', 'documento_venta', 'tipo_venta', 'condiciones_pago');
        $campos_join = array(
            'credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
            'documento_venta.id_venta=venta.venta_id', 'tipo_venta.tipo_venta_id=venta.venta_tipo', 'condiciones_pago.id_condiciones=tipo_venta.condicion_pago'
        );
        $tipo_join = false;

        $result['lstVenta'] = $this->venta_model->traer_by(
            $select,
            $from,
            $join,
            $campos_join,
            $tipo_join,
            $where,
            $nombre_in,
            $where_in,
            $nombre_or,
            $where_or,
            false,
            false,
            "RESULT_ARRAY"
        );
        if ($result['lstVenta'] == true) {
            return true;
        } else {
            return false;
        }
    }


    function index()
    {
        $idventa = $this->input->post('idventa');
        $notadebito = $this->input->post('notadebito');
        /*echo '<pre>';
        print_r($this->input->post());
        echo '</pre>';
        echo "----> ".$uuid_factura = $this->input->post('uuid_factura');*/
        $data["condiciones_pago"] = $this->condiciones_pago_model->get_all();
        $data["metodos_pago"] = $this->metodos_pago_model->get_all();
        $data['last_factura'] = $this->venta_model->get_last();
        $data['last_resolucion'] = $this->resolucion_model->get_last();
        $data['notadebito'] = $notadebito == true ? '1' : '0';

        $vendedor = null;

        $data["vendedores"] = $this->usuario_model->get_all_vendedores();
        $data["unidades_medida"] = $this->unidades_model->get_unidades();
        $data["tipos_venta"] = $this->tipo_venta_model->get_all();
        $data["droguerias"] = $this->drogueria_relacionada_model->get_all();
        $data["clientes"] = $this->cliente_model->get_all();
        $data['tipos_devolucion'] = array();
        //$data["productos"] = $this->pd->select_all_producto();
        $data["venta"] = array();
        if ($idventa != FALSE) {
            $data["venta"] = $this->venta_model->obtener_venta($idventa);
            $data["formaspago"] = $this->venta_model->get_formas_pago_by(array('id_venta' => $idventa));
            if ($this->input->post('devolver') == 1) {
                $data['devolver'] = 1;
                $data['tipos_devolucion'] = $this->tipo_devolucion_model->get_all($data["venta"][0]['uuid']);
                $deuda = $data["venta"][0]['dec_credito_montodeuda'] - $data["venta"][0]['dec_credito_montodebito'];
                $data['deuda'] = is_numeric($deuda) ? floatval($deuda) : false;
            }
        }
        $data['columnasToProd'] = VentaColumnasProductosElo::all();        
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/generarVenta', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    function pedidos()
    {
        $idventa = $this->input->post('idventa');
        $vendedor = null;
        $data = array();
        $useradmin = $this->session->userdata('admin');
        if ($useradmin == 1) {
            $data["clientes"] = $this->cliente_model->get_all();
        } else {
            $vendedor = $this->session->userdata('nUsuCodigo');
            $data["clientes"] = $this->cliente_model->get_all($vendedor);
        }
        $data['coso_id'] = $this->input->post('coso_id');
        $data["venta_id"] = $idventa;


        $data["venta"] = array();

        $data['estatus_consolidado'] = $this->input->post('estatus_consolidado');
        if ($idventa !== FALSE) {
            $data["venta"] = $this->venta_model->obtener_venta($idventa);
            if ($this->input->post('devolver') == 1) {
                $data['devolver'] = 1;
            }
            if ($this->input->post('preciosugerido') == 1) {
                $data['preciosugerido'] = 1;
            }
        }

        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/PedidosVentas', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function venta_backup()
    {
        $idventa = $this->input->post('idventa');

        $data = array();

        if ($idventa !== FALSE) {
            $data['venta'] = $this->venta_model->obtener_venta_backup($idventa);
        }

        echo json_encode($data);
    }

    function registrar_venta()
    {
        //log_message('error', 'Haciendo venta');
        $devolver = $this->input->post('devolver');
        $notadebito = $this->input->post('notadebito');

        $dataresult = array();
        $cajero = $this->session->userdata('cajero_id'); //TODO ESTO NOHACE FALTA PORQUE YA ESTA EN LA TABAL STTAUSCAJA, algun dia borrar
        $caja = $this->session->userdata('cajapertura'); // se amacena el id de statuscaja  no la caja

        //echo $caja;
        $statuscaja = $this->StatusCajaModel->getBy(array('id' => $caja));
        if (!empty($statuscaja['cierre'])) {
            $dataresult['result'] = "La caja en la que está intentando realizar la operación ya ha sido cerrada por otro usuario. Por favor cierre sessión e ingrese nuevamente para trabajar en otra caja";
            echo json_encode($dataresult);
        } else {
            if (($caja == '' or $cajero == '')) {
                $dataresult['result'] = "Debe aperturar una caja para poder continuar";
                echo json_encode($dataresult);
            } else {
                if ($this->input->is_ajax_request()) {
                    $config = array(
                        array(
                            'field' => 'condicion_pago_id',
                            'label' => 'condicion_pago_id',
                            'rules' => 'required',
                            'errors' => array(
                                'required' => 'You must provide a %s.',
                            ),
                        ),
                        array(
                            'field' => 'basegravada',
                            'label' => 'basegravada',
                            'rules' => 'required'
                        ),
                        array(
                            'field' => 'iva',
                            'label' => 'iva',
                            'rules' => 'required'
                        ), array(
                            'field' => 'totApagar',
                            'label' => 'totApagar',
                            'rules' => 'required'
                        ),
                    );

                    $this->form_validation->set_rules($config);

                    $cliente_id = $this->input->post('id_cliente', true);
                    $descuvalor = $this->input->post('descuentoenvalor', true);
                    $descuvalorhiden = $this->input->post('descuentoenvalorhidden', true);
                    $descuporc = $this->input->post('descuentoenporcentaje', true);
                    $descuporchiden = $this->input->post('descuentoenporcentajehidden', true);

                    $descuentoporcentajetotal = 0;
                    if (!empty($descuporc)) {
                        $descuentoporcentajetotal = (($this->input->post('subtotal', true) + $this->input->post('iva', true)) * $descuporc) / 100;
                    }

                    if ($this->form_validation->run() == FALSE) :
                        $dataresult['result'] = validation_errors();
                    else :
                        if ($_POST['basegravada'] != "" && $_POST['iva'] != "" && $_POST['totApagar'] != "") {
                            $venta = array(
                                'id_cliente' => (!empty($cliente_id) ? $cliente_id : NULL),
                                'id_vendedor' => $this->input->post('id_vendedor'),
                                'venta_tipo' => $this->input->post('tipoventa'),
                                'forma_pago' => $this->input->post('forma_pago'),
                                'forma_pago_monto_' => $this->input->post('forma_pago_monto_'),
                                'numero_recibo_monto_' => $this->input->post('numero_recibo_monto_'),
                                'total_otros_impuestos' => $this->input->post('otros_impuestos', true),
                                'condicion_pago' => $this->input->post('condicion_pago'),
                                'venta_status' => $this->input->post('venta_status'),
                                'local_id' => $this->session->userdata('id_local'),
                                'cajero_id' => $this->session->userdata('cajero_id'),
                                'caja_id' => $this->session->userdata('cajapertura'), //ESTE ES EL ID DE LA TABLA STATUS CAJA, NO DE LA CAJA COMO TAL
                                'gravado' => $this->input->post('basegravada', true),
                                'excluido' => $this->input->post('excluido', true),
                                'subtotal' => $this->input->post('subtotal', true),
                                'total_impuesto' => $this->input->post('iva', true),
                                'total' => $this->input->post('totApagar', true),
                                'porcentaje_desc' => (empty($descuporc) or $descuporc == 0) ? 0 : $descuporc,
                                'descuento_valor' => (empty($descuvalor) or $descuvalor == 0) ? $descuvalorhiden : $descuvalor,
                                'descuento_porcentaje' => (empty($descuentoporcentajetotal) or $descuentoporcentajetotal == 0) ? $descuporchiden : $descuentoporcentajetotal,
                                'desc_global' => ((empty($descuentoporcentajetotal) or $descuentoporcentajetotal == 0) && (empty($descuvalor) or $descuvalor == 0)) ? 0 : 1,

                                'importe' => $this->input->post('dineroentregado', true),
                                'cambio' => $this->input->post('cambio', true),
                                'diascondicionpagoinput' => $this->input->post('diascondicionpagoinput', true),
                                'tipo_documento' => $this->input->post('documento_generar', true),
                                'nota' => $this->input->post('nota'),
                                'fe_XmlFileName' => $this->input->post('XmlFileName'),
                                'fe_zipkey' => $this->input->post('zipkey'),
                                'fe_reponseDian' => $this->input->post('fe_reponseDian'),
                                'fe_numero' => $this->input->post('fe_numero'),
                                'fe_prefijo' => $this->input->post('fe_prefijo'),
                                'fe_type_document' => $this->input->post('fe_type_document'),
                                'fe_status' => $this->input->post('fe_status'),
                                'uuid' => $this->input->post('uuid'),
                                'fe_resolution_id' => $this->input->post('fe_resolution_id'),
                                'fe_issue_date' => date('Y-m-d', strtotime($this->input->post('fe_issue_date')))

                            );
                            $fe_resolution_id = $this->input->post('fe_resolution_id');
                            $detalle = json_decode($this->input->post('lst_producto'));


                            // var_dump($detalle);
                            $venta['cliente'] = json_decode($this->input->post('cliente'));
                            $id = $this->input->post('idventa');


                            if (empty($id)) {

                                $venta['fecha'] = date("Y-m-d H:i:s");

                                //si la venta esta en espera, o si es una factura electronica, no generamos resolucion
                                if ($venta['venta_status'] != ESPERA && empty($fe_resolution_id)) {
                                    $resolucion = $this->venta_model->generarnumeroFactura($venta['tipo_documento']);
                                } else {
                                    $resolucion = NULL;
                                }
                                if ($resolucion === false) {
                                    $resultado = NUMERACION_ERROR;
                                } else {
                                    $venta['venta_id'] = $id;

                                    if ($venta['venta_status'] != ESPERA) {
                                        $venta['id_resolucion'] = $resolucion['id_resolucion'];
                                        $venta['numero'] = $resolucion['numero'];
                                    } else {
                                        $venta['id_resolucion'] = $resolucion;
                                        $venta['numero'] = NULL;
                                    }


                                    $resultado = $this->venta_model->insertar_venta($venta, $detalle);

                                    $id = $resultado;

                                    if (is_numeric($resultado)) {


                                        //esto es para guardar en la tabla domicilios
                                        $where = array(
                                            'tipo_venta_id' => $this->input->post('tipoventa')
                                        );
                                        $tipoVenta = $this->tipo_venta_model->get_by($where);
                                        if ($tipoVenta['genera_control_domicilios'] == 1) {

                                            $datosDomicilip = array(
                                                'domicilio_id' => $id,
                                                'cliente_id' => $cliente_id,
                                                'domicilio_estatus' => DOMICILIO_ESPERA,
                                                'fecha_created' => date("Y-m-d H:i:s")
                                            );
                                            $guardarDomicilio = $this->domicilios_model->saveDomicilios($datosDomicilip);

                                            $datoshis = array(
                                                'fecha' => date("Y-m-d H:i:s"),
                                                'id_domicilio' => $id,
                                                'usuario' => $this->session->userdata('nUsuCodigo'),
                                                'estatus' => DOMICILIO_ESPERA,
                                            );
                                            $this->db->insert('domicilio_historial', $datoshis);
                                        }
                                    }
                                }
                            } else {

                                /*******AQUI ENTRA SI ESTOY EDITANDO LA VENTA ABIERTA , NO ENDEVOLUCION, ***/
                                if ($venta['venta_status'] == COMPLETADO && $devolver != 'true') {
                                    $resolucion = $this->venta_model->generarnumeroFactura($venta['tipo_documento']);;
                                } else {
                                    $resolucion = NULL;
                                }

                                if ($this->input->post('accion_resetear')) {
                                    $venta['accion'] = $this->input->post('accion_resetear');
                                }
                                if ($resolucion === false) {
                                    $resultado = NUMERACION_ERROR;
                                } else {

                                    /*******AQUI ENTRA SI ESTOY EDITANDO LA VENTA ABIERTA , NO ENDEVOLUCION, ***/
                                    if ($venta['venta_status'] == COMPLETADO && $devolver != 'true') {
                                        $venta['id_resolucion'] = $resolucion['id_resolucion'];
                                        $venta['numero'] = $resolucion['numero'];
                                    } else {
                                        $venta['id_resolucion'] = $resolucion;
                                        $venta['numero'] = NULL;
                                    }


                                    $venta['venta_id'] = $id;
                                    $venta['devolver'] = $devolver;
                                    $venta['notadebito'] = $notadebito;

                                    $venta['tipo_devolucion'] = json_decode($this->input->post('tipo_devolucion_obj'));

                                    if ($devolver == 'true') { //SOLO CUANDO ES DEVOLUCION o nota debito

                                        if ($notadebito == 'true') {
                                            //  echo "es notadibot";
                                            // es notadebito

                                            $resultadodev = $this->venta_model->notaDebito($venta, $detalle);
                                            if (is_array($resultadodev)) {
                                                $resultado = $resultadodev['idventa'];
                                                $dataresult['id_devolucion'] = $resultadodev['id_devolucion'];
                                                $dataresult['uuid'] = $resultadodev['uuid'];
                                            } else {
                                                $resultado = $resultadodev;
                                            }
                                        } else {
                                            // es devolucion
                                            $resultadodev = $this->venta_model->devolver_venta($venta, $detalle);
                                            if (is_array($resultadodev)) {
                                                $resultado = $resultadodev['idventa'];
                                                $dataresult['id_devolucion'] = $resultadodev['id_devolucion'];
                                                $dataresult['uuid'] = $resultadodev['uuid'];
                                            } else {
                                                $resultado = $resultadodev;
                                            }
                                        }
                                    } else { //CUANDO ES EDICION DE VENTA POR OTRA COSA POR EJEMPLO CONTINUAR UNA VENTA ABIERTA
                                        $resultado = $this->venta_model->actualizar_venta($venta, $detalle);
                                    }
                                }
                            }


                            if (is_numeric($resultado)) {
                                /*if ($this->input->post('devolver') == 'true') {
                                    $this->consolidado_model->updateDetalle(array('pedido_id' => $id, 'liquidacion_monto_cobrado' => $this->input->post('importe', true)));
                                }*/
                                $this->ventaEstatus($id, $this->input->post('venta_status', true));

                                //$dataresult['estatus_consolidado'] = $this->input->post('estatus_consolidado', true);;
                                $dataresult['result'] = "success";
                                $dataresult['idventa'] = $resultado;


                                $log = array(
                                    'usuario' => $this->session->userdata('nUsuCodigo'),
                                    'ip' => $_SERVER['REMOTE_ADDR'],
                                    'fecha' => date('Y-m-d H:i:s'),
                                    'tabla' => 'VENTA',
                                    'tipo' => empty($this->input->post('idventa')) ? LOG_INSERT : LOG_UPDATE,
                                    'data_before' => empty($this->input->post('idventa')) ? json_encode($venta) : json_encode($venta),
                                    'data_after' => json_encode($venta),
                                );
                                $this->systemLogsModel->insert($log);
                            } else {
                                $dataresult['result'] = $resultado;
                            }
                        } else {
                            $dataresult['result'] = GLOBAL_ERROR;
                        }

                    endif;


                    echo json_encode($dataresult);
                } else {
                    redirect(base_url() . 'ventas/', 'refresh');
                }
            }
        }
    }


    function guardar_notacredito()
    {

        if ($this->input->is_ajax_request()) {


            $venta = json_decode($this->input->post('lst_venta', true));


            $credito = $this->venta_model->updateCredito($venta, true);
            if ($credito != FALSE) {
                $json['success'] = 'Se ha generado una nota de credito exitoxamente';
            } else {
                $json['error'] = 'No se ha podido generar la nota de credito';
            }

            echo json_encode($json);
        }
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function ventas_by_cliente()
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }

        $data['ventatodos'] = "TODOS";
        $condicion = array('a.id_cliente >=' => 0);
        $data['ventas'] = $this->venta_model->get_ventas_by_cliente($condicion);
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/ventas_by_cliente', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function show_venta_cliente($id = FALSE)
    {

        if ($id != FALSE) {


            $condicion = array('venta.id_cliente' => $id);
            $data['ventas'] = $this->venta_model->get_ventas_by($condicion);
            $data['ventatodos'] = "CLIENTE";
            $this->load->view('menu/ventas/show_venta_cliente', $data);
        }
    }

    function parcheSantamonia()
    {

        $ventas = $this->venta_model->get_parche_santamonica();
        var_dump($ventas);
        foreach ($ventas as $venta) {


            $cajero = $venta['cajero_id'];
            $caja = $venta['caja_id']; // se amacena el id de statuscaja  no la caja

            $id = $venta['venta_id'];

            $campos = array('tipo_anulacion' => 1, 'nUsuCodigo' => $cajero);
            $data['resultado'] = $this->venta_model->devolver_stock(
                $id,
                $campos,
                PEDIDO_ANULADO,
                $caja,
                $cajero,
                $venta['fecha']
            );

            $this->venta_model->update_historial_credito_parche_santamonica($venta['credito_id'], $id);
            $this->venta_model->update_credito_parche_santamonica($venta['credito_id'], $venta['id_cliente'], $venta['fecha']);
        }
    }


    function cancelar()
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }

        $estatus = array('COMPLETADO');
        $data["ventas"] = $this->venta_model->get_venta_by_status($estatus, date('Y-m-d'));

        $data["tipos"] = $this->tipo_anulacion_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/cancelarVenta', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    function devolver()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }
        $estatus = array('COMPLETADO');
        // $data["ventas"] = $this->venta_model->get_venta_by_status($estatus);
        $data["vendedores"] = $this->usuario_model->get_all_vendedores();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/devolverventa', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function notadebito()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }
        $estatus = array('COMPLETADO');
        // $data["ventas"] = $this->venta_model->get_venta_by_status($estatus);
        $data["vendedores"] = $this->usuario_model->get_all_vendedores();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/notadebito', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function ventaEstatus($venta_id, $estatus)
    {
        $vendedor_id = $this->session->userdata('nUsuCodigo');
        $estatus = array('venta_id' => $venta_id, 'vendedor_id' => $vendedor_id, 'estatus' => $estatus, 'apertua_caja_id' => $this->session->userdata('cajapertura'));
        $this->venta_estatus->insert_estatus($estatus);
    }

    function anular_venta()
    {

        $cajero = $this->session->userdata('cajero_id'); //TODO ESTO NOHACE FALTA PORQUE YA ESTA EN LA TABAL STTAUSCAJA, algun dia borrar
        $caja = $this->session->userdata('cajapertura'); // se amacena el id de statuscaja  no la caja
        $statuscaja = $this->StatusCajaModel->getBy(array('id' => $caja));
        if (!empty($statuscaja['cierre'])) {
            $json['result'] = "La caja en la que está intentando realizar la operación ya ha sido cerrada por otro usuario. Por favor cierre sessión e ingrese nuevamente para trabajar en otra caja";
            //  echo json_encode($dataresult);
        } else {
            if (($caja == '' or $cajero == '')) {
                $json['error'] = "Debe aperturar una caja para poder continuar";
            } else {
                $id = $this->input->post('id');

                $campos = array('tipo_anulacion' => $this->input->post('motivo'), 'nUsuCodigo' => $this->session->userdata('nUsuCodigo'));
                $data['resultado'] = $this->venta_model->devolver_stock(
                    $id,
                    $campos,
                    PEDIDO_ANULADO,
                    $caja,
                    $this->session->userdata('nUsuCodigo'),
                    date('Y-m-d h:m:s')
                );

                if ($data['resultado'] != FALSE) {
                    //  $this->ventaEstatus($id, PEDIDO_ANULADO); COmentado porque se esta insertando doble registro, sto ya se hace en el model de dovlerstock
                    $json['success'] = 'Se ha anulado exitosamente';
                } else {
                    $json['error'] = 'Ha ocurrido un error al anular la venta';
                }
            }
        }
        echo json_encode($json);
    }

    function deleteVenta()
    {
        $id = $this->input->post('idventa');

        $campos = array('tipo_anulacion' => $this->input->post('motivo'), 'nUsuCodigo' => $this->session->userdata('nUsuCodigo'));
        $data['resultado'] = $this->venta_model->update_status($id, PEDIDO_ELIMINADO, $this->session->userdata('nUsuCodigo'));

        if ($data['resultado'] != FALSE) {
            $this->ventaEstatus($id, PEDIDO_ELIMINADO);
            $json['result'] = 'success';
        } else {
            $json['result'] = 'error';
        }

        echo json_encode($json);
    }

    function get_ventas()
    {
        $condicion = array();
        if ($this->input->post('id_local') != "") {
            $condicion['local_id'] = $this->input->post('id_local');
            $data['local'] = $this->input->post('id_local');
        }
        if ($this->input->post('id_cliente') != "") {
            $condicion['venta.id_cliente'] = $this->input->post('id_cliente');
            $data['id_cliente'] = $this->input->post('id_cliente');
        }
        if ($this->input->post('desde') != "") {
            $condicion['fecha >= '] = date('Y-m-d', strtotime($this->input->post('desde'))) . " " . date('H:i:s', strtotime('0:0:0'));
            $data['fecha_desde'] = date('Y-m-d', strtotime($this->input->post('desde'))) . " " . date('H:i:s', strtotime('0:0:0'));
        }
        if ($this->input->post('hasta') != "") {
            $condicion['fecha <='] = date('Y-m-d', strtotime($this->input->post('hasta'))) . " " . date('H:i:s', strtotime('23:59:59'));
            $data['fecha_hasta'] = date('Y-m-d', strtotime($this->input->post('hasta'))) . " " . date('H:i:s', strtotime('23:59:59'));
        }
        if ($this->input->post('estatus') != "") {
            if ($this->input->post('estatus') == PEDIDO_DEVUELTO) {
                $condicion['id_devolucion <>'] = NULL;
            } else {
                $condicion['venta_status'] = $this->input->post('estatus');
                $data['estatus'] = $this->input->post('estatus');
            }
        } else {
            $condicion['venta_status <>'] = PEDIDO_ELIMINADO;
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
        if ($this->input->post('id_consolidado') != "") {
            $id_consolidado = $this->input->post('id_consolidado');
            //$data['productos_cons'] = $this->consolidado_model->get_pedido('consolidado_id', $id_consolidado);
            $condicionpedidos = array();
            $condicionpedidos['consolidado_id'] = $id_consolidado;
            $data['productos_cons'] = $this->consolidado_model->get_pedidos_by($condicionpedidos);
        }
        // $condicion['venta_status'] = COMPLETADO;

        if ($this->input->post('id_departamento') != "") {
            $condicion['ciudades.estado_id'] = $this->input->post('id_departamento');
            $data['id_departamento'] = $this->input->post('id_departamento');
        }

        if ($this->input->post('id_ciudad') != "") {
            $condicion['cliente.ciudad_id'] = $this->input->post('id_ciudad');
            $data['id_ciudad'] = $this->input->post('id_ciudad');
        }

        if ($this->input->post('id_barrio') != "") {
            $condicion['cliente.id_zona'] = $this->input->post('id_barrio');
            $data['id_barrio'] = $this->input->post('id_barrio');
        }

        $data['venta'] = $this->venta_model->get_ventas_by($condicion);

        $ventas = $data['venta'];
        foreach ($ventas as $venta) {
            $id_cliente = $venta->id_cliente;
            $deuda = $this->validar_deuda($id_cliente);
            if ($deuda == true) {
                $venta->deudor = 1;
            }

            /*busco las formas de pago,en esta tabla, si es que tuvo*/
            $where = array('venta.venta_id' => $venta->venta_id);
            $venta->formas_de_pago = new stdClass();
            $venta->formas_de_pago = $this->venta_model->getFormaPago($where);
        }
        $data['formas_de_pago'] = $this->metodos_pago_model->get_all();
        $data['ventas'] = $ventas;

        if ($this->input->post('listar') == 'ventas') {

            $this->load->view('menu/ventas/lista_ventas', $data);
        } else {
            $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/lista_pedidos', $data, true);
            if ($this->input->is_ajax_request()) {
                echo $dataCuerpo['cuerpo'];
            }
        }
    }

    function get_devoluciones()
    {
        $condicion = array();
        if ($this->input->post('id_local') != "") {
            $condicion['local_id'] = $this->input->post('id_local');
            $data['local'] = $this->input->post('id_local');
        }
        if ($this->input->post('desde') != "") {
            $condicion['date(fecha_devolucion) >= '] = date('Y-m-d', strtotime($this->input->post('desde'))) . " " . date('H:i:s', strtotime('0:0:0'));
            $data['fecha_desde'] = date('Y-m-d', strtotime($this->input->post('desde'))) . " " . date('H:i:s', strtotime('0:0:0'));
        }
        if ($this->input->post('hasta') != "") {
            $condicion['date(fecha_devolucion) <='] = date('Y-m-d', strtotime($this->input->post('hasta'))) . " " . date('H:i:s', strtotime('23:59:59'));
            $data['fecha_hasta'] = date('Y-m-d', strtotime($this->input->post('hasta'))) . " " . date('H:i:s', strtotime('23:59:59'));
        }

        $condicion['id_devolucion <>'] = NULL;


        // $condicion['venta_status <>'] = PEDIDO_ELIMINADO;

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
        if ($this->input->post('id_consolidado') != "") {
            $id_consolidado = $this->input->post('id_consolidado');
            //$data['productos_cons'] = $this->consolidado_model->get_pedido('consolidado_id', $id_consolidado);
            $condicionpedidos = array();
            $condicionpedidos['consolidado_id'] = $id_consolidado;
            $data['productos_cons'] = $this->consolidado_model->get_pedidos_by($condicionpedidos);
        }
        // $condicion['venta_status'] = COMPLETADO;
        $data['venta'] = $this->venta_model->get_ventas_by($condicion);

        $ventas = $data['venta'];
        foreach ($ventas as $venta) {
            $id_cliente = $venta->id_cliente;
            $deuda = $this->validar_deuda($id_cliente);
            if ($deuda == true) {
                $venta->deudor = 1;
            }

            /*busco las formas de pago,en esta tabla, si es que tuvo*/
            $where = array('venta.venta_id' => $venta->venta_id);
            $venta->formas_de_pago = new stdClass();
            $venta->formas_de_pago = $this->venta_model->getFormaPago($where);
        }
        $data['formas_de_pago'] = $this->metodos_pago_model->get_all();
        $data['ventas'] = $ventas;

        if ($this->input->post('listar') == 'ventas') {

            $this->load->view('menu/ventas/lista_ventas', $data);
        } else {
            $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/lista_pedidos', $data, true);
            if ($this->input->is_ajax_request()) {
                echo $dataCuerpo['cuerpo'];
            }
        }
    }


    function get_ventas_por_status()
    {

        $condicion = array('local_id' => $this->session->userdata('id_local'));
        $data['local'] = $this->session->userdata('id_local');


        $condicion['venta_status'] = $this->input->post('estatus');
        $data['estatus'] = $this->input->post('estatus');


        $data['ventas'] = $this->venta_model->get_ventas_by($condicion);

        $this->load->view('menu/ventas/lista_ventas_status', $data);
    }


    function buscar_NroVenta_credito()
    {
        $validar_cronograma = $this->input->post('validar_cronograma');

        if ($this->input->is_ajax_request()) {
            $venta = $this->venta_model->buscar_NroVenta_credito($this->input->post('nro_venta', true));

            if (count($venta) > 0) {
                if (!empty($validar_cronograma)) {
                    $cronogrma = $this->venta_model->get_cronograma_by_venta($venta[0]->venta_id);
                    if (count($cronogrma) > 0) {
                        echo json_encode(array('error' => 'Ya existe un crongrama para la venta seleccionada'));
                    } else {
                        echo json_encode($venta);
                    }
                } else {

                    echo json_encode($venta);
                }
            } else {
                echo json_encode(array('error' => 'El número de venta ingresado no existe o no es una venta a credito'));
            }
        } else {
            redirect(base_url() . 'ventas/', 'refresh');
        }
    }


    function consultar()
    {
        $data['locales'] = $this->local_model->get_all();
        $data['formas_de_pago'] = $this->metodos_pago_model->get_all();
        $data['clientes'] = $this->cliente_model->get_all();

        if (isset($_GET['buscar']) and $_GET['buscar'] == 'pedidos') {
            $data['vendedores'] = $this->usuario_model->select_all_by_roll('VENDEDOR');
          
            $data['zonas'] = $this->zona_model->get_all();
            $vista = 'bandejaPedidos';
        } else {
            $vista = 'reporteVenta';
        }
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/' . $vista, $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    public function devoluciones()
    {
        $data['locales'] = $this->local_model->get_all();
        $data['formas_de_pago'] = $this->metodos_pago_model->get_all();

        if (isset($_GET['buscar']) and $_GET['buscar'] == 'pedidos') {
            $data['vendedores'] = $this->usuario_model->select_all_by_roll('VENDEDOR');
            $data['clientes'] = $this->cliente_model->get_all();
            $data['zonas'] = $this->zona_model->get_all();
            $vista = 'bandejaPedidos';
        } else {
            $vista = 'reporteDevoluciones';
        }
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/' . $vista, $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    function reporteUtilidadesProductos()
    {


        $data['locales'] = $this->local_model->get_all();
        $data['productos'] = 1;
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/reporteUtilidades', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function reporteUtilidadesCliente()
    {


        $data['locales'] = $this->local_model->get_all();
        $data['cliente'] = 1;
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/reporteUtilidades', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function reporteUtilidadesProveedor()
    {


        $data['locales'] = $this->local_model->get_all();
        $data['proveedor'] = 1;
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/reporteUtilidades', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function reporteRotacionZona()
    {

        $data['locales'] = $this->local_model->get_all();
        $data['zonas'] = $this->zona_model->get_all();
        $data['todo'] = 1;
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/reporteRotacionZona', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function ultimoPrecioProducto()
    {
        //busco el ultimo precio de venta de cada unidad
        $dato = array();
        $producto = $this->input->post('producto_id');

        //busco las unidades
        $unidades = $this->unidades_model->get_unidades();
        $dato['ultimosprecios'] = array();

        //recorro las unidades y hago el query
        foreach ($unidades as $row) {
            $buscar = $this->venta_model->ultimoPrecioProducto($producto, $row['id_unidad']);
            if (count($buscar) > 0) {
                $dato['ultimosprecios'][] = $buscar;
            }
        }

        echo json_encode($dato);
    }


    function pedidosZona($id = FALSE)
    {

        $this->load->view('menu/estadisticas/estadisticaZonaPedidos');
    }

    function getProductoZona()
    {
        $condicion = array();

        $condicion2 = "venta_status IN ('" . COMPLETADO . "','" . PEDIDO_DEVUELTO . "','" . PEDIDO_ENTREGADO . "','" . PEDIDO_GENERADO . "')";
        $condicion['venta_tipo'] = "ENTREGA";
        $retorno = "RESULT";
        $select = "(SELECT COUNT(venta.id_cliente)) as clientes_atendidos, (SELECT SUM(detalle_venta.cantidad))
                    as cantidad_vendida, usuario.nombre, detalle_venta.*,venta.*, producto.*,grupos.*,familia.*,lineas.*,unidades.*,usuario_has_zona.*,
                    zonas.*,ciudades.*,cliente.razon_social, consolidado_carga.fecha";

        $group = "unidad_medida";

        if (($this->input->post('id_zona') != "") && ($this->input->post('id_zona') != "TODAS")) {

            $condicion['zona_id'] = $this->input->post('id_zona');
            $data['zona'] = $this->input->post('id_zona');
        }
        if (($this->input->post('desde') != "")) {


            $condicion['date(consolidado_carga.fecha) >= '] = date('Y-m-d', strtotime($this->input->post('desde')));
            $data['fecha_desde'] = date('Y-m-d', strtotime($this->input->post('desde')));
        }
        if (($this->input->post('hasta') != "")) {


            $condicion['date(consolidado_carga.fecha) <='] = date('Y-m-d', strtotime($this->input->post('hasta')));


            $data['fecha_hasta'] = date('Y-m-d', strtotime($this->input->post('hasta')));
        }


        $data['ventas'] = $this->venta_model->getProductosZona($select, $condicion, $retorno, $group, $condicion2);
        $this->load->view('menu/ventas/listaReporteProductoZona', $data);
    }

    function informesVentasFecha()
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
            $data = $this->input->get('data');
            $where = "`venta_status` ='" . COMPLETADO . "' ";

            if (isset($data['cboCliente']) && $data['cboCliente'] != -1) {

                $where = $where . " AND venta.id_cliente =" . $data['cboCliente'];
            }
            if (isset($data['fecIni']) && $data['fecIni'] != "") {

                $where = $where . " AND date(fecha) >= '" . date('Y-m-d', strtotime($data['fecIni'])) . "'";
            }
            if (isset($data['fecFin']) && $data['fecFin'] != "") {

                $where = $where . " AND  date(fecha) <= '" . date('Y-m-d', strtotime($data['fecFin'])) . "'";
            }
            //  echo $where;
            $nombre_in[0] = 'var_credito_estado';
            $where_in[0] = array(CREDITO_DEBE, CREDITO_ACUENTA, CREDITO_NOTACREDITO, CREDITO_CANCELADO);
            $nombre_in[1] = 'venta_status';
            $where_in[1] = array(PEDIDO_ENTREGADO, PEDIDO_DEVUELTO, COMPLETADO, PEDIDO_GENERADO, PEDIDO_ENVIADO);
            ///////////////////////
            $select = 'venta.venta_id, venta_tipo, venta.id_cliente, nombres,apellidos, fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
            nombre_condiciones, condiciones_pago.dias,venta.id_cliente as clientV, venta.pagado,
            (select SUM(historial_monto) from historial_pagos_clientes where historial_pagos_clientes.venta_id = venta.venta_id  ) as confirmar,usuario.nUsuCodigo as vendedor_id, usuario.nombre as vendedor_nombre,,usuario.nombre as vendedor';
            $from = "venta";
            $join = array('credito', 'cliente', 'documento_venta', 'tipo_venta', 'condiciones_pago', 'usuario');
            $campos_join = array(
                'credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
                'documento_venta.id_venta=venta.venta_id', 'venta.venta_tipo=tipo_venta.tipo_venta_id', 'condiciones_pago.id_condiciones=tipo_venta.condicion_pago', 'usuario.nUsuCodigo=venta.id_vendedor'
            );
            $tipo_join = array('left', 'left', 'left', 'left', 'left', 'left',);

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


            $total = $this->venta_model->traer_by_mejorado(
                'COUNT(venta.venta_id) as total',
                $from,
                $join,
                $campos_join,
                $tipo_join,
                $where,
                $nombre_in,
                $where_in,
                $nombre_or,
                $where_or,
                $group,
                $order,
                "RESULT_ARRAY",
                false,
                false,
                $order_dir,
                false,
                $where_custom
            );


            $lstVenta = $this->venta_model->traer_by_mejorado(
                $select,
                $from,
                $join,
                $campos_join,
                $tipo_join,
                $where,
                $nombre_in,
                $where_in,
                $nombre_or,
                $where_or,
                $group,
                $order,
                "RESULT_ARRAY",
                $limit,
                $start,
                $order_dir,
                false,
                $where_custom
            );
            if (count($lstVenta) > 0) {

                foreach ($lstVenta as $v) {

                    $pendiente = 0;
                    $PRODUCTOjson = array();

                    $PRODUCTOjson[] = $v['venta_id'];
                    $PRODUCTOjson[] = $v['nombre_tipo_documento'];
                    $PRODUCTOjson[] = $v['documento_Serie'] . "-" . $v['documento_Numero'];

                    $PRODUCTOjson[] = $v['nombres'] . " " . $v['apellidos'];
                    $PRODUCTOjson[] = date("d-m-Y H:i:s", strtotime($v['fecha']));
                    $PRODUCTOjson[] = number_format($v['total'], 2);

                    $montoancelado = $montoancelado = number_format(floatval($v['dec_credito_montodebito']), 2);

                    $PRODUCTOjson[] = $montoancelado;

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


                    $botonas = '<div class="btn-group"><a class=\'btn btn-default tip\' title="Ver Venta" onclick="visualizar(' . $v["venta_id"] . ')"><i
								class="fa fa-search"></i> Historial</a>';

                    $botonas .= '</div>';
                    //$PRODUCTOjson[] = $botonas;
                    $array['productosjson'][] = $PRODUCTOjson;
                }
            }
            $array['data'] = $array['productosjson'];
            $array['draw'] = $draw; //esto debe venir por post
            $array['recordsTotal'] = $total[0]['total'];
            $array['recordsFiltered'] = $total[0]['total']; // esto dbe venir por post

            echo json_encode($array);
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }


    function deudasElevadas()
    {
        $data = "";
        $data["lstTrabajador"] = $this->venta_model->get_ventas_user();
        $data["zonas"] = $this->zona_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/reportes/deudasElevadas', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function lst_reg_deudasElevadas()
    {
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('cboTrabajador', true) != -1) {
                $where = array('venta.id_vendedor' => $this->input->post('cboTrabajador', true));
            }
            if ($this->input->post('cboZona', true) != -1) {
                $where = array('zonas.zona_id' => $this->input->post('cboZona', true));
            }
            if ($_POST['fecIni'] != "") {
                $where['fecha >= '] = date('Y-m-d', strtotime($this->input->post('fecIni')));
            }
            if ($_POST['fecFin'] != "") {
                $where['fecha <= '] = date('Y-m-d', strtotime($this->input->post('fecFin')));
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
            $where_in[1] = array(PEDIDO_ENTREGADO, PEDIDO_DEVUELTO, COMPLETADO);
            ///////////////////////
            $select = 'venta.venta_id, venta.id_cliente,venta.id_vendedor, razon_social,fecha, total,var_credito_estado, dec_credito_montodebito, documento_venta.*,
            nombre_condiciones,usuario.nombre,zonas.zona_nombre';
            $from = "venta";
            $join = array('credito', 'cliente', 'documento_venta', 'condiciones_pago', 'usuario', 'zonas');
            $campos_join = array(
                'credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
                'documento_venta.id_venta=venta.venta_id', 'condiciones_pago.id_condiciones=venta.condicion_pago',
                'usuario.nUsuCodigo=venta.id_vendedor', 'zonas.zona_id=cliente.id_zona'
            );
            $tipo_join = false;

            $result['lstVenta'] = $this->venta_model->traer_by(
                $select,
                $from,
                $join,
                $campos_join,
                $tipo_join,
                $where,
                $nombre_in,
                $where_in,
                $nombre_or,
                $where_or,
                false,
                false,
                "RESULT_ARRAY"
            );
            // var_dump($result);

            $this->load->view('menu/ventas/tbl_listareg_deudaselevadas', $result);
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }
    }


    public
    function verVenta()
    {
        $idventa = $this->input->post('idventa');
        $result['ventas'] = array();
        if ($idventa != FALSE) {
            $result['ventas'] = $this->venta_model->obtener_venta($idventa);


            $result['id_venta'] = $idventa;
            $result['retorno'] = 'venta/consultar';
            $result['impuestos'] = $this->impuestos_model->get_impuestos();

            $this->load->view('menu/ventas/visualizarVenta', $result);
        }
    }


    public
    function verDevolucion()
    {
        $idventa = $this->input->post('idventa');
        $id_devolucion = $this->input->post('id_devolucion');
        $result['ventas'] = array();
        if ($idventa != FALSE) {
            $result['ventas'] = $this->venta_model->obtener_venta($idventa);


            $result['detalle_devolucion'] = $this->venta_model->detalle_devolucion_venta($id_devolucion);

            $result['id_venta'] = $idventa;
            $result['retorno'] = 'venta/consultar';
            $result['impuestos'] = $this->impuestos_model->get_impuestos();

            $this->load->view('menu/ventas/visualizarDevolucion', $result);
        }
    }


    public
    function cotizar()
    {
        $idventa = $this->input->post('idventa');
        $result['ventas'] = array();


        $lista_bonos = $this->input->post('lst_bonos', true);
        if (empty($lista_bonos)) $lista_bonos = null;

        $detalles = json_decode($this->input->post('lst_producto', true));


        foreach ($detalles as $detalle) {

            $detallearray = (array) $detalle;

            $detallearray['fecha'] = date("Y-m-d H:i:s");
            $detallearray['documento_cliente'] = $this->input->post('id_cliente', true);
            $cliente = $this->cliente_model->get_by('id_cliente', $this->input->post('id_cliente', true));
            $producto = $this->pd->get_by('producto_id', $detallearray['id_producto']);


            $detallearray['producto_codigo_interno'] = $producto['producto_codigo_interno'];
            $detallearray['cliente'] = $cliente['nombres'] . ' ' . $cliente['apellidos'];
            $detallearray['direccion_cliente'] = $cliente['direccion'];
            $detallearray['id_vendedor'] = $this->session->userdata('nUsuCodigo');
            $detallearray['venta_tipo'] = $this->input->post('tipoventa');

            $detallearray['condicion_pago'] = $this->input->post('condicion_pago');
            $detallearray['venta_status'] = $this->input->post('venta_status', true);
            $detallearray['local_id'] = $this->session->userdata('id_local');

            $detallearray['subtotal'] = $this->input->post('subtotal', true);
            $detallearray['gravado'] = $this->input->post('basegravada', true);
            $detallearray['excluido'] = $this->input->post('excluido', true);
            $detallearray['total_impuesto'] = $this->input->post('iva', true);
            $detallearray['total_otros_impuestos'] = $this->input->post('otros_impuestos', true);
            $detallearray['total'] = $this->input->post('totApagar', true);
            $detallearray['descuento_valor'] = $this->input->post('descuentoenvalor', true);
            $detallearray['descuento_porcentaje'] = $this->input->post('descuentoenporcentaje', true);

            $detallearray['importe'] = $this->input->post('dineroentregado', true);
            $detallearray['cambio'] = $this->input->post('cambio', true);
            $detallearray['diascondicionpagoinput'] = $this->input->post('diascondicionpagoinput', true);
            $detallearray['tipo_documento'] = $this->input->post('tipo_documento', true);
            $detallearray['REPRESENTANTE_LEGAL'] = $this->session->userdata('REPRESENTANTE_LEGAL');
            $detallearray['RazonSocialEmpresa'] = $this->session->userdata('EMPRESA_NOMBRE');
            $detallearray['NIT'] = $this->session->userdata('NIT');
            $detallearray['TelefonoEmpresa'] = $this->session->userdata('EMPRESA_TELEFONO');
            $detallearray['DireccionEmpresa'] = $this->session->userdata('EMPRESA_DIRECCION');
            $detallearray['REGIMEN_CONTRIBUTIVO'] = $this->session->userdata('REGIMEN_CONTRIBUTIVO');
            $detallearray['REGIMEN_CONTRIBUTIVO'] = $this->regimen_model->get_by(array('regimen_id' => $this->session->userdata('REGIMEN_CONTRIBUTIVO')));
            $detallearray['REGIMEN_CONTRIBUTIVO'] = $detallearray['REGIMEN_CONTRIBUTIVO']['regimen_nombre'];

            $result['ventas'][] = $detallearray;
        }


        $result['resolucion'] = $this->resolucion_model->get_last();

        $result['retorno'] = 'venta/consultar';

        $this->load->view('menu/ventas/cotizarVenta', $result);
    }

    public
    function rtfNotaDeEntrega($id = null, $tipo)
    {

        if ($tipo == 'VENTA') {
            $result['notasdentrega'][]['ventas'] = $this->venta_model->obtener_venta_backup($id);
        } else {


            $result['notasdentrega'] = array();
            foreach ($result['detalleC'] as $pedido) {
                $id_pedido = $pedido['pedido_id'];
                if ($id != FALSE) {
                    $result['retorno'] = 'consolidadodecargas';
                    $result['id_venta'] = $id_pedido;
                    $result['notasdentrega'][]['ventas'] = $this->venta_model->obtener_venta_backup($id_pedido);
                }
            }
        }

        //$html = $this->load->view('menu/reportes/rtfNotaDeEntrega', $result,true);
        $notasdentrega = $result['notasdentrega'];

        // documento
        $phpword = new \PhpOffice\PhpWord\PhpWord();
        $styles = array(
            'pageSizeW' => '12755.905511811',
            'pageSizeH' => '7937.007874016',
            'marginTop' => '566.929133858',
            'marginLeft' => '866.858267717',
            'marginRight' => '866.929133858',
            'marginBottom' => '283.464566929',
        );


        $phpword->addFontStyle('rStyle', array('size' => 15, 'allCaps' => true, 'spaceBefore' => 0, 'spaceAfter' => 0, 'spacing' => 0));
        $phpword->addParagraphStyle('pStyle', array('align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0, 'spacing' => 0));
        $styleTable = array('borderSize' => 6, 'borderColor' => '999999', 'width' => 50 * 100);
        $tablastyle = array('width' => 50 * 100, 'unit' => 'pct', 'align' => 'left');
        if (isset($notasdentrega[0])) {
            $i = 0;
            foreach ($notasdentrega as $nota) {
                $section = $phpword->addSection($styles);
                $header = $section->addHeader();

                if (isset($nota['ventas'][0])) {
                    $ventas[0] = $nota['ventas'][0];

                    // tabla titulos
                    $table = $header->addTable($tablastyle);
                    $cell = $table->addRow(650, array('exactHeight' => true))->addCell(3000, array('valign ' => 'center', 'align' => 'center'));

                    $cell->addText(htmlspecialchars('NOTA DE ENTREGA '), 'rStyle', 'pStyle');

                    $header->addTableStyle('Border', $styleTable);
                    $innerCell = $table->addCell(2000, array('align' => 'right'))->addTable($styleTable)->addRow(200)->addCell(3000, array('align' => 'center'));
                    $innerCell->addText(htmlspecialchars('NOTA DE ENTREGA Nº'), array('size' => 12, 'align ' => 'center'), 'pStyle');
                    $innerCell->addText((isset($ventas[0]['serie']) and isset($ventas[0]['numero'])) ? $ventas[0]['serie'] . $ventas[0]['numero'] : '', array('size' => 12, 'align ' => 'center'), 'pStyle');
                    if (isset($result['detalleC'][0])) {
                        $table->addCell()->addText(htmlspecialchars("CGC: " + $result['detalleC'][0]['consolidado_id']));
                    }

                    $header->addTextBreak(1);
                    // tabla de datos basicos

                    $phpword->addFontStyle('rBasicos', array('size' => 8, 'allCaps' => true, 'spaceBefore' => 0, 'spaceAfter' => 0, 'spacing' => 0));
                    $table1 = $header->addTable($tablastyle);
                    $cell = $table1->addRow(150, array('exactHeight' => true))->addCell(566);
                    $cell->addText(htmlspecialchars('CLIENTE'), 'rBasicos');
                    $table1->addCell(7000)->addText(htmlspecialchars(strtoupper($ventas[0]['cliente'])), 'rBasicos');

                    $table1->addCell(4000)->addText(htmlspecialchars('COD. CLIE: ' . $ventas[0]['cliente_id']), 'rBasicos');
                    $table1->addCell(4000)->addText(htmlspecialchars('F. EMISION: ' . date('Y-m-d', strtotime($ventas[0]['fechaemision']))), 'rBasicos');
                    $table1->addCell(4000)->addText(htmlspecialchars('USUA: ' . strtoupper($ventas[0]['vendedor'])), 'rBasicos');

                    $table1->addRow(150, array('exactHeight' => true))->addCell(566)->addText(htmlspecialchars('DIRECCION: '), 'rBasicos');
                    $table1->addCell(7000, array('gridSpan' => 2))->addText(htmlspecialchars((isset($ventas[0]['clienteDireccionAlt'])) ? strtoupper($ventas[0]['clienteDireccionAlt']) : ''), 'rBasicos');

                    $table1->addCell(4000)->addText(htmlspecialchars('F. VENC.: ' . (isset($result['detalleC'][0]) ? date('Y-m-d', strtotime($result['detalleC'][0]['fecha'])) : '')), 'rBasicos');
                    $table1->addCell(4000)->addText(htmlspecialchars('HORA: ' . (isset($result['detalleC'][0]) ? date('H:i:s', strtotime($result['detalleC'][0]['fecha'])) : '')), 'rBasicos');

                    $table1->addRow(150, array('exactHeight' => true))->addCell(566)->addText(htmlspecialchars('CONTACTO: '), 'rBasicos');
                    $table1->addCell(7000)->addText(htmlspecialchars(((isset($ventas[0]['representanteCliente'])) ? strtoupper($ventas[0]['representanteCliente']) : '')), 'rBasicos');
                    $table1->addCell(4000)->addText((htmlspecialchars('TELEFONO: ' . (isset($ventas[0]['telefonoC1']) ? $ventas[0]['telefonoC1'] : ''))), 'rBasicos');

                    $table1->addCell(4000)->addText(htmlspecialchars('COND. VENTA:' . strtoupper($ventas[0]['nombre_condiciones'])), 'rBasicos');
                    $table1->addCell(4000)->addText(htmlspecialchars('VEND.:' . ((isset($ventas[0]['id_vendedor'])) ? $ventas[0]['id_vendedor'] : '')), 'rBasicos');

                    $header->addTextBreak(1);
                    $table1 = $section->addTable($tablastyle);
                    $table1->addRow(200, array('exactHeight' => true, 'tblHeader' => true))->addCell(1000, array('valign ' => 'bottom'))->addText(htmlspecialchars('CODIGO'), 'rBasicos');
                    $table1->addCell(9000)->addText(htmlspecialchars('DESCRIPCION'), 'rBasicos');
                    $table1->addCell(2000)->addText(htmlspecialchars('PRESENTACION'), 'rBasicos');
                    $table1->addCell(1500)->addText(htmlspecialchars('CANTIDAD'), 'rBasicos');
                    $table1->addCell(1500)->addText(htmlspecialchars('PREC. UNIT.'), 'rBasicos');
                    $table1->addCell(1000)->addText(htmlspecialchars('TOTAL'), 'rBasicos');
                    $table1->addRow(250, array('exactHeight' => true, 'tblHeader' => true))->addCell(null, array('valign' => 'top', 'gridSpan' => 6))
                        ->addText('___________________________________________________________________________________________________');

                    // tabla de productos
                    $table1 = $section->addTable($tablastyle);
                    foreach ($nota['ventas'] as $venta) {
                        $um = isset($venta['abreviatura']) ? $venta['abreviatura'] : $venta['nombre_unidad'];
                        $cantidad_entero = intval($venta['cantidad'] / 1) > 0 ? intval($venta['cantidad'] / 1) : '';
                        $cantidad_decimal = fmod($venta['cantidad'], 1);

                        $cantidad = $cantidad_entero;

                        if ($cantidad_decimal > 0) {
                            if (!empty($cantidad_entero)) {
                                $cantidad = $cantidad_entero . "." . $cantidad_decimal;
                            } else
                                $cantidad = $cantidad_decimal;

                            if ($cantidad_decimal == 0.25 or $cantidad_decimal == 0.250)
                                $cantidad = $cantidad_entero . " " . '1/4';
                            if ($cantidad_decimal == 0.5 or $cantidad_decimal == 0.50 or $cantidad_decimal == 0.500)
                                $cantidad = $cantidad_entero . " " . '1/2';
                            if ($cantidad_decimal == 0.75 or $cantidad_decimal == 0.750)
                                $cantidad = $cantidad_entero . " " . '3/4';
                        }


                        if ($venta['unidades'] == 12 or $venta['orden'] == 1) {
                            $cantidad = floatval($venta['cantidad']);
                        } else {
                            $cantidad = floatval($venta['cantidad'] * $venta['unidades']);
                            $um = $venta['unidad_minima'];
                        }


                        $table1->addRow(150, array('exactHeight' => true));
                        $table1->addCell(1000)->addText(htmlspecialchars($venta['producto_id']), 'rBasicos');
                        $table1->addCell(9000)->addText(htmlspecialchars(strtoupper($venta['nombre']) . (($venta['bono'] == 1) ? ' --- BONIFICACION' : '')), 'rBasicos');
                        $table1->addCell(2000)->addText(htmlspecialchars(strtoupper($venta['presentacion'])), 'rBasicos');
                        $table1->addCell(1500)->addText($cantidad . " " . $um, 'rBasicos');
                        $table1->addCell(1500)->addText($venta['preciounitario'], 'rBasicos');
                        $table1->addCell(1000)->addText($venta['importe'], 'rBasicos');
                    }
                    $footer = $section->addFooter();
                    // $footer->addTextBreak(1);
                    $table1 = $footer->addTable($tablastyle);
                    $table1->addRow(150, array('exactHeight' => true))->addCell(9000, array('gridSpan' => 3))->addText(htmlspecialchars('SON:' . MONEDA . numtoletras($ventas[0]['montoTotal'] * 10 / 10)), 'rBasicos');

                    $table1->addRow(150, array('exactHeight' => true))->addCell(4900)->addText(htmlspecialchars('*CANJEAR POR BOLETA O FACTURA '), 'rBasicos');
                    $table1->addCell()->addText(htmlspecialchars('____________________________ '), 'rBasicos', 'pStyle');
                    $table1->addCell(2000);
                    // $section->addTextBreak(1);
                    $table1->addRow(150, array('exactHeight' => true))->addCell(4900)->addText(htmlspecialchars(' *GRACIAS POR SU COMPRA. VUELVA PRONTO'), 'rBasicos');
                    $table1->addCell(7000)->addText(htmlspecialchars('RECIBO CONFORME'), 'rBasicos', 'pStyle');
                    $table1->addCell(2000)->addText(htmlspecialchars('Total: ' . MONEDA . ' ' . ceil($ventas[0]['montoTotal'] * 10) / 10), 'rBasicos');

                    // $section->addTextBreak(1);
                }
            }
        }


        $file = 'NotaDeEntrega' . $id . '.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpword, 'Word2007');
        $xmlWriter->save("php://output");
    }


    public
    function rtfBoleta($id, $tipo)
    {

        if ($tipo == 'VENTA') {

            $result = $this->venta_model->documentoVenta($id);
            $result['id_venta'] = $id;
            if ($result['ventas'][0]['descripcion'] != FACTURA) {

                $nombre = 'BOLETA';
                $result['boletas'][0] = $result;
                //  $html = $this->load->view('menu/reportes/rtfVentasBoletas', $result, true);
            }
        } else {
            $c = 0;


            $result['boletas'] = array();
            foreach ($result['detalleC'] as $pedido) {
                $id_pedido = $pedido['pedido_id'];
                if ($id != FALSE) {
                    if ($pedido['documento_tipo'] != FACTURA) {
                        $result['id_venta'] = $id_pedido;
                        $boletas = $this->venta_model->documentoVenta($id_pedido);

                        // var_dump($boletas['productos']);
                        $result['boletas'][] = $boletas;
                        $c++;
                    } else {
                    }
                }
            }
            if ($c >= 1) {
            } else {
                $result['boletas'] = "";
            }
        }


        //$html = $this->load->view('menu/reportes/rtfNotaDeEntrega', $result,true);
        $boletas = $result['boletas'];

        // documento
        $phpword = new \PhpOffice\PhpWord\PhpWord();
        $styles = array(
            'pageSizeW' => '7256.692913386',
            'pageSizeH' => '8798.74015748',
            'marginTop' => '396.850393701',
            'marginLeft' => '170.078740157',
            'marginRight' => '170.078740157',
            'marginBottom' => '396.850393701',
        );
        $section = $phpword->addSection($styles);

        $phpword->addFontStyle('rStyle', array('size' => 18, 'allCaps' => true));
        $phpword->addParagraphStyle('pStyle', array('align' => 'center'));
        $phpword->addFontStyle('rBasicos', array('size' => 7, 'allCaps' => true));
        $tablastyle = array('width' => 50 * 100, 'unit' => 'pct', 'align' => 'left');

        if (isset($boletas[0])) {
            foreach ($boletas as $boleta) {
                foreach ($boleta['ventas'] as $venta) {
                    $totalboleta = 0;

                    // tabla titulos
                    $table = $section->addTable($tablastyle);
                    $table->addRow()->addCell(5000, array('valign ' => 'center', 'align' => 'center'));

                    $innerCell = $table->addCell(4000, array('align' => 'right'))->addTable()->addRow()->addCell(3000, array('align' => 'center'));
                    $innerCell->addText($venta['serie'] . "-" . $venta['numero'], array('size' => 12, 'align ' => 'center'), 'pStyle');
                    $section->addTextBreak(1);

                    // tabla de datos basicos

                    $table1 = $section->addTable($tablastyle);
                    $table1->addRow(100);
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['cliente'])), 'rBasicos');
                    $table1->addRow(100);
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['direccion_cliente'])), 'rBasicos');
                    $table1->addRow(100);
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['direccion_cliente'])), 'rBasicos');


                    $section->addTextBreak(1);
                    $table1 = $section->addTable($tablastyle);
                    $table1->addRow(200);
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addRow(200);
                    $table1->addCell()->addText(htmlspecialchars($venta['serie'] . "-" . $venta['numero']), 'rBasicos');
                    $table1->addCell()->addText(htmlspecialchars($venta['nombre_condiciones']), 'rBasicos');
                    $table1->addCell()->addText();
                    $table1->addCell()->addText();
                    $table1->addCell()->addText(date('Y-m-d', strtotime($venta['fechaemision'])), 'rBasicos');
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['vendedor'])), 'rBasicos');
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['cliente'])), 'rBasicos');


                    // tabla de productos
                    $section->addTextBreak(1);
                    $table1 = $section->addTable($tablastyle);
                    $table1->addRow(200)->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();

                    foreach ($venta['productos'] as $producto) {
                        $totalboleta = $totalboleta + $producto['importe'];

                        if ($venta['documento_id'] == $producto['documento_id']) {
                            $um = isset($producto['abreviatura']) ? $producto['abreviatura'] : $producto['nombre_unidad'];
                            $cantidad_entero = intval($producto['cantidad'] / 1) > 0 ? intval($producto['cantidad'] / 1) : '';
                            $cantidad_decimal = fmod($producto['cantidad'], 1);

                            $cantidad = $cantidad_entero;

                            if ($cantidad_decimal > 0) {
                                if (!empty($cantidad_entero)) {
                                    $cantidad = $cantidad_entero . "." . $cantidad_decimal;
                                } else
                                    $cantidad = $cantidad_decimal;

                                if ($cantidad_decimal == 0.25 or $cantidad_decimal == 0.250)
                                    $cantidad = $cantidad_entero . " " . '1/4';
                                if ($cantidad_decimal == 0.5 or $cantidad_decimal == 0.50 or $cantidad_decimal == 0.500)
                                    $cantidad = $cantidad_entero . " " . '1/2';
                                if ($cantidad_decimal == 0.75 or $cantidad_decimal == 0.750)
                                    $cantidad = $cantidad_entero . " " . '3/4';
                            }


                            if ($producto['unidades'] == 12 || $producto['orden'] == 1) {
                                $cantidad = floatval($producto['cantidad']);
                            } else {
                                $cantidad = floatval($producto['cantidad'] * $producto['unidades']);
                                $um = $producto['unidad_minima'];
                            }


                            $table1->addRow(150, array('exactHeight' => true));
                            $table1->addCell()->addText(htmlspecialchars($producto['ddproductoID']), 'rBasicos');
                            $table1->addCell()->addText(htmlspecialchars(strtoupper($producto['nombre']) . ($producto['importe'] == 0 ? ' --- BONIFICACION' : '')), 'rBasicos');
                            $table1->addCell()->addText($um, 'rBasicos');
                            $table1->addCell()->addText($cantidad, 'rBasicos');
                            $table1->addCell()->addText($producto['preciounitario'], 'rBasicos');
                            $table1->addCell()->addText(ceil($producto['importe'] * 10) / 10, 'rBasicos');
                        }
                    }

                    $table1->addRow(200)->addCell(null, array('gridSpan' => 5));
                    $table1->addCell()->addText(MONEDA . ceil($totalboleta * 10) / 10, 'rBasicos');
                    $section->addTextBreak(1);
                    $innertable = $section->addTable();
                    $innertable->addRow()->addCell(2000)->addText();
                    $innertable->addCell()->addText(htmlspecialchars($venta['placa']), 'rBasicos');
                    $innertable->addRow()->addCell(2000)->addText(htmlspecialchars($venta['vendedor']), 'rBasicos');
                    $innertable->addCell();

                    $table1->addRow(200);
                    $table1->addCell(2000, array('gridSpan' => 7))->addText();
                    $table1->addRow()->addCell(null, array('gridSpan' => 7))->addText(numtoletras(ceil($totalboleta * 10) / 10, 'rBasicos'));
                    $section->addTextBreak(1);
                }
            }
        }


        $file = 'BoletadeVenta' . $id . '.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpword, 'Word2007');
        $xmlWriter->save("php://output");
    }


    public
    function rtfFactura($id, $tipo)
    {

        if ($tipo == 'VENTA') {

            $result = $this->venta_model->documentoVenta($id);
            $result['id_venta'] = $id;
            if ($result['ventas'][0]['descripcion'] == FACTURA) {

                $nombre = 'BOLETA';
                $result['boletas'][0] = $result;
                //  $html = $this->load->view('menu/reportes/rtfVentasBoletas', $result, true);
            }
        } else {
            $c = 0;


            $result['boletas'] = array();
            foreach ($result['detalleC'] as $pedido) {
                $id_pedido = $pedido['pedido_id'];
                if ($id != FALSE) {
                    if ($pedido['documento_tipo'] == FACTURA) {
                        $result['id_venta'] = $id_pedido;
                        $boletas = $this->venta_model->documentoVenta($id_pedido);

                        // var_dump($boletas['productos']);
                        $result['boletas'][] = $boletas;
                        $c++;
                    } else {
                    }
                }
            }
            if ($c >= 1) {
            } else {
                $result['boletas'] = "";
            }
        }


        //$html = $this->load->view('menu/reportes/rtfNotaDeEntrega', $result,true);
        $boletas = $result['boletas'];

        // documento
        $phpword = new \PhpOffice\PhpWord\PhpWord();
        $styles = array(
            'pageSizeW' => '12812.598425197',
            'pageSizeH' => '8617.322834646',
            'marginTop' => '396.850393701',
            'marginLeft' => '113.385826772',
            'marginRight' => '113.385826772',
            'marginBottom' => '340.157480315',
        );
        $section = $phpword->addSection($styles);

        $phpword->addFontStyle('rStyle', array('size' => 18, 'allCaps' => true));
        $phpword->addParagraphStyle('pStyle', array('align' => 'center'));
        $phpword->addFontStyle('rBasicos', array('size' => 7, 'allCaps' => true));
        $tablastyle = array('width' => 50 * 100, 'unit' => 'pct', 'align' => 'left');
        $phpword->addParagraphStyle('totales', array('align' => 'right'));
        if (isset($boletas[0])) {
            foreach ($boletas as $boleta) {
                foreach ($boleta['ventas'] as $venta) {


                    // tabla titulos
                    $table = $section->addTable($tablastyle);
                    $table->addRow()->addCell(5000, array('valign ' => 'center', 'align' => 'center'));

                    $innerCell = $table->addCell(4000, array('align' => 'right'))->addTable()->addRow()->addCell(3000, array('align' => 'center'));
                    $innerCell->addText($venta['serie'] . "-" . $venta['numero'], array('size' => 12, 'align ' => 'center'), 'pStyle');
                    $section->addTextBreak(1);

                    // tabla de datos basicos

                    $table1 = $section->addTable($tablastyle);
                    $table1->addRow(100);
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['cliente'])), 'rBasicos');
                    $table1->addRow(100);
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['direccion_cliente'])), 'rBasicos');
                    $table1->addRow(100);
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['direccion_cliente'])), 'rBasicos');


                    $section->addTextBreak(1);
                    $table1 = $section->addTable($tablastyle);
                    $table1->addRow(200);
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addRow(200);
                    $table1->addCell()->addText(htmlspecialchars($venta['serie'] . "-" . $venta['numero']), 'rBasicos');
                    $table1->addCell()->addText(htmlspecialchars($venta['nombre_condiciones']), 'rBasicos');
                    $table1->addCell()->addText();
                    $table1->addCell()->addText();
                    $table1->addCell()->addText(date('Y-m-d', strtotime($venta['fechaemision'])), 'rBasicos');
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['vendedor'])), 'rBasicos');
                    $table1->addCell()->addText(htmlspecialchars(strtoupper($venta['cliente'])), 'rBasicos');


                    // tabla de productos
                    $section->addTextBreak(1);
                    $table1 = $section->addTable($tablastyle);
                    $table1->addRow(200)->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();
                    $table1->addCell();

                    foreach ($venta['productos'] as $producto) {


                        if ($venta['documento_id'] == $producto['documento_id']) {
                            $um = isset($producto['abreviatura']) ? $producto['abreviatura'] : $producto['nombre_unidad'];
                            $cantidad_entero = intval($producto['cantidad'] / 1) > 0 ? intval($producto['cantidad'] / 1) : '';
                            $cantidad_decimal = fmod($producto['cantidad'], 1);

                            $cantidad = $cantidad_entero;

                            if ($cantidad_decimal > 0) {
                                if (!empty($cantidad_entero)) {
                                    $cantidad = $cantidad_entero . "." . $cantidad_decimal;
                                } else
                                    $cantidad = $cantidad_decimal;

                                if ($cantidad_decimal == 0.25 or $cantidad_decimal == 0.250)
                                    $cantidad = $cantidad_entero . " " . '1/4';
                                if ($cantidad_decimal == 0.5 or $cantidad_decimal == 0.50 or $cantidad_decimal == 0.500)
                                    $cantidad = $cantidad_entero . " " . '1/2';
                                if ($cantidad_decimal == 0.75 or $cantidad_decimal == 0.750)
                                    $cantidad = $cantidad_entero . " " . '3/4';
                            }


                            if ($producto['unidades'] == 12 || $producto['orden'] == 1) {
                                $cantidad = floatval($producto['cantidad']);
                            } else {
                                $cantidad = floatval($producto['cantidad'] * $producto['unidades']);
                                $um = $producto['unidad_minima'];
                            }


                            $table1->addRow(200);
                            $table1->addCell()->addText(htmlspecialchars($producto['ddproductoID']), 'rBasicos');
                            $table1->addCell()->addText(htmlspecialchars(strtoupper($producto['nombre']) . ($producto['importe'] == 0 ? ' --- BONIFICACION' : '')), 'rBasicos');
                            $table1->addCell()->addText($um, 'rBasicos');
                            $table1->addCell()->addText($cantidad, 'rBasicos');
                            $table1->addCell()->addText($producto['precioV'], 'rBasicos', 'totales');
                            $table1->addCell()->addText(0.00, 'rBasicos', 'totales');
                            $table1->addCell()->addText(ceil($producto['importe'] * 10) / 10, 'rBasicos', 'totales');
                        }
                    }


                    $table1->addRow(500)->addCell(null, array('gridSpan' => 6));
                    $table1->addCell()->addText(MONEDA . ceil($venta['subTotal'] * 10) / 10, 'rBasicos', 'totales');
                    $table1->addRow(500)->addCell(null, array('gridSpan' => 6));
                    $table1->addCell()->addText(MONEDA . ceil($venta['impuesto'] * 10) / 10, 'rBasicos', 'totales');
                    $table1->addRow(500)->addCell(null, array('gridSpan' => 6));
                    $table1->addCell()->addText(MONEDA . ceil($venta['montoTotal'] * 10) / 10, 'rBasicos', 'totales');


                    $table1->addRow(500);
                    $table1->addCell(null, array('gridSpan' => 7))->addText();
                    $table1->addRow()->addCell(null, array('gridSpan' => 7))->addText(numtoletras(ceil($venta['montoTotal'] * 10) / 10, 'rBasicos'));


                    $section->addPageBreak();
                }
            }
        }


        $file = 'Factura' . $id . '.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpword, 'Word2007');
        $xmlWriter->save("php://output");
    }

    /*
    public
    function verDocumentoFisal()
    {
        $idventa = $this->input->post('idventa');
        if ($idventa != FALSE) {
            $result = $this->venta_model->documentoVenta($idventa);
            $result['id_venta'] = $idventa;
            if ($result['ventas'][0]['descripcion'] == FACTURA) {
                $result['descripcion'] = FACTURA;
                $result['facturas'][0] = $result;
                $this->load->view('menu/ventas/visualizarVentas', $result);
            } else {
                $result['descripcion'] = 'BOLETA';
                $result['boletas'][0] = $result;
                $this->load->view('menu/ventas/visualizarVentasBoletas', $result);
            }

        }
    }
    */

    function comprobanteDiarioData()
    {
        $data = array();

        $id = $this->input->get('id');
        $reporte = $this->ComprobanteDiarioVentas->get_where(array('id_reporte' => $id));
        $fechadesde = $reporte['fecha_reporte'];
        $fechahasta = $reporte['fecha_reporte'];


        $data['fecha_desde'] = $fechadesde;
        $data['fecha_hasta'] = $fechahasta;
        $data['fecha_impreso'] = $reporte['fecha_generado'];

        $data['REGIMEN_CONTRIBUTIVO'] = $this->regimen_model->get_by(array('regimen_id' => $this->session->userdata('REGIMEN_CONTRIBUTIVO')));

        $condicic = array();
        $condicic['date(fecha) >='] = date('Y-m-d', strtotime($fechadesde));
        $condicic['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));

        $credito = $this->venta_model->get_total_solocredito($condicic);
        $totalventascredito = $credito['suma'];

        $data['totalventascredito'] = $totalventascredito;
        $data['credito'] = $credito;

        $data['abonosacarteraresult'] = $this->historial_pagos_clientes_model->getIngresosByCierreCaja(false, $fechadesde);


        $custom_query = " and date(venta_devolucion.fecha_devolucion) >= '" . date('Y-m-d', strtotime($fechadesde))
            . "' and date(venta_devolucion.fecha_devolucion) <='" . date('Y-m-d', strtotime($fechahasta)) . "'";
        //etos son a contado
        $data['calculodevoluciones'] = $this->venta_model->getSalidasByDevoluciones(false, $custom_query, false);


        //  $custom_query = " and date(venta_devolucion.fecha) >= '" . date('Y-m-d', strtotime($fechadesde)) . "' and date(venta_devolucion.fecha) <='" . date('Y-m-d', strtotime($fechahasta)) . "'";

        //$data['calculodevoluciones_dia'] = $this->venta_model->getSalidasByDevoluciones(false, $custom_query);


        // var_dump($data['calculodevoluciones'] );
        //estas son a credito
        $data['calculodevoluciones_credito'] = $this->venta_model->getSalidasByDevoluciones(false, $custom_query, true);

        $custom_query = " and date(dat_fecha_registro) >= '" . date('Y-m-d', strtotime($fechadesde)) . "' and date(dat_fecha_registro) <='" . date('Y-m-d', strtotime($fechahasta)) . "'";
        //etos son a contado
        $data['calculoanulaciones'] = $this->venta_model->getSalidasByAnulaciones(false, $custom_query);
        //estas son a credito
        $data['calculoanulaciones_credito'] = $this->venta_model->getSalidasByAnulaciones(false, $custom_query, true);

        $formaspagoa = $this->metodos_pago_model->get_all();
        $impuestos = $this->impuestos_model->get_impuestos();
        $grupos = $this->grupos_model->get_grupos();
        $data['formaspago'] = array();

        foreach ($formaspagoa as $formapago) {

            $totales = $formapago;
            $totales['totales'] = $this->StatusCajaModel->getTotalsIngresosByMetodoPago(
                $formapago['id_metodo'],
                $fechadesde,
                $fechahasta,
                $formapago['suma_total_ingreso'],
                $formapago['nombre_metodo'],
                false
            );
            array_push($data['formaspago'], $totales);
        }

        $condicionVenta['date(fecha) >='] = date('Y-m-d', strtotime($fechadesde));
        $condicionVenta['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));

        $data['totalesreales'] = $this->venta_model->get_totales_reales($condicionVenta);
        $data['totales_reales_backup'] = $this->venta_model->get_totales_reales_backup($condicionVenta);
        $data['totalventascondescuento'] = $this->venta_model->get_total_ventas_con_descuentos($condicionVenta);
        $data['impuestos'] = array();


        //echo $fechainicio;
        $condicionVenta = array(
            'venta_status' => COMPLETADO
        );
        $condicionVenta['date(fecha) >='] = date('Y-m-d', strtotime($fechadesde));
        $condicionVenta['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));


        foreach ($impuestos as $impuesto) {

            $total = $this->venta_model->getTotalesByImpuestos($impuesto['porcentaje_impuesto'], $condicionVenta);
            //   var_dump($total);
            $total_otros = $this->venta_model->getTotalesByOtroImpuestos($impuesto['id_impuesto'], $condicionVenta);
            $impuesto['totales'] = $total;
            $impuesto['totales_otros'] = $total_otros;
            //var_dump($impuesto['totales']);
            array_push($data['impuestos'], $impuesto);
        }


        $data['grupos'] = array();
        foreach ($grupos as $grupo) {

            $total = $this->venta_model->getTotalesByGrupo($grupo['id_grupo'], $condicionVenta);
            $grupo['totales'] = $total;
            array_push($data['grupos'], $grupo);
        }


        $last_venta = $this->venta_model->get_last($condicionVenta);
        $factura_fin = $last_venta['documento_Numero'];
        $data['factura_fin'] = $factura_fin;
        $first_venta = $this->venta_model->get_first($condicionVenta);
        $factura_inicio = $first_venta['documento_Numero'];
        $data['factura_inicio'] = $factura_inicio;
        $this->load->view('menu/ventas/comprobantediariodata', $data);
    }


    function comprobanteDiarioPrint()
    {

        try {
            $data = array();
            $fechadesde = $this->input->post('fecha_Desde');
            $id = $this->input->post('id');
            $insert = false;
            if (!is_numeric($id)) {
                $insert = $this->ComprobanteDiarioVentas->insert(array(
                    'fecha_reporte' => date('Y-m-d', strtotime($fechadesde)),
                    'fecha_generado' => date('Y-m-d H:i:s'),
                    'usuario_genero_reporte' => $this->session->userdata(USUARIO_SESSION),
                ));
            }

            if ($insert != false || is_numeric($id)) {

                $condicionVenta = array();
                $condicionVenta['date(fecha) >='] = date('Y-m-d', strtotime($fechadesde));
                $condicionVenta['date(fecha) <='] = date('Y-m-d', strtotime($fechadesde));


                $last_venta = $this->venta_model->get_last($condicionVenta);
                $factura_fin = $last_venta['documento_Numero'];

                $first_venta = $this->venta_model->get_first($condicionVenta);
                $factura_inicio = $first_venta['documento_Numero'];
                $totalventascondescuento = $this->venta_model->get_total_ventas_con_descuentos($condicionVenta);

                $data['fecha_desde'] = $fechadesde;
                $fecha_impreso = date('d-m-Y h:i A');
                $fecha_generado = date('d-m-Y h:i A');

                if (is_numeric($id)) {
                    $reporte = $this->ComprobanteDiarioVentas->get_where(array('id_reporte' => $id));
                    //$fecha_impreso = $reporte['fecha_generado'];
                    $fechadesde = $reporte['fecha_reporte'];
                    $fecha_generado = $reporte['fecha_generado'];
                } else {
                }

                $formaspago = $this->metodos_pago_model->get_all();


                $data['REGIMEN_CONTRIBUTIVO'] = $this->regimen_model->get_by(array('regimen_id' => $this->session->userdata('REGIMEN_CONTRIBUTIVO')));

                $printer = $this->receiptprint->connectUsb($this->session->userdata('IMPRESORA'), $this->session->userdata('USUARIO_IMPRESORA'), $this->session->userdata('PASSWORD_IMPRESORA'), $this->session->userdata('WORKGROUP_IMPRESORA'));
                /* Initialize */
                $printer->initialize();
                $printer->feed(1);

                $nombreempresa = $this->session->userdata('EMPRESA_NOMBRE');
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text($nombreempresa . " \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text('NIT ' . str_pad($this->session->userdata('NIT') . "\n", 10));

                $printer->text($data['REGIMEN_CONTRIBUTIVO']['regimen_nombre'] . " \n");
                $printer->text($this->session->userdata('EMPRESA_DIRECCION') . " \n");
                $printer->text('Telf:' . $this->session->userdata('EMPRESA_TELEFONO') . " \n");
                $printer->text("REPORTES/COMPROBANTEDIARIOVENTAS:");

                $printer->text(!empty($insert) ? $insert : $id);


                $printer->feed(1);
                $printer->text("----------------------------------------\n");

                $printer->text("FECHA:    " . date('d-m-Y h:i A', strtotime($fecha_generado)) . " \n");
                $printer->text("FECHA IMPRESION:    " . $fecha_impreso . " \n");
                $printer->feed(2);
                $printer->text("FACTURA :    " . $factura_inicio);
                $printer->text("  AL :    " . $factura_fin);
                $printer->feed(2);

                //RANGO REMISIONAL ?

                $printer->text(str_pad("Forma de pago", 20));
                $printer->text("Total");
                // $printer->text("Registro");
                $printer->feed(1);
                $printer->text("----------------------------------------\n");
                $totalefectivo = 0;
                $totalotros = 0;
                $totaldesceuntos = 0;


                $abonosacarteraresult = $this->historial_pagos_clientes_model->getIngresosByCierreCaja(false, $fechadesde);


                $totalgravado = 0;
                $totalexcluido = 0;
                $totaliva = 0;
                $totalotrosimpuestos = 0;

                foreach ($formaspago as $formapago) {
                    $printer->text(str_pad($formapago['id_metodo'], 2));
                    $printer->text(str_pad($formapago['nombre_metodo'], 20));
                    $totales = $this->StatusCajaModel->getTotalsIngresosByMetodoPago(
                        $formapago['id_metodo'],
                        $data['fecha_desde'],
                        $data['fecha_desde'],
                        $formapago['suma_total_ingreso'],
                        $formapago['nombre_metodo'],
                        false
                    );


                    $printer->text(str_pad(number_format(is_numeric($totales['total']) ? $totales['total'] : 0, 2, ',', '.'), 13));
                    $printer->text("(" . $totales['totalregistros'] . ")");

                    if ($formapago['suma_total_ingreso'] == 1) {
                        $totalefectivo = $totalefectivo + $totales['total'];
                    } else {
                        $totalotros = $totalotros + $totales['total'];
                    }
                    $totaldesceuntos = $totaldesceuntos + $totales['descuentos'];
                    $totalgravado = $totalgravado + $totales['gravado'];
                    $totalexcluido = $totalexcluido + $totales['excluido'];
                    $totaliva = $totaliva + $totales['iva'];
                    $totalotrosimpuestos = $totalotrosimpuestos + $totales['otros_impuestos'];
                    $printer->feed(1);
                }


                $credito = $this->venta_model->get_total_solocredito($condicionVenta);

                $totalventascredito = number_format($credito['suma'], 2, ',', '.');

                $printer->text(str_pad("", 2));
                $printer->text(str_pad("CREDITO", 20));
                $printer->text(str_pad($totalventascredito, 13));
                $printer->text("(" . $credito['registros'] . ")");
                $printer->feed(1);


                $custom_query = " and date(venta_devolucion.fecha_devolucion) >= '" . date('Y-m-d', strtotime($fechadesde)) .
                    "' and date(venta_devolucion.fecha_devolucion) <='" . date('Y-m-d', strtotime($fechadesde)) . "'";
                $calculodevoluciones = $this->venta_model->getSalidasByDevoluciones(false, $custom_query);
                $calculodevoluciones_credito = $this->venta_model->getSalidasByDevoluciones(false, $custom_query, true);

                //$calculodevoluciones = $calculodevoluciones['total'];

                $printer->text(str_pad("", 2));
                $printer->text(str_pad("DEVOLUCIONES", 20));
                $printer->text(str_pad(number_format(
                    $calculodevoluciones['total'] + $calculodevoluciones_credito['total'],
                    2,
                    ',',
                    '.'
                ), 13));

                $totregdev = $calculodevoluciones['registros'] + $calculodevoluciones_credito['registros'];
                $printer->text("(" . $totregdev . ")");
                $printer->feed(1);


                $custom_query = " and date(dat_fecha_registro) >= '" . date('Y-m-d', strtotime($fechadesde)) .
                    "' and date(dat_fecha_registro) <='" . date('Y-m-d', strtotime($fechadesde)) . "'";
                $calculoanulaciones = $this->venta_model->getSalidasByAnulaciones(false, $custom_query);
                $calculoanulaciones_credito = $this->venta_model->getSalidasByAnulaciones(false, $custom_query, true);


                $printer->text(str_pad("", 2));
                $printer->text(str_pad("ANULACIONES", 20));
                $printer->text(str_pad(number_format(
                    $calculoanulaciones['total'] + $calculoanulaciones_credito['total'],
                    2,
                    ',',
                    '.'
                ), 13));
                $totregan = $calculoanulaciones['registros'] + $calculoanulaciones_credito['registros'];
                $printer->text("(" . $totregan . ")");
                $printer->feed(1);


                $printer->text("----------------------------------------\n");

                $restar = $calculodevoluciones['total'] + $calculoanulaciones['total'];
                $restar_credito = $calculodevoluciones_credito['total'] + $calculoanulaciones_credito['total'];

                $efetivosupertotal = ($totalefectivo + $totalotros + $credito['suma']) - ($restar + $restar_credito);


                $totalefectivotot = ($totalefectivo + $abonosacarteraresult['efectivo']) - ($restar);


                $efetivosupertotal = number_format($efetivosupertotal, 2, ',', '.');
                $totalefectivotot = number_format($totalefectivotot, 2, ',', '.');

                $totaldesceuntos = number_format($totaldesceuntos, 2, ',', '.');
                $abonosacartera = number_format($abonosacarteraresult['total'], 2, ',', '.');

                $printer->text('TOTAL INGRESOS            ' . $efetivosupertotal . "\n");
                $printer->text('TOTAL EFECTIVO            ' . $totalefectivotot . "\n");
                $printer->text(str_pad('DESCUENTOS                ' . $totaldesceuntos, 20) . " (" . $totalventascondescuento['num'] . ") \n");
                $abononumregistros = $abonosacarteraresult['num'];
                $printer->text(str_pad('TOTAL ABONOS              ' . $abonosacartera, 20) . ' (' . $abononumregistros . ') ');
                $printer->text("\n");
                $printer->feed(1);


                $totales_reales_backup = $this->venta_model->get_totales_reales_backup($condicionVenta);


                /* $totalgravado = $totalgravado - ($calculoanulaciones['gravado'] + $calculodevoluciones['gravado'] + $calculodevoluciones_credito['gravado']
                         + $calculoanulaciones_credito['gravado']);
                 $totalgravado = $totalgravado + $credito['gravado'];*/

                /* $totalexcluido = $totalexcluido - ($calculoanulaciones['excluido'] + $calculodevoluciones['excluido'] + $calculoanulaciones_credito['excluido']
                         + $calculodevoluciones_credito['excluido']);
                 $totalexcluido = $totalexcluido + $credito['excluido'];*/
                /*$totaliva = $totaliva - ($calculoanulaciones['iva'] + $calculodevoluciones['iva']
                         + $calculoanulaciones_credito['iva'] + $calculodevoluciones_credito['iva']);
                 $totaliva = $totaliva + $credito['iva'];*/


                $totalgravado = $totales_reales_backup['gravado'] - ($calculoanulaciones['gravado'] + $calculodevoluciones['gravado']
                    + $calculoanulaciones_credito['gravado'] + $calculodevoluciones_credito['gravado']);


                $totalexcluido = $totales_reales_backup['excluido'] - ($calculoanulaciones['excluido'] + $calculodevoluciones['excluido']
                    + $calculoanulaciones_credito['excluido'] + $calculodevoluciones_credito['excluido']);
                $totaliva = $totales_reales_backup['iva'] - ($calculoanulaciones['iva'] + $calculodevoluciones['iva']
                    + $calculoanulaciones_credito['iva'] + $calculodevoluciones_credito['iva']);


                $totalotrosimpuestos = $totalotrosimpuestos - ($calculoanulaciones['otrosimpuestos'] + $calculodevoluciones['otrosimpuestos']);
                $totalotrosimpuestos = $totalotrosimpuestos + $credito['otros_impuestos'];

                $totalgravado = number_format($totalgravado, 2, ',', '.');
                $totalexcluido = number_format($totalexcluido, 2, ',', '.');
                $totaliva = number_format($totaliva, 2, ',', '.');
                $totalotrosimpuestos = number_format($totalotrosimpuestos, 2, ',', '.');

                $condicionVenta['date(fecha) >='] = date('Y-m-d', strtotime($fechadesde));
                $condicionVenta['date(fecha) <='] = date('Y-m-d', strtotime($fechadesde));

                //$totalesreales = $this->venta_model->get_totales_reales($condicionVenta);

                //$totalgravado = number_format(isset($totalesreales['gravado']) ? $totalesreales['gravado'] : 0, 2, ',', '.');
                //$totalexcluido = number_format(isset($totalesreales['excluido']) ? $totalesreales['excluido'] : 0, 2, ',', '.');
                //$totaliva = number_format(isset($totalesreales['iva']) ? $totalesreales['iva'] : 0, 2, ',', '.');
                //$totalotrosimpuestos = number_format(isset($totalesreales['otros_impuestos']) ? $totalesreales['otros_impuestos'] : 0, 2, ',', '.');


                $printer->text('TOTAL GRAVADO             ' . $totalgravado . "\n");
                $printer->text('TOTAL EXCLUIDO            ' . $totalexcluido . "\n");
                $printer->text('TOTAL IVA                 ' . $totaliva . "\n");
                $printer->text('TOTAL OTROS IMPUESTOS     ' . $totalotrosimpuestos . "\n");

                $printer->feed(1);


                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text(str_pad('FIRMA DEL RESPONSABLE', 20));

                $printer->feed(2);
                $printer->text(str_pad('-------------------', 20));
                $printer->feed(2);


                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $printer->text('LIQUIDACION DE IMPUESTO A LAS VENTAS');

                $printer->feed(1);
                $printer->text(str_pad("%", 10));
                $printer->text(str_pad("Gravado", 20));
                $printer->text("Iva");
                $printer->feed(1);
                $printer->text("----------------------------------------\n");
                $impuestos = $this->impuestos_model->get_impuestos();

                $totalventas = 0;
                $totaliva = 0;


                //echo $fechainicio;
                $condicionVenta = array(
                    'venta_status' => COMPLETADO
                );
                $condicionVenta['date(fecha) >='] = date('Y-m-d', strtotime($data['fecha_desde']));
                $condicionVenta['date(fecha) <='] = date('Y-m-d', strtotime($data['fecha_desde']));

                foreach ($impuestos as $impuesto) {

                    if ($impuesto['tipo_calculo'] == 'PORCENTAJE') {
                        $total = $this->venta_model->getTotalesByImpuestos($impuesto['porcentaje_impuesto'], $condicionVenta);
                        $impuesto['totales'] = $total;

                        $totgrav = isset($impuesto['totales']['subtotal']) ? $impuesto['totales']['subtotal'] : 0;
                        if ($totgrav > 0) {
                            $totdevgrav = isset($impuesto['totales']['devolucion_gravado']) ? $impuesto['totales']['devolucion_gravado'] : 0;
                            $totangrav = isset($impuesto['totales']['anulacion_gravado']) ? $impuesto['totales']['anulacion_gravado'] : 0;
                            $totgrav = $totgrav - ($totdevgrav + $totangrav);
                        }

                        if ($totgrav < 0) {
                            $totgrav = 0;
                        }
                        $totiva = isset($impuesto['totales']['iva']) ? $impuesto['totales']['iva'] : 0;
                        if ($totiva > 0) {
                            $totdeviva = isset($impuesto['totales']['devolucion_iva']) ? $impuesto['totales']['devolucion_iva'] : 0;
                            $totaniva = isset($impuesto['totales']['anulacion_iva']) ? $impuesto['totales']['anulacion_iva'] : 0;
                            $totiva = $totiva - ($totdeviva + $totaniva);
                        }
                        if ($totiva < 0) {
                            $totiva = 0;
                        }


                        $printer->text(str_pad(isset($impuesto['nombre_impuesto']) ? $impuesto['nombre_impuesto'] : '', 10));
                        $subtotimpuesto = number_format($totgrav, 2, ',', '.');

                        $printer->text(str_pad($subtotimpuesto, 20));
                        $ivamost = number_format($totiva, 2, ',', '.');
                        $printer->text($ivamost);

                        $totalventas = $totalventas + $totgrav;
                        $totaliva = $totaliva + $totiva;
                        $printer->feed(1);
                    }
                }
                $printer->text("----------------------------------------\n");

                $printer->text(str_pad("Total", 10));
                $totalventas = number_format($totalventas, 2, ',', '.');
                $totaliva = number_format($totaliva, 2, ',', '.');
                $printer->text(str_pad($totalventas, 20));
                $printer->text($totaliva);
                $printer->feed(3);


                $printer->text('OTROS IMPUESTOS');


                $printer->feed(1);
                $printer->text("----------------------------------------\n");


                $totalventas = 0;
                $totaliva = 0;
                $totalotros = 0;

                //echo $fechainicio;
                $condicionVenta = array(
                    'venta_status' => COMPLETADO
                );
                $condicionVenta['date(fecha) >='] = date('Y-m-d', strtotime($data['fecha_desde']));
                $condicionVenta['date(fecha) <='] = date('Y-m-d', strtotime($data['fecha_desde']));

                foreach ($impuestos as $impuesto) {
                    if ($impuesto['tipo_calculo'] == 'FIJO') {

                        $total = $this->venta_model->getTotalesByOtroImpuestos($impuesto['id_impuesto'], $condicionVenta);
                        $impuesto['totales_otros'] = $total;

                        $totgrav = isset($impuesto['totales_otros']['subtotal']) ? $impuesto['totales_otros']['subtotal'] : 0;
                        if ($totgrav > 0) {
                            $totdevgrav = isset($impuesto['totales_otros']['devolucion_gravado']) ? $impuesto['totales_otros']['devolucion_gravado'] : 0;
                            $totangrav = isset($impuesto['totales_otros']['anulacion_gravado']) ? $impuesto['totales_otros']['anulacion_gravado'] : 0;
                            $totgrav = $totgrav - ($totdevgrav + $totangrav);
                        }
                        if ($totgrav < 0) {
                            $totgrav = 0;
                        }

                        $totiva = isset($impuesto['totales_otros']['iva']) ? $impuesto['totales_otros']['iva'] : 0;
                        if ($totiva > 0) {
                            $totdeviva = isset($impuesto['totales_otros']['devolucion_iva']) ? $impuesto['totales_otros']['devolucion_iva'] : 0;
                            $totaniva = isset($impuesto['totales_otros']['anulacion_iva']) ? $impuesto['totales_otros']['anulacion_iva'] : 0;
                            $totiva = $totiva - ($totdeviva + $totaniva);
                        }
                        if ($totiva < 0) {
                            $totiva = 0;
                        }

                        $printer->text(str_pad(isset($impuesto['nombre_impuesto']) ? $impuesto['nombre_impuesto'] : '', 25));
                        $subtotimpuesto = number_format($impuesto['totales_otros']['subtotal'], 2, ',', '.');


                        $ivamost = number_format($totiva, 2, ',', '.');
                        $printer->text($ivamost);


                        // $totalventas = $totalventas + $impuesto['totales_otros']['subtotal'];
                        $totalotros = $totalotros + $totiva;
                        $printer->feed(1);
                    }
                }
                $printer->text("----------------------------------------\n");

                $printer->text(str_pad("Total", 25));
                // $totalventas = number_format($totalventas, 2, ',', '.');
                $totalotros = number_format($totalotros, 2, ',', '.');
                //   $printer->text(str_pad($totalventas, 20));
                $printer->text($totalotros);
                $printer->feed(3);


                $grupos = $this->grupos_model->get_grupos();


                $printer->text(str_pad('GrupoElo', 20));
                $printer->text(str_pad('GRAVADO', 10));
                $printer->text('IVA');
                $printer->feed(1);

                //echo $fechainicio;
                $condicionVenta = array(
                    'venta_status' => COMPLETADO
                );
                $condicionVenta['date(fecha) >='] = date('Y-m-d', strtotime($data['fecha_desde']));
                $condicionVenta['date(fecha) <='] = date('Y-m-d', strtotime($data['fecha_desde']));
                foreach ($grupos as $grupo) {

                    $total = $this->venta_model->getTotalesByGrupo($grupo['id_grupo'], $condicionVenta);
                    $grupo['totales'] = $total;
                    $printer->text(str_pad($grupo['nombre_grupo'], 23));

                    $subtotimpuesto = number_format($grupo['totales']['subtotal'], 2, ',', '.');

                    $printer->text(str_pad($subtotimpuesto, 10));

                    $ivamost = number_format($grupo['totales']['iva'], 2, ',', '.');
                    $printer->text($ivamost);
                    $printer->feed(1);
                }
                $printer->text("----------------------------------------\n");


                $printer->feed(5);
                $printer->cut(Printer::CUT_FULL, 10); //corta el papel
                $printer->pulse(); // abre la caja registradora
                /* Close printer */
                $printer->close();
                echo json_encode(array('result' => "success"));
            } else {
                log_message("error", "Error: Ourrio un error al crear el reporte");
                echo json_encode(array('result' => "Error: Ourrio un error al crear el reporte"));
            }
        } catch (Exception $e) {
            log_message("error", "Error: ha ocurrido un error " . $e->getMessage());
            echo json_encode(array('result' => "Error: ha ocurrido un error " . $e->getMessage() . "\n"));
            $this->receiptprint->close_after_exception();
        }
    }

    public
    function comprobantediarioventas()
    {
        $data = array();

        $data['historial'] = $this->ComprobanteDiarioVentas->get_all();
        $this->load->view('menu/reportes/comprobantediarioventas', $data);
    }


    public
    function verVentaJson()
    {
        $idventa = $this->input->post('idventa');

        if ($idventa != FALSE) {
            $result['ventas'] = $this->venta_model->obtener_venta($idventa);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }


    public
    function cargarCamion()
    {
        if ($this->input->post('id_consolidado') != "") {
            $id = $this->input->post('id_consolidado');
            $where = array('consolidado_id' => $id);
            $data['consolidado'] = $this->consolidado_model->get_consolidado_by($where);
        }
        $data['camiones'] = $this->camiones_model->get_all();
        $data['metros'] = $this->input->post('metros_c');
        $data['pedidos'] = $_POST["pedidos"];
        $this->load->view('menu/ventas/formCamiones', $data);
    }

    public
    function obtenerMetros()
    {
        $id_camion = $this->input->post('id_camion');
        $data['carga'] = $this->camiones_model->get_by('camiones_id', $id_camion);

        if ($this->input->is_ajax_request()) {
            // $msg = ['success' => 'un mensaje','error'=> $this->form->validation_errors()];
            //echo json_encode($msg);
            $metro_cubico = $data['carga']['metros_cubicos'];
            //$datos = ['capacidad' => $metro_cubico];
            echo $metro_cubico;
        }
    }


    function directPrintCotizar()
    {


        // try {


        $ventas = array();


        $detalles = json_decode($this->input->post('lst_producto', true));


        foreach ($detalles as $detalle) {

            $detallearray = (array) $detalle;

            $detallearray['fecha'] = date("Y-m-d H:i:s");
            $detallearray['documento_cliente'] = $this->input->post('id_cliente', true);
            $cliente = $this->cliente_model->get_by('id_cliente', $this->input->post('id_cliente', true));
            $producto = $this->pd->get_by('producto_id', $detallearray['id_producto']);


            $detallearray['producto_codigo_interno'] = $producto['producto_codigo_interno'];
            $detallearray['cliente'] = $cliente['nombres'] . ' ' . $cliente['apellidos'];
            $detallearray['direccion_cliente'] = $cliente['direccion'];
            $detallearray['id_vendedor'] = $this->input->post('id_vendedor',true);
            $detallearray['venta_tipo'] = $this->input->post('tipoventa');

            $detallearray['condicion_pago'] = $this->input->post('condicion_pago');
            $detallearray['venta_status'] = $this->input->post('venta_status', true);
            $detallearray['local_id'] = $this->session->userdata('id_local');

            $detallearray['subtotal'] = $this->input->post('subtotal', true);
            $detallearray['gravado'] = $this->input->post('basegravada', true);
            $detallearray['excluido'] = $this->input->post('excluido', true);
            $detallearray['total_impuesto'] = $this->input->post('iva', true);
            $detallearray['total'] = $this->input->post('totApagar', true);
            $detallearray['descuento_valor'] = $this->input->post('descuentoenvalor', true);
            $detallearray['descuento_porcentaje'] = $this->input->post('descuentoenporcentaje', true);

            $detallearray['importe'] = $this->input->post('dineroentregado', true);
            $detallearray['cambio'] = $this->input->post('cambio', true);
            $detallearray['diascondicionpagoinput'] = $this->input->post('diascondicionpagoinput', true);
            $detallearray['tipo_documento'] = $this->input->post('tipo_documento', true);
            $detallearray['REPRESENTANTE_LEGAL'] = $this->session->userdata('REPRESENTANTE_LEGAL');
            $detallearray['RazonSocialEmpresa'] = $this->session->userdata('EMPRESA_NOMBRE');
            $detallearray['NIT'] = $this->session->userdata('NIT');
            $detallearray['TelefonoEmpresa'] = $this->session->userdata('EMPRESA_TELEFONO');
            $detallearray['DireccionEmpresa'] = $this->session->userdata('EMPRESA_DIRECCION');
            $detallearray['REGIMEN_CONTRIBUTIVO'] = $this->regimen_model->get_by(array('regimen_id' => $this->session->userdata('REGIMEN_CONTRIBUTIVO')));
            $detallearray['REGIMEN_CONTRIBUTIVO'] = $detallearray['REGIMEN_CONTRIBUTIVO']['regimen_nombre'];


            $ventas[] = $detallearray;
        }


        $printer = $this->receiptprint->connectUsb(
            $this->session->userdata('IMPRESORA'),
            $this->session->userdata('USUARIO_IMPRESORA'),
            $this->session->userdata('PASSWORD_IMPRESORA'),
            $this->session->userdata('WORKGROUP_IMPRESORA')
        );
        /* Initialize */
        //$connector = new WindowsPrintConnector('generic');
        //$printer = new Printer($connector);

        $printer->initialize();


        $printer->feed(1);


        $printer->text(isset($ventas[0]['REPRESENTANTE_LEGAL']) ? strtoupper($ventas[0]['REPRESENTANTE_LEGAL']) . " \n" : '');
        $printer->text(isset($ventas[0]['RazonSocialEmpresa']) ? strtoupper($ventas[0]['RazonSocialEmpresa']) . " \n" : '');
        $printer->text('NIT ' . str_pad(isset($ventas[0]['NIT']) ? strtoupper($ventas[0]['NIT']) : '', 10));
        $printer->text('REGIMEN' . $this->session->userdata('REGIMEN_CONTRIBUTIVO') . " \n");
        $printer->text($this->session->userdata('EMPRESA_DIRECCION') . " \n");
        $printer->text('Telf:' . $this->session->userdata('EMPRESA_TELEFONO') . " \n");


        $printer->feed(1);
        $printer->text("----------------------------------------\n");


        $printer->text("COTIZACION DE MEDICAMENTOS");

        $printer->feed(1);
        $printer->text("----------------------------------------\n");

        if (
            isset($ventas[0]['documento_cliente']) && $ventas[0]['documento_cliente'] != ''
            && $ventas[0]['documento_cliente'] != null && !empty($ventas[0]['documento_cliente'])
        ) {
            $printer->text($ventas[0]['documento_cliente'] . " \n");
            $printer->text($ventas[0]['cliente'] . " " . $ventas[0]['apellidos'] . " \n");
            $printer->text($ventas[0]['direccion_cliente'] . " \n");

            $printer->feed(1);
            $printer->text("----------------------------------------\n");
        }

        //  $printer->text(" Venta Nº:");
        //$printer->text(str_pad(isset($ventas[0]['resolucion_prefijo']) ? isset($ventas[0]['resolucion_prefijo']) : '' . isset($ventas[0]['numero']) ? $ventas[0]['numero'] : '', 10));


        $printer->text(str_pad("Fecha", 12));
        $printer->text(str_pad("Hora", 10));
        $printer->text(str_pad("Cajero", 10));

        $printer->feed(1);
        $printer->text(str_pad(date('d/m/Y'), 12));
        $printer->text(str_pad(date('h:i A'), 10));
        $printer->text(str_pad($this->input->post('cajero'), 10));

        $printer->feed(1);
        $printer->text("Vendedor");
        $printer->feed(1);
        $printer->text($this->session->userdata('VENDEDOR_EN_FACTURA') == "CODIGO" ? $this->input->post('id_vendedor') :
            $this->usuario_model->getUserReturnName($this->input->post('id_vendedor')) . "\n");

        $printer->text("----------------------------------------\n");


        $total_excluidos = 0;
        $total_gravado = 0;
        $totales_impuestos = array();
        foreach ($ventas as $venta) {


            foreach ($venta['unidades'] as $detalle_unidad) {
                $detalle_unidad = (array) $detalle_unidad;

                if ($venta['porcentaje_impuesto'] > 0) {
                    $total_gravado = $total_gravado + $venta['impuesto'];
                } else {
                    $total_excluidos = $total_excluidos + $venta['impuesto'];
                }

                if ($detalle_unidad['cantidad'] > 0) {
                    $um = isset($detalle_unidad['abreviatura']) ? $detalle_unidad['abreviatura'] : $detalle_unidad['nombre_unidad'];
                    $detalle_unidad['cantidad'];
                    $cantidad = $detalle_unidad['cantidad'];


                    $subtotal = ($cantidad * $detalle_unidad['precio']);


                    if (isset($venta['id_impuesto'])) {

                        $actual_impuesto = isset($totales_impuestos[$venta['id_impuesto']]) ? $totales_impuestos[$venta['id_impuesto']] : 0;
                        $totales_impuestos[$venta['id_impuesto']] = $actual_impuesto + $detalle_unidad['impuesto'];
                    }

                    if (isset($venta['id_otro_impuesto'])) {

                        $actual_impuesto = isset($totales_impuestos[$venta['id_otro_impuesto']]) ? $totales_impuestos[$venta['id_otro_impuesto']] : 0;
                        $totales_impuestos[$venta['id_otro_impuesto']] = $actual_impuesto + $detalle_unidad['otro_impuesto'];
                    }

                    $item = new item($venta['producto_codigo_interno'], $venta['nombre'], $detalle_unidad['precio'], '$', $cantidad, $subtotal, $um);

                    $printer->text($item);
                }
            }
        }


        $printer->text("----------------------------------------\n");


        if ($this->session->userdata('REGIMEN_CONTRIBUTIVO') == 1) {
            $printer->text("Sub Total        -> " . MONEDA . " " . number_format($this->input->post('subtotal'), 2, ',', '.') . "\n");
        } else {
            $printer->text("Sub Total        -> " . MONEDA . " " . number_format($this->input->post('totApagar'), 2, ',', '.') . "\n");
        }

        $printer->text("Descuento        -> " . MONEDA . " " . number_format($ventas[0]['descuento_valor'] + $ventas[0]['descuento_porcentaje'], 2, ',', '.') . "\n");
        if ($this->session->userdata('REGIMEN_CONTRIBUTIVO') == 1) {
            $printer->text("Excluido         -> " . MONEDA . " " . number_format($this->input->post('excluido'), 2, ',', '.') . "\n");
            $printer->text("Gravado          -> " . MONEDA . " " . number_format($this->input->post('gravado'), 2, ',', '.') . "\n");
            $printer->text("Total IVA        -> " . MONEDA . " " . number_format($this->input->post('iva'), 2, ',', '.') . "\n");
            $printer->text("TOTAL FACTURA    -> " . MONEDA . " " . number_format($this->input->post('totApagar'), 2, ',', '.') . "\n");
        }
        // $printer->text("VALOR ENTREGADO  -> " . MONEDA . " " . number_format($ventas[0]['pagado'], 2, ',', '.') . "\n");
        //$printer->text("CAMBIO           -> " . MONEDA . " " . number_format($ventas[0]['cambio'], 2, ',', '.') . "\n");

        $printer->feed(1);


        $printer->text($this->session->userdata('MENSAJE_FACTURA'));


        $printer->feed(5);
        $printer->cut(Printer::CUT_FULL, 3); //corta el papel
        // $printer->pulse(); // abre la caja registradora
        /* Close printer */
        $printer->close();


        echo json_encode(array('result' => "success"));

        /*  } catch
          (Exception $e) {
              log_message("error", "Error: Could not print. Message " . $e->getMessage());
              echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
              $this->receiptprint->close_after_exception();
          }*/
    }

    function directPrintCotizarPdf()
    {


        $ruta = '.' . RUTA_COTIZACIONES;

        if (!is_dir('./uploads/')) {
            mkdir('./uploads/', 0775);
        }
        if (!is_dir($ruta)) {
            mkdir($ruta, 0775);

        }

        $ventas = array();

        $detalles = json_decode($this->input->post('lst_producto', true));



        $cliente_id = $this->input->post('id_cliente', true);
        $descuvalor = $this->input->post('descuentoenvalor', true);
        $descuvalorhiden = $this->input->post('descuentoenvalorhidden', true);
        $descuporc = $this->input->post('descuentoenporcentaje', true);
        $descuporchiden = $this->input->post('descuentoenporcentajehidden', true);

        $descuentoporcentajetotal = 0;
        if (!empty($descuporc)) {
            $descuentoporcentajetotal = (($this->input->post('subtotal', true) + $this->input->post('iva', true)) * $descuporc) / 100;
        }

        $totaldescuento=0;
        $regimen = $this->regimen_model->get_by(array('regimen_id' => $this->session->userdata('REGIMEN_CONTRIBUTIVO')));


        /**********************************************/
        $fe_resolution_id = $this->input->post('fe_resolution_id');
        $status=$this->input->post('venta_status');
        //si la venta esta en espera, o si es una factura electronica, no generamos resolucion
        if ($status != ESPERA && empty($fe_resolution_id)) {
            $resolucion = $this->venta_model->generarnumeroFactura($this->input->post('documento_generar', true));
        } else {
            $resolucion = NULL;
        }
        $resolucion_dian=false;
        if ($resolucion === false) {
            $resultado = NUMERACION_ERROR;
        } else {

            if ($status != ESPERA) {

                $resolucion_dian = $this->resolucion_model->get_by(array('resolucion_id'=>$resolucion['id_resolucion']));

            } else {

            }
        }
        /************************************************************/


        foreach ($detalles as $detalle) {

            $detallearray = (array) $detalle;

            $detallearray['fecha'] = date("Y-m-d H:i:s");

            $cliente = $this->cliente_model->get_by('id_cliente', $this->input->post('id_cliente', true));
            $producto = $this->pd->get_by('producto_id', $detallearray['id_producto']);
            $detallearray['documento_cliente'] = $cliente['identificacion'];
            $detallearray['celular'] = $cliente['celular'];
            $detallearray['telefono'] = $cliente['telefono'];

            $detallearray['producto_codigo_interno'] = $producto['producto_codigo_interno'];
            $detallearray['cliente'] = $cliente['nombres'] . ' ' . $cliente['apellidos'];
            $detallearray['direccion_cliente'] = $cliente['direccion'];
            $detallearray['id_vendedor'] = $this->input->post('id_vendedor');
            $detallearray['venta_tipo'] = $this->input->post('tipoventa');

            $detallearray['condicion_pago'] = $this->input->post('condicion_pago');
            $detallearray['venta_status'] = $this->input->post('venta_status', true);
            $detallearray['local_id'] = $this->session->userdata('id_local');

            $detallearray['importe'] = $this->input->post('dineroentregado', true);
            $detallearray['cambio'] = $this->input->post('cambio', true);
            $detallearray['diascondicionpagoinput'] = $this->input->post('diascondicionpagoinput', true);
            $detallearray['tipo_documento'] = $this->input->post('tipo_documento', true);
            $detallearray['REPRESENTANTE_LEGAL'] = $this->session->userdata('REPRESENTANTE_LEGAL');
            $detallearray['RazonSocialEmpresa'] = $this->session->userdata('EMPRESA_NOMBRE');
            $detallearray['NIT'] = $this->session->userdata('NIT');
            $detallearray['TelefonoEmpresa'] = $this->session->userdata('EMPRESA_TELEFONO');
            $detallearray['DireccionEmpresa'] = $this->session->userdata('EMPRESA_DIRECCION');
            $detallearray['REGIMEN_CONTRIBUTIVO'] = $regimen['regimen_nombre'];

            $totaldescuento = $totaldescuento+$detalle->descuento;
            $ventas[] = $detallearray;

            $ventas[0]['totaldescuento']=$totaldescuento;
            $ventas[0]['descuento_valor']= (empty($descuvalor) or $descuvalor == 0) ? $descuvalorhiden : $descuvalor;
            $ventas[0]['descuento_valor'] = $ventas[0]['descuento_valor']==''?0:$ventas[0]['descuento_valor'];
            $ventas[0]['descuento_porcentaje']=$descuentoporcentajetotal;
            $ventas[0]['excluido']=$this->input->post('excluido', true);
            $ventas[0]['gravado']=$this->input->post('basegravada', true);
            $ventas[0]['regimen_iva']=$regimen['genera_iva'];
            $ventas[0]['subTotal']=$this->input->post('subtotal', true);
            $ventas[0]['montoTotal']=$this->input->post('totApagar', true);
            $ventas[0]['impuesto']=$this->input->post('iva', true);
            $ventas[0]['total_otros_impuestos']= $this->input->post('otros_impuestos', true);

        }
        ////////////////////////////


        $resolucion_prefijo = isset($ventas[0]['resolucion_prefijo']) ? $ventas[0]['resolucion_prefijo'] . "-" : '';
        $numero_factura = isset($ventas[0]['numero']) ? $ventas[0]['numero'] : '';
        $file_name= 'cotizacion-'.date('d-m-Y-H:i:s').'.pdf';
        $data = array(
            'invoice_title' => '',
            'user_company' => $this->session->userdata('EMPRESA_NOMBRE'),
            'user_address' =>  $this->session->userdata('EMPRESA_DIRECCION'),
            'rep_legal' =>  $this->session->userdata('REPRESENTANTE_LEGAL'),
            'nit' => $this->session->userdata('NIT'),
            'telf_empresa' => $this->session->userdata('EMPRESA_TELEFONO'),
            'regimen' => isset($ventas[0]['REGIMEN_CONTRIBUTIVO']) ? strtoupper($ventas[0]['REGIMEN_CONTRIBUTIVO']) : '',

            'date' => date('d-m-Y', strtotime($ventas[0]['fecha'])),
            'hora' => date('H:i:d', strtotime($ventas[0]['fecha'])),
            'due_date' => date('d-m-Y', strtotime($ventas[0]['fecha'])),

            'current_date' => date('d-m-Y'),
            'invoice_numb' =>'Cotizar',
            'client_cedula' => isset($ventas[0]['documento_cliente']) ? strtoupper($ventas[0]['documento_cliente']) : '',
            'client_name' => $ventas[0]['cliente'],
            'address1' => isset($ventas[0]['direccion_cliente']) ? strtoupper($ventas[0]['direccion_cliente']) : '',
            'ventas' => $ventas,
            'cajero_id' =>$this->session->userdata('cajero_id'),
            'vendedor' => $this->session->userdata('VENDEDOR_EN_FACTURA') == "CODIGO" ? $ventas[0]['id_vendedor'] :
                $this->usuario_model->getUserReturnName($ventas[0]['id_vendedor']),
            'mensaje_factura' => $this->session->userdata('MENSAJE_FACTURA'),
            'ruta'=>$ruta,
            'file_name'=>$file_name,
            'resolucion_numero'=>$resolucion_dian!==false?$resolucion_dian['resolucion_numero']:'',
            'resolucion_fech_aprobacion'=>$resolucion_dian!==false?$resolucion_dian['resolucion_fech_aprobacion']:'',
            'resolucion_prefijo'=>$resolucion_dian!==false?$resolucion_dian['resolucion_prefijo']:'',
            'resolucion_numero_inicial'=>$resolucion_dian!==false?$resolucion_dian['resolucion_numero_inicial']:'',
            'resolucion_numero_final'=>$resolucion_dian!==false?$resolucion_dian['resolucion_numero_final']:'',

        );
        $data['page_title'] = 'COTIZACIÓN';

        if (!empty($ventas[0]['celular'])) {
            $data['cel_client '] = $ventas[0]['celular'];
        }
        if (!empty($ventas[0]['telefonoC1'])) {
            $data['fijo_client '] = $ventas[0]['telefonoC1'];
        }


        if (
            isset($ventas[0]['documento_cliente']) && $ventas[0]['documento_cliente'] != ''
            && $ventas[0]['documento_cliente'] != null && !empty($ventas[0]['documento_cliente'])
        ) {
            $data['client'] = true;
        } else {
            $data['client'] = false;
        }

        $this->load->view('reportesPdf/cotizacion_pdf', $data);
        $json=array('success' => true, 'file_name'=>RUTA_COTIZACIONES.$file_name);
        echo json_encode($json);
    }

    function directPrint()
    {


        try {


            $idventa = $this->input->post('idventa');
            $devolucion = $this->input->post('devolucion', true);
            $id_devolucion = $this->input->post('id_devolucion');
            $from_historial = $this->input->post('from_historial'); //variable que indica si estoy mandando a imprimir desde
            // el historial de ventas, si es asi, imprimo solo 1 copia siempre y debe decir COPIA

            if (!empty($id_devolucion) && $id_devolucion != "") {


                $this->directPrintDevolucion($id_devolucion, $idventa);
            } else {

                $result['ventas'] = array();
                if ($idventa != FALSE) {
                    $ventas = $this->venta_model->obtener_venta($idventa);

                    if ($from_historial == 1) {
                        $ventas[0]['numero_copias'] = 1;
                    }
                    for ($i = 1; $i <= $ventas[0]['numero_copias']; $i++) {

                        $impuestos = $this->impuestos_model->get_impuestos();

                        if (isset($ventas[0])) {
                            $printer = $this->receiptprint->connectUsb(
                                $this->session->userdata('IMPRESORA'),
                                $this->session->userdata('USUARIO_IMPRESORA'),
                                $this->session->userdata('PASSWORD_IMPRESORA'),
                                $this->session->userdata('WORKGROUP_IMPRESORA'),
                                $this->session->userdata('SISTEMA_OPERATIVO')
                            );


                            // $printer = $this->receiptprint->connectEthernet('192.168.0.21', 9100);

                            $printer->initialize();
                            //   $printer->text("Linea 1\n");
                            //  $printer->feed(4);
                            // $printer->text("Linea 2\n");
                            // $printer->feed(5);

                            //$printer->cut(Printer::CUT_FULL, 10); //corta el papel
                            // $printer->pulse(); // abre la caja registradora*/

                            // $printer->close();


                            /*  $connector = new WindowsPrintConnector('1P_PrintServ8E7\\tcpip');
                               $printer = new Printer($connector);*/

                            $printer->feed(1);

                            $printer->text(isset($ventas[0]['REPRESENTANTE_LEGAL']) ? strtoupper($ventas[0]['REPRESENTANTE_LEGAL']) . " \n" : '');
                            $printer->text(isset($ventas[0]['RazonSocialEmpresa']) ? strtoupper($ventas[0]['RazonSocialEmpresa']) . " \n" : '');
                            $printer->text('NIT ' . str_pad(isset($ventas[0]['NIT']) ? strtoupper($ventas[0]['NIT']) : '', 10));
                            $printer->text('REGIMEN' . isset($ventas[0]['REGIMEN_CONTRIBUTIVO']) ? strtoupper($ventas[0]['REGIMEN_CONTRIBUTIVO']) . " \n" : '');
                            $printer->text(isset($ventas[0]['DireccionEmpresa']) ? $ventas[0]['DireccionEmpresa'] . " \n" : '');
                            $printer->text('Telf:' . isset($ventas[0]['TelefonoEmpresa']) ? $ventas[0]['TelefonoEmpresa'] . " \n" : '');


                            $printer->feed(1);
                            $printer->text("----------------------------------------\n");

                            if (isset($ventas[0]['dias']) && $ventas[0]['dias'] != 0) {
                                $printer->text("VENTA A CREDITO");

                                $printer->feed(1);
                                $printer->text("----------------------------------------\n");
                            }

                            if (isset($ventas[0]['genera_control_domicilios']) && $ventas[0]['genera_control_domicilios'] == 1) {
                                $printer->text("VENTA A DOMICILIO");

                                $printer->feed(1);
                                $printer->text("----------------------------------------\n");
                            }


                            if (
                                isset($ventas[0]['documento_cliente']) && $ventas[0]['documento_cliente'] != ''
                                && $ventas[0]['documento_cliente'] != null && !empty($ventas[0]['documento_cliente'])
                            ) {
                                $printer->text($ventas[0]['documento_cliente'] . " \n");
                                $printer->text($ventas[0]['cliente'] . " " . $ventas[0]['apellidos'] . " \n");
                                $printer->text($ventas[0]['direccion_cliente'] . " \n");
                                $printer->text($ventas[0]['zona_nombre'] . " \n");
                                if (!empty($ventas[0]['celular'])) {
                                    $printer->text("Celular: " . $ventas[0]['celular'] . " \n"); //mostrar
                                }
                                if (!empty($ventas[0]['telefonoC1'])) {
                                    $printer->text("Fijo: " . $ventas[0]['telefonoC1'] . " \n"); //mostrar
                                }

                                $printer->feed(1);
                                $printer->text("----------------------------------------\n");
                            }


                            $printer->text("FACTURA DE VENTA Nº:");
                            $printer->text(isset($ventas[0]['resolucion_prefijo']) ? $ventas[0]['resolucion_prefijo'] . "-" : '');
                            $printer->text(str_pad(isset($ventas[0]['numero']) ? $ventas[0]['numero'] : '', 10));
                            $printer->feed(1);

                            $printer->text(str_pad("Fecha", 12));
                            $printer->text(str_pad("Hora", 10));
                            $printer->text(str_pad("Cajero", 10));

                            $printer->feed(1);
                            $printer->text(str_pad(date('d/m/Y', strtotime($ventas[0]['fechaemision'])), 12));
                            $printer->text(str_pad(date('h:i A', strtotime($ventas[0]['fechaemision'])), 10));
                            $printer->text(str_pad(isset($ventas[0]['cajero_id']) ? $ventas[0]['cajero_id'] : '', 10));


                            $printer->feed(1);
                            $printer->text("Vendedor");
                            $printer->feed(1);
                            $printer->text($this->session->userdata('VENDEDOR_EN_FACTURA') == "CODIGO" ? $ventas[0]['id_vendedor'] :
                                $this->usuario_model->getUserReturnName($ventas[0]['id_vendedor']) . "\n");

                            $printer->text("----------------------------------------\n");


                            if (isset($ventas[0]['dias']) && $ventas[0]['dias'] != 0) {
                                $fecha_inicio_plan = date('Y-m-d H:i:s');
                                $fecha_fin_plan = new DateTime($fecha_inicio_plan);
                                date_add($fecha_fin_plan, date_interval_create_from_date_string($ventas[0]['dias'] . ' day'));

                                $fecha_fin_plan = date_format($fecha_fin_plan, 'd/m/Y');
                                $printer->text("VENCE:  ");
                                $printer->text($fecha_fin_plan);
                                $printer->feed(1);
                                $printer->text("Vencimiento " . $ventas[0]['dias'] . "a partir de la fecha \n");

                                $printer->text("----------------------------------------\n");
                            }
                            $total_excluidos = 0;
                            $total_gravado = 0;
                            $totales_impuestos = array();
                            foreach ($ventas as $venta) {


                                foreach ($venta['detalle_unidad'] as $detalle_unidad) {
                                    if ($detalle_unidad['cantidad'] > 0) {
                                        $um = isset($detalle_unidad['abreviatura']) ? $detalle_unidad['abreviatura'] : $detalle_unidad['nombre_unidad'];
                                        $detalle_unidad['cantidad'];
                                        $cantidad = $detalle_unidad['cantidad'];


                                        $subtotal = ($cantidad * $detalle_unidad['precio']);

                                        if ($venta['porcentaje_impuesto'] > 0) {
                                            $total_gravado = $total_gravado + $detalle_unidad['impuesto'];
                                        } else {
                                            $total_excluidos = $total_excluidos + $detalle_unidad['impuesto'];
                                        }

                                        if (isset($venta['id_impuesto'])) {

                                            $actual_impuesto = isset($totales_impuestos[$venta['id_impuesto']]) ? $totales_impuestos[$venta['id_impuesto']] : 0;
                                            $totales_impuestos[$venta['id_impuesto']] = $actual_impuesto + $detalle_unidad['impuesto'];
                                        }

                                        if (isset($venta['id_otro_impuesto'])) {

                                            $actual_impuesto = isset($totales_impuestos[$venta['id_otro_impuesto']]) ? $totales_impuestos[$venta['id_otro_impuesto']] : 0;
                                            $totales_impuestos[$venta['id_otro_impuesto']] = $actual_impuesto + $detalle_unidad['otro_impuesto'];
                                        }


                                        $item = new item(
                                            $venta['producto_codigo_interno'],
                                            $venta['nombre'],
                                            $detalle_unidad['precio'],
                                            '$',
                                            $cantidad,
                                            $subtotal,
                                            $um
                                        );

                                        $printer->text($item);
                                        /* $printer->text($venta['producto_codigo_interno'] . " \t");
                                         $printer->text($venta['nombre'] . " \n");
                                         $printer->text($cantidad . " \t");
                                         $printer->text($detalle_unidad['precio'] . " \t");
                                         $printer->text($subtotal . " \t");
                                         $printer->text($um . " \t");*/
                                    }
                                }
                            }


                            $printer->text("----------------------------------------\n");


                            $totaldescuentostablaveta = $ventas[0]['descuento_valor'] + $ventas[0]['descuento_porcentaje'];

                            $desct = $totaldescuentostablaveta > 0 ? $totaldescuentostablaveta : $ventas[0]['totaldescuento'];
                            $mostrardescuento = MONEDA . " " . number_format($desct, 2, ',', '.');

                            if ($ventas[0]['regimen_iva'] == 1) {
                                $printer->text("Sub Total        -> " . MONEDA . " " . number_format($ventas[0]['subTotal'], 2, ',', '.') . "\n");
                            } else {
                                $printer->text("Sub Total        -> " . MONEDA . " " . number_format($ventas[0]['montoTotal'] + $desct, 2, ',', '.') . "\n");
                            }
                            $printer->text("Descuento        -> " . $mostrardescuento . "\n");
                            if ($ventas[0]['regimen_iva'] == 1) {
                                $printer->text("Excluido         -> " . MONEDA . " " . number_format($ventas[0]['excluido'], 2, ',', '.') . "\n");
                                $printer->text("Gravado          -> " . MONEDA . " " . number_format($ventas[0]['gravado'], 2, ',', '.') . "\n");
                                $printer->text("Total IVA        -> " . MONEDA . " " . number_format($ventas[0]['impuesto'], 2, ',', '.') . "\n");
                                $printer->text("Otros impuestos  -> " . MONEDA . " " . number_format($ventas[0]['total_otros_impuestos'], 2, ',', '.') . "\n");
                            }
                            $printer->text("TOTAL FACTURA    -> " . MONEDA . " " . number_format($ventas[0]['montoTotal'], 2, ',', '.') . "\n");
                            $printer->text("VALOR ENTREGADO  -> " . MONEDA . " " . number_format($ventas[0]['pagado'], 2, ',', '.') . "\n");
                            $printer->text("CAMBIO           -> " . MONEDA . " " . number_format($ventas[0]['cambio'], 2, ',', '.') . "\n");

                            $printer->feed(1);
                            if ($ventas[0]['regimen_iva'] == 1) {
                                foreach ($impuestos as $impuesto) {
                                    $printer->text(str_pad($impuesto['nombre_impuesto'], 20));
                                    $printer->text(number_format(isset($totales_impuestos[$impuesto['id_impuesto']])
                                        ? $totales_impuestos[$impuesto['id_impuesto']] : 0, 2, ',', '.'));
                                    $printer->feed(1);
                                }
                                $printer->feed(1);
                            }

                            if ($ventas[0]['regimen_iva'] == 1) {
                                $printer->setJustification(Printer::JUSTIFY_CENTER);
                                $printer->text("AUTORIZACION NUMERACION SEGUN RESOLUCION No " . $ventas[0]['resolucion_numero'] . " del " . $ventas[0]['resolucion_fech_aprobacion'] . "\n");

                                $printer->text("DEL " . $ventas[0]['resolucion_prefijo'] . "-" . $ventas[0]['resolucion_numero_inicial'] . " AL " . $ventas[0]['resolucion_prefijo'] . "-" . $ventas[0]['resolucion_numero_final'] . "\n");
                                $printer->feed(1);
                            }

                            $printer->text($this->session->userdata('MENSAJE_FACTURA'));

                            if ($this->session->userdata('MOSTRAR_PROSODE') == true) {
                                $printer->feed(3);

                                $printer->setJustification(Printer::JUSTIFY_CENTER);
                                $printer->setTextSize(2, 2);
                                /**
                                 * lo siguiente se comenta el 25/04/2020, y se pone lo de Bienes exentos,
                                 * ya que hay muchos clientes que lo piden a soliticud de gionvani
                                 */
                                /*
                                $printer->text('SID - Un producto de PROSODE SAS');
                                $printer->feed(1);
                                $printer->text('www.prosode.com');
                                */
                                $printer->text('Bienes Exentos');
                                $printer->feed(1);
                                $printer->text('Decreto 417 del 17 de marzo de 2020');
                            }
                            if ($from_historial == 1) {
                                $printer->feed(1);
                                $printer->text('COPIA');
                            }


                            $printer->feed(5);


                            $printer->cut(Printer::CUT_FULL, 10); //corta el papel
                            $printer->pulse(); // abre la caja registradora
                            /* Close printer */
                            $printer->close();
                        }
                    }
                }


                echo json_encode(array('result' => "success"));
            }
        } catch (Exception $e) {
            log_message("error", "Error: Could not print. Message " . $e->getMessage());
            echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
            $this->receiptprint->close_after_exception();
        }
    }


    function abrirCajaregistradora()
    {


        try {


            $idventa = $this->input->post('idventa');
            $devolucion = $this->input->post('devolucion', true);
            $id_devolucion = $this->input->post('id_devolucion');
            $from_historial = $this->input->post('from_historial'); //variable que indica si estoy mandando a imprimir desde
            // el historial de ventas, si es asi, imprimo solo 1 copia siempre y debe decir COPIA

            if (!empty($id_devolucion) && $id_devolucion != "") {


                // $this->directPrintDevolucion($id_devolucion, $idventa);

            } else {

                $result['ventas'] = array();
                if ($idventa != FALSE) {

                    $printer = $this->receiptprint->connectUsb(
                        $this->session->userdata('IMPRESORA'),
                        $this->session->userdata('USUARIO_IMPRESORA'),
                        $this->session->userdata('PASSWORD_IMPRESORA'),
                        $this->session->userdata('WORKGROUP_IMPRESORA'),
                        $this->session->userdata('SISTEMA_OPERATIVO')
                    );


                    // $printer = $this->receiptprint->connectEthernet('192.168.0.21', 9100);

                    $printer->initialize();


                    $printer->pulse(); // abre la caja registradora
                    /* Close printer */
                    $printer->close();
                }


                echo json_encode(array('result' => "success"));
            }
        } catch (Exception $e) {
            log_message("error", "Error: Could not print. Message " . $e->getMessage());
            echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
            $this->receiptprint->close_after_exception();
        }
    }


    function directPrintDevolucion($id_devolucion = false, $idventa = false)
    {

        if ($id_devolucion == false) {
            $id_devolucion = $this->input->post('id_devolucion');
        }

        if ($idventa == false) {
            $idventa = $this->input->post('idventa');
        }

        try {


            $result['ventas'] = array();
            if ($idventa != FALSE) {
                $ventas = $this->venta_model->obtener_venta($idventa);
                $detalle_devolucion = $this->venta_model->detalle_devolucion_venta($id_devolucion);


                if (isset($ventas[0])) {
                    $printer = $this->receiptprint->connectUsb($this->session->userdata('IMPRESORA'), $this->session->userdata('USUARIO_IMPRESORA'), $this->session->userdata('PASSWORD_IMPRESORA'), $this->session->userdata('WORKGROUP_IMPRESORA'));
                    /* Initialize */
                    $printer->initialize();


                    $printer->feed(1);


                    $printer->feed(1);
                    $printer->text("----------------------------------------\n");


                    $printer->text("NOTA DE DEVOLUCION DE PRODUCTOS");

                    $printer->feed(1);


                    $printer->text(str_pad("Fecha", 12));
                    $printer->text(str_pad("Hora", 10));
                    $printer->text(str_pad("Cajero", 10));

                    $printer->feed(1);
                    $printer->text(str_pad(date('d/m/Y', strtotime($ventas[0]['fechaemision'])), 12));
                    $printer->text(str_pad(date('h:i A', strtotime($ventas[0]['fechaemision'])), 10));
                    $printer->text(str_pad(isset($ventas[0]['cajero_id']) ? $ventas[0]['cajero_id'] : '', 10));

                    $printer->feed(1);
                    $printer->text(" FACTURA DE VENTA Nº:");
                    $printer->text(
                        str_pad(
                            (isset($ventas[0]['resolucion_prefijo']) 
                            ? isset($ventas[0]['resolucion_prefijo']) 
                            : '' . isset($ventas[0]['numero']) ) 
                            ? $ventas[0]['numero'] : ''
                        , 10)
                    );
                    $printer->feed(1);


                    $printer->text("----------------------------------------\n");


                    if (isset($ventas[0]['dias']) && $ventas[0]['dias'] != 0) {
                        $fecha_inicio_plan = date('Y-m-d H:i:s');
                        $fecha_fin_plan = new DateTime($fecha_inicio_plan);
                        date_add($fecha_fin_plan, date_interval_create_from_date_string($ventas[0]['dias'] . ' day'));

                        $fecha_fin_plan = date_format($fecha_fin_plan, 'd/m/Y');
                        $printer->text("VENCE:  ");
                        $printer->text($fecha_fin_plan);
                        $printer->feed(1);
                        $printer->text("Vencimiento " . $ventas[0]['dias'] . "a partir de la fecha \n");

                        $printer->text("----------------------------------------\n");
                    }

                    $subtotal_total=0;
                    $total_total=0;
                    $totales_impuestos = array();
                    // var_dump($detalle_devolucion);
                    foreach ($detalle_devolucion as $detalle) {


                        if ($detalle->cantidad > 0) {
                            $um = isset($detalle->abreviatura) ? $detalle->abreviatura : $detalle->nombre_unidad;

                            $cantidad = $detalle->cantidad;
                            $producto_codigo_interno = $detalle->producto_codigo_interno;

                            $precio = $detalle->precio;
                            $nombre = $detalle->producto_nombre;

                            $subtotal = $detalle->subtotal;

                            $subtotal_total = $subtotal_total + $detalle->subtotal_total;
                            $total_total = $total_total  +$detalle->total_total;
                            $item = new item(
                                $producto_codigo_interno,
                                $nombre,
                                $precio,
                                '$',
                                $cantidad,
                                $subtotal,
                                $um
                            );

                            $printer->text($item);
                        }
                    }


                    $printer->text("----------------------------------------\n");

                    $descuento = $detalle_devolucion[0]->descuento_total;
                    if ($ventas[0]['regimen_iva'] == 1) {
                        $printer->text("SUBTOTAL             -> " . MONEDA . " " . number_format( $subtotal_total , 2, ',', '.') . "\n");
                    } else {
                        $printer->text("SUBTOTAL             -> " . MONEDA . " " . number_format($total_total + $descuento, 2, ',', '.') . "\n");
                    }

                    if ($ventas[0]['regimen_iva'] == 1) {
                        $printer->text("TOTAL IVA        -> " . MONEDA . " " . number_format($detalle_devolucion[0]->impuesto_total, 2, ',', '.') . "\n");
                    }

                    $printer->text("OTROS IMPUESTOS      -> " . MONEDA . " " . number_format($detalle_devolucion[0]->otro_impuesto_total, 2, ',', '.') . "\n");

                    $printer->text("DESCUENTO            -> " . number_format($detalle_devolucion[0]->descuento_total, 2, ',', '.') . "\n");

                    $printer->text("TOTAL DEVOLUCION     -> " . MONEDA . " " . number_format($total_total, 2, ',', '.') . "\n");

                    $printer->feed(1);


                    $printer->feed(5);
                    $printer->cut(Printer::CUT_FULL, 10); //corta el papel
                    $printer->pulse(); // abre la caja registradora
                    /* Close printer */
                    $printer->close();
                }
            }


            echo json_encode(array('result' => "success"));
        } catch (Exception $e) {
            log_message("error", "Error: Could not print. Message " . $e->getMessage());
            echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
            $this->receiptprint->close_after_exception();
        }
    }





    /***Este parche se usa para solucionar el error encontrado el dia 02/07/2018 con las devoluciones, en la cual no se estaba reinsertando
     * en detalle_venta_unidad cuando un producto no tenia devoluciones**/
    public
    function parcheDevoluciones()
    {

        $data = $this->venta_model->get_con_devluciones();

        foreach ($data as $dat) {
            var_dump($dat);

            try {
                echo '<br><br>DEtalles:<br><br>';
                $detalles = $this->venta_model->get_back_unidad_venta($dat->id_producto, $dat->id_venta);

                $arra_det = array();

                var_dump($detalles);
                foreach ($detalles as $detalle) {
                    //var_dump($detalle);
                    if ($dat->id_detalle != NULL) {
                        $detalle['detalle_venta_id'] = $dat->id_detalle;
                        $arra_det[] = $detalle;
                    }
                }


                if (sizeof($arra_det) > 0) {
                    $this->venta_model->insert_detalle_venta_unidad($arra_det);
                }
            } catch (Exception $e) {
            }
        }

        echo '<br><br><br>';
    }


    //este parche se hace para solventar un problema encontrado que estaba pasando antes del 18 de feberro de 2018
    // cuando se actualizaa una venta_backup no se colocaba un where y por ende todos los registros de esa tabla se actualizaban cn el mismo monto
    //este parche pretende jalar los montos de los registros de la tabla venta y actualizar ess montos en venta_backup
    public
    function parcheVentaBackup()
    {

        $data = $this->venta_model->get_parcheVentaBackup();

        foreach ($data as $dat) {


            try {
                echo '<br><br>Borrando venta:<br><br>';

                var_dump($dat);


                $this->venta_model->updateVentaBackupRow($dat);
            } catch (Exception $e) {
            }
        }

        echo '<br><br><br>';
    }


    //este parche se hace para solventar un probema de inventario que tenia juan felipe.
    //lo que hace es buscar todos los productos que no tuvieron movimientos en cierto rango de fechas y setearle el valor de inventario en cero
    public
    function parcheInventario()
    {

        $fechaIni = '2018-12-22';
        //$fechaIni = $this->input->get('fechaini');
        //$kardexReference = '"ENTRADA POR AJUSTE DE INVENTARIO", "SALIDA POR AJUSTE DE INVENTARIO", "REGISTRO DE FISICOS"';
        $kardexReference = '"REGISTRO DE FISICOS"';

        $fechaFin = '2018-12-27';
        // $fechaFin = $this->input->get('fechafin');
        $data = $this->producto_model->get_all_by(array());

        $contadorcero = 0;
        foreach ($data as $dat) {

            $existe = $this->venta_model->get_parcheInventario($fechaIni, $fechaFin, $kardexReference, $dat['producto_id']);

            try {

                echo '<br><br>Num rows: ' . $existe[0]->contador;


                if ($existe[0]->contador == 0) {
                    $contadorcero++;

                    $this->inventario_model->updateInventarioforProduct($dat['producto_id'], 0);
                }
            } catch (Exception $e) {
            }
        }

        echo "<br><br>total items afectados: " . $contadorcero;
        echo '<br><br><br>';
    }


    function controlDomicilios()
    {
        //vista del index de control de domicilios
        $data = array();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/domicilios/bandejaDomicilios', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function viewMapaDomiciliario()
    {
        //retorna la vista de mapas de los domiciliarios
        $data = array();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/domicilios/mapaDomiciliarios', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function callMapaDomicilios()
    {

        //esto lo hago porque el mapa se abre en una entana nueva, asi qeu sete un valor en la bd
        //para que cuando cargue la vista tenga con que comprobar que estoy llamando a la vista del mapa

        $mostrar = $this->opciones_model->getByKey("SHOWMAPADOMICILIARIO");

        if (isset($mostrar['config_value'])) {
            $configuraciones = array(
                'config_key' => "SHOWMAPADOMICILIARIO",
                'config_value' => "SI"
            );
            $where = array(
                'config_key' => "SHOWMAPADOMICILIARIO",
            );
            $this->opciones_model->update($where, $configuraciones);
        } else {
            $configuraciones = array(
                'config_key' => "SHOWMAPADOMICILIARIO",
                'config_value' => "SI"
            );

            $this->db->insert('configuraciones', $configuraciones);
        }
    }

    function sendElectronicInvoice()
    {


        $client = new nusoap_client('https://vpfe-hab.dian.gov.co/WcfDianCustomerServices.svc?wsdl', true);

        //  var_dump($client);
        //$client -> setEndpoint('https://facturaelectronica.dian.gov.co/habilitacion/B2BIntegrationEngine/FacturaElectronica/facturaElectronica.wsdl');
        $err = $client->getError();
        if ($err) {
            echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        // Doc/lit parameters get wrapped

        $fecha = new DateTime();
        $archivo = file_get_contents('http://localhost/sid/invoices/ws_f90101589730000000001.zip');

        $archivodata = base64_encode($archivo);
        $params = array(
            'NIT' => $this->session->userdata('NIT'),
            'InvoiceNumber' => '990000000',
            'IssueDate' => $fecha->getTimestamp(),
            'Document' => $archivodata
        );

        $client->namespaces = array(
            'soapenv' => "http://schemas.xmlsoap.org/soap/envelope/",
            'rep' => "http://www.dian.gov.co/servicios/facturaelectronica/ReportarFactura",
        );

        $password = hash('sha256', 'fc8eac422eba16e22ffd8c6f94b3f40a6e38162c');

        $strXML = <<<XML
    
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <wsse:UsernameToken>
                <wsse:Username>d25aa79f-9d22-4d0f-a223-364c18c75dc5</wsse:Username>
                <wsse:Password Type="http://docs.oasisopen.org/wss/2004/01/oasis-200401-
wss-username-token-profile1.0#PasswordDigest">$password</wsse:Password>
         </wsse:UsernameToken>
        </wsse:Security>
XML;

        $nit = $this->session->userdata('NIT');
        $IssueDate = $fecha->getTimestamp();
        $Document = $archivodata;
        $bodyXML = <<<XML
        
    <rep:SendBillAsync>  
            <rep:fileName>test</rep:fileName>
            <rep:contentFile>$Document</rep:contentFile>   
            <rep:testSetId>d25aa79f-9d22-4d0f-a223-364c18c75dc5</rep:testSetId>   
    </rep:SendBillAsync>
XML;

        $client->setHeaders($strXML);
        $client->setHTTPEncoding();

        $client->useHTTPPersistentConnection();
        $result = $client->call('SendTestSetAsync', $bodyXML, '');
        //  echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';


        // Check for a fault
        if ($client->fault) {
            echo '<h2>Fault</h2><pre>';
            //  print_r($result);
            echo '</pre>';
        } else {
            // Check for errors
            $err = $client->getError();
            if ($err) {
                // Display the error
                //3 echo '<h2>Error</h2><pre>' . $err . '</pre>';

                //   echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
                echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
                echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
            } else {
                // Display the result
                echo '<h2>Result</h2><pre>';
                //  print_r($result);
                echo '</pre>';
            }
        }
    }


    public function parche_comisiones_holanda()
    {
        $comisiones = $this->venta_model->get_parche_comisiones_holanda();
        // var_dump($comisiones);
        foreach ($comisiones as $comision) {

            $id = $comision['id_comision'];
            $grupo = $comision['nombre_grupo'];
            $subtotal = $comision['subtotal'];
            switch ($grupo) {
                case 'ALIMENTOS':
                    $porcentaje = 0.5;
                    break;
                case 'MEDICAMENTOS':
                    $porcentaje = 1;
                    break;
                case 'ACCESORIOS':
                    $porcentaje = 4;
                    break;
                default:
                    $porcentaje = 0;
                    break;
            }

            echo $porcentaje;
            echo $subtotal;
            if ($porcentaje > 0) {
                $impuesto_nuevo = ($subtotal * $porcentaje) / 100;
                $this->venta_model->update_parche_comisiones_holanda($impuesto_nuevo, $porcentaje, $id);
            }
        }
    }

    function columnasmodalproductos(){

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }

        $data['columnas'] =VentaColumnasProductosElo::all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/columodalprodindex', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }
}


/* A wrapper to do organise item names & prices into columns */

class item
{
    private $name;
    private $price;
    private $dollarSign;
    private $cantidad;
    private $subtotal;
    private $um;

    public function __construct($codigo, $name, $price, $dollarSign = false, $cantidad, $subtotal, $um)
    {
        $this->codigo = $codigo;
        $this->name = $name;
        $this->price = $price;
        $this->dollarSign = $dollarSign;
        $this->cantidad = $cantidad;
        $this->subtotal = $subtotal;
        $this->um = $um;
    }

    public function __toString()
    {

        $leftCols = 10;
        $sign = ($this->dollarSign ? '$ ' : '');

        $item = str_pad($this->codigo, $leftCols);
        $item .= $this->name;
        $item .= "\n";

        $item .= str_pad($this->cantidad, $leftCols);
        $item .= str_pad($sign . $this->price, $leftCols);
        $item .= str_pad($sign . $this->subtotal, $leftCols);
        $item .= $this->um;
        return "$item\n";
    }
}
