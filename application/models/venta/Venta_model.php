<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class venta_model extends CI_Model
{
    public $CI;

    function __construct()
    {

        $this->load->model('kardex/kardex_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('producto/paquete_has_prod_model');
        $this->load->model('resolucion/resolucion_model');
        $this->load->model('unidades/unidades_model');
        $this->load->model('comision_vendedor/comision_model');
        parent::__construct();
    }

    function get_all()
    {
    }

    function get_parche_santamonica()
    {


        $query = "SELECT venta.*, credito.credito_id FROM venta 
JOIN detalle_venta ON `detalle_venta`.`id_venta`=venta.`venta_id`
JOIN credito ON credito.`id_venta`=venta.`venta_id`
JOIN documento_venta ON documento_venta.`id_venta`=venta.`venta_id`
WHERE DATE(fecha)='2019-03-02' AND `detalle_venta`.`id_producto`=31639
";

        return $this->db->query($query)->result_array();
    }


    function update_credito_parche_santamonica($credito_id, $id_cliente, $fecha)
    {


        $query = "UPDATE credito SET id_venta=NULL, id_cliente=$id_cliente ,
 credito_fecha='" . $fecha . "' WHERE credito_id=$credito_id";

        echo $query;
        $this->db->query($query);
    }

    function update_historial_credito_parche_santamonica($credito_id, $venta_id)
    {


        $query = "UPDATE historial_pagos_clientes SET id_credito=$credito_id WHERE venta_id=$venta_id";

        echo $query;
        $this->db->query($query);
    }

    function generarnumeroFactura($tipodoc)
    {

        /*********BUSCO LA ULTIMA RESOLUCION DE LA DIAN*******/
        $resolucion = $this->resolucion_model->get_last();


        if (count($resolucion) > 0) {
            $inicio = $resolucion['resolucion_numero_inicial'];
            $fin = $resolucion['resolucion_numero_final'];
        } else {
            return false;
        }
        $query_ins = $this->db->query("select 		   
		  `documento_venta`.`documento_Numero` AS `NUMERO`, `documento_venta`.`nombre_tipo_documento` AS `Documento`
		   from `documento_venta` where `documento_venta`.`nombre_tipo_documento` = '" . $tipodoc . "' and id_resolucion =" . $resolucion['resolucion_id'] . "
		    order by `documento_venta`.`id_tipo_documento` desc limit 0,1");
        $rs_ins = $query_ins->row_array();


        if (empty($rs_ins['NUMERO'])) {
            $numero = $inicio;
        } else {

            $numero = intval($rs_ins['NUMERO']) + 1;
        }

        if ($numero > $fin) {
            return false;
        } else {
            return array('id_resolucion' => $resolucion['resolucion_id'], 'numero' => $numero);
        }
    }

    function generarnumeroFacturaElectronica($resolucion, $FACT_E_resolucion_start_in)
    {


        if (count($resolucion) > 0) {
            $inicio = $FACT_E_resolucion_start_in;
            $fin = $resolucion['to'];
        } else {
            return false;
        }
        $query_ins = $this->db->query("select 		   
		fe_numero AS `NUMERO`
		   from venta where fe_resolution_id =" . $resolucion['id'] . "  
		    order by fe_numero desc limit 0,1");
        $rs_ins = $query_ins->row_array();



        if (empty($rs_ins['NUMERO']) || $inicio == '1') {
            $numero = $inicio;
        } else {

            $numero = intval($rs_ins['NUMERO']) + 1;
        }

      //  echo $numero;
        if ($numero < $FACT_E_resolucion_start_in) {
            $numero = $FACT_E_resolucion_start_in;
        }
      
        if ($numero > $fin) {
            return false;
        } else {

            return array('id_resolucion' => $resolucion['id'], 'numero' => $numero);
        }
    }

    function generarnumeroNotaCredito($resolucion, $FACT_E_resolucion_start_in)
    {


        if (count($resolucion) > 0) {
            $inicio = $FACT_E_resolucion_start_in;
            $fin = $resolucion['to'];
        } else {
            return false;
        }
        $query_ins = $this->db->query("select 		   
		credit_note.number AS `numero`
		   from credit_note where resolution_id =" . $resolucion['id'] . "  
		    order by credit_note.number desc limit 0,1");
        $rs_ins = $query_ins->row_array();


        if (empty($rs_ins['numero'])) {
            $numero = $inicio;
        } else {

            $numero = intval($rs_ins['numero']) + 1;
        }

        if ($numero < $FACT_E_resolucion_start_in) {
            $numero = $FACT_E_resolucion_start_in;
        }
        if ($numero > $fin) {
            return false;
        } else {
            return array('id_resolucion' => $resolucion['id'], 'numero' => $numero);
        }
    }

    function generarnumeroNotaDebito($resolucion, $FACT_E_resolucion_start_in)
    {


        if (count($resolucion) > 0) {
            $inicio = $FACT_E_resolucion_start_in;
            $fin = $resolucion['to'];
        } else {
            return false;
        }
        $query_ins = $this->db->query("select 		   
		debit_note.number AS `numero`
		   from debit_note where resolution_id =" . $resolucion['id'] . "  
		    order by debit_note.number desc limit 0,1");
        $rs_ins = $query_ins->row_array();


        if (empty($rs_ins['numero'])) {
            $numero = $inicio;
        } else {

            $numero = intval($rs_ins['numero']) + 1;
        }

        if ($numero < $FACT_E_resolucion_start_in) {
            $numero = $FACT_E_resolucion_start_in;
        }
        if ($numero > $fin) {
            return false;
        } else {
            return array('id_resolucion' => $resolucion['id'], 'numero' => $numero);
        }
    }


    //TODO REMOVER $cantidad_venta  A MENOS QUE SE QUIERA DEVOLVER EL COSTO DE TODA LA CANTIDAD QUE SE MOVIO

    /**************calculo el costo de una unidad especiica de la venta****/
    function calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, $tipo_calculo)
    {

        //Busco las unidades de medida del producto
        $unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $id_producto));


        $query_costo_u = $this->db->query("select producto_id,  costo_unitario, costo_promedio from producto
                    WHERE producto_id=" . $id_producto . " ");
        $costo_unitario = $query_costo_u->row_array();


        $costo = 0;

        if ($tipo_calculo == COSTO_UNITARIO) {
            $costo = isset($costo_unitario['costo_unitario']) ? $costo_unitario['costo_unitario'] : 0;
        } else {
            $costo = isset($costo_unitario['costo_promedio']) ? $costo_unitario['costo_promedio'] : 0;
        }


        //declaro el objeto de cada unidad
        $caja = $unidades_producto[0];
        $sizeof = sizeof($unidades_producto);
        if ($sizeof == 3) {
            $blister = $unidades_producto[1];
            $unidad = $unidades_producto[2];
        } else if ($sizeof == 2) {
            $unidad = $unidades_producto[1];
        }
        if ($unidad_medida_venta == $caja['id_unidad']) {

            $costo = $costo;
        }
        if (isset($blister) && $unidad_medida_venta == $blister['id_unidad']) {

            $costo = ($costo / $blister['unidades']);
        }
        if (isset($unidad) && $unidad_medida_venta == $unidad['id_unidad']) {

            $costo = ($costo / $caja['unidades']);
        }


        return $costo;
    }


    function insertar_venta($venta_cabecera, $detalle)
    {
        try {
            $this->db->trans_begin();


            $venta_cabecera['tipo_documento'] = FACTURA;


            /*****************/
            $venta = array(
                'fecha' => $venta_cabecera['fecha'],
                'id_cliente' => $venta_cabecera['id_cliente'],
                'id_vendedor' => $venta_cabecera['id_vendedor'],
                'cajero_id' => $venta_cabecera['cajero_id'],
                'caja_id' => $venta_cabecera['caja_id'],
                'venta_status' => $venta_cabecera['venta_status'],
                'local_id' => $venta_cabecera['local_id'],
                'subtotal' => $venta_cabecera['subtotal'],
                'total_impuesto' => $venta_cabecera['total_impuesto'],
                'total_otros_impuestos' => $venta_cabecera['total_otros_impuestos'],
                'total' => $venta_cabecera['total'],
                'cambio' => $venta_cabecera['cambio'],
                'pagado' => $venta_cabecera['importe'],
                'descuento_valor' => $venta_cabecera['descuento_valor'],
                'descuento_porcentaje' => $venta_cabecera['descuento_porcentaje'],
                'porcentaje_desc' => $venta_cabecera['porcentaje_desc'],
                'venta_tipo' => $venta_cabecera['venta_tipo'],
                'gravado' => $venta_cabecera['gravado'],
                'excluido' => $venta_cabecera['excluido'],
                //'nota' => isset($venta_cabecera['nota'])?$venta_cabecera['nota']:'',
                'desc_global' => $venta_cabecera['desc_global'],
                'fe_numero' => $venta_cabecera['fe_numero'],
                'fe_resolution_id' => $venta_cabecera['fe_resolution_id'],
                'uuid' => $venta_cabecera['uuid'],
                'fe_reponseDian' => $venta_cabecera['fe_reponseDian'],
                'fe_XmlFileName' => $venta_cabecera['fe_XmlFileName'],
                'fe_zipkey' => $venta_cabecera['fe_zipkey'],
                'fe_issue_date' => $venta_cabecera['fe_issue_date'],
                'fe_prefijo' => $venta_cabecera['fe_prefijo'],
                'fe_status' => $venta_cabecera['fe_status'],
                'fe_type_document' => $venta_cabecera['fe_type_document'],
                'regimen_contributivo' => $this->session->userdata('REGIMEN_CONTRIBUTIVO'),
            );
            $this->db->insert('venta', $venta);
            $venta_id = $this->db->insert_id();
            $numero = $venta_cabecera['numero'];
            if ($venta_cabecera['id_resolucion'] != null) {
                $tip_doc = array(
                    'nombre_tipo_documento' => $venta_cabecera['tipo_documento'],
                    'documento_Numero' => $numero,
                    'id_venta' => $venta_id,
                    'id_resolucion' => $venta_cabecera['id_resolucion'],
                );

                $this->db->insert('documento_venta', $tip_doc);
            }

            ///// backup de venta
            $venta['venta_id'] = $venta_id;
            $this->db->insert('venta_backup', $venta);


            // Detalle de la Venta
            $procesoDetalle = $this->procesarDetalleVenta($detalle, $venta_id, $venta_cabecera, $numero);

            if ($procesoDetalle !== TRUE) {
                return $procesoDetalle;
            }


            // Venta a Credito
            if ($venta_cabecera['diascondicionpagoinput'] > 0) {
                $credito = array(
                    'id_venta' => $venta_id,
                    'dec_credito_montodeuda' => $venta_cabecera['total'],
                    'var_credito_estado' => CREDITO_DEBE,
                    'dec_credito_montodebito' => 0,
                    'credito_dias' => !empty($venta_cabecera['cliente']->dias_credito) ? $venta_cabecera['cliente']->dias_credito : $venta_cabecera['diascondicionpagoinput']
                );
                $this->insertCredito($credito);
            }


            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();

                return GLOBAL_ERROR;
            } else {
                $this->db->trans_commit();
                return $venta_id;
            }


            $this->db->trans_off();
        } catch (Exception $e) {
            $this->db->devolverSinError();
            log_message('error', $e->getMessage());
            return GLOBAL_ERROR;
        }
    }


    function procesarDetalleVenta($detalle, $venta_id, $venta_cabecera, $numero)
    {

        $comisiones = array();

        foreach ($detalle as $row) {
            $id_producto = $row->id_producto;
            $nombre_producto = $row->nombre;
            $is_paquete = $row->is_paquete;
            $descuento = floatval($row->descuento);
            $desc_porcentaje = floatval($row->desc_porcentaje);
            $impuesto = floatval($row->impuesto);
            $otro_impuesto = floatval($row->otro_impuesto);
            $subtotal = floatval($row->subtotal);
            $total = floatval($row->total);
            $control_inven = floatval($row->control_inven);


            $porcentaje_impuesto = floatval($row->porcentaje_impuesto);
            $porcentaje_otro_impuesto = floatval($row->porcentaje_otro_impuesto);
            $porcentaje_comision = floatval($row->porcentaje_comision);

            $detalle_item = array(
                'id_venta' => $venta_id,
                'id_producto' => $id_producto,
                'porcentaje_impuesto' => $porcentaje_impuesto,
                'porcentaje_otro_impuesto' => $porcentaje_otro_impuesto,
                'descuento' => $descuento,
                'desc_porcentaje' => $desc_porcentaje,
                'subtotal' => $subtotal,
                'impuesto' => $impuesto,
                'otro_impuesto' => $otro_impuesto,
                'total' => $total,


            );
            $this->db->insert('detalle_venta', $detalle_item);
            $detalle_venta_id = $this->db->insert_id();

            if ($venta_cabecera['venta_status'] == COMPLETADO) {
                /***********************************************************************************************************/
                //COMISION VENDEDORES
                /**************************************************************************************************************/
                if ($porcentaje_comision > 0) {

                    $monto_comision = ($porcentaje_comision * ($subtotal - $descuento)) / 100;
                    array_push($comisiones, array('id_vendedor' => $venta_cabecera['id_vendedor'], 'id_detalle_venta' =>
                    $detalle_venta_id, 'porcentaje' => $porcentaje_comision, 'comision' => $monto_comision));
                }

                /**************************************************************************************************/


                /***********************************************************************************************************/
                //SALVAR METODOS DE PAGO
                /************************************************************************************************************/
                $i = 0;
                $formas_pago_venta = array();

                /*si tiene registro aqui, lo elimino primero, para actualizar los datos*/
                $this->db->delete('venta_forma_pago', array('id_venta' => $venta_id));
                foreach ($venta_cabecera['forma_pago'] as $formapago) {
                    $fp = array();
                    $fp['monto'] = $venta_cabecera['forma_pago_monto_'][$i];
                    $fp['id_forma_pago'] = $venta_cabecera['forma_pago'][$i];
                    $fp['nro_recibo'] = $venta_cabecera['numero_recibo_monto_'][$i];
                    $fp['id_venta'] = $venta_id;

                    array_push($formas_pago_venta, $fp);
                    $i++;
                }
                $this->db->insert_batch('venta_forma_pago', $formas_pago_venta);

                /*************************************************************************************************************/
            }

            /***********************esto es para la tabla detalle_venta_backup******************************/
            // si estoy editando la venta y NO es una devolucion
            if ($venta_cabecera['venta_status'] == COMPLETADO && $total > 0) {

                $this->db->insert('detalle_venta_backup', $detalle_item);
                $detalle_venta_bk_id = $this->db->insert_id();
            }
            /************************************/
            $totalentabla = 0;
            $unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $id_producto));
            $total_stock_minimas = $this->inventario_model->stockUnidadesMinimas($id_producto, $venta_cabecera['local_id']);

            foreach ($row->unidades as $detalle_unidad) {


                $precio = isset($detalle_unidad->precio) ? floatval($detalle_unidad->precio) : 0;
                $precio_sin_iva = isset($detalle_unidad->precio_sin_iva) ? floatval($detalle_unidad->precio_sin_iva) : 0;

                $costo = 0;
                $costo_promedio = 0;

                if (isset($detalle_unidad->cantidad)) {
                    $utilidad = 0;

                    if ($is_paquete == '1') {  //ENTRA AQUI CUANDO ES UN PQUETE, YA QUE EL INVENTARIO SE RESTA ALOS PROUCTOS QUE CONTIENE EL PAQUETE
                        $productos_paquete = $this->paquete_has_prod_model->get_all_by_prod_grouped($id_producto);


                        $costo = 0;
                        $costo_promedio = 0;
                        $totalentablapaquete = 0;


                        foreach ($productos_paquete as $prodpaq) {

                            $id_producto_paquete = $prodpaq['prod_id'];
                            $nombre_producto_paquete = $prodpaq['producto_nombre'];

                            $total_stock_minimaspaquete = $this->inventario_model->stockUnidadesMinimas($id_producto_paquete, $venta_cabecera['local_id']);

                            foreach ($prodpaq['unidades'] as $detallepag) {

                                $cantidad_venta_paquete = floatval($detallepag['cantidad']) * floatval($detalle_unidad->cantidad);
                                $unidad_medida_venta_paquete = $detallepag['unidad_id'];

                                log_message('debug', "Producto paquete maneja inventario: " . $prodpaq['control_inven']);
                                if ($prodpaq['control_inven'] == 1) {
                                    $totalentablapaquete += $this->inventario_model->covertUnidadMinima(
                                        $unidades_producto,
                                        $unidad_medida_venta_paquete,
                                        $cantidad_venta_paquete
                                    );
                                    log_message('info', $nombre_producto_paquete . " Validando inventario ...");
                                }


                                $costo_promedio_paq = $this->calcularCostos(
                                    $id_producto_paquete,
                                    $cantidad_venta_paquete,
                                    $unidad_medida_venta_paquete,
                                    COSTO_PROMEDIO
                                );

                                $costopaq = $this->calcularCostos(
                                    $id_producto_paquete,
                                    $cantidad_venta_paquete,
                                    $unidad_medida_venta_paquete,
                                    COSTO_UNITARIO
                                );
                                $costo = $costo + $costopaq;
                                $costo_promedio = $costo_promedio_paq + $costo_promedio;

                                if ($prodpaq['control_inven'] == 1 and $totalentablapaquete > $total_stock_minimaspaquete) {

                                    //TODO REVISAR ESTO MISMO EN LOS OTROS PROCESOS
                                    //NO ENTRA EN EL FLUJO NORMAL PORQUE NO TIENE STOCK
                                } else {
                                    if ($venta_cabecera['venta_status'] == COMPLETADO) {
                                        if ($prodpaq['control_inven'] == '1') {
                                            /**RESTO DEL INVENTARIO*/
                                            $return_inventario = $this->inventario_model->restar_inventario(
                                                $id_producto_paquete,
                                                $venta_cabecera['local_id'],
                                                $unidad_medida_venta_paquete,
                                                $cantidad_venta_paquete
                                            );
                                        } else {
                                            $return_inventario = $this->inventario_model->get_stock_array(
                                                $id_producto_paquete,
                                                $venta_cabecera['local_id'],
                                                $unidad_medida_venta_paquete
                                            );
                                        }


                                        /****INSERTO EL KARDEX**/
                                        //INSERTAR KARDEX SOLO SI EL ESTADO ES COMPLETADO
                                        if ($venta_cabecera['venta_status'] == COMPLETADO) {
                                            $this->kardex_model->set_kardex(
                                                $id_producto_paquete,
                                                $venta_cabecera['local_id'],
                                                $unidad_medida_venta_paquete,
                                                $cantidad_venta_paquete,
                                                ($venta_cabecera['condicion_pago'] > 1) ? VENTA_CREDITO : VENTA_CONTADO,
                                                $venta_cabecera['cajero_id'],
                                                $precio,
                                                $venta_id,
                                                $numero,
                                                $venta_cabecera['tipo_documento'],
                                                $venta_cabecera['id_cliente'],
                                                SALIDA,
                                                json_encode($return_inventario['stockviejo_array']),
                                                json_encode($return_inventario['stocknuevo_array']),
                                                null,
                                                $prodpaq['producto_impuesto'],
                                                $prodpaq['costo_unitario'] //TODO: HABRIA QUE VER SI ES NECESARIO GUARDAR EL COSTO DE LA UNIDAD EN VEZ DEL COSTO DE LA CAJA COMO SE HACE CON LOS PRODUCTOS QUE NO SON PREPACKS
                                            );
                                        }
                                    }
                                }
                            }

                            if ($prodpaq['control_inven'] == 1 and $totalentablapaquete > $total_stock_minimaspaquete) {
                                $this->db->devolverSinError();
                                return NO_STOCK . $nombre_producto_paquete;
                            }
                        }
                        $cantidad_venta = floatval($detalle_unidad->cantidad);
                        $utilidad = $this->calcularUtilidad($precio_sin_iva, $cantidad_venta, $costo);
                    }

                    $cantidad_venta = floatval($detalle_unidad->cantidad);
                    $unidad_medida_venta = $detalle_unidad->id_unidad;

                    /**RESTO DEL INVENTARIO*/
                    //  echo 'la que resta '.$unidad_medida_venta. " ".$cantidad_venta;
                    if ($is_paquete != '1') {

                        if ($row->control_inven == 1) {
                            $totalentabla += $this->inventario_model->covertUnidadMinima(
                                $unidades_producto,
                                $unidad_medida_venta,
                                $cantidad_venta
                            );
                        }
                        if ($row->control_inven == 1 and $totalentabla > $total_stock_minimas) {

                            //TPODO REVISAR ESTO MISMO EN LOS TOROS PROCESOS
                            //NO PASA POR EL PROCESO NORMAL PORQUE NO TIENE STOCK
                        } else {
                            if ($venta_cabecera['venta_status'] == COMPLETADO) {
                                if ($row->control_inven == 1) {
                                    $return_inventario = $this->inventario_model->restar_inventario($id_producto, $venta_cabecera['local_id'], $unidad_medida_venta, $cantidad_venta);
                                } else {
                                    $return_inventario = $this->inventario_model->get_stock_array(
                                        $id_producto,
                                        $venta_cabecera['local_id'],
                                        $unidad_medida_venta
                                    );
                                }
                                if ($cantidad_venta > 0) {

                                    $costo_promedio = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_PROMEDIO);
                                    $costo = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_UNITARIO);


                                    if ($venta_cabecera['venta_status'] == COMPLETADO) {
                                        $this->kardex_model->set_kardex(
                                            $id_producto,
                                            $venta_cabecera['local_id'],
                                            $unidad_medida_venta,
                                            $cantidad_venta,
                                            ($venta_cabecera['condicion_pago'] > 1) ? VENTA_CREDITO : VENTA_CONTADO,
                                            $venta_cabecera['cajero_id'],
                                            $precio,
                                            $venta_id,
                                            $numero,
                                            $venta_cabecera['tipo_documento'],
                                            $venta_cabecera['id_cliente'],
                                            SALIDA,
                                            json_encode($return_inventario['stockviejo_array']),
                                            json_encode($return_inventario['stocknuevo_array']),
                                            null,
                                            $porcentaje_impuesto,
                                            $costo // ANTES SE INSERTABA EL COSTO DE LA CAJA, AHORA CON ESTO QUEDO EL COSTO DE LA UNIDAD QUE ESTA VENDIENDO
                                        );
                                    }

                                    $utilidad = $this->calcularUtilidad($precio_sin_iva, $cantidad_venta, $costo);
                                }
                            }
                        }
                    }


                    if ($cantidad_venta > 0) {
                        $detalle_venta_unidad = array(
                            'unidad_id' => $unidad_medida_venta,
                            'cantidad' => $cantidad_venta,
                            'precio' => $precio,
                            'precio_sin_iva' => $precio_sin_iva,
                            'utilidad' => $utilidad,
                            'costo' => $costo,
                            'costo_promedio' => $costo_promedio,
                            'detalle_venta_id' => $detalle_venta_id,

                        );

                        $this->db->insert('detalle_venta_unidad', $detalle_venta_unidad);

                        if ($venta_cabecera['venta_status'] == COMPLETADO && $total > 0) {
                            $detalle_venta_unidad['detalle_venta_id'] = $detalle_venta_bk_id;

                            if ($venta_cabecera['venta_status'] == COMPLETADO) {
                                $detalle_venta_unidad_backup = array(
                                    'unidad_id' => $unidad_medida_venta,
                                    'cantidad' => $cantidad_venta,
                                    'precio' => $precio,
                                    'precio_sin_iva' => $precio_sin_iva,
                                    'utilidad' => $utilidad,
                                    'costo' => $costo,
                                    'costo_promedio' => $costo_promedio,
                                    'detalle_venta_id' => $detalle_venta_bk_id,

                                );


                                $this->db->insert('detalle_venta_unidad_backup', $detalle_venta_unidad_backup);
                            }
                        }
                    }
                }
            }


            if ($row->control_inven == 1 and $totalentabla > $total_stock_minimas) {
                $this->db->devolverSinError();
                return NO_STOCK . $nombre_producto;;
            }
        }

        if (count($comisiones) > 0) {
            $this->comision_model->insert_bacth($comisiones);
        }

        return TRUE;
    }

    function restoreVenta($venta_id, $venta_cabecera)
    {
        $venta_backup_ieja = $this->get_venta_backup($venta_id);

        foreach ($venta_backup_ieja as $row) {
            $venta_backup = array(
                'id_cliente' => $row['id_cliente'],
                'fecha' => $row['fecha'],
                'local_id' => $row['local_id'],
                'subtotal' => $row['subtotal'],
                'venta_status' => $venta_cabecera['venta_status'],
                'total_impuesto' => $row['total_impuesto'],
                'total' => $row['total'],
                'pagado' => $row['pagado'],
                'venta_tipo' => $row['venta_tipo']
            );
            $this->db->where('venta_id', $venta_id);
            $this->db->update('venta', $venta_backup);
        }
        $this->db->where('id_venta', $venta_id);
        $this->db->delete('detalle_venta');

        $query_detalle_venta_backup = $this->get_detalle_venta_backup($venta_id);
        foreach ($query_detalle_venta_backup as $row) {
            $detalle_item = array(
                'id_venta' => $venta_id,
                'id_producto' => $row['id_producto'],

            );

            $this->db->insert('detalle_venta', $detalle_item);

            $detalle_venta_unidad = $this->get_detalle_venta_unidad_backup($row->id_detalle);
            foreach ($detalle_venta_unidad as $detalleunidad) {
                $detalleunidad_item = array(
                    'unidad_id' => $detalleunidad['unidad_medida'],
                    'cantidad' => $detalleunidad['cantidad'],
                    'precio' => $detalleunidad['precio'],
                    'detalle_venta_id' => $detalleunidad['detalle_venta_id'],
                    'utilidad' => $detalleunidad['utilidad'],
                    'precio_sin_iva' => $detalleunidad['precio_sin_iva'],
                    'costo' => $detalleunidad['costo'],
                    'impuesto' => $detalleunidad['impuesto']
                );
                $this->db->insert('detalle_venta_unidad', $detalleunidad_item);
            }
        }
    }

    //ESTE METODO SOLAENTE SE USA EN DEVOLUCION DE VENTAS
    function devolver_venta($venta_cabecera, $detalle = false)
    {

        $comisiones = array();

        $ventadev_id = null;
        // var_dump($venta_cabecera);
        $this->db->trans_begin();
        $venta_id = $venta_cabecera['venta_id'];
        $venta = array(
            //'local_id' => $venta_cabecera['local_id'],
            'subtotal' => $venta_cabecera['subtotal'],
            'total_impuesto' => $venta_cabecera['total_impuesto'],
            'total_otros_impuestos' => $venta_cabecera['total_otros_impuestos'],
            'total' => $venta_cabecera['total'],
            'gravado' => $venta_cabecera['gravado'],
            'excluido' => $venta_cabecera['excluido'],
            'descuento_valor' => $venta_cabecera['descuento_valor'],
            'descuento_porcentaje' => $venta_cabecera['descuento_porcentaje'],
            'porcentaje_desc' => $venta_cabecera['porcentaje_desc'],

        );

        $venta['devuelta'] = 1;


        $query = $this->db->select('*');
        $query = $this->db->from('venta');
        $query = $this->db->where('venta_id', $venta_cabecera['venta_id']);
        $query = $this->db->get();
        $venta_anterior = $query->row_array();


        $nuevosubtotal = $venta_anterior['subtotal'] - $venta_cabecera['subtotal'];
        $nuevoimpuesto = $venta_anterior['total_impuesto'] - $venta_cabecera['total_impuesto'];
        $otros_impuestos = $venta_anterior['total_otros_impuestos'] - $venta_cabecera['total_otros_impuestos'];
        $nuevototal = $venta_anterior['total'] - $venta_cabecera['total'];
        $nuevodescuento = (floatval($venta_anterior['descuento_porcentaje']) + floatval($venta_anterior['descuento_valor']))
            - (floatval($venta_cabecera['descuento_porcentaje']) + floatval($venta_cabecera['descuento_valor']));


        if (!empty($venta_cabecera['id_cliente'])) {
            $venta['id_cliente'] = $venta_cabecera['id_cliente'];
        }
        if (!empty($venta_cabecera['venta_status'])) {
            $venta['venta_status'] = $venta_cabecera['venta_status'];
        }

        $this->db->where('venta_id', $venta_cabecera['venta_id']);
        $this->db->update('venta', $venta);


        if ($detalle != false) {

            /***************  DEVOLVER los items de la venta  ******/
            $query_detalle_venta = $this->getDetalleVenta(FALSE, array('detalle_venta.id_venta' => $venta_id), 'detalle_venta.id_detalle');

            for ($i = 0; $i < count($query_detalle_venta); $i++) {


                $detalleUnidadArray = $this->getDetalleVentaUnidad($query_detalle_venta[$i]['id_detalle']);

                //Borro comisiones de ese detalle venta
                $this->db->where('id_detalle_venta', $query_detalle_venta[$i]['id_detalle']);
                $this->db->delete('comision_vendedor');

                foreach ($detalleUnidadArray as $detalleUnidad) {

                    /***BORRO  DE DETALLE VENTA UNIDAD*******/
                    $this->db->where('detalle_venta_id', $detalleUnidad['detalle_venta_id']);
                    $this->db->delete('detalle_venta_unidad');
                }
            }

            /***BORRO  DE DETALLE VENTA *******/
            $this->db->where('id_venta', $venta_id);
            $this->db->delete('detalle_venta');

            $venta_devolucion = array(

                'apertura_caja_id' => $venta_cabecera['caja_id'],
                'fecha_devolucion' => date('Y-m-d H:i:s'),
                'id_venta' => $venta_id,
                'id_usuario' => $this->session->userdata('nUsuCodigo'),
                'subtotal' => $nuevosubtotal, // subtotal devuelto
                'impuesto' => $nuevoimpuesto, // impuesto devuelto
                'otros_impuestos' => $otros_impuestos, // impuesto devuelto
                'total' => $nuevototal, // total devuelto
                'descuento' => $nuevodescuento, //descuento devuelto


            );

            $this->db->insert('venta_devolucion', $venta_devolucion);

            $ventadev_id = $this->db->insert_id();


            /*********** MODIFICAR AHORA SI LA VENTA **************/
            foreach ($detalle as $row) {

                $id_producto = $row->id_producto;
                $nombre_producto = $row->nombre;
                $descuento = floatval($row->descuento);
                $impuesto = floatval($row->impuesto);

                $subtotal = floatval($row->subtotal);
                $desc_porcentaje = floatval($row->desc_porcentaje);
                $total = floatval($row->total);
                $porcentaje_impuesto = floatval($row->porcentaje_impuesto);
                $otro_impuesto = floatval($row->otro_impuesto);
                $porcentaje_otro_impuesto = floatval($row->porcentaje_otro_impuesto);
                $otro_impuesto_devolver = floatval($row->otro_impuesto_devolver);
                $is_paquete = $row->is_paquete;

                $detalle_item = array(
                    'id_venta' => $venta_id,
                    'id_producto' => $id_producto,
                    'porcentaje_impuesto' => $porcentaje_impuesto,
                    'porcentaje_otro_impuesto' => $porcentaje_otro_impuesto,
                    'descuento' => $descuento,
                    'desc_porcentaje' => $desc_porcentaje,
                    'subtotal' => $subtotal,
                    'impuesto' => $impuesto,
                    'otro_impuesto' => $otro_impuesto,
                    'total' => $total + $otro_impuesto,
                );


                if ($total > 0) {
                    $this->db->insert('detalle_venta', $detalle_item);
                    $detalle_venta_id = $this->db->insert_id();

                    /***********************************************************************************************************/
                    //COMISION VENDEDORES
                    /**************************************************************************************************************/
                    $porcentaje_comision = floatval($row->porcentaje_comision);
                    if ($porcentaje_comision > 0) {


                        $monto_comision = ($porcentaje_comision * ($subtotal - $descuento)) / 100;
                        array_push($comisiones, array('id_vendedor' => intval($query_detalle_venta[0]['id_vendedor']), 'id_detalle_venta' =>
                        $detalle_venta_id, 'porcentaje' => $porcentaje_comision, 'comision' => $monto_comision));
                    }
                }


                foreach ($row->unidades as $detalle_unidad) {
                    //  if (isset($detalle_unidad->cantidad_dev) && $detalle_unidad->cantidad_dev > 0) {

                    $precio = 0;
                    if (isset($detalle_unidad->precio)) {
                        $precio = $detalle_unidad->precio;
                    }
                    $precio_sin_iva = 0;
                    if (isset($detalle_unidad->precio_sin_iva)) {

                        $precio_sin_iva = $detalle_unidad->precio_sin_iva;
                    }
                    $costo = 0;
                    $costo_promedio = 0;
                    $utilidad = 0;


                    $cantidad_dev = isset($detalle_unidad->cantidad_dev) ? floatval($detalle_unidad->cantidad_dev) : 0;

                    $estadonuevo = 'ENTRADA POR ';
                    $estadonuevo .= VENTA_DEVOLUCION;

                    $cantidad_venta = $detalle_unidad->cantidad - $cantidad_dev;
                    // LA CANTIDAD DE LA VENTA ERMINA SIENDO LA CANTIDAD ANTERIRO MENOS LO QUE SE DEVUELVE
                    $unidad_medida_venta = $detalle_unidad->id_unidad;

                    if (isset($detalle_unidad->cantidad_dev) && $detalle_unidad->cantidad_dev > 0) {


                        if ($is_paquete == '1') {  //ENTRA AQUI CUANDO ES PQUETE, YA QUE EL INVENTARIO SE SUMA  ALOS PROUCTOS QUE CONTIENE EL PAQUETE
                            $productos_paquete = $this->paquete_has_prod_model->get_all_by_prod_grouped($id_producto);

                            $costo = 0;
                            $costo_promedio = 0;
                            foreach ($productos_paquete as $prodpaq) {

                                $id_producto = $prodpaq['prod_id'];

                                foreach ($prodpaq['unidades'] as $detallepag) {

                                    $cantidad_venta = floatval($detallepag['cantidad']) * (floatval($detalle_unidad->cantidad) - $cantidad_dev);
                                    $cantidad_dev = floatval($detallepag['cantidad']) * $cantidad_dev;
                                    $unidad_medida_venta = $detallepag['unidad_id'];
                                    $precio = 0;

                                    if ($row->control_inven == 1) {
                                        $local_inventario = array('id_producto' => $id_producto, 'id_local' => $venta_cabecera['local_id'], 'id_unidad' => $unidad_medida_venta);
                                        $resultinventario = $this->inventario_model->get_by($local_inventario);
                                        if (sizeof($resultinventario) > 0 and $cantidad_venta > $resultinventario['cantidad']) {
                                            $error = "Ha ingresado una cantidad superior al stock actual, para el producto 
                            " . $nombre_producto;
                                            log_message("error", $error);
                                            break;
                                        }
                                    }


                                    $costo_promedio_paq = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_PROMEDIO);

                                    $costopaq = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_UNITARIO);
                                    $costo = $costo + $costopaq;
                                    $costo_promedio = $costo_promedio_paq + $costo_promedio;


                                    $return_inventario = $this->inventario_model->sumar_inventario($id_producto, $venta_cabecera['local_id'], $unidad_medida_venta, $cantidad_dev);

                                    $this->kardex_model->set_kardex(
                                        $id_producto,
                                        $venta_cabecera['local_id'],
                                        $unidad_medida_venta,
                                        $cantidad_dev,
                                        $estadonuevo,
                                        $venta_cabecera['id_vendedor'],
                                        $precio,
                                        $venta_id,
                                        $query_detalle_venta[0]['documento_Numero'],
                                        $query_detalle_venta[0]['nombre_tipo_documento'],
                                        $venta_cabecera['id_cliente'],
                                        ENTRADA,
                                        json_encode($return_inventario['stockviejo_array']),
                                        json_encode($return_inventario['stocknuevo_array']),
                                        null,
                                        $porcentaje_impuesto,
                                        $costo
                                    );
                                }
                            }


                            $utilidad = $this->calcularUtilidad($precio_sin_iva, $cantidad_venta, $costo);
                        }


                        $cantidad_venta = $detalle_unidad->cantidad - $detalle_unidad->cantidad_dev; // LA CANTIDAD DE LA VENTA ERMINA SIENDO LA CANTIDAD ANTERIRO MENOS LO QUE SE DEVUELVE
                        $unidad_medida_venta = $detalle_unidad->id_unidad;

                        /**SUMO AL  INVENTARIO*/
                        if ($is_paquete != '1') {

                            $return_inventario = $this->inventario_model
                                ->sumar_inventario($row->id_producto, $query_detalle_venta[0]['local_id'], $unidad_medida_venta, $cantidad_dev);

                            $costo_promedio = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_PROMEDIO);
                            $costo = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_UNITARIO);

                            /****INSERTO EL KARDEX**/
                            $this->kardex_model->set_kardex(
                                $row->id_producto,
                                $venta_cabecera['local_id'],
                                $unidad_medida_venta,
                                $cantidad_dev,
                                $estadonuevo,
                                $this->session->userdata('nUsuCodigo'),
                                $precio,
                                $venta_id,
                                $query_detalle_venta[0]['documento_Numero'],
                                $query_detalle_venta[0]['nombre_tipo_documento'],
                                $query_detalle_venta[0]['id_cliente'],
                                ENTRADA,
                                json_encode($return_inventario['stockviejo_array']),
                                json_encode($return_inventario['stocknuevo_array']),
                                null,
                                $porcentaje_impuesto,
                                $costo
                            );


                            $utilidad = $this->calcularUtilidad($precio_sin_iva, $cantidad_venta, $costo);
                        }
                    }


                    if ($cantidad_venta > 0) {

                        $detalle_venta_unidad = array(
                            'unidad_id' => $unidad_medida_venta,
                            'cantidad' => $cantidad_venta,
                            'precio' => $precio,
                            'precio_sin_iva' => $precio_sin_iva,
                            'utilidad' => $utilidad,
                            'costo' => $costo,
                            'costo_promedio' => $costo_promedio,
                            'detalle_venta_id' => $detalle_venta_id,

                        );

                        $this->db->insert('detalle_venta_unidad', $detalle_venta_unidad);
                    }


                    if ($cantidad_dev > 0) {


                        $detalle_venta_devolucion = array(
                            'id_unidad' => $unidad_medida_venta,
                            'cantidad' => $cantidad_dev,
                            'precio' => $precio,
                            'precio_sin_iva' => $precio_sin_iva,
                            'id_producto' => $id_producto,
                            'id_cuadre_caja' => $venta_cabecera['caja_id'],
                            'fecha_devolucion' => date('Y-m-d H:i:s'),
                            'id_detalle_venta' => isset($detalle_venta_id) ? $detalle_venta_id : null,
                            'id_venta' => $venta_id,
                            'total' => ($cantidad_dev * $precio) + $otro_impuesto_devolver,
                            'subtotal' => $cantidad_dev * $precio_sin_iva,
                            'porcentaje_impuesto' => $porcentaje_impuesto,
                            'impuesto' => $precio - $precio_sin_iva,
                            'otro_impuesto' => $otro_impuesto_devolver,
                            'porcentaje_otro_impuesto' => $porcentaje_otro_impuesto,
                            'venta_devolucion_id' => $ventadev_id,

                        );

                        $this->db->insert('detalle_venta_devolucion', $detalle_venta_devolucion);
                    }
                    // }
                }
            }
        }


        /**Credito o contado*/
        $this->updateVentaTipo($venta_cabecera);
        if (count($comisiones) > 0) {
            $this->comision_model->insert_bacth($comisiones);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "Ha ocurido un error";
        } else {
            $this->db->trans_commit();

            $rtorno['idventa'] = $venta_id;
            $rtorno['id_devolucion'] = $ventadev_id;
            $rtorno['uuid'] = $venta_anterior['uuid'];
            return $rtorno;
        }
        $this->db->trans_off();
    }



    function notaDebito($venta_cabecera, $detalle = false)
    {

        $comisiones = array();

        $ventadev_id = null;
        // var_dump($venta_cabecera);
        $this->db->trans_begin();
        $venta_id = $venta_cabecera['venta_id'];
        $venta = array(
            //'local_id' => $venta_cabecera['local_id'],
            'subtotal' => $venta_cabecera['subtotal'],
            'total_impuesto' => $venta_cabecera['total_impuesto'],
            'total_otros_impuestos' => $venta_cabecera['total_otros_impuestos'],
            'total' => $venta_cabecera['total'],
            'gravado' => $venta_cabecera['gravado'],
            'excluido' => $venta_cabecera['excluido'],
            'descuento_valor' => $venta_cabecera['descuento_valor'],
            'descuento_porcentaje' => $venta_cabecera['descuento_porcentaje'],
            'porcentaje_desc' => $venta_cabecera['porcentaje_desc'],

        );

        $venta['devuelta'] = 1;


        $query = $this->db->select('*');
        $query = $this->db->from('venta');
        $query = $this->db->where('venta_id', $venta_cabecera['venta_id']);
        $query = $this->db->get();
        $venta_anterior = $query->row_array();


        $nuevosubtotal = $venta_anterior['subtotal'] + $venta_cabecera['subtotal'];
        $nuevoimpuesto = $venta_anterior['total_impuesto'] + $venta_cabecera['total_impuesto'];
        $otros_impuestos = $venta_anterior['total_otros_impuestos'] + $venta_cabecera['total_otros_impuestos'];
        $nuevototal = $venta_anterior['total'] + $venta_cabecera['total'];
        $nuevodescuento = (floatval($venta_anterior['descuento_porcentaje']) + floatval($venta_anterior['descuento_valor']))
            + (floatval($venta_cabecera['descuento_porcentaje']) + floatval($venta_cabecera['descuento_valor']));


        if (!empty($venta_cabecera['id_cliente'])) {
            $venta['id_cliente'] = $venta_cabecera['id_cliente'];
        }
        if (!empty($venta_cabecera['venta_status'])) {
            $venta['venta_status'] = $venta_cabecera['venta_status'];
        }

        $this->db->where('venta_id', $venta_cabecera['venta_id']);
        $this->db->update('venta', $venta);


        if ($detalle != false) {

            /***************  DEVOLVER los items de la venta  ******/
            $query_detalle_venta = $this->getDetalleVenta(FALSE, array('detalle_venta.id_venta' => $venta_id), 'detalle_venta.id_detalle');

            for ($i = 0; $i < count($query_detalle_venta); $i++) {


                $detalleUnidadArray = $this->getDetalleVentaUnidad($query_detalle_venta[$i]['id_detalle']);
                foreach ($detalleUnidadArray as $detalleUnidad) {


                    /***BORRO  DE DETALLE VENTA UNIDAD*******/
                    $this->db->where('detalle_venta_id', $detalleUnidad['detalle_venta_id']);
                    $this->db->delete('detalle_venta_unidad');

                    //Borro comisiones de ese detalle venta
                    $this->db->where('id_detalle_venta', $detalleUnidad['detalle_venta_id']);
                    $this->db->delete('comision_vendedor');
                }
            }

            /***BORRO  DE DETALLE VENTA *******/
            $this->db->where('id_venta', $venta_id);
            $this->db->delete('detalle_venta');

            $venta_devolucion = array(

                'apertura_caja_id' => $venta_cabecera['caja_id'],
                'fecha_devolucion' => date('Y-m-d H:i:s'),
                'id_venta' => $venta_id,
                'id_usuario' => $this->session->userdata('nUsuCodigo'),
                'subtotal' => $nuevosubtotal, // subtotal devuelto
                'impuesto' => $nuevoimpuesto, // impuesto devuelto
                'otros_impuestos' => $otros_impuestos, // impuesto devuelto
                'total' => $nuevototal, // total devuelto
                'descuento' => $nuevodescuento, //descuento devuelto


            );

            $this->db->insert('venta_devolucion', $venta_devolucion);

            $ventadev_id = $this->db->insert_id();


            /*********** MODIFICAR AHORA SI LA VENTA **************/
            foreach ($detalle as $row) {

                $id_producto = $row->id_producto;
                $nombre_producto = $row->nombre;
                $descuento = floatval($row->descuento);
                $impuesto = floatval($row->impuesto);

                $subtotal = floatval($row->subtotal);
                $desc_porcentaje = floatval($row->desc_porcentaje);
                $total = floatval($row->total);
                $porcentaje_impuesto = floatval($row->porcentaje_impuesto);
                $otro_impuesto = floatval($row->otro_impuesto);
                $porcentaje_otro_impuesto = floatval($row->porcentaje_otro_impuesto);
                $otro_impuesto_devolver = floatval($row->otro_impuesto_devolver);
                $is_paquete = $row->is_paquete;

                $detalle_item = array(
                    'id_venta' => $venta_id,
                    'id_producto' => $id_producto,
                    'porcentaje_impuesto' => $porcentaje_impuesto,
                    'porcentaje_otro_impuesto' => $porcentaje_otro_impuesto,
                    'descuento' => $descuento,
                    'desc_porcentaje' => $desc_porcentaje,
                    'subtotal' => $subtotal,
                    'impuesto' => $impuesto,
                    'otro_impuesto' => $otro_impuesto,
                    'total' => $total + $otro_impuesto,
                );


                if ($total > 0) {
                    $this->db->insert('detalle_venta', $detalle_item);
                    $detalle_venta_id = $this->db->insert_id();

                    /***********************************************************************************************************/
                    //COMISION VENDEDORES
                    /**************************************************************************************************************/
                    $porcentaje_comision = floatval($row->porcentaje_comision);
                    if ($porcentaje_comision > 0) {


                        $monto_comision = ($porcentaje_comision * ($subtotal - $descuento)) / 100;
                        array_push($comisiones, array('id_vendedor' => intval($query_detalle_venta[0]['id_vendedor']), 'id_detalle_venta' =>
                        $detalle_venta_id, 'porcentaje' => $porcentaje_comision, 'comision' => $monto_comision));
                    }
                }


                foreach ($row->unidades as $detalle_unidad) {
                    //  if (isset($detalle_unidad->cantidad_dev) && $detalle_unidad->cantidad_dev > 0) {

                    $precio = 0;
                    if (isset($detalle_unidad->precio)) {
                        $precio = $detalle_unidad->precio;
                    }
                    $precio_sin_iva = 0;
                    if (isset($detalle_unidad->precio_sin_iva)) {

                        $precio_sin_iva = $detalle_unidad->precio_sin_iva;
                    }
                    $costo = 0;
                    $costo_promedio = 0;
                    $utilidad = 0;


                    $cantidad_dev = isset($detalle_unidad->cantidad_dev) ? floatval($detalle_unidad->cantidad_dev) : 0;

                    $estadonuevo = '';
                    $estadonuevo .= NOTA_DEBITO;

                    $cantidad_venta = $detalle_unidad->cantidad + $cantidad_dev;

                    // LA CANTIDAD DE LA VENTA ERMINA SIENDO LA CANTIDAD ANTERIRO MAS LO QUE SE DEVUELVE
                    $unidad_medida_venta = $detalle_unidad->id_unidad;

                    if (isset($detalle_unidad->cantidad_dev) && $detalle_unidad->cantidad_dev > 0) {

                        if ($is_paquete == '1') {  //ENTRA AQUI CUANDO ES PQUETE, YA QUE EL INVENTARIO SE SUMA  ALOS PROUCTOS QUE CONTIENE EL PAQUETE
                            $productos_paquete = $this->paquete_has_prod_model->get_all_by_prod_grouped($id_producto);

                            $costo = 0;
                            $costo_promedio = 0;
                            foreach ($productos_paquete as $prodpaq) {

                                $id_producto = $prodpaq['prod_id'];

                                foreach ($prodpaq['unidades'] as $detallepag) {

                                    $cantidad_venta = floatval($detallepag['cantidad']) * (floatval($detalle_unidad->cantidad) - $cantidad_dev);
                                    $cantidad_dev = floatval($detallepag['cantidad']) * $cantidad_dev;
                                    $unidad_medida_venta = $detallepag['unidad_id'];
                                    $precio = 0;

                                    if ($row->control_inven == 1) {
                                        $local_inventario = array('id_producto' => $id_producto, 'id_local' => $venta_cabecera['local_id'], 'id_unidad' => $unidad_medida_venta);
                                        $resultinventario = $this->inventario_model->get_by($local_inventario);
                                        if (sizeof($resultinventario) > 0 and $cantidad_venta > $resultinventario['cantidad']) {
                                            $error = "Ha ingresado una cantidad superior al stock actual, para el producto 
                            " . $nombre_producto;
                                            log_message("error", $error);
                                            break;
                                        }
                                    }


                                    $costo_promedio_paq = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_PROMEDIO);

                                    $costopaq = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_UNITARIO);
                                    $costo = $costo + $costopaq;
                                    $costo_promedio = $costo_promedio_paq + $costo_promedio;


                                    $return_inventario = $this->inventario_model->sumar_inventario($id_producto, $venta_cabecera['local_id'], $unidad_medida_venta, $cantidad_dev);

                                    $this->kardex_model->set_kardex(
                                        $id_producto,
                                        $venta_cabecera['local_id'],
                                        $unidad_medida_venta,
                                        $cantidad_dev,
                                        $estadonuevo,
                                        $venta_cabecera['id_vendedor'],
                                        $precio,
                                        $venta_id,
                                        $query_detalle_venta[0]['documento_Numero'],
                                        $query_detalle_venta[0]['nombre_tipo_documento'],
                                        $venta_cabecera['id_cliente'],
                                        ENTRADA,
                                        json_encode($return_inventario['stockviejo_array']),
                                        json_encode($return_inventario['stocknuevo_array']),
                                        null,
                                        $porcentaje_impuesto,
                                        $costo
                                    );
                                }
                            }


                            $utilidad = $this->calcularUtilidad($precio_sin_iva, $cantidad_venta, $costo);
                        }


                        $cantidad_venta = $detalle_unidad->cantidad + $detalle_unidad->cantidad_dev; // LA CANTIDAD DE LA VENTA ERMINA SIENDO LA CANTIDAD ANTERIRO MENOS LO QUE SE DEVUELVE
                        $unidad_medida_venta = $detalle_unidad->id_unidad;

                        /**SUMO AL  INVENTARIO*/
                        if ($is_paquete != '1') {

                            $return_inventario = $this->inventario_model
                                ->sumar_inventario($row->id_producto, $query_detalle_venta[0]['local_id'], $unidad_medida_venta, $cantidad_dev);

                            $costo_promedio = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_PROMEDIO);
                            $costo = $this->calcularCostos($id_producto, $cantidad_venta, $unidad_medida_venta, COSTO_UNITARIO);

                            /****INSERTO EL KARDEX**/
                            $this->kardex_model->set_kardex(
                                $row->id_producto,
                                $venta_cabecera['local_id'],
                                $unidad_medida_venta,
                                $cantidad_dev,
                                $estadonuevo,
                                $this->session->userdata('nUsuCodigo'),
                                $precio,
                                $venta_id,
                                $query_detalle_venta[0]['documento_Numero'],
                                $query_detalle_venta[0]['nombre_tipo_documento'],
                                $query_detalle_venta[0]['id_cliente'],
                                ENTRADA,
                                json_encode($return_inventario['stockviejo_array']),
                                json_encode($return_inventario['stocknuevo_array']),
                                null,
                                $porcentaje_impuesto,
                                $costo
                            );


                            $utilidad = $this->calcularUtilidad($precio_sin_iva, $cantidad_venta, $costo);
                        }
                    }


                    if ($cantidad_venta > 0) {


                        $detalle_venta_unidad = array(
                            'unidad_id' => $unidad_medida_venta,
                            'cantidad' => $cantidad_venta,
                            'precio' => $precio,
                            'precio_sin_iva' => $precio_sin_iva,
                            'utilidad' => $utilidad,
                            'costo' => $costo,
                            'costo_promedio' => $costo_promedio,
                            'detalle_venta_id' => $detalle_venta_id,

                        );

                        $this->db->insert('detalle_venta_unidad', $detalle_venta_unidad);
                    }


                    if ($cantidad_dev > 0) {


                        $detalle_venta_devolucion = array(
                            'id_unidad' => $unidad_medida_venta,
                            'cantidad' => $cantidad_dev,
                            'precio' => $precio,
                            'precio_sin_iva' => $precio_sin_iva,
                            'id_producto' => $id_producto,
                            'id_cuadre_caja' => $venta_cabecera['caja_id'],
                            'fecha_devolucion' => date('Y-m-d H:i:s'),
                            'id_detalle_venta' => isset($detalle_venta_id) ? $detalle_venta_id : null,
                            'id_venta' => $venta_id,
                            'total' => ($cantidad_dev * $precio) + $otro_impuesto_devolver,
                            'subtotal' => $cantidad_dev * $precio_sin_iva,
                            'porcentaje_impuesto' => $porcentaje_impuesto,
                            'impuesto' => $precio - $precio_sin_iva,
                            'otro_impuesto' => $otro_impuesto_devolver,
                            'porcentaje_otro_impuesto' => $porcentaje_otro_impuesto,
                            'venta_devolucion_id' => $ventadev_id,

                        );

                        $this->db->insert('detalle_venta_devolucion', $detalle_venta_devolucion);
                    }
                    // }
                }
            }
        }


        /**Credito o contado*/
        $this->updateVentaTipo($venta_cabecera);
        if (count($comisiones) > 0) {
            $this->comision_model->insert_bacth($comisiones);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return "Ha ocurido un error";
        } else {
            $this->db->trans_commit();

            $rtorno['idventa'] = $venta_id;
            $rtorno['id_devolucion'] = $ventadev_id;
            $rtorno['uuid'] = $venta_anterior['uuid'];
            return $rtorno;
        }
        $this->db->trans_off();
    }

    //ESTE METODO SOLAENTE SE USA EDICION DE VENTAS ABIERTAS

    function actualizar_venta($venta_cabecera, $detalle = false)
    {
        try {

            $this->db->trans_begin();

            $venta_id = $venta_cabecera['venta_id'];

            $venta = array(
                //'local_id' => $venta_cabecera['local_id'],
                'subtotal' => $venta_cabecera['subtotal'],
                'total_impuesto' => $venta_cabecera['total_impuesto'],
                'total' => $venta_cabecera['total'],
                'gravado' => $venta_cabecera['gravado'],
                'excluido' => $venta_cabecera['excluido']

            );


            if (!empty($venta_cabecera['id_cliente'])) {
                $venta['id_cliente'] = $venta_cabecera['id_cliente'];
            }
            if (!empty($venta_cabecera['venta_status'])) {
                $venta['venta_status'] = $venta_cabecera['venta_status'];
            }


            $this->db->where('venta_id', $venta_cabecera['venta_id']);
            $this->db->update('venta', $venta);


            $this->db->where('venta_id', $venta_cabecera['venta_id']);
            $this->db->update('venta_backup', $venta);

            $numero = $venta_cabecera['numero'];


            if ($venta_cabecera['venta_status'] == COMPLETADO) {

                /***** borro todo de la venta backup ***/
                $query = "DELETE FROM detalle_venta_unidad_backup WHERE detalle_venta_id IN (SELECT * FROM(select detalle_venta_backup.id_detalle  
from detalle_venta_backup  JOIN  detalle_venta_unidad_backup ON 
detalle_venta_backup.id_detalle=detalle_venta_unidad_backup.detalle_venta_id  where detalle_venta_backup.id_venta =  $venta_id) AS t)  ";
                $this->db->query($query);

                $this->db->where('id_venta', $venta_id);
                $this->db->delete('detalle_venta_backup');

                $this->db->where('venta_id', $venta_cabecera['venta_id']);
                $this->db->update('venta_backup', $venta);

                if ($venta_cabecera['id_resolucion'] != null) {

                    $tip_doc = array(
                        'nombre_tipo_documento' => $venta_cabecera['tipo_documento'],
                        'documento_Numero' => $numero,
                        'id_venta' => $venta_id,
                        'id_resolucion' => $venta_cabecera['id_resolucion'],
                    );

                    $this->db->insert('documento_venta', $tip_doc);
                }
            }


            if ($detalle != false) {
                /***************  DEVOLVER los items de la venta  ******/
                /**********QUITO DEL INVETARIO TODOS LOS ITEMS DE LA VENTA***********/
                $query_detalle_venta = $this->getDetalleVenta(FALSE, array('detalle_venta.id_venta' => $venta_id), 'detalle_venta.id_detalle');

                for ($i = 0; $i < count($query_detalle_venta); $i++) {
                    $is_paquete = $query_detalle_venta[$i]['is_paquete'];

                    $detalleUnidadArray = $this->getDetalleVentaUnidad($query_detalle_venta[$i]['id_detalle']);
                    foreach ($detalleUnidadArray as $detalleUnidad) {

                        /***BORRO  DE DETALLE VENTA UNIDAD*******/
                        $this->db->where('detalle_venta_id', $detalleUnidad['detalle_venta_id']);
                        $this->db->delete('detalle_venta_unidad');


                        //Borro comisiones de ese detalle venta
                        $this->db->where('id_detalle_venta', $detalleUnidad['detalle_venta_id']);
                        $this->db->delete('comision_vendedor');
                    }
                }

                /***BORRO  DE DETALLE VENTA *******/
                $this->db->where('id_venta', $venta_id);
                $this->db->delete('detalle_venta');


                /*********** MODIFICAR AHORA SI LA VENTA **************/

                $procesoDetalle = $this->procesarDetalleVenta($detalle, $venta_id, $venta_cabecera, $numero);

                if ($procesoDetalle !== TRUE) {
                    return $procesoDetalle;
                }
            }

            $this->updateVentaTipo($venta_cabecera);


            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                log_message('error', $this->db->error());
                return GLOBAL_ERROR;
            } else {
                $this->db->trans_commit();
                return $venta_id;
            }


            $this->db->trans_off();
        } catch (Exception $e) {
            $this->db->devolverSinError();
            log_message('error', $e->getMessage());
            return GLOBAL_ERROR;
        }
    }

    //ESTE METODO SE DEBE USAR EN ALGUN MOMENTO SI SE LLEGA A MODIFICAR UNA VENTA QUE ESTE GUARDADA EN ESTADO GENERADO Y CUANDO NO SEA DEVOLUCION CMO LA DEVOLUCION ACTUAL
    //ahorita no se usa pero se deja por si acaso
    function actualizaInventario(
        $venta_id,
        $id_producto,
        $row,
        $venta_cabecera,
        $query_detalle_venta,
        $return_inventario,
        $unidad_medida_venta,
        $precio,
        $cantidad_venta,
        $estadonuevo,
        $porcentaje_impuesto,
        $costo
    ) {
        $query_detalle_venta_bk = $this->get_detalle_venta_backup($venta_id, $id_producto);
        if (isset($query_detalle_venta_bk['cantidad'])) {
            if ($query_detalle_venta_bk['cantidad'] - $row->cantidad > 0) {
                $kardextipo = ENTRADA;
                $estadonuevo = VENTA;
                /****INSERTO EL KARDEX**/
            } else {
                $kardextipo = SALIDA;
                $estadonuevo = 'SALIDA POR ';
                if ($venta_cabecera['devolver'] == 'true') {
                    $estadonuevo .= ($query_detalle_venta[0]['venta_tipo'] == VENTA_ENTREGA) ? PEDIDO_DEVUOLUCION : VENTA_DEVOLUCION;
                } else {
                    $estadonuevo .= ($query_detalle_venta[0]['venta_tipo'] == VENTA_ENTREGA) ? PEDIDO_EDICION : VENTA_EDICION;
                }
            }
            /****INSERTO EL KARDEX**/


            $this->kardex_model->set_kardex(
                $row->id_producto,
                $venta_cabecera['local_id'],
                $unidad_medida_venta,
                $query_detalle_venta_bk['cantidad'] - $cantidad_venta,
                $estadonuevo,
                $this->session->userdata('nUsuCodigo'),
                $precio,
                $venta_id,
                $query_detalle_venta[0]['documento_Numero'],
                $query_detalle_venta[0]['nombre_tipo_documento'],
                $query_detalle_venta[0]['id_cliente'],
                $kardextipo,
                json_encode($return_inventario['stockviejo_array']),
                json_encode($return_inventario['stocknuevo_array']),
                null,
                $porcentaje_impuesto,
                $costo
            );
        } else {


            /****INSERTO EL KARDEX**/
            $this->kardex_model->set_kardex(
                $row->id_producto,
                $venta_cabecera['local_id'],
                $unidad_medida_venta,
                $cantidad_venta,
                $estadonuevo,
                $this->session->userdata('nUsuCodigo'),
                $precio,
                $venta_id,
                $query_detalle_venta[0]['documento_Numero'],
                $query_detalle_venta[0]['nombre_tipo_documento'],
                $query_detalle_venta[0]['id_cliente'],
                SALIDA,
                json_encode($return_inventario['stockviejo_array']),
                json_encode($return_inventario['stocknuevo_array']),
                null,
                $porcentaje_impuesto,
                $costo
            );
        }
    }

    function record_sort($records, $field, $reverse = false)
    {
        $hash = array();
        foreach ($records as $record) {
            $keys = $record[$field];
            if (isset($hash[$keys])) {
                $hash[$keys] = $record;
            } else {
                $hash[$keys] = $record;
            }
        }

        ($reverse) ? krsort($hash) : ksort($hash);

        $records = array();
        foreach ($hash as $record) {
            $records[] = $record;
        }

        return $records;
    }


    function updateVentaTipo($venta_cabecera)
    {

        $where = array('id_venta' => $venta_cabecera['venta_id']);


        $queryCredito = $this->getCredito($where);

        if (isset($queryCredito['credito_id'])) {

            $credito = array(
                'credito_id' => $queryCredito['credito_id'],
                'dec_credito_montodeuda' => $venta_cabecera['total'],
                'var_credito_estado' => CREDITO_DEBE,
                'dec_credito_montodebito' => 0.0
            );

            if ($venta_cabecera['devolver'] == 'true') {
                $credito['totaldeuda'] = $venta_cabecera['total'];
                if ($venta_cabecera['importe'] < $venta_cabecera['total']) {
                    $credito['cuota'] = $venta_cabecera['importe'];
                    $credito['id_venta'] = $venta_cabecera['venta_id'];
                } else {
                    $credito['cuota'] = $venta_cabecera['importe'];
                    $credito['id_venta'] = $venta_cabecera['venta_id'];
                }
                if ($venta_cabecera['devolver'] == 'true') {
                    $credito['cuota'] = 0;
                }
                $credito = (object) $credito;
            }
            if ($venta_cabecera['diascondicionpagoinput'] < 1) {
                if ($venta_cabecera['devolver'] == 'false') {
                    // $this->db->where('venta_id', $venta_cabecera['venta_id']);
                    $this->db->delete('historial_pagos_clientes', array('venta_id' => $venta_cabecera['venta_id']));
                    $this->db->delete('credito', array('id_venta' => $venta_cabecera['venta_id']));
                }
                if ($venta_cabecera['devolver'] == 'true') {
                    $select_credito = $this->db->select('*')->from('credito')->where('id_venta', $venta_cabecera['venta_id'])->get()->row_array();
                    if (count($select_credito) > 0) {
                        $this->updateCredito($credito, true);
                    } else {
                        $this->insertCredito($credito);
                    }
                }
            } else {
                $select_credito = $this->db->select('*')->from('credito')->where('id_venta', $venta_cabecera['venta_id'])->get()->row_array();
                if (count($select_credito) > 0) {
                    if ($venta_cabecera['devolver'] == 'true') {
                        $this->updateCredito($credito, true);
                    } else {
                        $this->db->where('id_venta', $venta_cabecera['venta_id']);
                        $this->db->update('credito', $credito);
                    }
                } else {
                    $this->insertCredito($credito);
                }
            }
        }
    }


    function insertCredito($credito)
    {

        try {
            $this->db->trans_start();

            $this->db->insert('credito', $credito);

            $this->db->trans_complete();


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }

            $this->db->trans_off();
        } catch (Exception $e) {
            log_message('info', $e->getMessage());
        }
    }

    //Lo uso para actulizar la tabla credito arbitrariamente
    function actualizarCredito($values, $condition)
    {
        $this->db->trans_start();
        $this->db->trans_begin();

        $this->db->where($condition);
        $this->db->update('credito', $values);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }

        $this->db->trans_off();
    }

    function updateCredito($credito, $devolver = false)
    {
        $this->db->trans_start();

        $where = array('credito_id' => $credito->credito_id);


        $queryCredito = $this->getCredito($where);

        if ($devolver == true) {
            $total_deuda = $credito->dec_credito_montodeuda;
        } else {
            $total_deuda = $queryCredito['dec_credito_montodeuda'];
        }

        $total_deuda += 0;
        $queryCredito['dec_credito_montodebito'] += 0;
        $creditoInsert = array('dec_credito_montodeuda' => $total_deuda);
        $credito->cuota += 0;

        /**
         * se usa "round", por lo siguiente:
         * if you do it like this they should be the same. But note that a characteristic
         * of floating-point values is that calculations which seem to result in the same
         * value do not need to actually be identical. So if $a is a literal .17 and $b arrives
         * there through a calculation it can well be that they are different,
         * albeit both display the same value.
         */
        if (
            number_format(($queryCredito['dec_credito_montodebito'] + $credito->cuota), 2, '.', '') >=
            number_format($total_deuda, 2, '.', '')
        ) {
            $creditoInsert['var_credito_estado'] = CREDITO_CANCELADO;
        } else {

            if (($total_deuda - $queryCredito['dec_credito_montodebito']) > 0) {
                $creditoInsert['var_credito_estado'] = CREDITO_ACUENTA;
            }
        }
        $creditoInsert['dec_credito_montodebito'] = $queryCredito['dec_credito_montodebito'] + $credito->cuota;


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

    function getCredito($where)
    {
        $this->db->where($where);
        $query = $this->db->get('credito');
        return $query->row_array();
    }

    function restaurar_venta($id, $campos)
    {
        if (isset($campos['estatus_actual']) and $campos['estatus_actual'] == PEDIDO_DEVUELTO and $campos['venta_status'] != PEDIDO_DEVUELTO) {

            $sql_venta = $this->db->query("SELECT * FROM venta_backup where venta_id='$id' ");

            $query_venta = $sql_venta->result_array();

            foreach ($query_venta as $row) {

                $venta_backup = array(
                    'id_cliente' => $row['id_cliente'],
                    'fecha' => $row['fecha'],
                    'id_vendedor' => $row['id_vendedor'],
                    'local_id' => $row['local_id'],
                    'subtotal' => $row['subtotal'],
                    //  'numero_documento' => $row['numero_documento'],
                    'venta_status' => $campos['venta_status'],
                    //'condicion_pago' => $row['condicion_pago'],
                    'total_impuesto' => $row['total_impuesto'],
                    'total' => $row['total'],
                    'pagado' => $row['pagado'],
                    'venta_tipo' => $row['venta_tipo']

                );
                $this->db->where('venta_id', $id);
                $this->db->update('venta', $venta_backup);
            }


            $this->db->where('id_venta', $id);
            $this->db->delete('detalle_venta');

            $sql_detalle = $this->db->query("SELECT * FROM detalle_venta_backup where id_venta='$id' ");

            $query_detalle_venta_backup = $sql_detalle->result_array();

            foreach ($query_detalle_venta_backup as $row) {

                $detalle_item = array(
                    'id_venta' => $id,
                    'id_producto' => $row['id_producto'],
                    'precio' => $row['precio'],
                    'precio_sin_iva' => $row['precio_sin_iva'],
                    'cantidad' => $row['cantidad'],
                    'unidad_medida' => $row['unidad_medida'],
                    'detalle_costo_promedio' => $row['detalle_costo_promedio'],
                    'detalle_utilidad' => $row['detalle_utilidad'],

                    'bono' => $row['bono']
                );
                $this->db->insert('detalle_venta', $detalle_item);
            }
        }
    }

    function devolver_stock($id, $campos, $status = false, $caja, $nUsuCodigo, $fecha)
    {
        $this->db->trans_start();
        $this->db->trans_begin();

        $data = array();


        $sql_detalle_venta = $this->db->query("SELECT * FROM detalle_venta
JOIN producto ON producto.producto_id=detalle_venta.id_producto
JOIN venta ON venta.`venta_id`=detalle_venta.`id_venta`
left join documento_venta on documento_venta.id_venta=venta.venta_id
WHERE detalle_venta.id_venta='$id' group by detalle_venta.id_detalle");

        $query_detalle_venta = $sql_detalle_venta->result_array();

        $kardex = array();
        /*************COMIENZO A DEVOLVER EL STOCK**********/

        /**recorro primero la tabla detalle_vent*/
        for ($i = 0; $i < count($query_detalle_venta); $i++) {

            $local = $query_detalle_venta[$i]['local_id'];
            $producto_id = $query_detalle_venta[$i]['id_producto'];
            $sql_detalle_venta_unidad = $this->db->query("SELECT * FROM detalle_venta_unidad
            LEFT JOIN unidades_has_producto ON unidades_has_producto.id_unidad=detalle_venta_unidad.unidad_id
            LEFT JOIN unidades ON unidades.id_unidad=unidades_has_producto.id_unidad
            WHERE detalle_venta_unidad.detalle_venta_id='" . $query_detalle_venta[$i]['id_detalle'] . "'
             AND unidades_has_producto.producto_id='" . $producto_id . "' ");
            $sql_detalle_venta_unidad = $sql_detalle_venta_unidad->result_array();

            /**para cada detalle_venta recorro el detalle_vent_unidad*/
            foreach ($sql_detalle_venta_unidad as $detalle_unidad) {


                /***SUMO AL INVENTARIO**/
                $return_inventario = $this->inventario_model->sumar_inventario(
                    $producto_id,
                    $query_detalle_venta[$i]['local_id'],
                    $detalle_unidad['id_unidad'],
                    $detalle_unidad['cantidad']
                );


                /****INSERTO EL KARDEX**/

                $this->kardex_model->set_kardex(
                    $producto_id,
                    $query_detalle_venta[$i]['local_id'],
                    $detalle_unidad['id_unidad'],
                    $detalle_unidad['cantidad'],
                    'ANULACION DE VENTA',
                    $nUsuCodigo,
                    $detalle_unidad['precio'],
                    $query_detalle_venta[$i]['id_venta'],
                    $query_detalle_venta[$i]['documento_Numero'],
                    $query_detalle_venta[$i]['nombre_tipo_documento'],
                    $query_detalle_venta[0]['id_cliente'],
                    ENTRADA,
                    json_encode($return_inventario['stockviejo_array']),
                    json_encode($return_inventario['stocknuevo_array']),
                    NULL,
                    $query_detalle_venta[0]['producto_impuesto'],
                    $query_detalle_venta[0]['costo_unitario'],
                    $fecha
                );
            }
        }


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {

            if ($status == PEDIDO_ANULADO) {
                $arreglo = array(
                    'id_venta' => $id,
                    'tipo_anulacion' => $campos['tipo_anulacion'],
                    'nUsuCodigo' => $campos['nUsuCodigo'],
                    'dat_fecha_registro' => !empty($fecha) ? $fecha : date('Y-m-d H:is'),
                    'apertua_caja_id' => $caja

                );
                $this->db->insert('venta_anular', $arreglo);
            }

            $condicion = array('venta_id' => $id);
            $campos2 = array('venta_status' => $status);

            $data['resultado'] = $this->update_venta($condicion, $campos2);

            $venta_status['fecha'] = $fecha;
            $venta_status['venta_id'] = $id;
            $venta_status['vendedor_id'] = $campos['nUsuCodigo'];
            $venta_status['estatus'] = $status;
            $venta_status['apertua_caja_id'] = $caja;
            $this->db->insert('venta_estatus', $venta_status);
            $this->db->trans_commit();
            return true;
        }


        $this->db->trans_off();
    }

    function get_credito_by_venta($venta)
    {

        $this->db->select('*,  venta.pagado, venta.total');
        $this->db->from('credito');

        $this->db->join('venta', 'venta.venta_id=credito.id_venta', 'LEFT');
        $this->db->where('id_venta', $venta);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_credito_by_id($id)
    {
        $this->db->select('*,  venta.pagado, venta.total');
        $this->db->from('credito');

        $this->db->join('venta', 'venta.venta_id=credito.id_venta', 'LEFT');
        $this->db->where('credito_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }


    //TODO CAMBIAR TODOS LOS DEMAS POR ESTE METODO
    public
    function traer_by_mejorado(
        $select = false,
        $from = false,
        $join = false,
        $campos_join = false,
        $tipo_join,
        $where = false,
        $nombre_in,
        $where_in,
        $nombre_or,
        $where_or,
        $group = false,
        $order = false,
        $retorno = false,
        $limit = false,
        $start = 0,
        $order_dir = false,
        $like = false,
        $where_custom
    ) {
        if ($select != false) {
            $this->db->select($select);
            $this->db->from($from);
        }
        if ($join != false and $campos_join != false) {

            for ($i = 0; $i < count($join); $i++) {

                if ($tipo_join != false) {

                    // for ($t = 0; $t < count($tipo_join); $t++) {

                    // if ($tipo_join[$t] != "") {

                    $this->db->join($join[$i], $campos_join[$i], $tipo_join[$i]);
                    //}

                    //}

                } else {

                    $this->db->join($join[$i], $campos_join[$i]);
                }
            }
        }
        if ($where != false) {
            $this->db->where($where);
        }
        if ($like != false) {
            $this->db->like($like);
        }
        if ($where_custom != false) {
            $this->db->where($where_custom);
        }

        if ($nombre_in != false) {
            for ($i = 0; $i < count($nombre_in); $i++) {
                $this->db->where_in($nombre_in[$i], $where_in[$i]);
            }
        }

        if ($nombre_or != false) {
            for ($i = 0; $i < count($nombre_or); $i++) {
                $this->db->or_where($where_or);
            }
        }

        if ($limit != false) {
            $this->db->limit($limit, $start);
        }
        if ($group != false) {
            $this->db->group_by($group);
        }

        if ($order != false) {
            $this->db->order_by($order, $order_dir);
        }

        $query = $this->db->get();

        //echo $this->db->last_query();

        if ($retorno == "RESULT_ARRAY") {

            return $query->result_array();
        } elseif ($retorno == "RESULT") {
            return $query->result();
        } else {
            return $query->row_array();
        }
    }


    public
    function traer_by(
        $select = false,
        $from = false,
        $join = false,
        $campos_join = false,
        $tipo_join,
        $where = false,
        $nombre_in,
        $where_in,
        $nombre_or,
        $where_or,
        $group = false,
        $order = false,
        $retorno = false
    ) {

        if ($select != false) {
            $this->db->select($select);
            $this->db->from($from);
        }
        if ($join != false and $campos_join != false) {

            for ($i = 0; $i < count($join); $i++) {

                if ($tipo_join != false) {

                    // for ($t = 0; $t < count($tipo_join); $t++) {

                    ///                        if ($tipo_join[$t] != "") {

                    $this->db->join($join[$i], $campos_join[$i], $tipo_join[$i]);
                    //                     }

                    // }

                } else {

                    $this->db->join($join[$i], $campos_join[$i]);
                }
            }
        }
        if ($where != false) {
            $this->db->where($where);
        }

        if ($nombre_in != false) {
            for ($i = 0; $i < count($nombre_in); $i++) {
                $this->db->where_in($nombre_in[$i], $where_in[$i]);
            }
        }

        if ($nombre_or != false) {
            for ($i = 0; $i < count($nombre_or); $i++) {
                $this->db->or_where($where_or);
            }
        }

        if ($group != false) {
            $this->db->group_by($group);
        }

        if ($order != false) {
            $this->db->order_by($order);
        }

        $query = $this->db->get();
        //echo $this->db->last_query();

        if ($retorno == "RESULT_ARRAY") {

            return $query->result_array();
        } elseif ($retorno == "RESULT") {
            return $query->result();
        } else {
            return $query->row_array();
        }
    }

    function get_by($campo, $valor)
    {
        $this->db->where($campo, $valor);
        //  $this->db->join('condiciones_pago', 'condiciones_pago.id_condiciones=venta.condicion_pago', 'left');
        $query = $this->db->get('venta');
        return $query->row_array();
    }

    function get_all_by($campo, $valor)
    {
        $this->db->where($campo, $valor);
        //  $this->db->join('condiciones_pago', 'condiciones_pago.id_condiciones=venta.condicion_pago', 'left');
        $query = $this->db->get('venta');
        return $query->result_array();
    }

    public function ventas_por_fecha($fechadesde, $fechahasta, $tipos_venta_select = "TODOS", $tipos_venta_text = "TODOS")
    {


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

        $where_custom = false;

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = 'DATE(fecha)';
        $select = 'SUM(total) as total, 
        (select SUM(venta_devolucion.total) from venta_devolucion
        join venta on venta.venta_id=venta_devolucion.id_venta
        JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`venta`.`venta_tipo`
JOIN condiciones_pago ON tipo_venta.condicion_pago=condiciones_pago.id_condiciones
where date(fecha_devolucion)=date(vb.fecha)
AND `condiciones_pago`.`dias` = "0" ';

        if ($tipos_venta_select != "TODOS") {
            $select .= ' and venta.venta_tipo=' . $tipos_venta_select;
        }

        $select .= ' ) as devoluciones,  (select SUM(va.total) from venta va
JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`va`.`venta_tipo`
JOIN condiciones_pago ON tipo_venta.condicion_pago=condiciones_pago.id_condiciones
where date(fecha)=date(vb.fecha) and va.venta_status  = "' . PEDIDO_ANULADO . '" ';

        if ($tipos_venta_select != "TODOS") {
            $select .= ' and va.venta_tipo=' . $tipos_venta_select;
        }
        $select .= ' AND `condiciones_pago`.`dias` = "0") as anulaciones, 
vb.venta_id, SUM(total_impuesto) as total_impuesto, SUM(excluido) as excluido, SUM(gravado) as gravado, 
            fecha, SUM(descuento_valor) as descuento_valor, SUM(descuento_porcentaje) as descuento_porcentaje,
            (SELECT SUM(descuento) 
FROM detalle_venta_backup  join venta_backup on venta_backup.venta_id=detalle_venta_backup.id_venta
 WHERE (impuesto <= 0  OR impuesto IS NULL ) ';
        if ($tipos_venta_select != "TODOS") {
            $select .= ' and venta_backup.venta_tipo=' . $tipos_venta_select;
        }
        $select .= ' AND date(venta_backup.fecha)=date(vb.fecha))
AS descexluido, 
(SELECT SUM(descuento) 
FROM detalle_venta_backup join venta_backup on venta_backup.venta_id=detalle_venta_backup.id_venta
 WHERE (impuesto <> 0  AND  impuesto IS NOT NULL ) ';
        if ($tipos_venta_select != "TODOS") {
            $select .= ' and venta_backup.venta_tipo=' . $tipos_venta_select;
        }
        $select .= ' AND  date(venta_backup.fecha)=date(vb.fecha)) AS desgravado';
        $from = "venta_backup vb";
        $join = array();
        $campos_join = array();
        $tipo_join = array(null, null, null, null, 'left');

        $order = 'fecha';
        $start = 0;
        $limit = false;

        $datas['data'] = $this->traer_by_mejorado(
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
            'asc',
            false,
            $where_custom
        );


        $array = array();

        $count = 0;
        $datajson = array();
        $impuestos = $this->impuestos_model->get_impuestos();
        foreach ($datas['data'] as $data) {

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
            $json[] = date('d-m-Y', strtotime($data['fecha']));
            if ($tipos_venta_text != "TODOS") {
                $json[] = $tipos_venta_text;
            }

            $json[] = number_format($data['excluido'], 2, ',', '.');
            $json[] = number_format($data['descexluido'], 2, ',', '.');
            $json[] = number_format($data['gravado'], 2, ',', '.');
            $json[] = number_format($data['desgravado'], 2, ',', '.');
            $json[] = number_format(floatval($data['descuento_valor']) + floatval($data['descuento_porcentaje']), 2, ',', '.');


            foreach ($impuestos as $impuesto) {
                $totalimp = $this->getTotalesByImpuestos(
                    $impuesto['porcentaje_impuesto'],
                    array('DATE(fecha)' => date('Y-m-d', strtotime($data['fecha'])))
                );
                $json[] = is_numeric($totalimp['iva']) ? number_format($totalimp['iva'], 2, ',', '.') : 0;

                if ($impuesto['porcentaje_impuesto'] > 0) {
                    $gravado = is_numeric($totalimp['iva']) ? ($totalimp['iva'] / $impuesto['porcentaje_impuesto']) * 100 : 0;
                } else {
                    $gravado = 0;
                }
                $json[] = number_format($gravado, 2, ',', '.');
            }
            $json[] = number_format($data['total_impuesto'], 2, ',', '.');
            $json[] = number_format($data['anulaciones'], 2, ',', '.');
            $json[] = number_format($data['devoluciones'], 2, ',', '.');
            $json[] = number_format($data['total'] - ($data['devoluciones'] + $data['anulaciones']), 2, ',', '.');

            $datajson[] = $json;

            $count++;
        }

        $array['data'] = $datajson;

        return json_encode($array);
    }


    function getProductosZona($select, $where, $retorno, $group, $condicion2)
    {
        $this->db->select($select);
        $this->db->from('detalle_venta');
        $this->db->join('venta', 'venta.venta_id=detalle_venta.id_venta');
        $this->db->join('producto', 'producto.producto_id=detalle_venta.id_producto');
        $this->db->join('cliente', 'venta.id_cliente=cliente.id_cliente');
        $this->db->join('usuario', 'usuario.nUsuCodigo=venta.id_vendedor');
        $this->db->join('unidades', 'unidades.id_unidad=detalle_venta.unidad_medida');
        $this->db->join('familia', 'familia.id_familia=producto.producto_familia', 'left');
        $this->db->join('lineas', 'lineas.id_linea=producto.producto_linea', 'left');
        $this->db->join('usuario_has_zona', 'usuario_has_zona.id_usuario=usuario.nUsuCodigo', 'left');
        $this->db->join('zonas', 'usuario_has_zona.id_zona=zonas.zona_id', 'left');
        $this->db->join('ciudades', 'ciudades.ciudad_id=zonas.ciudad_id');
        $this->db->join('grupos', 'grupos.id_grupo=producto.produto_grupo', 'left');

        $this->db->where('zonas.status', 1);
        $this->db->where($condicion2);

        $this->db->where($where);


        if ($group != false) {
            $this->db->group_by($group);
        }

        $query = $this->db->get();

        //echo $this->db->last_query();
        if ($retorno == "RESULT") {
            return $query->result();
        }
    }

    function getProductosZonaX($select, $where, $retorno, $group)
    {
        $this->db->select($select);
        $this->db->from('detalle_venta');
        $this->db->join('venta', 'venta.venta_id=detalle_venta.id_venta');
        $this->db->join('producto', 'producto.producto_id=detalle_venta.id_producto');
        $this->db->join('cliente', 'venta.id_cliente=cliente.id_cliente');
        $this->db->join('usuario', 'usuario.nUsuCodigo=venta.id_vendedor');
        $this->db->join('documento_venta', 'documento_venta.id_venta=venta.venta_id', 'left');
        $this->db->join('proveedor', 'producto.producto_proveedor=proveedor.id_proveedor');
        $this->db->join('unidades', 'unidades.id_unidad=detalle_venta.unidad_medida');
        $this->db->join('familia', 'familia.id_familia=producto.producto_familia');
        $this->db->join('lineas', 'lineas.id_linea=producto.producto_linea');
        $this->db->join('usuario_has_zona', 'usuario_has_zona.id_usuario=usuario.nUsuCodigo');
        $this->db->join('zonas', 'usuario_has_zona.id_zona=zonas.zona_id');
        $this->db->join('ciudades', 'ciudades.ciudad_id=zonas.ciudad_id');
        $this->db->join('grupos', 'grupos.id_grupo=producto.produto_grupo');
        $this->db->where('zonas.status', 1);

        $this->db->where($where);

        if ($group != false) {
            $this->db->group_by($group);
            $this->db->order_by("count(venta.venta_id)", "desc");
            $this->db->limit(10);
        }

        $query = $this->db->get();

        if ($retorno == "RESULT") {
            return $query->result();
        }
    }


    function  getUtilidades($select, $where, $retorno, $group)
    {
        $this->db->select($select);
        $this->db->from('detalle_venta_unidad');
        $this->db->join('detalle_venta', 'detalle_venta_unidad.detalle_venta_id=detalle_venta.id_detalle');
        $this->db->join('venta', 'venta.venta_id=detalle_venta.id_venta');
        $this->db->join('unidades', 'unidades.id_unidad=detalle_venta_unidad.unidad_id');
        $this->db->join('producto', 'producto.producto_id=detalle_venta.id_producto');
        $this->db->join('impuestos', 'producto.producto_impuesto=impuestos.id_impuesto', 'left');

        $this->db->join('documento_venta', 'documento_venta.id_venta=venta.venta_id', 'left');
        $this->db->join('proveedor', 'producto.producto_proveedor=proveedor.id_proveedor', 'left');


        $this->db->where($where);
        if ($group != false) {
            $this->db->group_by($group);
            $this->db->order_by('sum(detalle_utilidad)', "desc");
            $this->db->limit(10);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($retorno == "RESULT") {
            return $query->result();
        }
    }

    function get_ventas_user()
    {
        $this->db->join('usuario', 'usuario.nUsuCodigo=venta.id_vendedor');
        $this->db->join('cliente', 'cliente.id_cliente=venta.id_cliente');
        $this->db->group_by('id_vendedor');
        $query = $this->db->get('venta');
        return $query->result_array();
    }

    function ventas_grafica($where)
    {

        $query = $this->db->query("SELECT  detalle_venta.id_producto,venta.fecha,precio FROM `detalle_venta`
LEFT JOIN venta ON venta.venta_id = detalle_venta.id_venta
WHERE  id_producto =" . $where . " ");
        return $query->result_array();
    }

    function compras_grafica($where)
    {

        $query = $this->db->query("SELECT id_producto,precio,fecha_registro FROM `detalleingreso`
LEFT JOIN ingreso ON ingreso.id_ingreso = detalleingreso.id_ingreso WHERE id_producto=" . $where . " ");
        return $query->result_array();
    }

    function getDetalleVenta($order, $where, $groupby = false)
    {
        $this->db->select('detalle_venta.*,producto.*,venta.fecha,venta.venta_id AS numero, venta.id_vendedor, 
        venta.venta_status AS estado,local_nombre,local_id, documento_venta.*,
        venta.id_cliente, venta.venta_status');
        $this->db->from('detalle_venta');
        $this->db->join('producto', 'producto.producto_id=detalle_venta.id_producto');
        $this->db->join('venta', 'venta.venta_id=detalle_venta.id_venta');
        $this->db->join('local', 'local.int_local_id=venta.local_id');
        $this->db->join('documento_venta', 'documento_venta.id_venta=venta.venta_id', 'left');
        $this->db->where($where);
        $this->db->group_by($groupby);
        if ($order != FALSE) {
            $this->db->order_by($order);
        }
        $query = $this->db->get();

        return $query->result_array();
    }

    function update_inventario($campos, $wheres)
    {
        $this->db->trans_start();
        $this->db->where($wheres);
        $this->db->update('inventario', $campos);
        $this->db->trans_complete();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    function update_venta($condicion, $campos)
    {
        $this->db->trans_start();
        $this->db->where($condicion);
        $this->db->update('venta', $campos);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function update_venta_backup($condicion, $campos)
    {
        $this->db->trans_start();
        $this->db->where($condicion);
        $this->db->update('venta_backup', $campos);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }


    function get_estatus($id)
    {
        $this->db->where('venta_id', $id);
        $query = $this->db->get('venta');
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['venta_status'];
        } else {
            return '';
        }
    }

    function buscar_NroVenta_credito($nroVenta)
    {
        $this->db->select('v.venta_id,c.dec_credito_montodeuda,cl.razon_social');
        $this->db->from('venta v');
        $this->db->join('credito c', 'v.venta_id=c.id_venta');
        $this->db->join('documento_venta', 'documento_venta.id_venta = v.venta_id');
        $this->db->join('cliente cl', 'cl.id_cliente = v.id_cliente');
        $this->db->where('v.venta_id', $nroVenta);

        $query = $this->db->get();

        return $query->result();
    }

    function get_venta_by_status($estatus, $fecha = false)
    {
        $this->db->select('*');
        $this->db->from('venta');
        $this->db->join('cliente', 'cliente.id_cliente=venta.id_cliente', 'left');
        $this->db->join('local', 'local.int_local_id=venta.local_id', 'left');
        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta.venta_tipo', 'left');
        $this->db->join('condiciones_pago', 'condiciones_pago.id_condiciones=tipo_venta.condicion_pago', 'left');
        $this->db->join('documento_venta', 'documento_venta.id_venta=venta.venta_id', 'left');
        $this->db->join('usuario', 'usuario.nUsuCodigo=venta.id_vendedor', 'left');
        $this->db->join('resolucion_dian', 'resolucion_dian.resolucion_id=documento_venta.id_resolucion', 'left');

        $this->db->where_in('venta_status', $estatus);
        if ($fecha != false) {
            $this->db->where('DATE(venta.fecha)', $fecha);
        }


        $query = $this->db->get();

        return $query->result();
    }

    function get_ventas_by($condicion)
    {
        $this->db->select('venta.*, cliente.*, tipo_venta.maneja_impresion, resolucion_dian.*, venta_devolucion.id_devolucion,  zonas.zona_nombre, 
        local.*,condiciones_pago.*,documento_venta.*,usuario.*, tipo_venta.tipo_venta_nombre,

            (select count(id_producto) from detalle_venta where id_venta = venta.venta_id ) as cantidad_productos');

        $this->db->from('venta');
        $this->db->join('cliente', 'cliente.id_cliente=venta.id_cliente', 'left');
        $this->db->join('ciudades', 'ciudades.ciudad_id=cliente.ciudad_id', 'left');
        $this->db->join('zonas', 'cliente.id_zona=zonas.zona_id', 'left');
        $this->db->join('local', 'local.int_local_id=venta.local_id', 'left');
        //$this->db->join('condiciones_pago', 'condiciones_pago.id_condiciones=venta.condicion_pago');
        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta.venta_tipo');
        $this->db->join('condiciones_pago', 'condiciones_pago.id_condiciones=tipo_venta.condicion_pago', 'left');
        $this->db->join('documento_venta', 'documento_venta.id_venta=venta.venta_id', 'left');
        $this->db->join('usuario', 'usuario.nUsuCodigo=venta.id_vendedor', 'left');
        $this->db->join('venta_devolucion', 'venta_devolucion.id_venta=venta.venta_id', 'left');
        $this->db->join('resolucion_dian', 'resolucion_dian.resolucion_id=documento_venta.id_resolucion', 'left');
        $this->db->order_by('venta.venta_id', 'desc');
        $this->db->where($condicion);
        $query = $this->db->get();

        // echo $this->db->last_query();

        return $query->result();
    }

    function getSalidasByDevoluciones($satatuscaja = false, $custom_where = false, $credito = false)
    {

        //        echo $satatuscaja;
        //aqui voy  a alcular total venta y total enta backy louego resto ambos y eso es tody
        $query = "select SUM(venta_devolucion.total) as total,  
( select SUM(detalle_venta_devolucion.subtotal) as gravado  FROM 
detalle_venta_devolucion
join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta 
join venta on venta.venta_id= detalle_venta_devolucion.id_venta 
 JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`venta`.`venta_tipo`
 JOIN `condiciones_pago` ON `tipo_venta`.`condicion_pago`=`condiciones_pago`.`id_condiciones`
 where detalle_venta_devolucion.impuesto>0 ";


        if ($credito == false) {
            $query = $query . " AND `condiciones_pago`.`dias` = '0'";
        } else {
            //todos lo que fue a credito credito
            $query = $query . " AND `condiciones_pago`.`dias` > '0'";
        }


        if ($satatuscaja != false) {
            $query = $query . " AND
id_cuadre_caja=" . $satatuscaja;
        }

        if ($custom_where != false) {
            $query = $query . $custom_where;
        }


        $query .= ") as gravado, 
( select SUM(detalle_venta_devolucion.total) as excluido  FROM  detalle_venta_devolucion
join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta 

join venta on venta.venta_id= venta_devolucion.id_venta 

 JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`venta`.`venta_tipo`
 JOIN `condiciones_pago` ON `tipo_venta`.`condicion_pago`=`condiciones_pago`.`id_condiciones`
 where detalle_venta_devolucion.impuesto<=0 ";

        if ($credito == false) {
            $query = $query . " AND `condiciones_pago`.`dias` = '0'";
        } else {
            //todos lo que fue a credito credito
            $query = $query . " AND `condiciones_pago`.`dias` > '0'";
        }

        if ($satatuscaja != false) {
            $query = $query . " AND
id_cuadre_caja=" . $satatuscaja;
        }

        if ($custom_where != false) {
            $query = $query . $custom_where;
        }


        $query .= "
) as excluido, 
( select SUM(detalle_venta_devolucion.impuesto * detalle_venta_devolucion.cantidad) as iva  FROM 
detalle_venta_devolucion
join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta 


join venta on venta.venta_id= venta_devolucion.id_venta 
 JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`venta`.`venta_tipo`
 JOIN `condiciones_pago` ON `tipo_venta`.`condicion_pago`=`condiciones_pago`.`id_condiciones`
where  1 ";


        if ($credito == false) {
            $query = $query . " AND `condiciones_pago`.`dias` = '0'";
        } else {
            //todos lo que fue a credito credito
            $query = $query . " AND `condiciones_pago`.`dias` > '0'";
        }
        if ($satatuscaja != false) {
            $query = $query . " AND
id_cuadre_caja=" . $satatuscaja;
        }

        if ($custom_where != false) {
            $query = $query . $custom_where;
        }


        $query .= ") as iva, ( select SUM(detalle_venta_devolucion.otro_impuesto) as otrosimpuestos  FROM 
detalle_venta_devolucion
join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta 

join venta on venta.venta_id= venta_devolucion.id_venta 
 JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`venta`.`venta_tipo`
 JOIN `condiciones_pago` ON `tipo_venta`.`condicion_pago`=`condiciones_pago`.`id_condiciones`
where  1 ";


        if ($credito == false) {
            $query = $query . " AND `condiciones_pago`.`dias` = '0'";
        } else {
            //todos lo que fue a credito credito
            $query = $query . " AND `condiciones_pago`.`dias` > '0'";
        }


        if ($satatuscaja != false) {
            $query = $query . " AND
id_cuadre_caja=" . $satatuscaja;
        }

        if ($custom_where != false) {
            $query = $query . $custom_where;
        }


        $query .= ") as otrosimpuestos, 
count(venta_devolucion.id_devolucion) as registros from venta_devolucion
join venta on venta.venta_id= venta_devolucion.id_venta 
 JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`venta`.`venta_tipo`

JOIN `condiciones_pago` ON `tipo_venta`.`condicion_pago`=`condiciones_pago`.`id_condiciones`
where 1 ";

        if ($credito == false) {
            $query = $query . " AND `condiciones_pago`.`dias` = '0'";
        } else {
            //todos lo que fue a credito credito
            $query = $query . " AND `condiciones_pago`.`dias` > '0'";
        }

        if ($satatuscaja != false) {
            $query = $query . " AND
apertura_caja_id=" . $satatuscaja;
        }

        if ($custom_where != false) {
            $query = $query . $custom_where;
        }

        $query = $this->db->query($query);

        // echo $this->db->last_query();
        $venta = $query->row_array();

        // var_dump($venta);

        //
        $result = array();
        $result['total'] = is_numeric($venta['total']) ? $venta['total'] : 0;
        $result['registros'] = is_numeric($venta['registros']) ? $venta['registros'] : 0;
        $result['gravado'] = is_numeric($venta['gravado']) ? $venta['gravado'] : 0;
        $result['excluido'] = is_numeric($venta['excluido']) ? $venta['excluido'] : 0;
        $result['iva'] = is_numeric($venta['iva']) ? $venta['iva'] : 0;
        $result['otrosimpuestos'] = is_numeric($venta['otrosimpuestos']) ? $venta['otrosimpuestos'] : 0;


        return $result;
    }

    function getSalidasByAnulaciones($satatuscaja = false, $custom_where = false, $no_credito = false)
    {

        //aqui voy  a alcular total venta y total enta backy louego resto ambos y eso es tody
        $query = "select SUM(venta.total)  as total, SUM(venta.total_otros_impuestos) as otrosimpuestos, 
SUM(venta.total_impuesto) AS iva,  SUM(venta.gravado) as gravado,
    SUM(venta.excluido) as excluido,  count(venta_anular.id_venta) as registros from venta_backup 
join venta on venta.venta_id= venta_backup.venta_id join venta_anular on venta_anular.id_venta= venta.venta_id
JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`venta_backup`.`venta_tipo`
JOIN `condiciones_pago` ON `tipo_venta`.`condicion_pago`=`condiciones_pago`.`id_condiciones`
 where venta.venta_status  = '" . PEDIDO_ANULADO . "' ";


        if ($no_credito == false) {
            $query = $query . " AND `condiciones_pago`.`dias` = '0'";
        } else {
            //todos lo que fue a credito credito
            $query = $query . " AND `condiciones_pago`.`dias` > '0'";
        }

        if ($satatuscaja != false) {

            $query = $query . " AND
venta_anular.apertua_caja_id=" . $satatuscaja;
        }


        if ($custom_where != false) {

            $query = $query . $custom_where;
        }
        $query = $this->db->query($query);
        $venta = $query->row_array();

        //echo $this->db->last_query();
        $result = array();
        $result['total'] = is_numeric($venta['total']) ? $venta['total'] : 0;
        $result['registros'] = is_numeric($venta['registros']) ? $venta['registros'] : 0;
        $result['gravado'] = is_numeric($venta['gravado']) ? $venta['gravado'] : 0;
        $result['excluido'] = is_numeric($venta['excluido']) ? $venta['excluido'] : 0;
        $result['iva'] = is_numeric($venta['iva']) ? $venta['iva'] : 0;
        $result['otrosimpuestos'] = is_numeric($venta['otrosimpuestos']) ? $venta['otrosimpuestos'] : 0;
        return $result;
    }


    //con anulaciones
    function get_total_by_forma_pago($condicion = false)
    {


        $query = "
   SELECT  venta_id,  SUM(monto) as suma, SUM(total) AS total,  id_forma_pago, SUM(gravado) as gravado, SUM(excluido) as excluido, SUM(total_impuesto) AS iva,
    SUM(total_otros_impuestos) AS otros_impuestos, SUM(descuento_valor) AS descuento_valor, 
    SUM(descuento_porcentaje) AS descuento_porcentaje, COUNT(DISTINCT venta_backup.venta_id) AS num 
    FROM ((SELECT DISTINCT venta_id, gravado, venta_backup.total,  
   excluido AS excluido, total_impuesto, total_otros_impuestos, descuento_porcentaje, descuento_valor,
    venta_status, fecha, venta_tipo    , monto, id_forma_pago
     FROM `venta_backup` JOIN `venta_forma_pago` ON `venta_forma_pago`.`id_venta`=`venta_backup`.`venta_id` AND `monto`>0 
    JOIN `tipo_venta` ON `tipo_venta`.`tipo_venta_id`=`venta_backup`.`venta_tipo` JOIN `condiciones_pago`
     ON `tipo_venta`.`condicion_pago`=`condiciones_pago`.`id_condiciones` WHERE  `condiciones_pago`.`dias` = '0'  
   
     
";
        foreach ($condicion as $k => $v) {

            $query .= " and " . $k . " '" . $v . "'";
        }
        $query .= "  GROUP BY  venta_backup.venta_id ) venta_backup) ";

        //echo $query;
        $query = $this->db->query($query);
        //echo $this->db->last_query();
        return $query->row_array();
    }


    function get_total_ventas_con_descuentos($condicion = false)
    {


        $query = "
   SELECT  COUNT(DISTINCT venta_id) as num
     FROM `venta_backup`  WHERE venta_status = 'COMPLETADO' AND (descuento_valor>0  OR descuento_porcentaje>0)
      
";
        foreach ($condicion as $k => $v) {

            $query .= " and " . $k . " '" . $v . "'";
        }


        $query = $this->db->query($query);
        //echo $this->db->last_query();
        return $query->row_array();
    }


    //con anulacione sy devoliciones, vienen en los subqueries para restar en la vista
    // en esta tabla e subtotal no descuenta el descuento, toca restarlo
    //osea subtotal - descuento igual a subtotal
    function getTotalesByImpuestos($impuestoId, $condicion = false)
    {

        $custom_condicion = "";
        $custom_condicion_dev2 = "";
        $custom_condicion_ANU = "";


        if ($condicion) {

            foreach ($condicion as $c => $v) {


                switch ($c):
                    case 'venta_status':

                        $custom_condicion_ANU .= " and venta_status='" . PEDIDO_ANULADO . "'";
                        $custom_condicion .= " and " . $c . " = '" . $v . "'";
                        break;
                    case 'date(fecha) >=':

                        $custom_condicion_ANU .= " and DATE(dat_fecha_registro) >= '" . $v . "'";
                        $custom_condicion .= " and DATE(venta_devolucion.fecha_devolucion) >= '" . $v . "'";
                        $custom_condicion_dev2 .= " and DATE(venta_backup.fecha) >= '" . $v . "'";
                        break;

                    case 'date(fecha) <=':

                        $custom_condicion_ANU .= " and DATE(dat_fecha_registro) <= '" . $v . "'";
                        $custom_condicion .= " and DATE(venta_devolucion.fecha_devolucion) <='" . $v . "'";
                        $custom_condicion_dev2 .= " and DATE(venta_backup.fecha) <='" . $v . "'";
                        break;
                    default:
                        $custom_condicion .= " and " . $c . " = '" . $v . "'";
                        $custom_condicion_ANU .= " and " . $c . " = '" . $v . "'";
                        break;
                endswitch;
            }
        }

        $select = " SUM(detalle_venta_backup.subtotal- detalle_venta_backup.descuento) as subtotal,
        SUM(detalle_venta_backup.descuento) as descuento,  ";


        $select .= "(select SUM(detalle_venta_devolucion.subtotal) from detalle_venta_devolucion join producto p on p.producto_id=detalle_venta_devolucion.id_producto
        join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta";

        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }
        $select .= ' detalle_venta_devolucion.impuesto > 0  and porcentaje_impuesto= ' . $impuestoId . ' ' . $custom_condicion . ') as devolucion_gravado,';

        $select .= "(select SUM(detalle_venta_devolucion.subtotal) from detalle_venta_devolucion join producto p on p.producto_id=detalle_venta_devolucion.id_producto
        join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta";

        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }
        $select .= ' detalle_venta_devolucion.impuesto > 0  and porcentaje_impuesto= ' . $impuestoId . ' ' . $custom_condicion . ' ' . $custom_condicion_dev2 . ')
         as devolucion_gravado_dia,
         
           (select SUM(detalle_venta_devolucion.impuesto*detalle_venta_devolucion.cantidad) from detalle_venta_devolucion 
           join producto p on p.producto_id=detalle_venta_devolucion.id_producto
           join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta ';
        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }

        $select .= ' detalle_venta_devolucion.impuesto > 0  and porcentaje_impuesto= ' . $impuestoId . ' ' . $custom_condicion . ') as devolucion_iva,
         
         (select SUM(detalle_venta_devolucion.impuesto*detalle_venta_devolucion.cantidad) from detalle_venta_devolucion join producto p on p.producto_id=detalle_venta_devolucion.id_producto
           join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta ';


        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }

        $select .= ' detalle_venta_devolucion.impuesto > 0  and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion_dev2 . ') as devolucion_iva_dia,
         
          (select SUM(detalle_venta.subtotal - detalle_venta.descuento) from detalle_venta 
          join venta_anular on venta_anular.id_venta=detalle_venta.id_venta
           join venta on venta.venta_id=detalle_venta.id_venta
          join producto p on p.producto_id=detalle_venta.id_producto
         where detalle_venta.impuesto>0   and porcentaje_impuesto= ' . $impuestoId . ' ' . $custom_condicion_ANU . ' ';


        if (isset($condicion['venta_tipo'])) {
            $select .= "and venta.`venta_tipo`=" . $condicion['venta_tipo'];
        }
        $select .= ' ) as anulacion_gravado,
         
            (select SUM(detalle_venta.impuesto) from detalle_venta 
           join venta_anular on venta_anular.id_venta=detalle_venta.id_venta
            join venta on venta.venta_id=detalle_venta.id_venta
          join producto p on p.producto_id=detalle_venta.id_producto
         where detalle_venta.impuesto>0 and porcentaje_impuesto= ' . $impuestoId . ' ' . $custom_condicion_ANU . ' ';
        if (isset($condicion['venta_tipo'])) {
            $select .= "and venta.`venta_tipo`=" . $condicion['venta_tipo'];
        }

        $select .= ' ) as anulacion_iva, SUM(detalle_venta_backup.impuesto) AS iva';


        $this->db->select($select);
        $this->db->join('detalle_venta_backup', 'detalle_venta_backup.id_venta=venta_backup.venta_id');

        $this->db->join('producto', 'detalle_venta_backup.id_producto=producto.producto_id');

        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta_backup.venta_tipo');
        $this->db->join('condiciones_pago', 'tipo_venta.condicion_pago=condiciones_pago.id_condiciones');

        //   $this->db->where('condiciones_pago.dias','0');
        $this->db->where("porcentaje_impuesto", $impuestoId);
        $this->db->where("impuesto >", 0);

        $this->db->where($condicion);
        // $this->db->group_by('venta_id');
        $query = $this->db->get('venta_backup');
        //echo $this->db->last_query();
        return $query->row_array();
    }


    function getTotalesRealesByImpuestos($impuestoId, $condicion = false)
    {

        $custom_condicion = "";
        $custom_condicion_dev2 = "";
        $custom_condicion_ANU = "";


        if ($condicion) {

            foreach ($condicion as $c => $v) {


                switch ($c):
                    case 'venta_status':

                        $custom_condicion_ANU .= " and venta_status='" . PEDIDO_ANULADO . "'";
                        $custom_condicion .= " and " . $c . " = '" . $v . "'";
                        break;
                    case 'date(fecha) >=':

                        $custom_condicion_ANU .= " and DATE(dat_fecha_registro) >= '" . $v . "'";
                        $custom_condicion .= " and DATE(venta_devolucion.fecha_devolucion) >= '" . $v . "'";
                        $custom_condicion_dev2 .= " and DATE(venta.fecha) >= '" . $v . "'";
                        break;

                    case 'date(fecha) <=':

                        $custom_condicion_ANU .= " and DATE(dat_fecha_registro) <= '" . $v . "'";
                        $custom_condicion .= " and DATE(venta_devolucion.fecha_devolucion) <='" . $v . "'";
                        $custom_condicion_dev2 .= " and DATE(venta.fecha) <='" . $v . "'";
                        break;
                    default:
                        $custom_condicion .= " and " . $c . " = '" . $v . "'";
                        $custom_condicion_ANU .= " and " . $c . " = '" . $v . "'";
                        break;
                endswitch;
            }
        }

        $select = " SUM(detalle_venta.subtotal- detalle_venta.descuento) as subtotal,
        SUM(detalle_venta.descuento) as descuento,  ";


        $select .= "(select SUM(detalle_venta_devolucion.subtotal) from detalle_venta_devolucion join producto p on p.producto_id=detalle_venta_devolucion.id_producto
        join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta";

        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }
        $select .= ' detalle_venta_devolucion.impuesto > 0  and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion . ') as devolucion_gravado,';

        $select .= "(select SUM(detalle_venta_devolucion.subtotal) from detalle_venta_devolucion join producto p on p.producto_id=detalle_venta_devolucion.id_producto
        join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta";

        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }
        $select .= ' detalle_venta_devolucion.impuesto > 0  and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion . ' ' . $custom_condicion_dev2 . ') as devolucion_gravado_dia,
         
           (select SUM(detalle_venta_devolucion.impuesto) from detalle_venta_devolucion join producto p on p.producto_id=detalle_venta_devolucion.id_producto
           join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta ';
        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }

        $select .= ' detalle_venta_devolucion.impuesto > 0  and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion . ') as devolucion_iva,
         
         (select SUM(detalle_venta_devolucion.impuesto) from detalle_venta_devolucion join producto p on p.producto_id=detalle_venta_devolucion.id_producto
           join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta ';


        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }

        $select .= ' detalle_venta_devolucion.impuesto > 0  and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion_dev2 . ') as devolucion_iva_dia,
         
          (select SUM(detalle_venta.subtotal - detalle_venta.descuento) from detalle_venta 
          join venta_anular on venta_anular.id_venta=detalle_venta.id_venta
           join venta on venta.venta_id=detalle_venta.id_venta
          join producto p on p.producto_id=detalle_venta.id_producto
         where detalle_venta.impuesto>0   and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion_ANU . ' ';


        if (isset($condicion['venta_tipo'])) {
            $select .= "and venta.`venta_tipo`=" . $condicion['venta_tipo'];
        }
        $select .= ' ) as anulacion_gravado,
         
            (select SUM(detalle_venta.impuesto) from detalle_venta 
           join venta_anular on venta_anular.id_venta=detalle_venta.id_venta
            join venta on venta.venta_id=detalle_venta.id_venta
          join producto p on p.producto_id=detalle_venta.id_producto
         where detalle_venta.impuesto>0 and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion_ANU . ' ';
        if (isset($condicion['venta_tipo'])) {
            $select .= "and venta.`venta_tipo`=" . $condicion['venta_tipo'];
        }

        $select .= ' ) as anulacion_iva, SUM(detalle_venta.impuesto) AS iva';


        $this->db->select($select);
        $this->db->join('detalle_venta', 'detalle_venta.id_venta=venta.venta_id');

        $this->db->join('producto', 'detalle_venta.id_producto=producto.producto_id');

        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta.venta_tipo');
        $this->db->join('condiciones_pago', 'tipo_venta.condicion_pago=condiciones_pago.id_condiciones');

        //   $this->db->where('condiciones_pago.dias','0');
        $this->db->where("producto_impuesto", $impuestoId);
        $this->db->where("impuesto >", 0);

        $this->db->where($condicion);
        // $this->db->group_by('venta_id');
        $query = $this->db->get('venta');
        //echo $this->db->last_query();
        return $query->row_array();
    }


    //con anulacione sy devoliciones, vienen en los subqueries para restar en la vista
    function getTotalesByOtroImpuestos($impuestoId, $condicion = false)
    {

        $custom_condicion = "";
        $custom_condicion_ANU = "";
        if ($condicion) {
            foreach ($condicion as $c => $v) {
                switch ($c):
                    case 'venta_status':
                        $custom_condicion_ANU .= " and venta_status='" . PEDIDO_ANULADO . "'";
                        $custom_condicion .= " and " . $c . " = '" . $v . "'";
                        break;
                    case 'date(fecha) >=':

                        $custom_condicion_ANU .= " and DATE(dat_fecha_registro) >= '" . $v . "'";
                        $custom_condicion .= " and DATE(venta_devolucion.fecha_devolucion) >= '" . $v . "'";
                        break;

                    case 'date(fecha) <=':

                        $custom_condicion_ANU .= " and DATE(dat_fecha_registro) <= '" . $v . "'";
                        $custom_condicion .= " and DATE(venta_devolucion.fecha_devolucion) <='" . $v . "'";
                        break;
                    default:
                        $custom_condicion .= " and " . $c . " = '" . $v . "'";
                        $custom_condicion_ANU .= " and " . $c . " = '" . $v . "'";
                        break;
                endswitch;
            }
        }


        $select = ' SUM(detalle_venta_backup.subtotal- detalle_venta_backup.descuento) as subtotal,
        
        (select SUM(venta_devolucion.subtotal) from detalle_venta_devolucion join producto p on p.producto_id=detalle_venta_devolucion.id_producto
         join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta';
        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }
        $select .= ' detalle_venta_devolucion.impuesto > 0 
          and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion . ') as devolucion_gravado,
           (select SUM(venta_devolucion.impuesto) from detalle_venta_devolucion join producto p on p.producto_id=detalle_venta_devolucion.id_producto
            join venta_devolucion on venta_devolucion.id_venta= detalle_venta_devolucion.id_venta ';
        if (isset($condicion['venta_tipo'])) {
            $select .= " JOIN venta ON venta.venta_id= venta_devolucion.id_venta 
            where venta.`venta_tipo`=" . $condicion['venta_tipo'] . " and ";
        } else {
            $select .= " where ";
        }
        $select .= ' detalle_venta_devolucion.impuesto > 0   and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion . ') as devolucion_iva,
         
         
          (select SUM(detalle_venta.subtotal- detalle_venta.descuento) from detalle_venta 
          join venta_anular on venta_anular.id_venta=detalle_venta.id_venta
           join venta on venta.venta_id=detalle_venta.id_venta
          join producto p on p.producto_id=detalle_venta.id_producto
         where detalle_venta.impuesto>0  and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion_ANU . ' ';
        if (isset($condicion['venta_tipo'])) {
            $select .= "and venta.`venta_tipo`=" . $condicion['venta_tipo'];
        }

        $select .= ') as anulacion_gravado,
         
            (select SUM(detalle_venta.impuesto) from detalle_venta 
           join venta_anular on venta_anular.id_venta=detalle_venta.id_venta
            join venta on venta.venta_id=detalle_venta.id_venta
          join producto p on p.producto_id=detalle_venta.id_producto
         where detalle_venta.impuesto>0  and producto_impuesto= ' . $impuestoId . ' ' . $custom_condicion_ANU . ' ';
        if (isset($condicion['venta_tipo'])) {
            $select .= "and venta.`venta_tipo`=" . $condicion['venta_tipo'];
        }

        $select .= ') as anulacion_iva, SUM(detalle_venta_backup.otro_impuesto) AS iva';
        $this->db->select($select);
        $this->db->join('detalle_venta_backup', 'detalle_venta_backup.id_venta=venta_backup.venta_id');
        $this->db->join('producto', 'detalle_venta_backup.id_producto=producto.producto_id');

        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta_backup.venta_tipo');
        $this->db->join('condiciones_pago', 'tipo_venta.condicion_pago=condiciones_pago.id_condiciones');

        //   $this->db->where('condiciones_pago.dias','0');
        $this->db->where("producto.otro_impuesto", $impuestoId);
        $this->db->where("venta_status", COMPLETADO);

        $this->db->where($condicion);
        // $this->db->group_by('venta_id');
        $query = $this->db->get('venta_backup');
        //echo $this->db->last_query();
        return $query->row_array();
    }

    //sin anlacions // todo hacerlas con anulacione sy devouciones y raerlos en subquery para retsar
    function getTotalesByGrupo($grupo_id, $condicion = false)
    {
        $this->db->select(' SUM(detalle_venta.subtotal) as subtotal,
         SUM(detalle_venta.impuesto) AS iva');
        $this->db->join('detalle_venta', 'detalle_venta.id_venta=venta.venta_id');
        $this->db->join('producto', 'detalle_venta.id_producto=producto.producto_id');
        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta.venta_tipo');
        $this->db->join('condiciones_pago', 'tipo_venta.condicion_pago=condiciones_pago.id_condiciones');
        // $this->db->where('condiciones_pago.dias','0');
        $this->db->where("produto_grupo", $grupo_id);
        $this->db->where($condicion);
        $this->db->where("venta_status", COMPLETADO);
        // $this->db->group_by('venta_id');
        $query = $this->db->get('venta');
        // echo $this->db->last_query();

        return $query->row_array();
    }

    //con anulaciones
    function

    get_total_soloefectivo($condicion = false)
    {
        $this->db->select('SUM(total) as suma, SUM(gravado) AS gravado,  venta_id, SUM(excluido) AS excluido , SUM(total_impuesto) AS iva ,
         SUM(total_otros_impuestos) AS otros_impuestos ,
        SUM(descuento_valor) AS descuento_valor, SUM(descuento_porcentaje) AS descuento_porcentaje, COUNT(DISTINCT venta_backup.venta_id) as num');
        $this->db->join('venta_forma_pago', 'venta_forma_pago.id_venta=venta_backup.venta_id AND monto>0', 'LEFT OUTER');

        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta_backup.venta_tipo');
        $this->db->join('condiciones_pago', 'tipo_venta.condicion_pago=condiciones_pago.id_condiciones');
        $this->db->where('condiciones_pago.dias', '0');

        $this->db->where($condicion);
        $this->db->where('venta_status', 'COMPLETADO');
        $this->db->where('id_forma_pago IS NULL');
        // $this->db->group_by('venta_id');
        $query = $this->db->get('venta_backup');


        ///echo $this->db->last_query();
        return $query->row_array();
    }

    //sin anulaciones
    function get_totales_reales($condicion = false)
    {
        $this->db->select('SUM(total) as suma, SUM(gravado) AS gravado,  SUM(excluido) AS excluido , SUM(total_impuesto) AS iva ,
         SUM(total_otros_impuestos) AS otros_impuestos ,
        SUM(descuento_valor) AS descuento_valor, SUM(descuento_porcentaje) AS descuento_porcentaje');
        ///$this->db->join('venta_forma_pago', 'venta_forma_pago.id_venta=venta_backup.venta_id AND monto>0', 'LEFT OUTER');

        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta.venta_tipo');
        $this->db->join('condiciones_pago', 'tipo_venta.condicion_pago=condiciones_pago.id_condiciones');
        //$this->db->where('condiciones_pago.dias', '0');

        $this->db->where($condicion);
        $this->db->where('venta_status', 'COMPLETADO');
        // $this->db->where('id_forma_pago IS NULL');
        // $this->db->group_by('venta_id');
        $query = $this->db->get('venta');


        //echo $this->db->last_query();
        return $query->row_array();
    }


    //CON anulaciones
    function get_totales_reales_backup($condicion = false)
    {
        $this->db->select('SUM(total) as suma, SUM(gravado) AS gravado,  SUM(excluido) AS excluido , SUM(total_impuesto) AS iva ,
         SUM(total_otros_impuestos) AS otros_impuestos ,
        SUM(descuento_valor) AS descuento_valor, SUM(descuento_porcentaje) AS descuento_porcentaje');
        ///$this->db->join('venta_forma_pago', 'venta_forma_pago.id_venta=venta_backup.venta_id AND monto>0', 'LEFT OUTER');

        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta_backup.venta_tipo');
        $this->db->join('condiciones_pago', 'tipo_venta.condicion_pago=condiciones_pago.id_condiciones');
        //$this->db->where('condiciones_pago.dias', '0');

        $this->db->where($condicion);
        $this->db->where('venta_status', 'COMPLETADO');
        // $this->db->where('id_forma_pago IS NULL');
        // $this->db->group_by('venta_id');
        $query = $this->db->get('venta_backup');


        //echo $this->db->last_query();
        return $query->row_array();
    }


    //esto trae las ventas backup , toa restarle despues anulaciones y devoluciones
    function get_total_solocredito($condicion = false)
    {
        $this->db->select('SUM(total) as suma, count(venta.venta_id) as registros ,
         SUM(gravado) AS gravado, SUM(excluido) AS excluido , SUM(total_impuesto) AS iva ,
        
        SUM(total_otros_impuestos) AS otros_impuestos ,
        
        SUM(descuento_valor) AS descuento_valor, SUM(descuento_porcentaje) AS descuento_porcentaje, COUNT(venta_id) as num');

        $this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta.venta_tipo');
        $this->db->join('condiciones_pago', 'tipo_venta.condicion_pago=condiciones_pago.id_condiciones');
        $this->db->where('condiciones_pago.dias >', '0');

        $this->db->where($condicion);
        $this->db->where('venta_status', 'COMPLETADO');
        // $this->db->group_by('venta_id');
        $query = $this->db->get('venta_backup venta');

        //  echo $this->db->last_query();
        return $query->row_array();
    }


    function get_total_ventas_by_date($condicion)
    {
        $sql = "SELECT SUM(total) as suma FROM venta WHERE venta_status = 'COMPLETADO' AND DATE(fecha)='" . $condicion . "'";
        $query = $this->db->query($sql);

        return $query->row_array();
    }

    function get_ventas_by_cliente($condicion)
    {
        $this->db->select('SUM(subtotal) AS sub_total,SUM(total_impuesto) AS impuesto,SUM(total) AS totalizado,
         a.*,b.*,c.*,d.*,e.*,f.*');
        $this->db->from('venta a');
        $this->db->join('cliente b', 'b.id_cliente=a.id_cliente');
        $this->db->join('local c', 'c.int_local_id=a.local_id');
        $this->db->join('condiciones_pago d', 'd.id_condiciones=a.condicion_pago');
        $this->db->join('documento_venta e', 'e.id_venta=a.venta_id');
        $this->db->join('usuario f', 'f.nUsuCodigo=a.id_vendedor');
        $this->db->group_by('a.id_cliente');
        $this->db->where($condicion);
        $query = $this->db->get();

        return $query->result();
    }

    //////////////////////////////////////////////////////////////////////////////////////
    function select_rpt_venta($tipo, $fecha, $anio, $mes, $forma)
    {
        $this->db->select('nVenCodigo,
						cliente.var_cliente_nombre,
						usuario.nombre,
						dat_venta_fecregistro,
						dec_venta_montoTotal,
				
						documento_venta.documento_Numero,
						nombre_numero_documento,
						var_venta_estado,
						l.var_local_nombre,
						ct.var_constante_descripcion as cFormapago');
        $this->db->from('venta');
        $this->db->join('cliente', 'cliente.nCliCodigo=venta.nCliCodigo');
        $this->db->join('documento_venta', 'documento_venta.nTipDocumento=venta.nTipDocumento');
        $this->db->join('usuario', 'usuario.nUsuCodigo=venta.nUsuCodigo');
        $this->db->join('constante ct', 'ct.int_constante_valor=venta.int_venta_formaPago');
        $this->db->join('local l', 'l.int_local_id=venta.int_venta_local');
        $this->db->where('venta.var_venta_estado != ', 2);
        $this->db->where('ct.int_constante_clase', 3);

        switch ($tipo) {
            case 1:
                $this->db->where('venta.int_venta_formaPago', $forma);
                $this->db->where('DATE(venta.dat_venta_fecregistro)', $fecha);
                break;
            case 2:
                $this->db->where('DATE(venta.dat_venta_fecregistro)', $fecha);
                break;
            case 3:
                $this->db->where('venta.int_venta_formaPago', $forma);
                $this->db->where('YEAR(venta.dat_venta_fecregistro)', $anio);
                $this->db->where('MONTH(venta.dat_venta_fecregistro)', $mes);
                break;
            case 4:
                $this->db->where('YEAR(venta.dat_venta_fecregistro)', $anio);
                $this->db->where('MONTH(venta.dat_venta_fecregistro)', $mes);
                break;
            case 5:
                $this->db->where('venta.int_venta_formaPago', $forma);
                $this->db->where('YEAR(venta.dat_venta_fecregistro)', $anio);
                break;
            case 6:
                $this->db->where('YEAR(venta.dat_venta_fecregistro)', $anio);
                break;
        }
        $query = $this->db->get();

        return $query->result();
    }

    function select_ventas_credito()
    {
        $this->db->select('*');
        $this->db->from('venta');
        $this->db->join('credito', 'credito.id_venta=venta.venta_id');
        //TODO si quieren las ventas concretadas se debe filtrar el sttaus
        $this->db->join('documento_venta', 'documento_venta.id_venta=venta.venta_id', 'left');
        $query = $this->db->get();

        return $query->result();
    }

    function select_venta_estadocuenta()
    {
        $this->db->select('*');
        $this->db->from('venta');
        $this->db->join('credito', 'credito.id_venta=venta.venta_id');
        $this->db->join('documento_venta', 'documento_venta.id_venta=venta.venta_id', 'left');
        $query = $this->db->get();

        return $query->result();
    }

    function count_by($field, $where)
    {
        $this->db->select('count(venta_id) as count');
        $this->db->from('venta');
        $this->db->where_in($field, $where);
        $query = $this->db->get();
        return $query->row_array();
    }

    function count_ventas($where = array(''))
    {
        $this->db->select('count(venta_id) as count');
        $this->db->from('venta');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row_array();
    }

    function get_total_ventas($where = array(''))
    {
        $this->db->select('sum(total) as suma');
        $this->db->from('venta');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row_array();
    }

    function get_last($where = false)
    {
        $this->db->select('documento_venta.documento_Numero, venta.venta_id');
        $this->db->from('documento_venta');
        $this->db->join('venta', 'venta.venta_id=documento_venta.id_venta');
        $this->db->where('venta.venta_status', COMPLETADO);
        if ($where != false) {
            $this->db->where($where);
        }
        $this->db->order_by('venta.venta_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row_array();
    }

    function get_first($where = false)
    {
        $this->db->select('documento_venta.documento_Numero, venta.venta_id');
        $this->db->from('documento_venta');
        $this->db->join('venta', 'venta.venta_id=documento_venta.id_venta');
        $this->db->where('venta.venta_status', COMPLETADO);
        if ($where != false) {
            $this->db->where($where);
        }
        $this->db->order_by('venta.venta_id', 'asc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    function detalle_devolucion_venta($id_devolucion)
    {

        $this->db->select('detalle_venta_devolucion.*, producto.producto_nombre, producto.producto_codigo_interno,
         unidades.abreviatura, unidades.nombre_unidad, venta_devolucion.descuento as descuento_total,
       SUM(detalle_venta_devolucion.total) as total_total, SUM(detalle_venta_devolucion.impuesto * detalle_venta_devolucion.cantidad) as impuesto_total,  
       venta_devolucion.otros_impuestos as otro_impuesto_total,
        SUM(detalle_venta_devolucion.subtotal) as subtotal_total   ');
        $this->db->from('detalle_venta_devolucion');
        $this->db->join('venta_devolucion', 'venta_devolucion.id_devolucion=detalle_venta_devolucion.venta_devolucion_id');
        $this->db->join('unidades', 'unidades.id_unidad=detalle_venta_devolucion.id_unidad');
        $this->db->join('producto', 'producto.producto_id=detalle_venta_devolucion.id_producto');
        $this->db->group_by('detalle_venta_devolucion.id_detalle');

        $this->db->where('venta_devolucion_id', $id_devolucion);
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }


    function get_total_impuestos_grouped($venta_id)
    {
        $query = "SELECT SUM(impuesto) as impuesto ,SUM(subtotal-descuento) as amount,  detalle_venta.porcentaje_impuesto, fe_impuesto
 FROM detalle_venta 
 left join producto pd on pd.producto_id = detalle_venta.id_producto
 left join impuestos i on i.id_impuesto = pd.producto_impuesto
  where id_venta=" . $venta_id . " and impuesto>0 GROUP BY porcentaje_impuesto";
        $query = $this->db->query($query);
        return $query->result();
    }

    function get_total_otros_impuestos_grouped($venta_id)
    {
        $query = "SELECT SUM(detalle_venta.otro_impuesto) as impuesto ,detalle_venta.porcentaje_otro_impuesto as per_unit_amount,
         fe_impuesto as fe_otro_impuesto,
          SUM(subtotal-descuento) as amount,
         detalle_venta.porcentaje_otro_impuesto 
FROM detalle_venta
left join producto pd on pd.producto_id = detalle_venta.id_producto
 left join impuestos i on i.id_impuesto = pd.otro_impuesto
  where id_venta=" . $venta_id . " and detalle_venta.otro_impuesto >0 GROUP BY detalle_venta.otro_impuesto";
        $query = $this->db->query($query);
        return $query->result();
    }


    function obtener_venta($id_venta)
    {
        $querystring = "select v.venta_id, v.uuid, v.fe_prefijo, v.fe_numero, v.fe_issue_date,  v.desc_global, v.total as montoTotal,v.subtotal  as subTotal,v.cambio, sum(tr.descuento) as totaldescuento, 
 v.descuento_porcentaje, v.descuento_valor, v.cajero_id, tr.desc_porcentaje, tr.descuento, tr.desc_porcentaje,tr.total as total_detalle_venta,
 v.total_impuesto as impuesto, v.total_otros_impuestos,  v.pagado,c.direccion as clienteDireccion, c.direccion as clienteDireccionAlt,pd.producto_id, pd.producto_codigo_interno,
 pd.producto_nombre as nombre,pd.costo_unitario,  z.zona_nombre, v.excluido, v.gravado, pd.producto_tipo,
  pd.producto_id as producto_id, pd.control_inven,  tr.id_detalle,i.id_impuesto, i.tipo_calculo as tipo_impuesto, i.fe_impuesto, oi.fe_impuesto as fe_otro_impuesto,  oi.porcentaje_impuesto as porcentaje_otro_impuesto, 
  oi.tipo_calculo as tipo_otro_impuesto,  oi.id_impuesto as id_otro_impuesto, tr.otro_impuesto as otro_impuesto_detalle_venta,
 v.fecha as fechaemision, cre.dec_credito_montodeuda, cre.dec_credito_montodebito, resolucion_dian.*,
p.nombre as vendedor,p.nUsuCodigo as id_vendedor,t.nombre_tipo_documento as descripcion,  t.documento_Numero as numero, t.nombre_tipo_documento,
c.nombres as cliente, c.apellidos, c.celular, c.id_cliente as cliente_id,c.merchant_registration, c.fe_type_liability,
 c.fe_regime, c.ciudad_id, c.type_document_identification_id,c.type_organization_id,c.tax_detail_id,
 c.email as email_cliente, c.direccion as direccion_cliente,c.telefono as telefonoC1,
 c.identificacion as documento_cliente, cp.id_condiciones, cp.nombre_condiciones, v.venta_status,v.venta_tipo,
 i.porcentaje_impuesto, pd.is_paquete, pd.precio_abierto, pd.fe_type_item_identification_id, tipo_anulacion.tipo_anulacion_nombre, tr.porcentaje_impuesto as porcentaje_impuesto_backup, 
 tr.porcentaje_otro_impuesto as porcentaje_otro_impuesto_backup, 
 (select config_value from configuraciones where config_key='" . EMPRESA_NOMBRE . "') as RazonSocialEmpresa,
regimen.regimen_nombre as REGIMEN_CONTRIBUTIVO, regimen.genera_iva as regimen_iva,
 (select config_value from configuraciones where config_key='" . REPRESENTANTE_LEGAL . "') as REPRESENTANTE_LEGAL,
 (select config_value from configuraciones where config_key='" . EMPRESA_DIRECCION . "') as DireccionEmpresa,
 (select config_value from configuraciones where config_key='" . EMPRESA_TELEFONO . "') as TelefonoEmpresa, cp.dias,
 (select config_value from configuraciones where config_key='" . NIT . "') as NIT, tipo_venta.tipo_venta_nombre, tipo_venta.numero_copias,  
 tipo_venta.genera_control_domicilios,
 (select abreviatura from unidades_has_producto join unidades on unidades.id_unidad=unidades_has_producto.id_unidad
 where unidades_has_producto.producto_id=pd.producto_id order by unidades.orden desc limit 1) as unidad_minima
from venta as v
left join usuario p on p.nUsuCodigo = v.id_vendedor
left join documento_venta t on t.id_venta = v.venta_id
left join detalle_venta tr on tr.id_venta = v.venta_id
left join cliente c on c.id_cliente = v.id_cliente
left join zonas z on c.id_zona = z.zona_id
left join venta_anular on venta_anular.id_venta = v.venta_id
left join tipo_anulacion on venta_anular.tipo_anulacion = tipo_anulacion.tipo_anulacion_id
left join producto pd on pd.producto_id = tr.id_producto
left join tipo_venta  on tipo_venta.tipo_venta_id = v.venta_tipo
left join condiciones_pago cp on cp.id_condiciones = tipo_venta.condicion_pago
left join regimen  on regimen.regimen_id = v.regimen_contributivo
left join impuestos i on i.id_impuesto = pd.producto_impuesto
left join impuestos oi on oi.id_impuesto = pd.otro_impuesto
left join credito cre on cre.id_venta=v.venta_id
left join resolucion_dian on resolucion_dian.resolucion_id=t.id_resolucion

where v.venta_id=" . $id_venta . " group by tr.id_detalle order by v.venta_id desc ";

        $query = $this->db->query($querystring);
        //   echo $this->db->last_Query();

        $venta_detail = $query->result_array();

        $ventaaray = array();

        foreach ($venta_detail as $detalle) {

            if (isset($detalle['id_detalle'])) {
                $detalle['detalle_unidad'] = $this->getDetalleVentaUnidad($detalle['id_detalle']);
            }
            array_push($ventaaray, $detalle);
        }

        return $ventaaray;
    }

    function getDetalleVentaUnidad($id_detalle, $resul = "ARRAY")
    {
        $querystring = "select * from detalle_venta_unidad  
 join unidades on unidades.id_unidad = detalle_venta_unidad.unidad_id
 join detalle_venta on detalle_venta.id_detalle = detalle_venta_unidad.detalle_venta_id
 where detalle_venta_id = " . $id_detalle;
        $query = $this->db->query($querystring);
        if ($resul == 'ARRAY') {
            return $query->result_array();
        } else {
            return $query->result();
        }
    }


    function getDetalleVentaUnidadBackup($id_detalle)
    {
        $querystring = "select * from detalle_venta_unidad  
 join unidades on unidades.id_unidad = detalle_venta_unidad.unidad_id
 join detalle_venta on detalle_venta.id_detalle = detalle_venta_unidad.detalle_venta_id
 where detalle_venta_id = " . $id_detalle;
        $query = $this->db->query($querystring);
        return $query->result_array();
    }


    function get_con_devluciones()
    {

        $querystring = "select detalle_venta.* from detalle_venta_devolucion 
left join detalle_venta_unidad on detalle_venta_unidad.detalle_venta_id = detalle_venta_devolucion.id_detalle_venta
join detalle_venta on detalle_venta.id_detalle=detalle_venta_devolucion.id_detalle_venta
 where detalle_venta_unidad.detalle_venta_id is null
   
   ";

        $query = $this->db->query($querystring);
        //echo $this->db->last_query();

        return $query->result();
    }


    function get_parcheVentaBackup()
    {

        $querystring = "SELECT * FROM venta WHERE DATE(fecha)<='2018-02-18'";

        $query = $this->db->query($querystring);
        //echo $this->db->last_query();

        return $query->result();
    }

    function get_parche_comisiones_holanda()
    {

        $querystring = "SELECT comision_vendedor.*, grupos.nombre_grupo, detalle_venta.subtotal FROM comision_vendedor 
join detalle_venta on detalle_venta.id_detalle=comision_vendedor.id_detalle_venta
join producto on producto.producto_id = detalle_venta.id_producto
join grupos on producto.produto_grupo = grupos.id_grupo
  join venta on venta.venta_id=detalle_venta.id_venta  ";

        $query = $this->db->query($querystring);
        //echo $this->db->last_query();

        return $query->result_array();
    }

    function update_parche_comisiones_holanda($impuesto_nuevo, $porcentaje, $id)
    {

        $querystring = "UPDATE comision_vendedor set porcentaje= $porcentaje,comision=$impuesto_nuevo where comision_vendedor.id_comision=$id ";

        echo $querystring;
        $this->db->query($querystring);
        //echo $this->db->last_query();


    }

    function get_parcheInventario($fechaIni, $fechaFin, $kardexReference, $prod)
    {

        $querystring = "SELECT count(cKardexProducto) as contador FROM kardex WHERE  DATE(dkardexFecha)>='$fechaIni' and DATE(dkardexFecha)<='$fechaFin' 
and cKardexProducto=$prod  and ckardexReferencia IN ($kardexReference)";


        $query = $this->db->query($querystring);
        //echo $this->db->last_query();

        return $query->result();
    }


    //pal parhe
    function get_back_unidad_venta($producto_id, $id_venta)
    {

        $querystring = "select detalle_venta_unidad_backup.* from detalle_venta_unidad_backup 
join detalle_venta_backup on detalle_venta_backup.id_detalle = detalle_venta_unidad_backup.detalle_venta_id
 where detalle_venta_backup.id_producto = $producto_id
 and detalle_venta_backup.id_venta = $id_venta 
   
   ";

        $query = $this->db->query($querystring);
        //echo $this->db->last_query();

        return $query->result_array();
    }

    function get_detalle_venta_unidad($detalle_id)
    {

        $querystring = "select detalle_venta_unidad.*, unidades.abreviatura from unidades 

left join detalle_venta_unidad on unidades.id_unidad=detalle_venta_unidad.unidad_id
left join detalle_venta on detalle_venta.id_detalle = detalle_venta_unidad.detalle_venta_id
 where detalle_venta.id_detalle = $detalle_id  order by orden asc
   
   ";

        $query = $this->db->query($querystring);

        return $query->result_array();
    }

    function insert_detalle_venta_unidad($data)
    {
        $this->db->insert_batch('detalle_venta_unidad', $data);
    }

    function obtener_venta_backup($id_venta)
    {
        $querystring = "select v.venta_id, v.uuid, v.fe_numero, v.fe_issue_date,  v.desc_global, v.total as montoTotal,v.subtotal  as subTotal,v.cambio, sum(tr.descuento) as totaldescuento, 
 v.descuento_porcentaje, v.descuento_valor, v.cajero_id, tr.desc_porcentaje, tr.descuento, tr.desc_porcentaje,tr.total as total_detalle_venta,
 v.total_impuesto as impuesto, v.total_otros_impuestos,  v.pagado,c.direccion as clienteDireccion, c.direccion as clienteDireccionAlt,pd.producto_id, pd.producto_codigo_interno,
 pd.producto_nombre as nombre,pd.costo_unitario,  z.zona_nombre, v.excluido, v.gravado, pd.producto_tipo,
  pd.producto_id as producto_id, pd.control_inven,  tr.id_detalle,i.id_impuesto, i.tipo_calculo as tipo_impuesto,   oi.porcentaje_impuesto as porcentaje_otro_impuesto, 
  oi.tipo_calculo as tipo_otro_impuesto,  oi.id_impuesto as id_otro_impuesto, tr.otro_impuesto as otro_impuesto_detalle_venta,
 v.fecha as fechaemision, cre.dec_credito_montodeuda, cre.dec_credito_montodebito, resolucion_dian.*,
p.nombre as vendedor,p.nUsuCodigo as id_vendedor,t.nombre_tipo_documento as descripcion,  t.documento_Numero as numero, t.nombre_tipo_documento,
c.nombres as cliente, c.apellidos, c.celular, c.id_cliente as cliente_id,c.merchant_registration,  c.fe_type_liability, c.email as email_cliente, c.direccion as direccion_cliente,c.telefono as telefonoC1,
 c.identificacion as documento_cliente, cp.id_condiciones, cp.nombre_condiciones, v.venta_status,v.venta_tipo,
 i.porcentaje_impuesto, pd.is_paquete, pd.precio_abierto, tipo_anulacion.tipo_anulacion_nombre, tr.porcentaje_impuesto as porcentaje_impuesto_backup, 
 tr.porcentaje_otro_impuesto as porcentaje_otro_impuesto_backup, 
 (select config_value from configuraciones where config_key='" . EMPRESA_NOMBRE . "') as RazonSocialEmpresa,
regimen.regimen_nombre as REGIMEN_CONTRIBUTIVO, regimen.genera_iva as regimen_iva,
 (select config_value from configuraciones where config_key='" . REPRESENTANTE_LEGAL . "') as REPRESENTANTE_LEGAL,
 (select config_value from configuraciones where config_key='Producto.php" . EMPRESA_DIRECCION . "') as DireccionEmpresa,
 (select config_value from configuraciones where config_key='" . EMPRESA_TELEFONO . "') as TelefonoEmpresa, cp.dias,
 (select config_value from configuraciones where config_key='" . NIT . "') as NIT, tipo_venta.tipo_venta_nombre, tipo_venta.numero_copias,  
 tipo_venta.genera_control_domicilios,
 (select abreviatura from unidades_has_producto join unidades on unidades.id_unidad=unidades_has_producto.id_unidad
 where unidades_has_producto.producto_id=pd.producto_id order by unidades.orden desc limit 1) as unidad_minima
from venta_backup as v
left join usuario p on p.nUsuCodigo = v.id_vendedor
left join documento_venta t on t.id_venta = v.venta_id
left join detalle_venta tr on tr.id_venta = v.venta_id
left join cliente c on c.id_cliente = v.id_cliente
left join zonas z on c.id_zona = z.zona_id
left join venta_anular on venta_anular.id_venta = v.venta_id
left join tipo_anulacion on venta_anular.tipo_anulacion = tipo_anulacion.tipo_anulacion_id
left join producto pd on pd.producto_id = tr.id_producto
left join tipo_venta  on tipo_venta.tipo_venta_id = v.venta_tipo
left join condiciones_pago cp on cp.id_condiciones = tipo_venta.condicion_pago
left join regimen  on regimen.regimen_id = v.regimen_contributivo
left join impuestos i on i.id_impuesto = pd.producto_impuesto
left join impuestos oi on oi.id_impuesto = pd.otro_impuesto
left join credito cre on cre.id_venta=v.venta_id
left join resolucion_dian on resolucion_dian.resolucion_id=t.id_resolucion

where v.venta_id=" . $id_venta . " group by tr.id_detalle order by v.venta_id desc ";

        $query = $this->db->query($querystring);
        //   echo $this->db->last_Query();

        $venta_detail = $query->result_array();

        $ventaaray = array();

        foreach ($venta_detail as $detalle) {

            if (isset($detalle['id_detalle'])) {
                $detalle['detalle_unidad'] = $this->getDetalleVentaUnidad($detalle['id_detalle']);
            }
            array_push($ventaaray, $detalle);
        }

        return $ventaaray;
    }

    function documentoVenta($id_venta = null)
    {
        $ventaCol = "'0' as documento_id,
			t.nombre_tipo_documento as descripcion,
			
			t.documento_Numero as numero,";
        $ventaAdd = "inner join documento_venta t on t.id_venta = v.venta_id";

        $produCol = "'0' as documento_id,";
        $unidadCol = "dd.unidad_medida";
        $produAdd = "inner join detalle_venta dd on dd.id_venta = v.venta_id";


        $descripcion = $this->db->query("select
				v.venta_id,
				v.total as montoTotal,
				v.subtotal as subTotal,
				v.total_impuesto as impuesto, 
				v.pagado, 

				v.fecha as fechaemision, 
				cre.dec_credito_montodeuda,
				p.nombre as vendedor,
				cami.camiones_placa as placa,
				cami.id_trabajadores,
				p.nUsuCodigo,

				" . $ventaCol . "
				c.razon_social as cliente, 
				c.id_cliente as cliente_id, 
				c.direccion as direccion_cliente,
				c.identificacion as documento_cliente, 
				cp.id_condiciones, 
				cp.nombre_condiciones,
				v.venta_status,
				(select config_value from configuraciones where config_key='" . EMPRESA_NOMBRE . "') as RazonSocialEmpresa,
				(select config_value from configuraciones where config_key='" . EMPRESA_DIRECCION . "') as DireccionEmpresa,
				(select config_value from configuraciones where config_key='" . EMPRESA_TELEFONO . "') as TelefonoEmpresa
			from 
				venta as v
				inner join usuario p on p.nUsuCodigo = v.id_vendedor
				" . $ventaAdd . "
				inner join cliente c on c.id_cliente = v.id_cliente

				inner join condiciones_pago cp on cp.id_condiciones = v.condicion_pago
				left join credito cre on cre.id_venta = v.venta_id
				left join camiones cami on cami.id_trabajadores = p.nUsuCodigo

			where
				v.venta_id = " . $id_venta . " order by 1");

        $data = array();
        $ventas = $descripcion->result_array();

        $data['ventas'] = array();

        foreach ($ventas as $venta) {
            $productos = $this->db->query("select
				v.venta_id,
				" . $produCol . "
				pd.producto_nombre as nombre,

			

				pd.producto_id as producto_id,
				dv.precio as precioV,
				dv.id_producto as productoDV,
				dd.cantidad as cantidad,
				dd.id_producto as ddproductoID,
				dd.precio as preciounitario,
			
				    dv.bono,
				u.id_unidad, 
				u.nombre_unidad, 
				u.abreviatura,
				i.porcentaje_impuesto, 
				up.unidades, 
				up.orden,
				(select abreviatura from unidades_has_producto join unidades on unidades.id_unidad = unidades_has_producto.id_unidad
			where 
				ddproductoID = pd.producto_id order by orden desc limit 1) as unidad_minima


			from
				venta as v
				" . $produAdd . "
				inner join producto pd on pd.producto_id = dd.id_producto
				inner join detalle_venta dv on dv.id_producto = pd.producto_id
				inner join unidades u on u.id_unidad = " . $unidadCol . "
				inner join impuestos i on i.id_impuesto = pd.producto_impuesto
				inner join unidades_has_producto up on up.producto_id = pd.producto_id and up.id_unidad = " . $unidadCol . "
			where
				v.venta_id = " . $id_venta . " group by documento_detalle_id  order by 1,productoDV desc ");

            $productos = $productos->result_array();
            //echo $this->db->last_query();
            //  var_dump($productos);

            foreach ($productos as $p) {
                $data['productos'][] = $p;
            }

            $venta['productos'] = $productos;
            $data['ventas'][] = $venta;
        }

        //var_dump( $data['productos']);
        return $data;
    }

    function get_formas_pago_by($where)
    {

        $this->db->select('*');
        $this->db->from('venta_forma_pago');
        $this->db->where($where);
        //  $this->db->group_by('id_venta', 'id_forma_pago');
        $query = $this->db->get();

        return $query->result();
    }

    function update_status($id_venta, $estatus, $vendedor_id)
    {
        $this->db->trans_start();

        $data = array('venta_status' => $estatus);
        //CAMBIO DE ESTATUS EN EL DE LA VENTA
        $this->db->where('venta_id', $id_venta);
        $this->db->update('venta', $data);
        //INSERCION PARA LLEVAR REGISTRO DE CAMBIO DE ESTATUS

        $dataregistro = array(
            'venta_id' => $id_venta, 'vendedor_id' => $vendedor_id,
            'estatus' => $estatus, 'fecha' => date('Y-m-d h:m:s')
        );
        $this->db->insert('venta_estatus', $dataregistro);

        $this->db->trans_complete();
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    function ventas_contado($where)
    {
        $w = "DATE(fecha) >= DATE('" . $where['fecha'] . "') AND DATE(fecha) <= DATE('" . $where['fecha'] . "')";
        $this->db->select('SUM(pagado) as totalV');
        $this->db->where($w);
        $this->db->where('condiciones_pago.dias', 0);
        $this->db->where_in('venta.venta_status', array(COMPLETADO));
        $this->db->from('venta');
        $this->db->join('condiciones_pago', 'condiciones_pago.id_condiciones=venta.condicion_pago');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row_array();
    }

    function cobrosadelantados_caja($where)
    {
        $w = "DATE(venta.confirmacion_fecha) >= DATE('" . $where['fecha'] . "') AND DATE(venta.confirmacion_fecha) <= DATE('" . $where['fecha'] . "') AND venta.confirmacion_caja IS NOT NULL AND pagado > 0.00
         AND venta_tipo='ENTREGA' ";
        $this->db->select('SUM(pagado) as adelantoCaja');
        $this->db->where($w);

        //$this->db->where_in('venta.venta_status', array(PEDIDO_DEVUELTO, PEDIDO_ENTREGADO, COMPLETADO, PEDIDO_GENERADO));

        $this->db->from('venta');

        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row_array();
    }

    function cobrosadelantados_banco($where)
    {
        $w = "DATE(venta.confirmacion_fecha) >= DATE('" . $where['fecha'] . "') AND DATE(venta.confirmacion_fecha) <= DATE('" . $where['fecha'] . "') AND venta.confirmacion_banco IS NOT NULL AND pagado > 0.00
        AND venta_tipo='ENTREGA' ";
        $this->db->select('SUM(pagado) as adelantoBanco');
        $this->db->where($w);

        //   $this->db->where_in('venta.venta_status', array(PEDIDO_DEVUELTO, PEDIDO_ENTREGADO, COMPLETADO, PEDIDO_GENERADO));

        $this->db->from('venta');

        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row_array();
    }

    function cuadre_caja_cobros_caja($where)
    {

        /* $w = array('DATE(liquidacion_fecha) >=' => $where['fecha'],
             'DATE(liquidacion_fecha) <=' => $where['fecha'],
             'historial_estatus' => 'CONFIRMADO'
         );
         $this->db->select('liquidacion_cobranza.liquidacion_id as liq,historial_pagos_clientes.*,liquidacion_cobranza_detalle.*'); //SUM(historial_monto) as suma

         $this->db->from('liquidacion_cobranza_detalle');
         $this->db->join('historial_pagos_clientes', 'liquidacion_cobranza_detalle.pago_id=historial_pagos_clientes.historial_id');
         $this->db->join('liquidacion_cobranza', 'liquidacion_cobranza.liquidacion_id=liquidacion_cobranza_detalle.liquidacion_id');
         $this->db->where('historial_caja_id IS NOT NULL');
         $this->db->where($w);
         $this->db->order_by('liquidacion_cobranza_detalle.liquidacion_id', 'desc');
         $query = $this->db->get();

         return $query->result_array();*/


        $w = array(
            'DATE(liquidacion_fecha) >=' => $where['fecha'],
            'DATE(liquidacion_fecha) <=' => $where['fecha'],
            'historial_estatus' => 'CONFIRMADO'
        );
        $this->db->select('*,SUM(historial_monto) as suma');
        $this->db->where('historial_caja_id IS NOT NULL');
        $this->db->where($w);
        $this->db->from('historial_pagos_clientes');
        $this->db->join('liquidacion_cobranza_detalle', 'liquidacion_cobranza_detalle.pago_id=historial_id');
        $this->db->join('liquidacion_cobranza', 'liquidacion_cobranza.liquidacion_id=liquidacion_cobranza_detalle.liquidacion_id');
        $query = $this->db->get();

        //  echo $this->db->last_query();
        return $query->row_array();
    }

    function cuadre_caja_cobros_banco($where)
    {
        $w = array(
            'DATE(liquidacion_fecha) >=' => $where['fecha'],
            'DATE(liquidacion_fecha) <=' => $where['fecha'],
            'historial_estatus' => 'CONFIRMADO'
        );
        $this->db->select('*,SUM(historial_monto) as duedaBanco');
        $this->db->where($w);
        $this->db->where('historial_banco_id IS NOT NULL');
        $this->db->from('historial_pagos_clientes');
        $this->db->join('liquidacion_cobranza_detalle', 'liquidacion_cobranza_detalle.pago_id=historial_id');
        $this->db->join('liquidacion_cobranza', 'liquidacion_cobranza.liquidacion_id=liquidacion_cobranza_detalle.liquidacion_id');
        $query = $this->db->get();

        return $query->row_array();
    }

    function pagos_adelantados($where)
    {
        $this->db->select('venta.*, banco.banco_nombre, cliente.razon_social, usuario.nombre as nombreVendedor,cliente.id_cliente as cod_cliente, consolidado_carga.status');
        $this->db->from('venta');
        $this->db->join('usuario', 'usuario.nUsuCodigo = venta.id_vendedor');
        $this->db->join('cliente', 'cliente.id_cliente  = venta.id_cliente');


        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    function pagos_caja_banco($where)
    {
        $this->db->select('venta.*, usuario.nombre as nombreVendedor,cliente.id_cliente as cod_cliente');
        $this->db->from('venta');
        $this->db->join('usuario', 'usuario.nUsuCodigo = venta.id_vendedor');
        $this->db->join('cliente', 'cliente.id_cliente  = venta.id_cliente');
        $this->db->where('venta_id', $where);
        $query = $this->db->get();
        return $query->result_array();
    }

    function pagos_adelantados_filtro($where)
    {
        /* if($where == 1){
             $where = 'confirmacion_usuario IS NOT NULL';
         }elseif($where == 2){
             $where = 'confirmacion_usuario IS NULL';
         }*/

        $this->db->select('*, usuario.nombre as nombreVendedor,cliente.id_cliente as cod_cliente');
        $this->db->from('venta');
        $this->db->join('usuario', 'usuario.nUsuCodigo = venta.id_vendedor');
        $this->db->join('cliente', 'cliente.id_cliente  = venta.id_cliente');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    function pagos_caja_cobrado($datos, $id, $monto)
    {
        $this->db->where('venta_id', $id);
        $this->db->update('venta', $datos);
        $where = array('id_venta' => $id);
        $queryCredito = $this->getCredito($where);
        $credito['var_credito_estado'] = CREDITO_ACUENTA;
        if (($queryCredito['dec_credito_montodebito'] + $monto) >= $queryCredito['dec_credito_montodeuda']) {

            $credito['var_credito_estado'] = CREDITO_CANCELADO;
            $credito['dec_credito_montodebito'] = $queryCredito['dec_credito_montodebito'] + $monto;
        }


        $credito['dec_credito_montodebito'] = $monto;

        $condition = array('id_venta' => $id);
        return $this->actualizarCredito($credito, $condition);
    }

    function pagos_banco_cobrado($datos, $id, $monto)
    {
        $this->db->where('venta_id', $id);
        $result1 = $this->db->update('venta', $datos);

        $credito['dec_credito_montodebito'] = $monto;
        $credito['var_credito_estado'] = CREDITO_ACUENTA;

        $where = array('id_venta' => $id);

        $queryCredito = $this->getCredito($where);
        if (sizeof($queryCredito)) {

            if (($queryCredito['dec_credito_montodebito'] + $monto) >= $queryCredito['dec_credito_montodeuda']) {

                $credito['var_credito_estado'] = CREDITO_CANCELADO;
                $credito['dec_credito_montodebito'] = $queryCredito['dec_credito_montodebito'] + $monto;
            }


            $condition = array('id_venta' => $id);
            return $this->actualizarCredito($credito, $condition);
        } else {
            return $result1;
        }
    }


    function calcularUtilidad($precio, $cantidad_venta, $costo)
    {

        $utilidad = ($precio - $costo) * $cantidad_venta;

        return $utilidad;
    }

    function get_venta_backup($venta_id)
    {
        $sql_venta = $this->db->query("SELECT * FROM venta_backup where venta_id='$venta_id' ");
        return $sql_venta->result_array();
    }

    function get_detalle_venta_backup($venta_id, $id_producto = false)
    {

        $query = "SELECT * FROM detalle_venta_backup where id_venta='$venta_id' ";

        if ($id_producto != false) {
            $query .= " and detalle_venta_backup.id_producto='$id_producto'";
        }
        $sql_detalle = $this->db->query($query);
        return $sql_detalle->result_array();
    }

    function get_detalle_venta_unidad_backup($detalle_venta_id)
    {
        $sql_detalle = $this->db->query("SELECT * FROM detalle_venta_unidad_backup where detalle_venta_id='$detalle_venta_id' ");
        return $sql_detalle->result_array();
    }

    function ultimoPrecioProducto($producto, $unidad)
    {
        $this->db->select('*');
        $this->db->from('detalle_venta_unidad');
        $this->db->join('detalle_venta', 'detalle_venta.id_detalle=detalle_venta_unidad.detalle_venta_id');
        $this->db->join('venta', 'venta.venta_id=detalle_venta.id_venta');
        $this->db->where('detalle_venta.id_producto', $producto);
        $this->db->where('venta.fecha = (SELECT MAX(venta.fecha) FROM venta JOIN detalle_venta ON detalle_venta.id_venta=venta.venta_id
         JOIN detalle_venta_unidad ON detalle_venta.`id_detalle`=detalle_venta_unidad.`detalle_venta_id` WHERE detalle_venta.id_producto=' . $producto . ' and detalle_venta_unidad.unidad_id=' . $unidad . ')');
        $this->db->where('detalle_venta_unidad.unidad_id', $unidad);

        $query = $this->db->get();
        return $query->result();
    }


    function updateVentaBackupRow($venta)
    {
        $this->db->update('venta_backup', $venta, array('venta_id' => $venta->venta_id));
    }

    /*trae los datos de la tabla venta_forma_pago */
    function getFormaPago($where = array(''))
    {
        $this->db->select('venta_forma_pago.*,venta.total, metodos_pago.nombre_metodo');
        $this->db->from('venta');
        $this->db->join('venta_forma_pago', 'venta_forma_pago.id_venta=venta.venta_id', 'left');
        $this->db->join('metodos_pago', 'metodos_pago.id_metodo=venta_forma_pago.id_forma_pago', 'left');
        $this->db->where($where);
        $this->db->group_by('venta_id,id_forma_pago');
        $query = $this->db->get();

        //   echo $this->db->last_query();
        return $query->result();
    }
}
