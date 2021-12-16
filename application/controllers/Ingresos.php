<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Mike42\Escpos\Printer;

class ingresos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ReceiptPrint');
        $this->load->model('cliente/cliente_model', 'cl');
        $this->load->model('local/local_model');
        $this->load->model('producto/producto_model');

        $this->load->model('proveedor/proveedor_model');
        $this->load->model('unidades/unidades_model');
        $this->load->model('ingreso/ingreso_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('detalle_ingreso/detalle_ingreso_model');
        $this->load->model('pagos_ingreso/pagos_ingreso_model');
        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('tipo_producto/tipo_producto_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $this->load->model('grupos/grupos_model');
        $this->load->model('producto_barra/producto_barra_model');


//pd producto pv proveedor
        $this->load->library('Pdf');
        $this->load->library('phpExcel/PHPExcel.php');
        $this->very_sesion();
    }


    function index()
    {


        $idingreso = $this->input->post('idingreso');

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }

        $data["niveles_grupos"] = $this->grupos_model->get_niveles();
        $data['locales'] = $this->local_model->get_all_result();
        $data['tipos_productos'] = $this->tipo_producto_model->get_all();
        $data['condiciones'] = $this->condiciones_pago_model->get_all();
        $data["unidades_medida"] = $this->unidades_model->get_unidades();
        $data["lstProveedor"] = $this->proveedor_model->select_all_proveedor();
        $data["ubicaciones"] = $this->ubicacion_fisica_model->get_all();
        $data["grupos"] = $this->grupos_model->get_grupos();
        $data["metodos"] = $this->condiciones_pago_model->get_all();

        $data['detalles'] = array();
        $data['detalle_especial'] = array();
        $data["ingreso"] = array();
        if ($idingreso != false) {

            $this->load->model('detalleingreso_especial/detalleingreso_especial_model');
            //busco a ver si en esta compra hubo prepacks o obsequios
            $where = array(
                'ingreso.id_ingreso' => $idingreso
            );
            $data['detalle_especial'] = $this->detalleingreso_especial_model->getByidIngreso($where);


            $data["ingreso"] = $this->ingreso_model->get_ingresos_by(array('ingreso.id_ingreso' => $idingreso));
            $data['detalles'] = $this->buscarDetalleIngreso($idingreso);
            $data["ingreso"] = $data["ingreso"][0];
        } else {
            $idingreso = 'false';
        }

        $data['id_ingreso'] = $idingreso;
        $data['columnasToProd'] = VentaColumnasProductosElo::all();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/ingreso/ingresos', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function buscarDetalleIngreso($idingreso)
    {
        //busco el detalle con sus unidades
        $detalle = $this->detalle_ingreso_model->get_by_result('detalleingreso.id_ingreso', $idingreso);
        return $detalle;
    }

    //este metodo busca los detalles con prepack y obsequios, filtrados por id de detalle ingreso
    function getDetalleEspecial()
    {

        $this->load->model('detalleingreso_especial/detalleingreso_especial_model');

        $where = array(
            'detalle_ingreso_id' => $this->input->post('detalle_ingreso_id')
        );
        $datos = $this->detalleingreso_especial_model->getBy($where);

        echo json_encode($datos);

    }


    function get_unidades_has_producto()
    {

        $id_producto = $this->input->post('id_producto');
        $data['unidades'] = $this->unidades_model->get_by_producto($id_producto);
        $data['producto'] = $this->producto_model->get_by('producto_id', $id_producto);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function detallesProducto($id = FALSE)
    {
        if ($id != FALSE) {
            $data['productos'] = $this->ingreso_model->get_detalles_by('id_producto', $id);
        }
        $this->load->view('menu/ventas/detallesProducto', $data);
    }

    function procesar_arcoopidrogas()
    {


        $data = array();
        /*esto es para actualizar el catalogo de coopidrogas*/
        if (!empty($_FILES) and $_FILES['buscar_ruta']['size'] != '0') {

            $filas = file($_FILES['buscar_ruta']['tmp_name']);

            $this->load->model('producto_barra/producto_barra_model');
            $this->load->model('unidades_has_precio/unidades_has_precio_model');


            $array_insert = array();
            $numero_factura = "";
            $codigo_coopidrogas = "";
            $proveedor = $this->proveedor_model->get_by('upper(proveedor_nombre)', "COOPIDROGAS");


            if (sizeof($proveedor) > 0) {
                $yabuscofactura = false;
                foreach ($filas as $v) {

                    $arreglo = array();

                    $datos = explode(",", $v);

                    $codigo_coopidrogas = trim($datos[0]);
                    $numero_factura = trim($datos[2]);
                    $codigo_interno = $this->input->post('facturaespecial') && $this->input->post('facturaespecial')=='on'? 'S'.trim($datos[3]):trim($datos[3]);
                    $descripcion = rtrim($datos[4]);
                    $cantidad_facturada = number_format($datos[5], 0, "", "");
                    $costo_unitario = number_format($datos[6], 2, ".", "");
                    $costo_corriente = number_format($datos[7], 2, ".", "");
                    $iva = $datos[8] == 00000 ? "" : $datos[8] * 100;

                    if ($yabuscofactura == false) {
                        $condicion = array(
                            'documento_numero' => $numero_factura,
                            'int_Proveedor_id' => $proveedor['id_proveedor'],
                            'ingreso_status' => INGRESO_COMPLETADO
                        );
                        $buscarfactura = $this->ingreso_model->get_ingresos_by($condicion);
                        $yabuscofactura = true;
                        if (count($buscarfactura) > 0) {
                            $data['error'] = "La factura ya fu&eacute; ingresada para este proveedor, por favor intente con otra";
                            if ($buscarfactura[0]->ingreso_status == INGRESO_PENDIENTE) {
                                $data['error'] = "Esta factura se encuentra en estatus " . INGRESO_PENDIENTE . " 
                            puede reanudarla en la opci&oacute;n Consultar Compras";
                            }
                            break;
                        }
                    }


                    $bonificacion = trim($datos[9]);
                    $codigodebarra = $datos[10];
                    //busco los datos que me interesan en la tabla producto
                    $tabla_producto = $this->producto_model->get_by('producto_codigo_interno', $codigo_interno);
                    $arreglo['codigo_barra'] = array();

                    if (!empty($tabla_producto)) {
                        //si encontro datos con ese codigo interno

                        $arreglo['productonuevo'] = false;
                        $arreglo['producto_id'] = $tabla_producto['producto_id'];

                        // $arreglo['bonificacion']=$tabla_producto['producto_bonificaciones']==null?0:$tabla_producto['producto_bonificaciones'];

                        //busco el iva
                        /* if($tabla_producto['producto_impuesto']!=null) {
                             $impuesto = $this->impuestos_model->get_by('id_impuesto', $tabla_producto['producto_impuesto']);
                             if(!empty($impuesto['porcentaje_impuesto'])){

                                 $arreglo['iva']=$impuesto['porcentaje_impuesto'];
                             }else{
                                 $arreglo['iva']="";
                             }

                         }else{
                             $arreglo['iva']="";
                         }*/

                        //busco los precios y unidades de este producto
                        $where = array(
                            'id_producto' => $tabla_producto['producto_id']
                        );
                        $arreglo['precios'] = $this->unidades_has_precio_model->get_all_where($where);

                        //busco las unidades de este producto, contenido interno
                        $where = array('unidades_has_producto.producto_id' => $tabla_producto['producto_id']);
                        $arreglo['contenido_interno'] = $this->unidades_model->solo_unidades_xprod($where);

                        //busco el tipo de producto
                        if ($tabla_producto['producto_tipo'] != null) {
                            $where = array('tipo_prod_id' => $tabla_producto['producto_tipo']);
                            $producto_tipo = $this->tipo_producto_model->get_by($where);
                            $arreglo['producto_tipo'] = $producto_tipo['tipo_prod_id'];
                        } else {
                            $arreglo['producto_tipo'] = "";
                        }

                        //busco la ubicacion fisica
                        if ($tabla_producto['producto_ubicacion_fisica'] != null) {
                            $where = array('ubicacion_id' => $tabla_producto['producto_ubicacion_fisica']);
                            $ubicacion_fisica = $this->ubicacion_fisica_model->get_by($where);
                            $arreglo['producto_ubicacion_fisica'] = $ubicacion_fisica['ubicacion_id'];
                        } else {
                            $arreglo['producto_ubicacion_fisica'] = "";
                        }

                        //busco los grupos
                        if ($tabla_producto['produto_grupo'] != null) {
                            $grupo = $this->grupos_model->get_by('id_grupo', $tabla_producto['produto_grupo']);
                            $arreglo['produto_grupo'] = $grupo['id_grupo'];
                        } else {
                            $arreglo['produto_grupo'] = "";
                        }

                        $wherebarra = array(
                            'producto_id' => $tabla_producto['producto_id']
                        );
                        $codigosDeBarra = $this->producto_barra_model->get_codigo_barra($wherebarra);

                        if (count($codigosDeBarra) > 0) {

                            foreach ($codigosDeBarra as $cod) {
                                $arreglo['codigo_barra'][] = $cod['codigo_barra'];
                            }
                        }

                    } else {

                        $arreglo['productonuevo'] = true;
                        $arreglo['producto_id'] = "";
                        $arreglo['contenido_interno'] = array();
                        $arreglo['precios'] = array();
                        $arreglo['producto_ubicacion_fisica'] = "";
                        $arreglo['producto_tipo'] = "";
                        $arreglo['produto_grupo'] = "";
                        $arreglo['codigo_barra'][] = $codigodebarra;

                    }

                    //preguntar por el costo corriente.  estos 4 valores hay que preguntar si son los del archivo o los de la BD
                    $arreglo['cantidad'] = $cantidad_facturada;
                    $arreglo['precio_corriente'] = $costo_corriente;
                    $arreglo['costo_unitario'] = $costo_unitario;
                    $arreglo['iva'] = $iva;
                    $arreglo['bonificacion'] = $bonificacion;
                    $arreglo['descuento'] = "";
                    $arreglo['codigo_interno_sin_comilla'] = $codigo_interno;
                    $arreglo['producto_codigo_interno'] = '"' . $codigo_interno . '"';
                    $arreglo['producto_nombre'] = $descripcion;

                    $arreglo['total_iva'] = '0.00';
                    $arreglo['total'] = '0.00';
                    $arreglo['codigo_interno_temp'] = $codigo_interno . "_0";

                    $array_insert[] = $arreglo;

                    //aqui valido si ya he guardado este producto en el arreglo
                    $encontro = 0;
                    $contarray = 0;

                    //var_dump($codigo_interno);
                    foreach ($array_insert as $row) {
                        //var_dump($row['codigo_interno_sin_comilla']);
                        if ($row['codigo_interno_sin_comilla'] == $codigo_interno) {
                            //var_dump("entro");
                            $array_insert[$contarray]['codigo_interno_temp'] = $codigo_interno . "_" . $encontro;
                            $encontro++;
                        }
                        $contarray++;
                    }

                    // var_dump("_________");
                }
            } else {
                $data['error'] = "El proveedor Coopidrogas no existe o no está asociado a una ciudad";
            }
            $data['numero_documento'] = $numero_factura;
            $data['coopidrogas'] = $array_insert;

            //con esto valido que el codigo del archivo pertenezca a coopidrogas

            if ($codigo_coopidrogas != "" and $codigo_coopidrogas != $this->session->userdata('CODIGO_COOPIDROGAS')) {

                $data['error'] = "El archivo no pertenece a la Droguer&iacute;a";
            }

            if ($this->session->userdata('CODIGO_COOPIDROGAS') == "") {

                $data['error'] = "Debe configurar el c&oacute;digo de Proveedor Principal en Parámetros de instalación, Parámetros generales";
            }


        } else {
            $data['error'] = "Debe ingresar un archivo";
        }

        echo json_encode($data);
    }

    function validaringreso($lst_producto)
    {
        $retorno = true;
        $lst_producto = preg_replace('/[[:cntrl:]]/', '', $lst_producto);
        $lst_producto = json_decode($lst_producto, true);

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
        if (count($lst_producto) < 1) {
            $retorno = "Debe seleccionar al menos un producto";
        }


        return $retorno;
    }

    function registrar_ingreso()
    {

        $json = array();
        if ($this->input->is_ajax_request()) {

            $validar = $this->validaringreso($this->input->post('lst_producto'));

            if ($validar != false) {

                $condicion = array(
                    'documento_numero' => $this->input->post('doc_numero'),
                    'int_Proveedor_id' => $this->input->post('cboProveedor'),
                    'ingreso_status' => INGRESO_COMPLETADO
                );
                $buscarfactura = $this->ingreso_model->get_ingresos_by($condicion);

                if ((count($buscarfactura) < 1) || (count($buscarfactura) > 0 &&
                        $buscarfactura[0]->id_ingreso == $this->input->post('id_ingreso'))
                ) {

                    if ($this->input->post('id_ingreso') == "") {

                        $rs = $this->ingreso_model->insertar_compra($this->input->post());
                    } else {

                        $rs = $this->ingreso_model->update_compra($this->input->post());
                    }

                    if ($rs != false) {
                        $json['success'] = 'Solicitud Procesada con exito';
                        $json['id'] = $rs;
                        $json['estatus_devuelto'] = $this->input->post('status') ? $this->input->post('status') : '';

                    } else {
                        $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
                    }
                } else {
                    $json['error'] = "La factura ya fu&eacute; ingresada para este proveedor, por favor intente con otra";
                }

            } else {
                $json['error'] = $validar;

            }
        } else {

            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }
        echo json_encode($json);
    }


    function lst_reg_ingreso()
    {
        if ($this->input->is_ajax_request()) {
//$data['lstCompra'] = $this->v->select_compra(date("y-m-d", strtotime($this->input->post('fecIni',true))),date("y-m-d", strtotime($this->input->post('fecFin',true))));
//$this->load->view('menu/ventas/tbl_listareg_compra',$data);
            echo json_encode($this->ingreso_model->select_compra(date("y-m-d", strtotime($this->input->post('fecIni', true))), date("y-m-d", strtotime($this->input->post('fecFin', true)))));
        } else {
            redirect(base_url() . 'ingresos/', 'refresh');
        }
    }


    function consultar()
    {

        $data['locales'] = $this->local_model->get_all();
        $data['proveedores'] = $this->proveedor_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ingreso/consultar_ingreso', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function informe_detallado()
    {

        $data['impuestos'] = $this->impuestos_model->get_impuestos();

        $data['locales'] = $this->local_model->get_all();
        $data['impuestos'] = $this->impuestos_model->get_impuestos();
        $data['proveedores'] = $this->proveedor_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ingreso/informe_detallado', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function devolucion()
    {


        $data['locales'] = $this->local_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ingreso/devolucion', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function anular_ingreso()
    {


        $resultado = $this->ingreso_model->anular_ingreso();
        if ($resultado != false) {

            $json['success'] = 'Se ha anulado exitosamente';

        } else {
            $json['error'] = 'Ha ocurrido un error al anular el ingreso';

        }


        echo json_encode($json);
    }

    function eliminar_ingreso()
    {

        $resultado = $this->ingreso_model->eliminar_ingreso();
        if ($resultado != false) {

            $json['success'] = 'Se ha eliminado exitosamente';

        } else {
            $json['error'] = 'Ha ocurrido un error al eliminar el ingreso';

        }


        echo json_encode($json);
    }

    function modificarcompraespecifico()
    {
        $resultado = $this->ingreso_model->modificarcompraespecifico();
        if ($resultado != false) {

            $json['success'] = 'Se ha acomodado exitosamente';

        } else {
            $json['error'] = 'Ha ocurrido un error al acomodar el ingreso';

        }


        echo json_encode($json);
    }


    function get_ingresos()
    {
        $condicion = array();
        if ($this->input->post('id_local') != "seleccione") {
            $condicion = array('local_id' => $this->input->post('id_local'));
            $data['local_id'] = $this->input->post('id_local');
        }
        if ($this->input->post('status') != "seleccione") {
            $condicion['ingreso_status'] = $this->input->post('status');
            $data['status'] = $this->input->post('status');
        }
        if ($this->input->post('proveedor') != "seleccione") {
            $condicion['int_Proveedor_id'] = $this->input->post('proveedor');
            $data['proveedor'] = $this->input->post('proveedor');
        }

        if ($this->input->post('desde') != "") {

            $condicion['fecha_registro >= '] = date('Y-m-d H:i:s', strtotime($this->input->post('desde') . " 00:00:00"));
            $data['fecha_desde'] = date('Y-m-d H:i:s', strtotime($this->input->post('desde') . " 00:00:00"));
        }
        if ($this->input->post('hasta') != "") {

            $condicion['fecha_registro <='] = date('Y-m-d H:i:s', strtotime($this->input->post('hasta') . " 23:59:59"));
            $data['fecha_hasta'] = date('Y-m-d H:i:s', strtotime($this->input->post('hasta') . " 23:59:59"));
        }

        if ($this->input->post('anular') != 0) {

            $data['anular'] = 1;
        }


        $data['ingresos'] = $this->ingreso_model->get_ingresos_by($condicion);

        $this->load->view('menu/ingreso/lista_ingreso', $data);

    }

    function informe_detallado_get()
    {
        $condicion = array();
        if ($this->input->post('id_local') != "seleccione") {
            $condicion = array('local_id' => $this->input->post('id_local'));
            $data['local_id'] = $this->input->post('id_local');
        }
        if ($this->input->post('status') != "seleccione") {
            $condicion['ingreso_status'] = $this->input->post('status');
            $data['status'] = $this->input->post('status');
        }
        if ($this->input->post('proveedor') != "seleccione") {
            $condicion['int_Proveedor_id'] = $this->input->post('proveedor');
            $data['proveedor'] = $this->input->post('proveedor');
        }

        if ($this->input->post('desde') != "") {

            $condicion['fecha_registro >= '] = date('Y-m-d H:i:s', strtotime($this->input->post('desde') . " 00:00:00"));
            $data['fecha_desde'] = date('Y-m-d H:i:s', strtotime($this->input->post('desde') . " 00:00:00"));
        }
        if ($this->input->post('hasta') != "") {

            $condicion['fecha_registro <='] = date('Y-m-d H:i:s', strtotime($this->input->post('hasta') . " 23:59:59"));
            $data['fecha_hasta'] = date('Y-m-d H:i:s', strtotime($this->input->post('hasta') . " 23:59:59"));
        }

        if ($this->input->post('anular') != 0) {

            $data['anular'] = 1;
        }


        $data['impuestos'] = $this->impuestos_model->get_impuestos();
        $data['ingresos'] = $this->ingreso_model->get_informe_det_ingreso($condicion);

        $this->load->view('menu/ingreso/lista_informe_detallado', $data);

    }

    function form($id = FALSE, $local = false)
    {

        $data = array();
        $data["unidades"] = $this->unidades_model->get_unidades();
        if ($id != FALSE and $local != false) {
            $data['detalles'] = $this->detalle_ingreso_model->get_by_result('detalleingreso.id_ingreso', $id);
            $data['id_detalle'] = $id;
        }

        $this->load->view('menu/ingreso/form_detalle_ingreso', $data);
    }

    function directPrintCompra($id = FALSE)
    {


        try {

            $total = 0;
            $where = array(
                'id_ingreso' => $id
            );
            $ingreso = $this->ingreso_model->getSoloIngreso($where);
            $unidades = $this->unidades_model->get_unidades();
            $data = $this->buscarDetalleIngreso($id);
            $data = (array)$data;


            $printer = $this->receiptprint->connectUsb($this->session->userdata('IMPRESORA'), $this->session->userdata('USUARIO_IMPRESORA'), $this->session->userdata('PASSWORD_IMPRESORA'), $this->session->userdata('WORKGROUP_IMPRESORA'));
            /* Initialize */
            $printer->initialize();
            $printer->feed(1);

            $nombreempresa = $this->session->userdata('EMPRESA_NOMBRE');
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($nombreempresa . " \n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text('NIT ' . str_pad($this->session->userdata('NIT') . "\n", 10));

            //$printer->text($data['REGIMEN_CONTRIBUTIVO']['regimen_nombre'] . " \n");
            $printer->text($this->session->userdata('EMPRESA_DIRECCION') . " \n");
            $printer->text('Telf:' . $this->session->userdata('EMPRESA_TELEFONO') . " \n");
            //$printer->text("REPORTES/COMPROBANTEDIARIOVENTAS:");

            $printer->text('Factura Nº  ' . $ingreso['documento_numero'] . "\n");
            $printer->text('Fecha:  ' . date("d-m-Y", strtotime($ingreso['fecha_registro'])) . "\n");

            $printer->text("----------------------------------------\n");
            $printer->text(str_pad("Cod", 5));
            $printer->text(str_pad("Nombre", 11));
            $printer->text(str_pad("Uni", 5));
            $printer->text(str_pad("Cant", 5));
            $printer->text(str_pad("Costo", 5));

            $printer->feed(1);
            $printer->text("----------------------------------------\n");
            foreach ($data as $inv) {

                foreach ($inv->detalle_unidad as $row) {
                    $printer->text(str_pad($inv->producto_codigo_interno, 5));
                    $printer->feed(1);
                    $printer->text(str_pad($inv->producto_nombre, 11));
                    $printer->feed(1);

                    $abreviatura = "";
                    foreach ($unidades as $unidad) {
                        if ($unidad['id_unidad'] == $row->unidad_id) {
                            $abreviatura = $unidad['abreviatura'];
                        }
                    }
                    $printer->text(str_pad($abreviatura, 5));
                    $printer->text(str_pad($row->cantidad, 5));
                    $printer->text(str_pad($row->costo_total, 5));
                    $printer->feed(1);
                }
            }
            $printer->text("----------------------------------------\n");
            $printer->text(str_pad("Total: ".$ingreso['total_ingreso'], 5));

            $printer->feed(5);
            $printer->cut(Printer::CUT_FULL, 10); //corta el papel
            $printer->pulse(); // abre la caja registradora
            /* Close printer */
            $printer->close();

            echo json_encode(array('result' => "success"));

        } catch
        (Exception $e) {
            log_message("error", "Error: Could not print. Message " . $e->getMessage());
            echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
            $this->ReceiptPrint->close_after_exception();
        }
    }


    function pedidosugerido()
    {

        $data = array();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ingreso/pedidosugerido', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

}
