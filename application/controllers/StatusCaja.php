<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Mike42\Escpos\Printer;

class StatusCaja extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('regimen/regimen_model');
        $this->load->model('cajas/StatusCajaModel');
        $this->load->model('cajas/cajas_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('metodosdepago/metodos_pago_model');
        $this->load->model('historial_pagos_clientes/historial_pagos_clientes_model');
        $this->load->model('venta/venta_model');

        $this->load->library('ReceiptPrint');
        $this->very_sesion();
    }


    function apertura()
    {
        $data['cajas'] = $this->cajas_model->get_all();
        $data['cajas_abiertas'] = $this->StatusCajaModel->getAlBy(
            array(
                'apertura IS NOT null' => NULL,
                'cierre IS null ' => NULL)
        );
        $data['usuarios'] = $this->usuario_model->select_all_user();


        $this->load->view('menu/cajas/apertura', $data);
    }

    function cierre()
    {
        $dataCuerpo['cajas_abiertas'] = $this->StatusCajaModel->getAlBy(
            array(
                'apertura IS NOT null' => NULL,
                'cierre IS null ' => NULL)
        );

        $dataCuerpo['usuarios'] = $this->usuario_model->select_all_user();
        $this->load->view('menu/cajas/cierre', $dataCuerpo);
    }


    function guardar()
    {

        $cajero = $this->input->post('cajero');
        $observacion_cierre = $this->input->post('observacion_cierre');
        $observacion_apertura = $this->input->post('observacion_apertura');;
        $operacion = $this->input->post('operacion');
        $base = $this->input->post('base');
        $monto = $this->input->post('monto');
        $caja_id = $this->input->post('caja_id');
        $id = $this->input->post('id');
        $fecha = date('Y-m-d H:i:s');


        $datos = array(
            'base' => $base,
            'monto_cierre' => $monto,
            'observacion_apertura' => $observacion_apertura,
            'observacion_cierre' => $observacion_cierre,
        );
        if ($operacion == 'APERTURA') {


            $datos['apertura'] = $fecha;
            $datos['cajero'] = $cajero;
            $datos['caja_id'] = $caja_id;

            $data['resultado'] = $this->StatusCajaModel->set($datos);
            $id = $data['resultado'];

        } else {

            if ($this->session->userdata('PEDIR_VALOR_CIERRE_CAJA') == "SI") {

                if ($monto == "" || $monto <= 0) {
                    $json['result'] = 'error';
                    $json['msg'] = 'Debe ingresar un valor mayor a 0, en el valor del cierre.';
                    echo json_encode($json);
                    exit();
                }
            }
            $datos['cierre'] = $fecha;
            $datos['id'] = $id;

            $data['resultado'] = $this->StatusCajaModel->update($datos);
        }


        if ($data['resultado'] === false) {
            $json['result'] = 'error';
            $json['msg'] = 'Ha ocurrido un error al cerrar la caja';
        } else {
            if ($operacion == 'APERTURA') {
                $this->session->set_userdata('cajapertura', $id);
                $this->session->set_userdata('cajero_id', $cajero);
                $this->session->set_userdata('caja_id', $caja_id);
            } else {
                $this->session->unset_userdata('caja_id');
                $this->session->unset_userdata('cajero_id');
                $this->session->unset_userdata('cajapertura');
            }
            $json['result'] = 'success';
            $json['cajeronombre'] = $cajero;
            $json['id'] = $id;
        }


        $json['id'] = $id;
        echo json_encode($json);

    }

    function selectCaja()
    {


        $id = $this->input->post('id');
        $caja_id = $this->input->post('caja_id');


        $this->session->set_userdata('caja_id', $caja_id);
        $this->session->set_userdata('cajapertura', $id);
        $this->session->set_userdata('cajero_id', $this->session->userdata('nUsuCodigo'));


        $json['result'] = 'success';


        echo json_encode($json);
    }


    function cuadrecajerohistory()
    {


        $data['cajas'] = $this->cajas_model->get_all();
        $data['usuarios'] = $this->usuario_model->select_all_user();
        $this->load->view('menu/cajas/history', $data);
    }

    function cajasabiertas()
    {

        $data = array();
        $data['cajas'] = $this->StatusCajaModel->getAlBy(
            array(
                'apertura IS NOT null' => NULL,
                'cierre IS null ' => NULL)
        );
        $this->load->view('menu/cajas/cajas_abiertas', $data);
    }

    function preview()
    {

        $data=array();
        $id = $this->input->post('id');

        if ($id != false) {
            $data['REGIMEN_CONTRIBUTIVO'] = $this->regimen_model->get_by(array('regimen_id' => $this->session->userdata('REGIMEN_CONTRIBUTIVO')));
            $data['cierrecaja'] = $this->StatusCajaModel->getBy(array('id' => $id));
            $formaspagoa = $this->metodos_pago_model->get_all_by(array('incluye_cuadre_caja' => 1));
            $data['formaspago'] = array();
            $last_venta = $this->venta_model->get_last(array('caja_id' => $id));
            $data['factura_fin'] = $last_venta['documento_Numero'];
            $first_venta = $this->venta_model->get_first(array('caja_id' => $id));
            $data['factura_inicio'] = $first_venta['documento_Numero'];
            $data['calculodevoluciones'] = $this->venta_model->getSalidasByDevoluciones($id);
            $data['calculodevoluciones_credito'] = $this->venta_model->getSalidasByDevoluciones($id, false, true);
            $data['calculoanulaciones'] = $this->venta_model->getSalidasByAnulaciones($id);
            $data['calculoanulaciones_credito'] = $this->venta_model->getSalidasByAnulaciones($id, false, true);
            $data['totalventascondescuento'] = $this->venta_model->get_total_ventas_con_descuentos(array('venta_backup.caja_id=' => $id));
            $credito = $this->venta_model->get_total_solocredito(array('caja_id' => $id));
            $data['totalventascredito'] = $credito['suma'];
            $data['credito'] = $credito;
            //  var_dump($data['calculoanulaciones']);
            $data['abonosacarteraresult'] = $this->historial_pagos_clientes_model->getIngresosByCierreCaja($id);
            foreach ($formaspagoa as $formapago) {
                $totales = $formapago;
                $totales['totales'] = $this->StatusCajaModel->getTotalsIngresosByMetodoPago($formapago['id_metodo'], $data['cierrecaja']['apertura'],
                    $data['cierrecaja']['cierre'], $formapago['suma_total_ingreso'], $formapago['nombre_metodo'], $id);

                array_push($data['formaspago'], $totales);
            }
        }
        $this->load->view('menu/cajas/preview', $data);
    }

    function getCajaSession()
    {
        $cajas_abiertas = $this->StatusCajaModel->getAlBy(
            array(
                'apertura IS NOT null' => NULL,
                'cierre IS null ' => NULL)
        );
        $cajaabierta = null;
        // echo count($cajas_abiertas);
        if (count($cajas_abiertas) > 0) {
            foreach ($cajas_abiertas as $caja) {
                if ($caja['cajero'] == $this->session->userdata('nUsuCodigo')) {
                    $cajaabierta = $caja;
                };
            }
        }

        if ($cajaabierta != null) {
            echo json_encode(array('result' => ERROR, 'message' => 'Ya aperturaste caja desde otra computadora, por favor refresca la pantalla para actualizar'));
        } else {
            echo json_encode(array('result' => SUCCESS));
        }
    }

    function directPrintCierre()
    {

        try {

            $id = $this->input->post('id');

            $result['ventas'] = array();
            if ($id != FALSE) {
                $cierrecaja = $this->StatusCajaModel->getBy(array('id' => $id));
                $last_venta = $this->venta_model->get_last(array('caja_id' => $id));
                $factura_fin = $last_venta['documento_Numero'];
                $first_venta = $this->venta_model->get_first(array('caja_id' => $id));
                $factura_inicio = $first_venta['documento_Numero'];
                $formaspago = $this->metodos_pago_model->get_all_by(array('incluye_cuadre_caja' => 1));
                $data['REGIMEN_CONTRIBUTIVO'] = $this->regimen_model->get_by(array('regimen_id' => $this->session->userdata('REGIMEN_CONTRIBUTIVO')));
                $printer = $this->receiptprint->connectUsb($this->session->userdata('IMPRESORA'), $this->session->userdata('USUARIO_IMPRESORA'), $this->session->userdata('PASSWORD_IMPRESORA'), $this->session->userdata('WORKGROUP_IMPRESORA'));
                /* Initialize */
                $printer->initialize();
                $printer->feed(1);

                $nombreempresa = $this->session->userdata('EMPRESA_NOMBRE');
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text($nombreempresa . " \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text('NIT ' . str_pad($this->session->userdata('NIT') . "\n", 10));

                $printer->text($data['REGIMEN_CONTRIBUTIVO']['regimen_nombre'] . " \n");
                $printer->text($this->session->userdata('EMPRESA_DIRECCION') . " \n");
                $printer->text('Telf:' . $this->session->userdata('EMPRESA_TELEFONO') . " \n");
                $printer->text("REPORTES/CUADRE CAJA \n");
                $printer->text("CUADRE CAJA:    " . $cierrecaja['id']);

                $printer->feed(1);
                $printer->text("----------------------------------------\n");

                $printer->text("FECHA:   " . date('d-m-Y', strtotime($cierrecaja['apertura'])) . " A " . date('d-m-Y', strtotime($cierrecaja['cierre'])) . "\n");
                $printer->text("HORA:    " . date('h:i A', strtotime($cierrecaja['cierre'])) . " \n");
                $printer->text("CAJERO:  " . $cierrecaja['nombre']);
                $printer->feed(2);
                $printer->text("FACTURA: " . $factura_inicio);
                $printer->text("  AL:    " . $factura_fin);

                $printer->feed(2);

                //RANGO REMISIONAL ?

                $printer->text(str_pad("Forma de pago", 20));
                $printer->text("Valor");
                // $printer->text("Registro");
                $printer->feed(1);
                $printer->text("----------------------------------------\n");
                $totalefectivoventas = 0;
                $totalefectivo = 0;
                $totalsistema = 0;
                $totalotros = 0;
                $totaldesceuntos = 0;
                $abonosacarteraresult = $this->historial_pagos_clientes_model->getIngresosByCierreCaja($id);
                $totalventascondescuento = $this->venta_model->get_total_ventas_con_descuentos(array('venta_backup.caja_id=' => $id));


                foreach ($formaspago as $formapago) {
                    $printer->text(str_pad($formapago['id_metodo'], 2));
                    $printer->text(str_pad($formapago['nombre_metodo'], 20));

                    $totales = $this->StatusCajaModel->getTotalsIngresosByMetodoPago($formapago['id_metodo'], $cierrecaja['apertura'],
                        $cierrecaja['cierre'], $formapago['suma_total_ingreso'], $formapago['nombre_metodo'], $id);


                    $printer->text(str_pad(number_format($totales['total'], 2, ',', '.'), 13));
                    $printer->text("(" . $totales['totalregistros'] . ")");

                    if ($formapago['suma_total_ingreso'] == 1) {
                        $totalefectivoventas = $totalefectivoventas + $totales['total'];
                    } else {
                        $totalotros = $totalotros + $totales['total'];
                    }
                    $totaldesceuntos = $totaldesceuntos + $totales['descuentos'];

                    $printer->feed(1);
                }


                $credito = $this->venta_model->get_total_solocredito(array('caja_id' => $id));

                $totalventascredito = $credito['suma'];


                $calculodevoluciones = $this->venta_model->getSalidasByDevoluciones($id);
                $calculodevoluciones_credito = $this->venta_model->getSalidasByDevoluciones($id, false, true);
                $printer->text(str_pad("", 2));
                $printer->text(str_pad("DEVOLUCIONES", 20));
                $printer->text(str_pad(number_format($calculodevoluciones['total'] + $calculodevoluciones_credito['total'], 2, ',', '.'), 13));
                $totregdev = $calculodevoluciones['registros'] + $calculodevoluciones_credito['registros'];
                $printer->text("(" . $totregdev . ")");
                $printer->feed(1);


                $calculoanulaciones = $this->venta_model->getSalidasByAnulaciones($id);
                $calculoanulaciones_credito = $this->venta_model->getSalidasByAnulaciones($id, false, true);
                $printer->text(str_pad("", 2));
                $printer->text(str_pad("ANULACIONES", 20));
                $totan = $calculoanulaciones['total'] + $calculoanulaciones_credito['total'];
                $printer->text(str_pad(number_format($totan, 2, ',', '.'), 13));
                $totregan = $calculoanulaciones['registros'] + $calculoanulaciones_credito['registros'];
                $printer->text("(" . $totregan . ")");
                $printer->feed(1);


                $printer->text(str_pad("", 2));
                $printer->text(str_pad("CREDITO", 20));
                $printer->text(str_pad(number_format($totalventascredito, 2, ',', '.'), 13));
                $printer->text("(" . $credito['registros'] . ")");
                $printer->feed(1);

                $restar = $calculodevoluciones['total'] + $calculoanulaciones['total'];

                $totalefectivo = ($totalefectivoventas + $abonosacarteraresult['efectivo']) - $restar;

                //$totalsistema=($totalefectivo+$abonosacarteraresult['efectivo'])-$restar;


                $totalsistema = $totalefectivo + $totalotros + $totalventascredito - $abonosacarteraresult['efectivo'];

                $printer->text("----------------------------------------\n");
                $printer->feed(1);
                $restar_credito = $calculodevoluciones_credito['total'] + $calculoanulaciones_credito['total'];


                $totalingresos = $totalefectivoventas + $totalotros + $credito['suma'] - ($restar + $restar_credito);


                $totabonoscartera = number_format($abonosacarteraresult['efectivo'] + $abonosacarteraresult['otros'], 2, ',', '.');
                $abononumregistros = $abonosacarteraresult['num'];
                $totalotros = $totalotros + $totalventascredito;
                $printer->text('TOTAL INGRESOS           ' . number_format($totalingresos, 2, ',', '.') . "\n");
                $printer->text('TOTAL EFECTIVO           ' . number_format($totalefectivo, 2, ',', '.') . "\n");
                $printer->text('TOTAL OTROS DOC.         ' . number_format($totalotros, 2, ',', '.') . "\n");
                $printer->text(str_pad('DESCUENTOS               ' . number_format($totaldesceuntos, 2, ',', '.'), 34) . " (" . $totalventascondescuento['num'] . ") \n");
                $printer->text(str_pad('TOTAL ABONOS             ' . $totabonoscartera, 34) . ' (' . $abononumregistros . ') ');
                //$printer->text('ABONOS CARTERA OTROS     ' . number_format($abonosacarteraresult['otros'],2,',','.') . "\n");
                $printer->feed(1);
                //$printer->text('TOTAL SISTEMA            ' . number_format($totalsistema ,2,',','.'). "\n");
                //$printer->feed(1);

                $printer->text('Valor entregado          ' . number_format($cierrecaja['monto_cierre'], 2, ',', '.') . "\n");

                $printer->feed(1);


                $sobrante = $cierrecaja['monto_cierre'] - $totalefectivo;

                $printer->text('Sobrante/Faltante       $');
                $printer->text(number_format($sobrante, 2, ',', '.'));
                $printer->feed(2);

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text(str_pad('Recibe', 20));
                $printer->text(str_pad('Entrega', 20));
                $printer->feed(2);
                $printer->text(str_pad('-------------------', 20));
                $printer->text(str_pad('-------------------', 20));
                $printer->feed(2);
                $printer->text('Derechos reservados Prosode SAS ' . date('Y'));

                $printer->feed(5);
                $printer->cut(Printer::CUT_FULL, 10); //corta el papel
                $printer->pulse(); // abre la caja registradora
                /* Close printer */
                $printer->close();


            }


            echo json_encode(array('result' => "success"));

        } catch
        (Exception $e) {
            log_message("error", "Error: Could not print. Message " . $e->getMessage());
            echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
            $this->receiptprint->close_after_exception();
        }


    }


}