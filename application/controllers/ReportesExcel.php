<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Mike42\Escpos\Printer;

class ReportesExcel extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('venta/venta_model');
        $this->load->model('local/local_model');
        $this->load->model('regimen/regimen_model');
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
        $this->load->model('tipo_producto/tipo_producto_model');
        //   $this->load->library('phpword');
        $this->load->library('Request');
        $this->load->model('cajas/StatusCajaModel');
        $this->load->library('Pdf');
        $this->load->library('session');
        $this->load->library('phpExcel/PHPExcel.php');

        $this->very_sesion();

    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function ventas_by_cliente()
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
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


    function pdfReporteZona($zona, $desde, $hasta)
    {


        $condicion = array();

        $condicion2 = "venta_status IN ('" . COMPLETADO . "','" . PEDIDO_DEVUELTO . "','" . PEDIDO_ENTREGADO . "','" . PEDIDO_GENERADO . "')";
        $condicion['venta_tipo'] = "ENTREGA";
        $retorno = "RESULT";
        $select = "(SELECT COUNT(venta.id_cliente)) as clientes_atendidos, (SELECT SUM(detalle_venta.cantidad))
                    as cantidad_vendida, usuario.nombre, detalle_venta.*,venta.*, producto.*,grupos.*,familia.*,lineas.*,unidades.*,usuario_has_zona.*,
                    zonas.*,ciudades.*,cliente.razon_social, consolidado_carga.fecha";

        $group = "unidad_medida";

        if ($zona != "TODAS") {

            $condicion['zona_id'] = $this->input->post('id_zona');
            $data['zona'] = $this->input->post('id_zona');


        }
        if (($desde != "")) {


            $condicion['date(consolidado_carga.fecha) >= '] = date('Y-m-d', strtotime($desde));

        }
        if (($hasta != "")) {


            $condicion['date(consolidado_carga.fecha) <='] = date('Y-m-d', strtotime($hasta));


            $data['fecha_hasta'] = date('Y-m-d', strtotime($hasta));
        }


        $ventas = $this->venta_model->getProductosZona($select, $condicion, $retorno, $group, $condicion2);

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('L');
        // $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('REPORTE UTILIDADES');
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

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>LISTA</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);


        //preparamos y maquetamos el contenido a crear
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #000; font-weight: bold; background-color: #CED6DB; }";
        $html .= "td{color: #222; font-weight: bold; background-color: #fff;}";
        $html .= "table{border:0.2px}";
        $html .= "body{font-size:15px}";
        $html .= "</style>";


        if (isset($zona_nombre)) {
            $html .= "<br><b>" . $zona_nombre['zona_nombre'] . ":</b> " . "<br>";
        } else {

            $html .= "<br><b>Utilidades:</b> " . "<br>";
        }

        $html .= "<table><tr><th>Distrito</th><th>Urbanización</th><th>Vendedor</th><th>Grupo</th><th>Familia</th><th>Linea</th>";
        $html .= "<th>Producto</th><th>Cantidad Vendida</th><th>Unidad</th><th>Clientes</th></tr>";

        foreach ($ventas as $venta) {
            $html .= " <tr><td>" . $venta->ciudad_nombre . "</td><td >" . $venta->urb . "</td>";
            $html .= " <td>" . $venta->nombre . " </td><td >" . $venta->nombre_grupo . "</td>";
            $html .= " <td>" . $venta->nombre_familia . "</td><td >" . $venta->nombre_linea . "</td>";
            $html .= " <td>" . $venta->producto_nombre . "</td><td>" . $venta->cantidad_vendida . "</td>";
            $html .= " <td>" . $venta->nombre_unidad . "</td><td>" . $venta->clientes_atendidos . "</td></tr>";


        }

        $html .= "</table>";

// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este mÃ©todo tiene varias opciones, consulte la documentaciÃ³n para mÃ¡s informaciÃ³n.

        $nombre_archivo = utf8_decode("ReporteZonaProductos.pdf");

        $pdf->Output($nombre_archivo, 'D');


    }


    function pdfReporteUtilidades($local, $fecha_desde, $fecha_hasta, $utilidades)
    {

        if ($local != 0) {
            $condicion = array('local_id' => $local);
            $local_nombre = $this->local_model->get_by('int_local_id', $local);
        }
        if ($fecha_desde != 0) {

            $condicion['date(fecha) >= '] = date('Y-m-d', strtotime($fecha_desde));
        }
        if ($fecha_hasta != 0) {

            $condicion['date(fecha) <='] = date('Y-m-d', strtotime($fecha_hasta));
        }

        $condicion = "venta_status IN ('" . COMPLETADO . "','" . PEDIDO_DEVUELTO . "','" . PEDIDO_ENTREGADO . "','" . PEDIDO_GENERADO . "')";
        $retorno = "RESULT";

        if ($utilidades == "TODO") {
            $select = "documento_venta.documento_Numero,  documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.razon_social";

            $group = false;

        } elseif ($utilidades == "PRODUCTO") {

            $select = "(SELECT SUM(detalle_utilidad)) AS suma,documento_venta.documento_Numero,  documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.razon_social";
            $group = "producto_id";

        } elseif ($utilidades == "CLIENTE") {

            $select = "(SELECT SUM(detalle_utilidad)) AS suma,documento_venta.documento_Numero,  documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.razon_social,cliente.id_cliente";

            $group = "cliente.id_cliente";
        } elseif ($utilidades == "PROVEEDOR") {

            $select = "(SELECT SUM(detalle_utilidad)) AS suma,documento_venta.documento_Numero,documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.razon_social,cliente.id_cliente,
 proveedor.proveedor_nombre,proveedor.id_proveedor";

            $group = "producto.producto_proveedor";
        }
        $ventas = $this->venta_model->getUtilidades($select, $condicion, $retorno, $group);


        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('L');
        // $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('REPORTE UTILIDADES');
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

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>LISTA</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);


        //preparamos y maquetamos el contenido a crear
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #000; font-weight: bold; background-color: #CED6DB; }";
        $html .= "td{color: #222; font-weight: bold; background-color: #fff;}";
        $html .= "table{border:0.2px}";
        $html .= "body{font-size:15px}";
        $html .= "</style>";


        if (isset($local_nombre)) {
            $html .= "<br><b>" . $local_nombre['local_nombre'] . ":</b> " . "<br>";
        } else {

            $html .= "<br><b>Utilidades:</b> " . "<br>";
        }

        if ($utilidades == "TODO") {

            $html .= "<table><tr> <th>Fecha y Hora</th><th>C&oacute;digo</th><th>N&uacute;mero</th><th>Cantidad</th><th>Producto</th> ";
            $html .= " <th>Cliente</th><th>Vendedor</th><th>Costo</th><th>Precio</th><th>Utilidad</th></tr>";
            foreach ($ventas as $venta) {
                $html .= " <tr><td >" . date('d-m-Y H:i:s', strtotime($venta->fecha)) . "</td><td >" . $venta->venta_id . "</td>";
                $html .= " <td >" . $venta->documento_Serie . " " . $venta->documento_Numero . "</td><td >" . $venta->cantidad . "</td>";
                $html .= "  <td >" . $venta->producto_nombre . "</td><td >" . $venta->razon_social . "</td>";
                $html .= " <td>" . $venta->nombre . "</td><td>" . $venta->detalle_costo_promedio . "</td><td>" . $venta->precio . "</td>";
                $html .= " <td>" . $venta->detalle_utilidad . "</td></tr>";

            }
        } elseif ($utilidades == "PRODUCTO") {
            $html .= "<table><tr> <th>C&oacute;digo</th><th>Producto</th><th>Utilidad</th></tr>";
            foreach ($ventas as $venta) {
                $html .= " <tr><td >" . $venta->id_producto . "</td><td >" . $venta->producto_nombre . "</td> <td >" . $venta->suma . "</td></tr>";
            }
        } elseif ($utilidades == "CLIENTE") {
            $html .= "<table><tr> <th>C&oacute;digo</th><th>Cliente</th><th>Utilidad</th></tr>";
            foreach ($ventas as $venta) {
                $html .= " <tr><td >" . $venta->id_producto . "</td><td >" . $venta->razon_social . "</td> <td >" . $venta->suma . "</td></tr>";
            }
        } elseif ($utilidades == "PROVEEDOR") {
            $html .= "<table><tr> <th>C&oacute;digo</th><th>Proveedor</th><th>Utilidad</th></tr>";
            foreach ($ventas as $venta) {
                $html .= " <tr><td >" . $venta->id_proveedor . "</td><td >" . $venta->proveedor_nombre . "</td> <td >" . $venta->suma . "</td></tr>";
            }
        }

        $html .= "</table>";

// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este mÃ©todo tiene varias opciones, consulte la documentaciÃ³n para mÃ¡s informaciÃ³n.
        if ($utilidades == "TODO") {
            $nombre_archivo = utf8_decode("ReporteUtilidades.pdf");
        } elseif ($utilidades == "PRODUCTO") {
            $nombre_archivo = utf8_decode("ReporteUtilidadesPorProducto.pdf");
        } elseif ($utilidades == "CLIENTE") {
            $nombre_archivo = utf8_decode("ReporteUtilidadesPorCliente.pdf");
        } elseif ($utilidades == "PROVEEDOR") {
            $nombre_archivo = utf8_decode("ReporteUtilidadesPorProveedor.pdf");
        }


        $pdf->Output($nombre_archivo, 'D');


    }

    function excel($local, $fecha_desde, $fecha_hasta, $estatus, $totalventas)
    {

        if ($local != 0) {
            $condicion = array('local_id' => $local);
        }
        if ($fecha_desde != 0) {

            $condicion['fecha >= '] = date('Y-m-d', strtotime($fecha_desde)) . " " . date('H:i:s');
        }
        if ($fecha_hasta != 0) {

            $condicion['fecha <='] = date('Y-m-d', strtotime($fecha_hasta)) . " " . date('H:i:s');
        }
        if ($estatus != 0) {
            $condicion['venta_status'] = $estatus;
        }

        if ($totalventas == "TODOS") {

            $condicion = array('a.id_cliente >=' => 0);
            $total = $this->venta_model->get_ventas_by_cliente($condicion);

        } elseif ($totalventas != 0 and $totalventas != "TODOS") {

            $condicion = array('venta.id_cliente' => $totalventas);
            $clientes = $this->venta_model->get_ventas_by($condicion);


        } else {

            $ventas = $this->venta_model->get_ventas_by($condicion);

        }
        // configuramos las propiedades del documento
        $this->phpexcel->getProperties()
            //->setCreator("Arkos Noem Arenom")
            //->setLastModifiedBy("Arkos Noem Arenom")
            ->setTitle("Ventas")
            ->setSubject("Ventas")
            ->setDescription("Ventas")
            ->setKeywords("Ventas")
            ->setCategory("Ventas");

        if (isset($ventas)) {
            $columna[0] = "NUMERO DE VENTA";
            $columna[1] = "CLIENTE";
            $columna[2] = "VENDEDOR";
            $columna[3] = "FECHA";
            $columna[4] = "TIPO DE DOCUMENTO";
            $columna[5] = "ESTATUS";
            $columna[6] = "LOCAL";
            $columna[7] = "CONDICION DE PAGO";
            $columna[8] = "SUB TOTAL";
            $columna[9] = "TOTAL IMPUESTO";
        } elseif (isset($total)) {

            $columna[0] = "NUMERO DE VENTA";
            $columna[1] = "FECHA";
            $columna[2] = "VENDEDOR";
            $columna[3] = "CLIENTE";
            $columna[4] = "FORMATO";
            $columna[5] = "FORMA DE PAGO";
            $columna[6] = "ESTADO";
            $columna[7] = "DIAS DE PLAZO";
            $columna[8] = "SUB TOTAL";
            $columna[9] = "IMPUESTO";
            $columna[10] = "TOTAL";
        } elseif (isset($clientes)) {

            $columna[0] = "NUMERO DE VENTA";
            $columna[1] = "FECHA";
            $columna[2] = "VENDEDOR";
            $columna[3] = "CLIENTE";
            $columna[4] = "CONDICIONES DE PAGO";
            $columna[5] = "ESTADO";
            $columna[6] = "LOCAL";
            $columna[7] = "SUB TOTAL";
            $columna[8] = "IMPUESTO";
            $columna[9] = "TOTAL";
        }

        $col = 0;
        for ($i = 0; $i < count($columna); $i++) {

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 1, $columna[$i]);

        }

        $row = 2;

        if (isset($ventas)) {
            $col = 0;
            foreach ($ventas as $venta) {


                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->venta_id);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->razon_social);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->nombre);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, date('d-m-Y H:i:s', strtotime($venta->fecha)));

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->nombre_tipo_documento);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->venta_status);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->dias);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->sub_total);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->impuesto);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->totalizado);

                $row++;
            }
        } elseif (isset($total)) {
            $row = 2;
            foreach ($total as $totales) {
                $col = 0;

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $totales->venta_id);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, date('d-m-Y H:i:s', strtotime($totales->fecha)));

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $totales->nombre);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $totales->razon_social);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, "FORMATO1");

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, "forma de pago 1");

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $totales->venta_status);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $totales->dias);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $totales->sub_total);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $totales->impuesto);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $totales->totalizado);

                $row++;
            }

        } elseif (isset($clientes)) {
            $row = 2;
            foreach ($clientes as $cliente) {
                $col = 0;

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $cliente->venta_id);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, date('d-m-Y H:i:s', strtotime($cliente->fecha)));

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $cliente->nombre);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $cliente->razon_social);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $cliente->nombre_condiciones);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $cliente->venta_status);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $cliente->local_nombre);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $cliente->subtotal);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $cliente->total_impuesto);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $cliente->total);

                $row++;
            }

        }

// Renombramos la hoja de trabajo
        $this->phpexcel->getActiveSheet()->setTitle('Ventas');


// configuramos el documento para que la hoja
// de trabajo nÃºmero 0 sera la primera en mostrarse
// al abrir el documento
        $this->phpexcel->setActiveSheetIndex(0);


// redireccionamos la salida al navegador del cliente (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Ventas.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');

    }


    function excelInformeVentasPorFecha()
    {
        $fecha_desde = $this->input->get('fecha_desde');
        $fecha_hasta = $this->input->get('fecha_hasta');
        $tipos_venta_select= $this->input->get('tipos_venta_select');
        $tipos_venta_text=$this->input->get('tipos_venta_text');

        $fecha = date('d-m-Y H:i:s');
        $usuario = $this->session->userdata("username");
        $this->phpexcel->setActiveSheetIndex(0);
        $result = json_decode($this->venta_model->ventas_por_fecha($fecha_desde,$fecha_hasta,$tipos_venta_select,$tipos_venta_text));


        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, 1, 'SID-SISTEMA INTEGRAL DE DROGUERIAS');
        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, 2, $this->session->userdata('EMPRESA_NOMBRE'));
        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, 3, 'NIT:'.$this->session->userdata('NIT'));
        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, 4, 'DESDE: '.$fecha_desde.' HASTA: '.$fecha_hasta.' FECHA/HORA: '.$fecha.' USUARIO: '.$usuario);
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $impuestos=$this->impuestos_model->get_impuestos();
        $colspan=13+sizeof($impuestos);

        $colspan=toAlpha($colspan);


        $this->phpexcel->getActiveSheet()->mergeCells("A1:".$colspan."1");
        $this->phpexcel->getActiveSheet()->mergeCells('A2:'.$colspan.'2');
        $this->phpexcel->getActiveSheet()->mergeCells('A3:'.$colspan.'3');
        $this->phpexcel->getActiveSheet()->mergeCells('A4:'.$colspan.'4');
        $sheet = $this->phpexcel->getActiveSheet();
        $sheet->getStyle("A1:".$colspan."1")->applyFromArray($style);
        $sheet->getStyle("A2:".$colspan."2")->applyFromArray($style);
        $sheet->getStyle("A3:".$colspan."3")->applyFromArray($style);
        $sheet->getStyle("A4:".$colspan."4")->applyFromArray($style);

        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(2, 5, 'Base Excuida');
        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(4, 5, 'Base Gavada');

        $this->phpexcel->getActiveSheet()->mergeCells('C5:D5');
        $this->phpexcel->getActiveSheet()->mergeCells('E5:F5');

        $cimo=0;
        $columna[$cimo] = "FECHA";
        $cimo++;

        if($tipos_venta_text!="TODOS"){
            $columna[$cimo]  = "Tipo de Venta";
            $cimo++;
        }

        $columna[$cimo] = "VALOR";
        $cimo++;
        $columna[$cimo] = "DESCUENTO";
        $cimo++;
        $columna[$cimo] = "BASE";
        $cimo++;
        $columna[$cimo] = "DESCUENTO";
        $cimo++;
        $columna[$cimo] = "DESCUENTO TOTAL";
        $cimo++;

        foreach ($impuestos as $impuesto){
            $columna[$cimo] = $impuesto['nombre_impuesto'];
            $cimo++;
            $columna[$cimo] = 'Gravado '.$impuesto['nombre_impuesto'];
            $cimo++;
        }
        $columna[$cimo++] = "TOTAL IVA";
        $columna[$cimo++] = "ANULACIONES";
        $columna[$cimo++] = "DEVOLUCIONES";
        $columna[$cimo++] = "VENTA TOTAL";

        for ($i = 0; $i < count($columna); $i++) {
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 6, $columna[$i]);
        }


        $row = 7;
        $recorrer = $result->data;
        foreach ($recorrer as $data) {
            $col = 0;
            $total=0;
            foreach ($data as $datum){
                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col, $row, $datum);
                $col++;
            }
            $row++;
        }
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->phpexcel->getDefaultStyle()->applyFromArray($styleArray);

// redireccionamos la salida al navegador del cliente (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteSid.xls"');
        header('Cache-Control: max-age=0');


        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');



    }

function excelInformeCompras()
    {
        $fecha_desde = $this->input->get('fecha_desde');
        $fecha_hasta = $this->input->get('fecha_hasta');
        $tipos_venta_select= $this->input->get('tipos_venta_select');
        $tipos_venta_text=$this->input->get('tipos_venta_text');

        $fecha = date('d-m-Y H:i:s');
        $usuario = $this->session->userdata("username");
        $this->phpexcel->setActiveSheetIndex(0);
        $result = json_decode($this->ingreso_model->compras_por_fecha($fecha_desde,$fecha_hasta,$tipos_venta_select,$tipos_venta_text));


        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, 1, 'SID-SISTEMA INTEGRAL DE DROGUERIAS');
        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, 2, $this->session->userdata('EMPRESA_NOMBRE'));
        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, 3, 'NIT:'.$this->session->userdata('NIT'));
        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, 4, 'DESDE: '.$fecha_desde.' HASTA: '.$fecha_hasta.' FECHA/HORA: '.$fecha.' USUARIO: '.$usuario);
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $impuestos=$this->impuestos_model->get_impuestos();
        $colspan=13+sizeof($impuestos);

        $colspan=toAlpha($colspan);


        $this->phpexcel->getActiveSheet()->mergeCells("A1:".$colspan."1");
        $this->phpexcel->getActiveSheet()->mergeCells('A2:'.$colspan.'2');
        $this->phpexcel->getActiveSheet()->mergeCells('A3:'.$colspan.'3');
        $this->phpexcel->getActiveSheet()->mergeCells('A4:'.$colspan.'4');
        $sheet = $this->phpexcel->getActiveSheet();
        $sheet->getStyle("A1:".$colspan."1")->applyFromArray($style);
        $sheet->getStyle("A2:".$colspan."2")->applyFromArray($style);
        $sheet->getStyle("A3:".$colspan."3")->applyFromArray($style);
        $sheet->getStyle("A4:".$colspan."4")->applyFromArray($style);

        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(2, 5, 'Base Excuida');
        $this->phpexcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(4, 5, 'Base Gavada');

        $this->phpexcel->getActiveSheet()->mergeCells('C5:D5');
        $this->phpexcel->getActiveSheet()->mergeCells('E5:F5');

        $cimo=0;
        $columna[$cimo] = "FECHA";
        $cimo++;



        foreach ($impuestos as $impuesto){
            $columna[$cimo] = $impuesto['nombre_impuesto'];
            $cimo++;
            $columna[$cimo] = 'Gravado '.$impuesto['nombre_impuesto'];
            $cimo++;
        }
        $columna[$cimo++] = "TOTAL IVA";
        $columna[$cimo++] = "TOTAL GRAVADO";
        $columna[$cimo++] = "TOTAL EXCLUIDO";
        $columna[$cimo++] = "GRAVADO + EXCLUIDO";
        $columna[$cimo++] = "VENTA TOTAL";

        for ($i = 0; $i < count($columna); $i++) {
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 6, $columna[$i]);
        }


        $row = 7;
        $recorrer = $result->data;
        foreach ($recorrer as $data) {
            $col = 0;
            $total=0;
            foreach ($data as $datum){
                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col, $row, $datum);
                $col++;
            }
            $row++;
        }
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->phpexcel->getDefaultStyle()->applyFromArray($styleArray);

// redireccionamos la salida al navegador del cliente (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteSid.xls"');
        header('Cache-Control: max-age=0');


        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');



    }



    function geProductosParaPedidoSugerido()
    {


        $result = array();
        $datas = array();
        $data = $this->input->get('data');
        $type = $this->input->get('type');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : $this->input->get('fecha_desde');
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : $this->input->get('fecha_hasta');

        $where = array('venta_status' => COMPLETADO);
        if ($fechadesde != "") {
            $where['date(fecha) >= '] = date('Y-m-d', strtotime($fechadesde));
        }
        if ($fechahasta != "") {
            $where['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));
        }
        $search = $this->input->get('search');
        $where_custom = false;

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'id_producto';
        $select = 'SUM(vb.total) as total, producto_nombre,  producto_codigo_interno,
            vb.venta_id, fecha, id_producto, (select codigo_barra from producto_codigo_barra where producto_id  = id_producto limit 1) as codigo_barra';
        $from = "venta_backup vb";
        $join = array('detalle_venta as dvb', 'producto');
        $campos_join = array(
            'dvb.id_venta = vb.venta_id',
            'producto.producto_id = dvb.id_producto');

        $tipo_join = array(false, false);


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

        //  echo $this->db->last_query();


        $count = 0;


        $row=1;




// redireccionamos la salida al navegador del cliente (Excel2007)

        if($type==="excel") {
            $this->phpexcel->setActiveSheetIndex(0);

            foreach ($datas['clientes'] as $data) {

                if(isset( $_GET['prod_'.$data['id_producto']])) {

                    $this->phpexcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow(0, $row,  $this->session->userdata('CODIGO_COOPIDROGAS'));
                    $this->phpexcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow(1, $row, $data['producto_codigo_interno']);
                    $this->phpexcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow(2, $row,  $data['producto_nombre']);
                    $this->phpexcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow(3, $row, $_GET['prod_' . $data['id_producto']]);
                    $this->phpexcel->setActiveSheetIndex(0);
                    $this->phpexcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow(4, $row, $data['codigo_barra']);
                    $this->phpexcel->setActiveSheetIndex(0);

                    $count++;

                    $row++;
                }
            }



            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="ProductosParaPedidoSugerido.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
            $objWriter->save('php://output');
        }else{
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment;filename="ProductosParaPedidoSugerido.txt"');
            header('Cache-Control: max-age=0');
            //$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
            $handle = fopen('php://output', 'w');

            foreach ($datas['clientes'] as $data) {

                if(isset( $_GET['prod_'.$data['id_producto']])) {

                    fwrite($handle, "\"".$this->session->userdata('CODIGO_COOPIDROGAS')."\"\t");
                    fwrite($handle, "\"".$data['producto_codigo_interno']."\"\t");
                    fwrite($handle, "\"".$data['producto_nombre']."\"\t");
                    fwrite($handle,  "\"".$_GET['prod_' . $data['id_producto']]."\"\t");
                    fwrite($handle,   "\"".$data['codigo_barra']."\"\r\n");

                    $count++;

                    $row++;
                }
            }

            fclose($handle);
            exit;
        }






    }



    function excelReporteZona($zona, $desde, $hasta)
    {

        $condicion = array();

        $condicion2 = "venta_status IN ('" . COMPLETADO . "','" . PEDIDO_DEVUELTO . "','" . PEDIDO_ENTREGADO . "','" . PEDIDO_GENERADO . "')";
        $condicion['venta_tipo'] = "ENTREGA";
        $retorno = "RESULT";
        $select = "(SELECT COUNT(venta.id_cliente)) as clientes_atendidos, (SELECT SUM(detalle_venta.cantidad))
                    as cantidad_vendida, usuario.nombre, detalle_venta.*,venta.*, producto.*,grupos.*,familia.*,lineas.*,unidades.*,usuario_has_zona.*,
                    zonas.*,ciudades.*,cliente.razon_social, consolidado_carga.fecha";

        $group = "unidad_medida";

        if ($zona != "TODAS") {

            $condicion['zona_id'] = $this->input->post('id_zona');
            $data['zona'] = $this->input->post('id_zona');


        }
        if ($desde != "") {


            $condicion['date(consolidado_carga.fecha) >= '] = date('Y-m-d', strtotime($desde));
            $data['fecha_desde'] = date('Y-m-d', strtotime($this->input->post('desde')));
        }
        if (($hasta != "")) {


            $condicion['date(consolidado_carga.fecha) <='] = date('Y-m-d', strtotime($hasta));


            $data['fecha_hasta'] = date('Y-m-d', strtotime($this->input->post('hasta')));
        }

        $ventas = $this->venta_model->getProductosZona($select, $condicion, $retorno, $group, $condicion2);

        // configuramos las propiedades del documento
        $this->phpexcel->getProperties()
            //->setCreator("Arkos Noem Arenom")
            //->setLastModifiedBy("Arkos Noem Arenom")
            ->setTitle("ReporteZonaProductos")
            ->setSubject("ReporteZonaProductos")
            ->setDescription("ReporteZonaProductos")
            ->setKeywords("ReporteZonaProductos")
            ->setCategory("ReporteZonaProductos");

        $columna[0] = "DISTRITO";
        $columna[1] = "URBANIZACIÓN";
        $columna[2] = "VENDEDOR";
        $columna[3] = "GRUPO";
        $columna[4] = "FAMILIA";
        $columna[5] = "LINEA";
        $columna[6] = "PRODUCTO";
        $columna[7] = "CANTIDAD VENDIDA";
        $columna[8] = "UNIDAD";
        $columna[9] = "CLIENTES";

        $col = 0;
        for ($i = 0; $i < count($columna); $i++) {

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 1, $columna[$i]);

        }

        $row = 2;

        foreach ($ventas as $venta) {
            $col = 0;

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->ciudad_nombre);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->urb);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->nombre);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->nombre_grupo);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->nombre_familia);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->nombre_linea);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->producto_nombre);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->cantidad_vendida);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->nombre_unidad);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col++, $row, $venta->clientes_atendidos);
            $row++;

        }

// Renombramos la hoja de trabajo
        $this->phpexcel->getActiveSheet()->setTitle('ReporteZonaProductos');

// configuramos el documento para que la hoja
// de trabajo nÃºmero 0 sera la primera en mostrarse
// al abrir el documento
        $this->phpexcel->setActiveSheetIndex(0);


// redireccionamos la salida al navegador del cliente (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteZonaProductos.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');

    }


    function excelReporteUtilidades($local, $fecha_desde, $fecha_hasta, $utilidades)
    {

        if ($local != 0) {
            $condicion = array('local_id' => $local);
        }
        if ($fecha_desde != 0) {

            $condicion['date(fecha) >= '] = date('Y-m-d', strtotime($fecha_desde));
        }
        if ($fecha_hasta != 0) {

            $condicion['date(fecha) <='] = date('Y-m-d', strtotime($fecha_hasta));
        }


        $condicion = "venta_status IN ('" . COMPLETADO . "','" . PEDIDO_DEVUELTO . "','" . PEDIDO_ENTREGADO . "','" . PEDIDO_GENERADO . "')";
        $retorno = "RESULT";

        if ($utilidades == "TODO") {
            $select = "documento_venta.documento_Numero,  documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.razon_social";

            $group = false;

        } elseif ($utilidades == "PRODUCTO") {

            $select = "(SELECT SUM(detalle_utilidad)) AS suma,documento_venta.documento_Numero,  documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.razon_social";
            $group = "producto_id";

        } elseif ($utilidades == "CLIENTE") {

            $select = "(SELECT SUM(detalle_utilidad)) AS suma,documento_venta.documento_Numero,  documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.razon_social,cliente.id_cliente";

            $group = "cliente.id_cliente";
        } elseif ($utilidades == "PROVEEDOR") {

            $select = "(SELECT SUM(detalle_utilidad)) AS suma,documento_venta.documento_Numero,documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.razon_social,cliente.id_cliente,
 proveedor.proveedor_nombre,proveedor.id_proveedor";

            $group = "producto.producto_proveedor";
        }
        $ventas = $this->venta_model->getUtilidades($select, $condicion, $retorno, $group);

        // configuramos las propiedades del documento
        $this->phpexcel->getProperties()
            //->setCreator("Arkos Noem Arenom")
            //->setLastModifiedBy("Arkos Noem Arenom")
            ->setTitle("ReporteUtilidades")
            ->setSubject("ReporteUtilidades")
            ->setDescription("ReporteUtilidades")
            ->setKeywords("ReporteUtilidades")
            ->setCategory("ReporteUtilidades");

        if ($utilidades == "TODO") {
            $columna[0] = "FECHA Y HORA";
            $columna[1] = "CODIGO";
            $columna[2] = "NUMERO";
            $columna[3] = "CANTIDAD";
            $columna[4] = "PRODUCTO";
            $columna[5] = "CLIENTE";
            $columna[6] = "VENDEDOR";
            $columna[7] = "COSTO";
            $columna[8] = "PRECIO";
            $columna[9] = "UTILIDAD";
        } elseif ($utilidades == "PRODUCTO") {
            $columna[0] = "CODIGO";
            $columna[1] = "PRODUCTO";
            $columna[2] = "UTILIDAD";

        } elseif ($utilidades == "CLIENTE") {
            $columna[0] = "CODIGO";
            $columna[1] = "CLIENTE";
            $columna[2] = "UTILIDAD";

        } elseif ($utilidades == "PROVEEDOR") {
            $columna[0] = "CODIGO";
            $columna[1] = "PROVEEDOR";
            $columna[2] = "UTILIDAD";

        }

        $col = 0;
        for ($i = 0; $i < count($columna); $i++) {

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 1, $columna[$i]);

        }

        $row = 2;

        if ($utilidades == "TODO") {

            foreach ($ventas as $venta) {
                $col = 0;

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, date('d-m-Y H:i:s', strtotime($venta->fecha)));

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->venta_id);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->documento_Serie . " " . $venta->documento_Numero);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->cantidad);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->producto_nombre);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->razon_social);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->nombre);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->detalle_costo_promedio);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->precio);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->detalle_utilidad);
                $row++;

            }
        } elseif ($utilidades == "PRODUCTO") {

            foreach ($ventas as $venta) {
                $col = 0;

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->id_producto);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->producto_nombre);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->suma);
                $row++;
            }
        } elseif ($utilidades == "CLIENTE") {

            foreach ($ventas as $venta) {
                $col = 0;

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->id_cliente);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->razon_social);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->suma);
                $row++;
            }
        } elseif ($utilidades == "PROVEEDOR") {

            foreach ($ventas as $venta) {
                $col = 0;

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->id_proveedor);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->proveedor_nombre);

                $this->phpexcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col++, $row, $venta->suma);
                $row++;
            }
        }

// Renombramos la hoja de trabajo
        if ($utilidades == "TODO") {
            $this->phpexcel->getActiveSheet()->setTitle('ReporteUtilidades');
        } elseif ($utilidades == "CLIENTE") {
            $this->phpexcel->getActiveSheet()->setTitle('ReporteUtilidadesPorCliente');
        } elseif ($utilidades == "PROVEEDOR") {
            $this->phpexcel->getActiveSheet()->setTitle('ReporteUtilidadesPorProveedor');
        } elseif ($utilidades == "PRODUCTO") {
            $this->phpexcel->getActiveSheet()->setTitle('ReporteUtilidadesPorProducto');
        }


// configuramos el documento para que la hoja
// de trabajo nÃºmero 0 sera la primera en mostrarse
// al abrir el documento
        $this->phpexcel->setActiveSheetIndex(0);


// redireccionamos la salida al navegador del cliente (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if ($utilidades == "TODO") {
            header('Content-Disposition: attachment;filename="ReporteUtilidades.xlsx"');
        } elseif ($utilidades == "CLIENTE") {
            header('Content-Disposition: attachment;filename="ReporteUtilidadesPorCliente.xlsx"');
        } elseif ($utilidades == "PROVEEDOR") {
            header('Content-Disposition: attachment;filename="ReporteUtilidadesPorProveedor.xlsx"');
        } elseif ($utilidades == "PRODUCTO") {
            header('Content-Disposition: attachment;filename="ReporteUtilidadesPorProducto.xlsx"');
        }


        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');

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


        if (isset($_GET['buscar']) and $_GET['buscar'] == 'pedidos') {
            $data['vendedores'] = $this->usuario_model->select_all_by_roll('VENDEDOR');
            $data['clientes'] = $this->cliente_model->get_all();
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

    function informeVentasFecha()
    {

        $data['locales'] = $this->local_model->get_all();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/reportes/informeVentasFecha', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function participacionVentas()
    {

        $data['vendedores'] = $this->usuario_model->get_all_vendedores();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/reportes/participacionVentas', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function comparativasVendedor()
    {

        $data = array();
        $data['vendedores'] = $this->usuario_model->get_all_vendedores();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/reportes/comparativasVendedor', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function comparativasVendedorGrupo()
    {
        //Esto originalmente era por grupo pero es por tipo

        $data = array();
        $data['vendedores'] = $this->usuario_model->get_all_vendedores();
        $data['grupos'] = $this->tipo_producto_model->get_all('RESULT');
        $dataCuerpo['cuerpo'] = $this->load->view('menu/reportes/comparativasVendedorGrupo', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    function reporteUtilidades()
    {


        $data['locales'] = $this->local_model->get_all();
        $data['todo'] = 1;
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ventas/reporteUtilidades', $data, true);
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
            (select SUM(historial_monto) from historial_pagos_clientes where (historial_pagos_clientes.venta_id = venta.venta_id or
             historial_pagos_clientes.id_credito = credito.credito_id )  ) as confirmar,usuario.nUsuCodigo as vendedor_id, usuario.nombre as vendedor_nombre,,usuario.nombre as vendedor';
            $from = "venta";
            $join = array('credito', 'cliente', 'documento_venta', 'tipo_venta', 'condiciones_pago', 'usuario');
            $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
                'documento_venta.id_venta=venta.venta_id', 'venta.venta_tipo=tipo_venta.tipo_venta_id', 'condiciones_pago.id_condiciones=tipo_venta.condicion_pago', 'usuario.nUsuCodigo=venta.id_vendedor');
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
            $array['draw'] = $draw;//esto debe venir por post
            $array['recordsTotal'] = $total[0]['total'];
            $array['recordsFiltered'] = $total[0]['total']; // esto dbe venir por post

            echo json_encode($array);
        } else {
            redirect(base_url() . 'venta/', 'refresh');
        }


    }


    function getUtiidadesVentas()
    {

        // $post = $this->input->get("data");
        $post = $this->input->post();


        $fecIni = $post['fecIni'];
        $fecFin = $post['fecFin'];
        $utilidades = $post['utilidades'];


        $condicion = "venta_status IN ('" . COMPLETADO . "','" . PEDIDO_DEVUELTO . "','" . PEDIDO_ENTREGADO . "','" . PEDIDO_GENERADO . "')";

        if ($fecIni != "") {
            $condicion .= " and date(fecha) >='" . date('Y-m-d', strtotime($fecIni)) . "'";
            $data['fecIni'] = $fecIni;

        }
        if ($fecFin != "") {
            $condicion . " and date(fecha) <='" . date('Y-m-d', strtotime($fecFin)) . "'";
            $data['fecFin'] = $fecFin;
        }

        $retorno = "RESULT";
        if ($utilidades == "TODOS") {
            $select = "documento_venta.documento_Numero,  cantidad,detalle_venta_unidad.costo_promedio, detalle_venta_unidad.costo,  detalle_venta_unidad.precio_sin_iva, 
            unidades.nombre_unidad,  detalle_venta_unidad.utilidad,
 detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id";
            $data['utilidades'] = "TODO";
            $group = false;


        } elseif ($utilidades == "PRODUCTOS") {

            $select = "(SELECT SUM(detalle_utilidad)) AS suma,documento_venta.documento_Numero,  documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.nombres";
            $data['utilidades'] = "PRODUCTO";
            $group = "producto_id";
        } elseif ($utilidades == "CLIENTE") {

            $select = "(SELECT SUM(detalle_utilidad)) AS suma,documento_venta.documento_Numero,  documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.nombres,cliente.id_cliente";
            $data['utilidades'] = "CLIENTE";
            $group = "cliente.id_cliente";
        } elseif ($utilidades == "PROVEEDOR") {

            $select = "(SELECT SUM(detalle_utilidad)) AS suma,documento_venta.documento_Numero,documento_venta.documento_Serie,
 usuario.nombre,detalle_venta.*,venta.*, producto.producto_nombre,producto.producto_id,cliente.nombres,cliente.id_cliente,
 proveedor.proveedor_nombre,proveedor.id_proveedor";
            $data['utilidades'] = "PROVEEDOR";
            $group = "producto.producto_proveedor";
        }

        $data['ventas'] = $this->venta_model->getUtilidades($select, $condicion, $retorno, $group);

        $this->load->view('menu/ventas/listaReporteUtilidades', $data);
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
            $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
                'documento_venta.id_venta=venta.venta_id', 'condiciones_pago.id_condiciones=venta.condicion_pago',
                'usuario.nUsuCodigo=venta.id_vendedor', 'zonas.zona_id=cliente.id_zona');
            $tipo_join = false;

            $result['lstVenta'] = $this->venta_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, false, false, "RESULT_ARRAY");
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
    function cotizar()
    {
        $idventa = $this->input->post('idventa');
        $result['ventas'] = array();


        $lista_bonos = $this->input->post('lst_bonos', true);
        if (empty($lista_bonos)) $lista_bonos = null;

        $detalles = json_decode($this->input->post('lst_producto', true));


        foreach ($detalles as $detalle) {

            $detallearray = (array)$detalle;

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
                    $innerCell->addText((isset($ventas[0]['serie']) AND isset($ventas[0]['numero'])) ? $ventas[0]['serie'] . $ventas[0]['numero'] : '', array('size' => 12, 'align ' => 'center'), 'pStyle');
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


        try {


            $result['ventas'] = array();


            $detalles = json_decode($this->input->post('lst_producto', true));


            foreach ($detalles as $detalle) {

                $detallearray = (array)$detalle;

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


                $ventas = $detallearray;
            }


            $printer = $this->receiptprint->connectUsb($this->session->userdata('IMPRESORA'), $this->session->userdata('USUARIO_IMPRESORA'), $this->session->userdata('PASSWORD_IMPRESORA'), $this->session->userdata('WORKGROUP_IMPRESORA'));
            /* Initialize */
            $printer->initialize();


            $printer->feed(1);


            $printer->text(isset($ventas[0]['REPRESENTANTE_LEGAL']) ? strtoupper($ventas[0]['REPRESENTANTE_LEGAL']) . " \n" : '');
            $printer->text(isset($ventas[0]['RazonSocialEmpresa']) ? strtoupper($ventas[0]['RazonSocialEmpresa']) . " \n" : '');
            $printer->text('NIT ' . str_pad(isset($ventas[0]['NIT']) ? strtoupper($ventas[0]['NIT']) : '', 10));
            $printer->text('REGIMEN' . isset($ventas[0]['REGIMEN_CONTRIBUTIVO']) ? strtoupper($ventas[0]['REGIMEN_CONTRIBUTIVO']) . " \n" : '');
            $printer->text(isset($ventas[0]['DireccionEmpresa']) ? $ventas[0]['DireccionEmpresa'] . " \n" : '');
            $printer->text('Telf:' . isset($ventas[0]['TelefonoEmpresa']) ? $ventas[0]['TelefonoEmpresa'] . " \n" : '');


            $printer->feed(1);
            $printer->text("----------------------------------------\n");


            $printer->text("COTIZACION DE MEDICAMENTOS");

            $printer->feed(1);
            $printer->text("----------------------------------------\n");

            if (isset($ventas[0]['documento_cliente']) && $ventas[0]['documento_cliente'] != ''
                && $ventas[0]['documento_cliente'] != null && !empty($ventas[0]['documento_cliente'])
            ) {
                $printer->text($ventas[0]['documento_cliente'] . " \n");
                $printer->text($ventas[0]['cliente'] . " " . $ventas[0]['apellidos'] . " \n");
                $printer->text($ventas[0]['direccion_cliente'] . " \n");

                $printer->feed(1);
                $printer->text("----------------------------------------\n");
            }

            $printer->text(" Venta Nº:");
            $printer->text(str_pad(isset($ventas[0]['resolucion_prefijo']) ? isset($ventas[0]['resolucion_prefijo']) : '' . isset($ventas[0]['numero']) ? $ventas[0]['numero'] : '', 10));
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
            $printer->text($this->session->userdata('VENDEDOR_EN_FACTURA')=="CODIGO"?$ventas[0]['id_vendedor']:
                $this->usuario_model->getUserReturnName($ventas[0]['id_vendedor']) . "\n");

            $printer->text("----------------------------------------\n");


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

                        $item = new item($venta['producto_codigo_interno'], $venta['nombre'], $detalle_unidad['precio'], '$', $cantidad, $subtotal, $um);

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

            $printer->text("Sub Total        -> " . MONEDA . " " . number_format($ventas[0]['subTotal'], 2, ',', '.') . "\n");
            $printer->text("Descuento        -> " . MONEDA . " " . number_format($ventas[0]['descuento_valor'] + $ventas[0]['descuento_porcentaje'], 2, ',', '.') . "\n");
            if ($ventas[0]['regimen_iva'] == 1) {
                $printer->text("Excluido         -> " . MONEDA . " " . number_format($ventas[0]['excluido'], 2, ',', '.') . "\n");
                $printer->text("Gravado          -> " . MONEDA . " " . number_format($ventas[0]['gravado'], 2, ',', '.') . "\n");
                $printer->text("Total IVA        -> " . MONEDA . " " . number_format($ventas[0]['impuesto'], 2, ',', '.') . "\n");
                $printer->text("TOTAL FACTURA    -> " . MONEDA . " " . number_format($ventas[0]['montoTotal'], 2, ',', '.') . "\n");
            }
            $printer->text("VALOR ENTREGADO  -> " . MONEDA . " " . number_format($ventas[0]['pagado'], 2, ',', '.') . "\n");
            $printer->text("CAMBIO           -> " . MONEDA . " " . number_format($ventas[0]['cambio'], 2, ',', '.') . "\n");

            $printer->feed(1);


            $printer->text($this->session->userdata('MENSAJE_FACTURA'));

            $printer->feed(5);
            $printer->cut(Printer::CUT_FULL, 3); //corta el papel
            $printer->pulse(); // abre la caja registradora
            /* Close printer */
            $printer->close();


            echo json_encode(array('result' => "success"));

        } catch
        (Exception $e) {
            log_message("error", "Error: Could not print. Message " . $e->getMessage());
            echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
            $this->receiptprint->close_after_exception();
        }


    }

    function directPrint()
    {


        try {


            $idventa = $this->input->post('idventa');

            $result['ventas'] = array();
            if ($idventa != FALSE) {
                $ventas = $this->venta_model->obtener_venta($idventa);


                $impuestos = $this->impuestos_model->get_impuestos();

                if (isset($ventas[0])) {
                    $printer = $this->receiptprint->connectUsb($this->session->userdata('IMPRESORA'), $this->session->userdata('USUARIO_IMPRESORA'), $this->session->userdata('PASSWORD_IMPRESORA'), $this->session->userdata('WORKGROUP_IMPRESORA'));
                    /* Initialize */
                    $printer->initialize();


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


                    if (isset($ventas[0]['documento_cliente']) && $ventas[0]['documento_cliente'] != ''
                        && $ventas[0]['documento_cliente'] != null && !empty($ventas[0]['documento_cliente'])
                    ) {
                        $printer->text($ventas[0]['documento_cliente'] . " \n");
                        $printer->text($ventas[0]['cliente'] . " " . $ventas[0]['apellidos'] . " \n");
                        $printer->text($ventas[0]['direccion_cliente'] . " \n");

                        $printer->feed(1);
                        $printer->text("----------------------------------------\n");
                    }

                    $printer->text(" FACTURA DE VENTA Nº:");
                    $printer->text(str_pad(isset($ventas[0]['resolucion_prefijo']) ? isset($ventas[0]['resolucion_prefijo']) : '' . isset($ventas[0]['numero']) ? $ventas[0]['numero'] : '', 10));
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
                    $printer->text($this->session->userdata('VENDEDOR_EN_FACTURA')=="CODIGO"?$ventas[0]['id_vendedor']:
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

                    $printer->text("Sub Total        -> " . MONEDA . " " . number_format($ventas[0]['subTotal'], 2, ',', '.') . "\n");
                    $printer->text("Descuento        -> " . MONEDA . " " . number_format($ventas[0]['descuento_valor'] + $ventas[0]['descuento_porcentaje'], 2, ',', '.') . "\n");
                    if ($ventas[0]['regimen_iva'] == 1) {
                        $printer->text("Excluido         -> " . MONEDA . " " . number_format($ventas[0]['excluido'], 2, ',', '.') . "\n");
                        $printer->text("Gravado          -> " . MONEDA . " " . number_format($ventas[0]['gravado'], 2, ',', '.') . "\n");
                        $printer->text("Total IVA        -> " . MONEDA . " " . number_format($ventas[0]['impuesto'], 2, ',', '.') . "\n");
                    }
                    $printer->text("TOTAL FACTURA    -> " . MONEDA . " " . number_format($ventas[0]['montoTotal'], 2, ',', '.') . "\n");
                    $printer->text("VALOR ENTREGADO  -> " . MONEDA . " " . number_format($ventas[0]['pagado'], 2, ',', '.') . "\n");
                    $printer->text("CAMBIO           -> " . MONEDA . " " . number_format($ventas[0]['cambio'], 2, ',', '.') . "\n");

                    $printer->feed(1);
                    if ($ventas[0]['regimen_iva'] == 1) {
                        foreach ($impuestos as $impuesto) {
                            $printer->text("Iva" . $impuesto['porcentaje_impuesto'] . "%: ");
                            $printer->text(isset($totales_impuestos[$impuesto['id_impuesto']]) ? $totales_impuestos[$impuesto['id_impuesto']] . "\n" : '');
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

                    $printer->feed(5);
                    $printer->cut(Printer::CUT_FULL, 10); //corta el papel
                    $printer->pulse(); // abre la caja registradora
                    /* Close printer */
                    $printer->close();

                }


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