<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ingreso_model extends CI_Model
{


	function __construct()
	{
		parent::__construct();
		$this->load->model('kardex/kardex_model');
		$this->load->model('inventario/inventario_model');
		$this->load->model('system_logs/systemLogsModel');
	}

	function getSoloIngreso($where)
	{
		$this->db->select('*');
		$this->db->from('ingreso');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row_array();
	}

	function armarTablaIngreso($post)
	{

		$status = INGRESO_COMPLETADO;
		if ($post['status'] == INGRESO_PENDIENTE) {
			$status = INGRESO_PENDIENTE;
		}
		$proveedor = $post['cboProveedor'];
		if ($post['cboProveedor'] == "") {
			$proveedor = NULL;
		}

		$condicion_pago = $post['condicion_pago'];
		if ($post['condicion_pago'] == "") {
			$condicion_pago = NULL;
		}
		$compra = array(
			'int_Proveedor_id' => $proveedor,
			'nUsuCodigo' => $this->session->userdata('nUsuCodigo'),
			'fecha_emision' => isset($post['fecEmision']) && $post['fecEmision'] != null && $post['fecEmision'] != "" ? date("Y-m-d", strtotime($post['fecEmision']))  : date("Y-m-d H:i:s"),
			'local_id' => $this->session->userdata('id_local'),
			'documento_numero' => $post['doc_numero'],
			'ingreso_status' => $status,
			'impuesto_ingreso' => isset($post['total_iva']) ? $post['total_iva'] : 0, //total de la suma d elos iva
			'sub_total_ingreso' => isset($post['total_costo']) ? $post['total_costo'] : 0, //total sin el total del iva
			'total_ingreso' => isset($post['total_facturado']) ? $post['total_facturado'] : 0, // total de los costos mas el totl de los iva
			'condicion_pago' => $condicion_pago,
			'total_bonificado' => isset($post['total_bonificado']) ? $post['total_bonificado'] : 0,
			'total_descuento' => isset($post['total_descuento']) ? $post['total_descuento'] : 0,
			'tipo_carga' => isset($post['tipo_carga']) ? $post['tipo_carga'] : "",
			'last_update'=> date("Y-m-d H:i:s")
		);

		if ($post['actualizar'] == false) {
			$compra['fecha_registro'] = date("Y-m-d H:i:s");
		}
		return $compra;

	}

	function cargarModelosCompra()
	{
		$this->load->model('producto/producto_model');
		$this->load->model('unidades/unidades_model');
		$this->load->model('impuesto/impuestos_model');
		$this->load->model('detalle_ingreso_unidad/detalle_ingreso_unidad_model');
		$this->load->model('condicionespago/condiciones_pago_model');
		$this->load->model('unidades_has_precio/unidades_has_precio_model');

		$this->load->model('producto_barra/producto_barra_model');
		$this->load->model('detalleingreso_especial/detalleingreso_especial_model');

	}


	function insertar_compra($post)
	{
		$this->db->trans_begin();
		$post['actualizar'] = false;
		$compra = $this->armarTablaIngreso($post);

		$this->db->insert('ingreso', $compra);
		$insert_id = $this->db->insert_id();
		$post['id_ingreso'] = $insert_id;
		$post['fecha_registro'] = $compra['fecha_registro'];
		return $this->procesarCompra($post);

	}


	function update_compra($post)
	{
		$this->db->trans_begin();

		$where = array(
			'id_ingreso' => $post['id_ingreso']
		);
		$statusViejo = $this->getSoloIngreso($where);
		$post['statusViejo'] = $statusViejo['ingreso_status'];
		$post['actualizar'] = true;
		$compra = $this->armarTablaIngreso($post);
		$this->db->where('id_ingreso', $post['id_ingreso']);
		$this->db->update('ingreso', $compra);

		return $this->procesarCompra($post);


	}

	function guardarProducto($post, $row, $cont)
	{


		$impuesto_id = null;
		//busco el impuesto ya que con coopidrogas solo viene el % entonces si no existe creo un impuesto nuevo

		$buscaimpuesto = $this->impuestos_model->get_by('porcentaje_impuesto', $post['iva_'][$cont]);

		if ($post['iva_'][$cont] != "" && $post['iva_'][$cont] != null && $post['iva_'][$cont] > 0) {
			if (count($buscaimpuesto) < 1) {
				$impuesto = array(
					'nombre_impuesto' => "Impuesto " . $post['iva_'][$cont] . " %",
					'porcentaje_impuesto' => $post['iva_'][$cont],
					'estatus_impuesto' => 1
				);
				$impuesto_id = $this->impuestos_model->crear_impuesto($impuesto);
			} else {
				$impuesto_id = $buscaimpuesto['id_impuesto'];
			}
		}

		//agregar el campo a la tabla fecha de venc
		$datos_producto = array(
			'producto_bonificaciones' => isset($post['bonificacion_'][$cont]) && $post['bonificacion_'][$cont] != "" && $post['bonificacion_'][$cont] != 0 ? $post['bonificacion_'][$cont] : null,
			'producto_tipo' => isset($post['tipoprod_'][$cont]) && $post['tipoprod_'][$cont] != "" && $post['tipoprod_'][$cont] != 0 ? $post['tipoprod_'][$cont] : null,
			'producto_ubicacion_fisica' => isset($post['ubicacion_'][$cont]) && $post['ubicacion_'][$cont] != "" && $post['ubicacion_'][$cont] != 0 ? $post['ubicacion_'][$cont] : null,
			'produto_grupo' => isset($post['grupo_'][$cont]) && $post['grupo_'][$cont] != "" && $post['grupo_'][$cont] != 0 ? $post['grupo_'][$cont] : null,
			'producto_impuesto' => $impuesto_id,
			'producto_codigo_interno' => strtoupper($row['producto_codigo_interno']),
			'producto_nombre' => urldecode(strtoupper($row['producto_nombre'])),
			'producto_estatus' => 1,
			'producto_activo' => 1,
		);

		//si $post['actualizar'] ==false es que se esta insertando una compra nueva
		if (isset($post['actualizar']) && $post['actualizar'] == false) {
			$datos_producto['ultima_fecha_compra'] = date("Y-m-d H:i:s");
		}

		if (isset($post['descuento_'][$cont]) and $post['descuento_'][$cont] != "") {
			$datos_producto['porcentaje_descuento'] = $post['descuento_'][$cont];
		}

		if (isset($post['bonificacion_'][$cont]) and $post['bonificacion_'][$cont] != "") {
			$datos_producto['producto_bonificaciones'] = $post['bonificacion_'][$cont];
		}

		if (isset($post['costo_unitario_'][$cont]) and $post['costo_unitario_'][$cont] != "") {
			//estamos en coopidrogas
			$datos_producto['costo_unitario'] = $post['costo_unitario_'][$cont];

		}

		if (isset($post['precio_corriente_'][$cont]) and $post['precio_corriente_'][$cont] != "") {
			$datos_producto['precio_corriente'] = $post['precio_corriente_'][$cont];
		}

		//guardo o actualizo los datos del producto
		if ($row['producto_id'] == "") {

			$datos_producto['control_inven'] = 1;
			return $id_producto = $this->producto_model->solo_insertar($datos_producto);

		} else {

			$actualizar = $this->producto_model->solo_update(array('producto_id' => $row['producto_id']), $datos_producto);
			if ($actualizar == true) {
				return "actualizado";
			} else {
				return $actualizar;
			}
		}
	}

	function procesarCompra($post)
	{

	    try {
            $this->cargarModelosCompra();

            $cont = 0;
            $condiciones = $this->condiciones_pago_model->get_all();

            $datos_condicion = $this->condiciones_pago_model->get_by('id_condiciones', $post['condicion_pago']);

            $post['nombre_condiciones'] = $datos_condicion['nombre_condiciones'];


            //var_dump($post['lst_producto']);
            /*esto lo que hace es convertirlo de objeto a arreglo, ya que con objeto, a veces  arroja error que no se puede hacer foreach
            */

            $lst_producto = preg_replace('/[[:cntrl:]]/', '', $post['lst_producto']);
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

            foreach ($lst_producto as $row) {


                $producto_before = array();
                $producto_after = array();

                $producto_before = $this->producto_model->buscar_id($row['producto_id']);

                $where = array('producto_id' => $row['producto_id']);
                $producto_before['contenido_interno'] = $this->unidades_model->solo_unidades_xprod($where);
                $producto_before['codigo_barra'] = $this->producto_barra_model->get_codigo_barra($where);

                $where = array('id_producto' => $row['producto_id']);
                $producto_before['precios'] = $this->unidades_has_precio_model->get_all_where($where);

                $where = array('paquete_id' => $row['producto_id']);
                $producto_before['paquete'] = $this->paquete_has_prod_model->get_where($where);

                if (!isset($post['iva_'][$cont])) {
                    while (!isset($post['iva_'][$cont])) {
                        $cont++;
                    }
                }
                //guardo el producto o lo actualizo
                $guardar = $this->guardarProducto($post, $row, $cont);

                if ($guardar != false and $guardar != "actualizado") {
                    $row['producto_id'] = $guardar;
                }
                //si existe $row['codigosBarra, quiere decir que trae codigos de barra de la compra

                if (isset($row['codigosBarra'])) {

                    //para no guardar los codigos de barra de los obsequios
                    if (!isset($row['is_obsequio']) || (isset($row['is_obsequio']) && count($row['is_obsequio']) < 1)) {

                        $where = array(
                            'producto_id' => $row['producto_id']
                        );

                        $this->producto_barra_model->delete($where);

                        //guardo todos los codigos de barra, hay una validacion de si existe no se guarda
                        foreach ($row['codigosBarra'] as $barra) {

                            $this->producto_barra_model->guardarEnCompra($row['producto_id'], $barra);
                        }
                    }
                }

                $id_detalleingreso = false;

                $cont_unidad = 0;

                //elimino los precios
                $where = array(
                    'id_producto' => $row['producto_id']
                );
                //guardo y elimino los precios
                $preciosActuales = $this->unidades_has_precio_model->get_all_where($where);
                $this->unidades_has_precio_model->delete($where);


                $where = array(
                    'producto_id' => $row['producto_id']
                );
                $unidadesviejas = $this->unidades_model->solo_unidades_xprod($where);

                $guardoDetalle = false;
                $total_con_descuento = 0; //la suma del total de costos con descuento, sin el iva

                $datosProducto = $this->producto_model->get_by('producto_id', $row['producto_id']);

                //elimino las unidades solo una vez en unidades_has_producto para insertar
                $where = array(
                    'producto_id' => $row['producto_id']
                );
                $this->unidades_model->delete_unidades_producto($where);


                /*inserto las unidades del producto de una vez*/
                foreach ($row['unidades'] as $unidad) {

                    $unidad_id = $unidad['id'];
                    $cantidad = $unidad['cantidad'];
                    $costo = null;
                    //esto es para guardar el stock minimo y maxino que tenian
                    $stockminimoviejo = NULL;
                    $stockmaximoviejo = NULL;
                    foreach ($unidadesviejas as $unidad_vieja) {
                        if ($unidad_vieja['id_unidad'] == $row['contenido_interno'][$unidad_id]) {
                            $stockminimoviejo = $unidad_vieja['stock_minimo'];
                            $stockmaximoviejo = $unidad_vieja['stock_maximo'];
                            $costo = $unidad_vieja['costo'];
                        }
                    }
                    if (isset($unidad['cantidad']) && $cantidad != false && $cantidad != "" && $cantidad != '0') {
                        $costo = $unidad['costo'];
                        //guardo el contenido interno mientras no venga= ""
                        if (isset($row['contenido_interno']) && $row['contenido_interno'][$unidad_id] != '') {

                            $datos = array(
                                'id_unidad' => $unidad_id,
                                'producto_id' => $row['producto_id'],
                                'unidades' => $row['contenido_interno'][$unidad_id],
                                'costo' => $costo > 0 ? $costo / $cantidad : 0, //el nuevo costo de la unidad
                                'stock_minimo' => $stockminimoviejo,
                                'stock_maximo' => $stockmaximoviejo
                            );
                            $this->unidades_model->insert_unidades_producto($datos);
                        }
                    } else {
                        //aqui solo entra cuando la cantidad viene en 0 o vacia
                        /* en el caso de coopidrogas, solo viene cantidad para caja, es decir, entra en la primera condicion
                        pero para las demas unidades viene vacio o en 0; e igual si tiene contenido interno, lo guardo. */
                        if (isset($row['contenido_interno']) && $row['contenido_interno'][$unidad_id] != '' && (!isset($row['is_prepack']) || count($row['is_prepack']) < 1)) {
                            $datos = array(
                                'id_unidad' => $unidad_id,
                                'producto_id' => $row['producto_id'],
                                'unidades' => $row['contenido_interno'][$unidad_id],
                                'costo' => $costo,
                                'stock_minimo' => $stockminimoviejo,
                                'stock_maximo' => $stockmaximoviejo
                            );
                            $this->unidades_model->insert_unidades_producto($datos);
                        }
                    }
                }

                //recorro todas las unidades
                foreach ($row['unidades'] as $unidad) {

                    $unidad_id = $unidad['id'];
                    $cantidad = $unidad['cantidad'];
                    $costo = $unidad['costo'];

                    $buscarDetalleUnidad = array();

                    //para no ingresar las que sea igual a 0 o vengas vacias
                    if (isset($unidad['cantidad']) && $cantidad != false && $cantidad != "" && $cantidad != '0') {
                        $is_prepack = 0;
                        $is_obsequio = 0;

                        $buscarProdEnDetalle = array();

                        if ($guardoDetalle == false) {

                            //esto es para que guarde una sola vez en detalleingreso

                            $where = array(
                                'id_ingreso' => $post['id_ingreso'],
                                'id_producto' => $row['producto_id'],
                                'status' => 0
                            );
                            //busco si ya guarde este producto en la compra
                            $buscarProdEnDetalle = $this->detalle_ingreso_model->getSoloDetalle($where);

                            $status = 0;
                            $tipo = "INSERTAR";

                            /*el producto al final de todo esto siempre va a quedar en status 0*/
                            /*cuando se actualiza el producto en la edicion de la compra, o
                            cuando se esta editando la compra pero se esta insrtando un nuevo producto que no estaba en la compra,
                            el status es 1.*/
                            /*de resto, si es una compra nueva, todos los productos se guardan en estatus 0*/

                            if (count($buscarProdEnDetalle) > 0) {
                                //encontro 1 solo registro
                                $status = 1;
                                $tipo = "ACTUALIZAR";
                                $id_detalleingreso = $buscarProdEnDetalle[0]['id_detalle_ingreso'];
                            } else {
                                //aqui entra en el update de la compra
                                //si no consiguio el producto en detalle, quiere decir que edite la compra
                                // y agregue un producto a la compra
                                $status = 1;
                                $tipo = "INSERTAR";
                            }


                            $guardar = $this->guardarDetalleCompra(
                                $post['id_ingreso'],
                                $row['producto_id'],
                                number_format($row['total_producto'], 2, '.', ''),
                                isset($post['bonificacion_'][$cont]) ? $post['bonificacion_'][$cont] : 0,
                                isset($post['descuento_'][$cont]) ? $post['descuento_'][$cont] : 0,
                                $post['iva_'][$cont], $post['total_iva_'][$cont], $status, $tipo, $id_detalleingreso);


                            //si es ==false quiere decir que es un nuevo producto en esta compra
                            if ($id_detalleingreso == false) {

                                $id_detalleingreso = $guardar;
                            }


                            $guardoDetalle = true;

                        }  ///end $guardoDetalle


                        if (isset($row['is_prepack'])) {

                            if (count($row['is_prepack']) > 0) {

                                $is_prepack = 1;
                            }
                            //si es prepack y tiene sus productos que lo componen
                            $this->detallePrepack($row, $post, $id_detalleingreso);

                            $cuantoCosto = $row['total_producto'] / $cantidad;

                        } elseif (isset($row['is_obsequio'])) {


                            $cuantoCosto = 0;
                            //pregunto si trae productos asociados
                            if (count($row['is_obsequio']) > 0) {
                                $is_obsequio = 1;
                                $this->detalleObsequio($row, $post, $id_detalleingreso);
                            }

                        } else {
                            //si no es prepack y tampoco obsequio
                            $cuantoCosto = 0;
                            if ($costo != '0') {
                                $cuantoCosto = $costo / $cantidad;
                            }
                        }

                        $wheredetalleunidad = array(
                            'detalle_ingreso_id' => $id_detalleingreso,
                            'unidad_id' => $unidad_id,
                            'actualizado' => 0
                        );
                        $buscarDetalleUnidad = $this->detalle_ingreso_unidad_model->getDetalleUnidad($wheredetalleunidad);


                        $status = 0;
                        $tipo = "INSERTAR";


                        if (count($buscarDetalleUnidad) > 0) {
                            $status = 1;
                            $tipo = "ACTUALIZAR";

                        } elseif (isset($post['actualizar']) and $post['actualizar'] == true) {
                            $status = 1;
                            $tipo = "INSERTAR";
                        }


                        $cantidadsinobsequio = $cantidad;

                        //esto se hace para preguntar si a este producto le fue asociada una cantidad como obsequio
                        //para que el calculo del costo unitario sea en base a esta suma de la cantidad que viene
                        //y la que le fue obsequiada
                        if (isset($unidad['cantidad_obsequiada']) && $unidad['cantidad_obsequiada'] != 0) {
                            $cantidad = $cantidadsinobsequio + $unidad['cantidad_obsequiada'];
                            //esta $cantidad es para calcular el costo unitario en caso de que al producto le hayan
                            // obsequiado otro producto
                        }

                        //guardo el contenido interno mientras no venga= ""
                        if (isset($row['contenido_interno']) && $row['contenido_interno'][$unidad_id] != '') {

                            //lo hago por cada unidad, ya que por cada unidad, actualizo el costo unitario del producto.
                            //lo hago antes de actualizar el costo unitario, paramandar el costo unitario antes de actualizar
                            $productoactual = $this->producto_model->get_by('producto_id', $row['producto_id']);

                            /*calculo el costo unitario DEL PRODUCTO*/
                            $costo_unitario = $this->unidades_model->costo_unitario($row['producto_id'], $unidad_id,
                                $costo > 0 ? $costo / $cantidad : 0);

                            $costo_con_descuento = $cuantoCosto;
                            /*si el producto tenia algun % de descuento, se lo calculo, para que afecte el costo unitario*/
                            if ($post['descuento_'][$cont] != "" && $post['descuento_'][$cont] > 0) {
                                if ($cuantoCosto != false) {
                                    $costo_unitario = $costo_unitario - (($post['descuento_'][$cont] * $costo_unitario) / 100);

                                    /*calculo el costo de esta unidad con el descuento*/
                                    $costo_con_descuento = $cuantoCosto - (($post['descuento_'][$cont] * $cuantoCosto) / 100);

                                    $total_con_descuento = $total_con_descuento + ($costo_con_descuento * $cantidad);
                                }
                            } else {
                                $total_con_descuento = $total_con_descuento + $costo;
                            }

                            /******************************/
                            if ($costo_unitario != false) {
                                $this->producto_model->solo_update(array('producto_id' => $row['producto_id']),
                                    array('costo_unitario' => number_format($costo_unitario, 2, '.', '')));
                            }

                            //busco cuanto costaba esta unidad antes, envio el $datosProducto['costo_unitario'] que era el costo unitario
                            //antes de actualizarlo por cada unidad, para que tome los costos viejos en base a ese costo unitario
                            $costabaestaunidad = $this->producto_model->calcularCostoUnidad($unidad_id, $datosProducto['costo_unitario'], $unidadesviejas);


                            /*el costo_total(sin iva ni descuento) + el impuesto*/
                            $cuantodeimpuestoporunidad = $post['total_iva_'][$cont] != "" && $post['total_iva_'][$cont] > 0 ?
                                $this->calcular_iva($costo, $post['iva_'][$cont], $post['descuento_'][$cont]) : 0.00;


                            /*calculo el total final del costo de esta unidad, con iva y con descuento si es que tuvo*/
                            $totalfinalunidad = $this->calcular_total_final($costo, $post['iva_'][$cont], $post['descuento_'][$cont]);

                            $this->saveDetalleUnidad($unidad_id, $cantidadsinobsequio,
                                number_format($cuantoCosto, 2, '.', ''), $id_detalleingreso,
                                number_format($costo, 2, '.', ''),
                                number_format($cuantodeimpuestoporunidad, 2, '.', ''),
                                $post['id_ingreso'], $status, $tipo,
                                number_format($costabaestaunidad, 2, '.', ''),
                                number_format($productoactual['costo_unitario'], 2, '.', ''),
                                number_format($costo_unitario, 2, '.', ''),
                                number_format($costo_con_descuento, 2, '.', ''),
                                number_format($totalfinalunidad, 2, '.', ''));


                            foreach ($condiciones as $condicion) {

                                if (isset($row['precios']) && count($row['precios']) > 0) {


                                    $id_condicion = $condicion['id_condiciones'];

                                    if (isset($row['precios'][$unidad_id][$id_condicion]['precio']) && $row['precios'][$unidad_id][$id_condicion]['precio'] != "") {

                                        $precio_minimo = false;
                                        $precio_maximo = false;
                                        foreach ($preciosActuales as $precioEstaunidad) {
                                            if ($precioEstaunidad['id_condiciones_pago'] == $id_condicion &&
                                                $precioEstaunidad['id_unidad'] == $unidad_id
                                            ) {

                                                $precio_maximo = $precioEstaunidad['precio_maximo'];
                                                $precio_minimo = $precioEstaunidad['precio_minimo'];
                                            }
                                        }

                                        $unidad_has_precio = array(
                                            "id_condiciones_pago" => $id_condicion,
                                            "id_unidad" => $unidad_id,
                                            "id_producto" => $row['producto_id'],
                                            "precio" => $row['precios'][$unidad_id][$id_condicion]['precio'],
                                            "utilidad" => $row['precios'][$unidad_id][$id_condicion]['utilidad']
                                        );

                                        if ($precio_minimo != false) {
                                            $unidad_has_precio['precio_minimo'] = $precio_minimo;
                                        }
                                        if ($precio_maximo != false) {
                                            $unidad_has_precio['precio_maximo'] = $precio_maximo;
                                        }

                                        $this->unidades_has_precio_model->insert($unidad_has_precio);
                                    }
                                }

                            }

                            $ultimasuma = $productoactual['suma_costo_caja'] != "" && $productoactual['suma_costo_caja'] != null ? $productoactual['suma_costo_caja'] : 0;
                            $ultimacantidadcaja = $productoactual['cantidad_caja'] != "" && $productoactual['cantidad_caja'] != null ? $productoactual['cantidad_caja'] : 0;

                            /*para setear el inventario y el kardex*/
                            if (count($buscarDetalleUnidad) > 0) {

                                //si entra aqui quiere decir que estoy editando la compra

                                //$post['status'] viene de ingreso.js al guardar la compra, ya que puede ser
                                //INGRESO_COMPLETADO o INGRESO_PENDIENTE
                                if ($post['status'] == INGRESO_COMPLETADO) {

                                    $cantidadDetalle = $buscarDetalleUnidad[0]->cantidad;
                                    //$cantidadDetalle es la cantidad vieja, unidad->cantidad es la nueva
                                    //pregunto si la cantidad que estoy ingresando nueva es menor que la que ya estaba guardada para pasar
                                    //la condicion,   O si el estatus viejo de la compra, antes de que se actualizara arriba, era
                                    //INGRESO_PENDIENTE, ya que en este caso siempre voy a sumar la cantidad que venga al stock

                                    $this->costo_promedio("RESTAR", $ultimasuma, $ultimacantidadcaja, $costo_unitario,
                                        $buscarDetalleUnidad[0]->costo_unitario_despues, $row['producto_id']);


                                    //la condicion de abajo pregunta si la cantidad del detalle viejo es vacia y distinta de la que
                                    //esta llegando ahora, entonces le creo el movimiento, le sumo o le resto.
                                    //tambien se mete en esa condicion si el estatusviejo era INGRESO_PENDIENTE
                                    //queriendo decir, que ahora el estatus nuevo es INGRESO_COMPLETADO
                                    if (($cantidadDetalle != '' and $cantidadDetalle != $unidad['cantidad']) ||
                                        ($post['statusViejo'] == INGRESO_PENDIENTE)
                                    ) {

                                        //pregunto si la cantidad que estoy ingresando nueva es mayor que la que ya estaba guardada
                                        //o en el caso de que el estatus  viejo de la compra sea INGRESO_PENDIENTE, siempre se suma a stock
                                        if (($unidad['cantidad'] > $cantidadDetalle) || ($post['statusViejo'] == INGRESO_PENDIENTE)) {

                                            //resto de una vez la cantidad que llega nueva menos la cantidad vieja del detalle
                                            //pero si el estatusviejo es INGRESO_PENDIENTE entonces la cantidad es la cantidad que viene llegando
                                            $cantidad = $unidad['cantidad'] - $cantidadDetalle;
                                            if ($post['statusViejo'] == INGRESO_PENDIENTE) {
                                                $cantidad = $unidad['cantidad'];
                                            }


                                            if (isset($row['is_prepack']) && count($row['is_prepack']) > 0 && is_array($row['is_prepack'])) {

                                                $operacion = "NINGUNA";
                                                //buscl el inventario de la caja del prepack, por si tiene
                                                $whereinventario = array(
                                                    'id_producto' => $row['producto_id'],
                                                    'id_unidad' => $unidad_id,
                                                    'id_local' => $this->session->userdata('id_local')
                                                );
                                                $inventario_caja = $this->inventario_model->get_by($whereinventario);

                                                $this->setInventario(
                                                    $row['producto_id'],
                                                    $unidad_id,
                                                    $unidad['cantidad'],
                                                    $post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA . " - " . INGRESO_PREPACK,
                                                    $unidad['costo'] / $unidad['cantidad'],
                                                    $post['id_ingreso'],
                                                    $post['doc_numero'],
                                                    $post['cboProveedor'],
                                                    ENTRADA,
                                                    $operacion,
                                                    array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => isset($inventario_caja['cantidad']) && $inventario_caja['cantidad'] != null ? $inventario_caja['cantidad'] : 0)), //stockviejo
                                                    array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => isset($inventario_caja['cantidad']) && $inventario_caja['cantidad'] != null ? $inventario_caja['cantidad'] + $unidad['cantidad'] : $unidad['cantidad'])),//stocknuevo
                                                    null,
                                                    $this->session->userdata('id_local'),
                                                    $datosProducto['control_inven'],
                                                    $datosProducto['porcentaje_impuesto'],
                                                    $costo_unitario);

                                                $this->setInventario(
                                                    $row['producto_id'],
                                                    $unidad_id,
                                                    $unidad['cantidad'],
                                                    $post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA . " - " . SALIDA_PREPACK,
                                                    $unidad['costo'] / $unidad['cantidad'],
                                                    $post['id_ingreso'],
                                                    $post['doc_numero'], $post['cboProveedor'],
                                                    SALIDA,
                                                    $operacion,
                                                    array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => isset($inventario_caja['cantidad']) && $inventario_caja['cantidad'] != null ? $inventario_caja['cantidad'] + $unidad['cantidad'] : $unidad['cantidad'])),
                                                    array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => isset($inventario_caja['cantidad']) && $inventario_caja['cantidad'] != null ? $inventario_caja['cantidad'] : 0)),
                                                    null,
                                                    $this->session->userdata('id_local'),
                                                    $datosProducto['control_inven'],
                                                    $datosProducto['porcentaje_impuesto'],
                                                    $costo_unitario);


                                            } elseif (isset($row['is_obsequio']) && count($row['is_obsequio']) > 0 && is_array($row['is_obsequio'])) {

                                                $operacion = "NINGUNA";

                                                $whereinventario = array(
                                                    'id_producto' => $row['producto_id'],
                                                    'id_unidad' => $unidad_id,
                                                    'id_local' => $this->session->userdata('id_local')
                                                );
                                                $inventario_caja = $this->inventario_model->get_by($whereinventario);
                                                $this->setInventario(
                                                    $row['producto_id'],
                                                    $unidad_id,
                                                    $unidad['cantidad'],
                                                    $post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA . " - " . INGRESO_OBSEQUIO,
                                                    $unidad['costo'] / $unidad['cantidad'],
                                                    $post['id_ingreso'],
                                                    $post['doc_numero'],
                                                    $post['cboProveedor'],
                                                    ENTRADA,
                                                    $operacion,
                                                    array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => isset($inventario_caja['cantidad']) && $inventario_caja['cantidad'] != null ? $inventario_caja['cantidad'] : 0)), //stockviejo
                                                    array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => isset($inventario_caja['cantidad']) && $inventario_caja['cantidad'] != null ? $inventario_caja['cantidad'] + $unidad['cantidad'] : $unidad['cantidad'])),//stocknuevo
                                                    null,
                                                    $this->session->userdata('id_local'),
                                                    $datosProducto['control_inven'],
                                                    $datosProducto['porcentaje_impuesto'], $costo_unitario);

                                                $this->setInventario(
                                                    $row['producto_id'],
                                                    $unidad_id,
                                                    $unidad['cantidad'],
                                                    $post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA . " - " . SALIDA_OBSEQUIO,
                                                    $unidad['costo'] / $unidad['cantidad'],
                                                    $post['id_ingreso'],
                                                    $post['doc_numero'],
                                                    $post['cboProveedor'],
                                                    SALIDA,
                                                    $operacion,
                                                    array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => isset($inventario_caja['cantidad']) && $inventario_caja['cantidad'] != null ? $inventario_caja['cantidad'] + $unidad['cantidad'] : $unidad['cantidad'])),
                                                    array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => isset($inventario_caja['cantidad']) && $inventario_caja['cantidad'] != null ? $inventario_caja['cantidad'] : 0)),
                                                    null,
                                                    $this->session->userdata('id_local'),
                                                    $datosProducto['control_inven'],
                                                    $datosProducto['porcentaje_impuesto'],
                                                    $costo_unitario);

                                            } else {

                                                $operacion = "SUMA";

                                                $this->setInventario(
                                                    $row['producto_id'],
                                                    $unidad_id,
                                                    $cantidad,
                                                    $post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA,
                                                    $unidad['costo'] / $unidad['cantidad'],
                                                    $post['id_ingreso'],
                                                    $post['doc_numero'],
                                                    $post['cboProveedor'],
                                                    ENTRADA,
                                                    $operacion,
                                                    $stockviejo = array(),
                                                    $stocknuevo = array(), null,
                                                    $this->session->userdata('id_local'),
                                                    $datosProducto['control_inven'],
                                                    $datosProducto['porcentaje_impuesto'],
                                                    $costo_unitario);
                                            }


                                        } else {

                                            $operacion = "RESTA";

                                            $this->setInventario(
                                                $row['producto_id'],
                                                $unidad_id,
                                                $cantidadDetalle - $unidad['cantidad'],
                                                $post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA,
                                                $unidad['costo'] / $unidad['cantidad'],
                                                $post['id_ingreso'],
                                                $post['doc_numero'],
                                                $post['cboProveedor'],
                                                SALIDA,
                                                $operacion,
                                                $stockviejo = array(),
                                                $stocknuevo = array(), null,
                                                $this->session->userdata('id_local'),
                                                $datosProducto['control_inven'],
                                                $datosProducto['porcentaje_impuesto'],
                                                $costo_unitario);
                                        }

                                    }

                                    //aqui valido si el producto fue un obsequio o un prepack, pero le quitaron los prductos que lo
                                    //componen o los que habian sido asociados, para entonces sumarle su inventario como un prducto normal
                                    $this->validarObsequioBorrado($productoactual, $post, $row, $unidad);
                                    $this->validarObseOPrepackAgregado($productoactual, $post, $row, $unidad);

                                }
                            } else {
                                //quiere decir que no estoy editando una compra, o no encontro el producto en la compra

                                //esto lo hago para saber el estatus que voy a guardar,
                                //ya que cuando de sale de la pantalla de compras por error y hay datos cargados en la pantalla,
                                //se guarda la compra pero en estatus INGRESO_PENDIENTE, para no afectar el inventario de los productos

                                if ($post['status'] == INGRESO_COMPLETADO) {


                                    if (isset($row['is_prepack']) && count($row['is_prepack']) > 0 && is_array($row['is_prepack'])) {

                                        $operacion = "NINGUNA";

                                        $this->setInventario(
                                            $row['producto_id'],
                                            $unidad_id,
                                            $unidad['cantidad'],
                                            COMPRA_A_ . $post['nombre_condiciones'] . " - " . INGRESO_PREPACK,
                                            $unidad['costo'] / $unidad['cantidad'],
                                            $post['id_ingreso'],
                                            $post['doc_numero'],
                                            $post['cboProveedor'],
                                            ENTRADA,
                                            $operacion,
                                            array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => 0)),
                                            array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => $unidad['cantidad'])),
                                            null, $this->session->userdata('id_local'),
                                            $datosProducto['control_inven'],
                                            $datosProducto['porcentaje_impuesto'],
                                            $costo_unitario);

                                        $this->setInventario(
                                            $row['producto_id'],
                                            $unidad_id,
                                            $unidad['cantidad'],
                                            COMPRA_A_ . $post['nombre_condiciones'] . " - " . SALIDA_PREPACK,
                                            $unidad['costo'] / $unidad['cantidad'],
                                            $post['id_ingreso'],
                                            $post['doc_numero'],
                                            $post['cboProveedor'],
                                            SALIDA,
                                            $operacion,
                                            array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => $unidad['cantidad'])),
                                            array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => 0)), null,
                                            $this->session->userdata('id_local'),
                                            $datosProducto['control_inven'],
                                            $datosProducto['porcentaje_impuesto'],
                                            $costo_unitario);


                                    } elseif (isset($row['is_obsequio']) && count($row['is_obsequio']) > 0 && is_array($row['is_obsequio'])) {

                                        $operacion = "NINGUNA";

                                        $this->setInventario(
                                            $row['producto_id'],
                                            $unidad_id,
                                            $unidad['cantidad'],
                                            COMPRA_A_ . $post['nombre_condiciones'] . " - " . INGRESO_OBSEQUIO,
                                            $unidad['costo'] / $unidad['cantidad'],
                                            $post['id_ingreso'],
                                            $post['doc_numero'],
                                            $post['cboProveedor'],
                                            ENTRADA,
                                            $operacion,
                                            array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => 0)),
                                            array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => $unidad['cantidad'])),
                                            null, $this->session->userdata('id_local'),
                                            $datosProducto['control_inven'],
                                            $datosProducto['porcentaje_impuesto'],
                                            $costo_unitario);

                                        $this->setInventario(
                                            $row['producto_id'],
                                            $unidad_id,
                                            $unidad['cantidad'],
                                            COMPRA_A_ . $post['nombre_condiciones'] . " - " . SALIDA_OBSEQUIO,
                                            $unidad['costo'] / $unidad['cantidad'],
                                            $post['id_ingreso'],
                                            $post['doc_numero'],
                                            $post['cboProveedor'],
                                            SALIDA,
                                            $operacion,
                                            array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => $unidad['cantidad'])),
                                            array($unidad_id => array('nombre' => 'CAJA', 'cantidad' => 0)), null,
                                            $this->session->userdata('id_local'),
                                            $datosProducto['control_inven'],
                                            $datosProducto['porcentaje_impuesto'],
                                            $costo_unitario);


                                    } else {

                                        $operacion = "SUMA";

                                        $this->setInventario(
                                            $row['producto_id'],
                                            $unidad['id'],
                                            $unidad['cantidad'],
                                            COMPRA_A_ . $post['nombre_condiciones'],
                                            $unidad['costo'] / $unidad['cantidad'],
                                            $post['id_ingreso'],
                                            $post['doc_numero'],
                                            $post['cboProveedor'],
                                            ENTRADA,
                                            $operacion,
                                            $stockviejo = array(), $stocknuevo = array(), null,
                                            $this->session->userdata('id_local'),
                                            $datosProducto['control_inven'],
                                            $datosProducto['porcentaje_impuesto'],
                                            $costo_unitario);
                                    }

                                    //actualizo el costo promedio
                                    $this->costo_promedio("SUMAR", $ultimasuma, $ultimacantidadcaja, $costo_unitario,
                                        0, $row['producto_id']);
                                }
                            }


                        }//cierra la condicion de si tenia contenido interno


                        $this->producto_model->solo_update(array('producto_id' => $row['producto_id']),
                            array('is_prepack' => $is_prepack, 'is_obsequio' => $is_obsequio));


                    } else {
                        //aqui solo entra cuando la cantidad viene en 0 o vacia
                        /* en el caso de coopidrogas, solo viene cantidad para caja, es decir, entra en la primera condicion
                        pero para las demas unidades viene vacio o en 0; e igual si tiene contenido interno, lo guardo. */
                        if (isset($row['contenido_interno']) && $row['contenido_interno'][$unidad_id] != '' && (!isset($row['is_prepack']) || count($row['is_prepack']) < 1)) {

                            foreach ($condiciones as $condicion) {

                                if (count($row['precios']) > 0) {

                                    $id_condicion = $condicion['id_condiciones'];

                                    if (isset($row['precios'][$unidad_id][$id_condicion]['precio']) &&
                                        $row['precios'][$unidad_id][$id_condicion]['precio'] != ""
                                    ) {

                                        $precio_minimo = false;
                                        $precio_maximo = false;
                                        foreach ($preciosActuales as $precioEstaunidad) {
                                            if ($precioEstaunidad['id_condiciones_pago'] == $id_condicion &&
                                                $precioEstaunidad['id_unidad'] == $unidad_id
                                            ) {

                                                $precio_maximo = $precioEstaunidad['precio_maximo'];
                                                $precio_minimo = $precioEstaunidad['precio_minimo'];
                                            }
                                        }
                                        $unidad_has_precio = array(
                                            "id_condiciones_pago" => $id_condicion,
                                            "id_unidad" => $unidad_id,
                                            "id_producto" => $row['producto_id'],
                                            "precio" => $row['precios'][$unidad_id][$id_condicion]['precio'],
                                            "utilidad" => $row['precios'][$unidad_id][$id_condicion]['utilidad']
                                        );

                                        if ($precio_minimo != false) {
                                            $unidad_has_precio['precio_minimo'] = $precio_minimo;
                                        }
                                        if ($precio_maximo != false) {
                                            $unidad_has_precio['precio_maximo'] = $precio_maximo;
                                        }

                                        $this->unidades_has_precio_model->insert($unidad_has_precio);
                                    }
                                }
                            }
                        }
                    }
                    /*final de isset(unidad->cantidad) */

                    $cont_unidad++;
                }

                $where = array(
                    'id_detalle_ingreso' => $id_detalleingreso
                );
                $updateDetalleUnidad = array(
                    'total_con_descuento' => number_format($total_con_descuento, 2, '.', ''));
                $this->db->where($where);
                $this->db->update('detalleingreso', $updateDetalleUnidad);

                $datosProducto = $this->producto_model->get_by('producto_id', $row['producto_id']);
                //Actualizo el costo de cada unidad, calculado segun el costo unitario de la caja
                $this->producto_model->updateCostosByProduct($row['producto_id'], $datosProducto['costo_unitario']);
                //fin del parche

                $cont++;
            }

            if (isset($post['actualizar']) and $post['actualizar'] == true) {
                //busco si existen productos con actualizado= 0, quiere decir que en caso de edicion de la compra
                //estos productos fueron eliminados de la compra
                $where = array(
                    'detalle_ingreso_unidad.ingreso_id' => $post['id_ingreso'],
                    'detalle_ingreso_unidad.actualizado' => 0
                );
                $buscarDetalleUnidad = $this->detalle_ingreso_unidad_model->getDetalleUnidadDetalle($where);


                if (count($buscarDetalleUnidad) > 0) {


                    //los devuelvo a su inventario que debe
                    foreach ($buscarDetalleUnidad as $fila) {
                        $datosProducto = $this->producto_model->get_by('producto_id', $fila->id_producto);
                        $operacion = "RESTA";
                        if ($post['status'] == INGRESO_COMPLETADO) {
                            $this->setInventario(
                                $fila->id_producto,
                                $fila->unidad_id,
                                $fila->cantidad,
                                MODIFICACION_COMPRA,
                                $fila->costo,
                                $post['id_ingreso'],
                                $post['doc_numero'],
                                $post['cboProveedor'],
                                SALIDA,
                                $operacion,
                                $stockviejo = array(), $stocknuevo = array(), null,
                                $this->session->userdata('id_local'),
                                $datosProducto['control_inven'],
                                $datosProducto['porcentaje_impuesto'],
                                $datosProducto['costo_unitario']);

                            $ultimasuma = $datosProducto['suma_costo_caja'];
                            $ultimacantidadcaja = $datosProducto['cantidad_caja'];

                            //aqui no importa el valor del costo unitario
                            $this->costo_promedio("ELIMINAR", $ultimasuma, $ultimacantidadcaja, $datosProducto['costo_unitario'],
                                $fila->costo_unitario_despues, $fila->id_producto);
                        }
                    }
                }

                //busco si existen productos con actualizado= 0, quiere decir que en caso de edicion de la compra
                //estos productos fueron eliminados de la compra
                $where = array(
                    'detalleingreso_especial.ingreso' => $post['id_ingreso'],
                    'detalleingreso_especial.actualizado' => 0
                );
                $buscarDetalleUnidad = $this->detalleingreso_especial_model->getSoloDetalle($where);

                if (count($buscarDetalleUnidad) > 0) {

                    //los devuelvo a su inventario que debe
                    foreach ($buscarDetalleUnidad as $fila) {
                        $datosProducto = $this->producto_model->get_by('producto_id', $fila['producto_id_especial']);
                        $operacion = "RESTA";
                        if ($post['status'] == INGRESO_COMPLETADO) {
                            $this->setInventario(
                                $fila['producto_id_especial'],
                                $fila['unidad_id'],
                                $fila['cantidad'],
                                MODIFICACION_COMPRA,
                                $fila['costo_uni'],
                                $post['id_ingreso'],
                                $post['doc_numero'],
                                $post['cboProveedor'],
                                SALIDA,
                                $operacion,
                                $stockviejo = array(), $stocknuevo = array(), null,
                                $this->session->userdata('id_local'),
                                $datosProducto['control_inven'],
                                $datosProducto['porcentaje_impuesto'],
                                $datosProducto['costo_unitario']);
                        }
                    }
                }

                /*******elimino los productos que no fueron actualizados o que fueron eliminados de la compra*****/
                $where = array(
                    'ingreso_id' => $post['id_ingreso'],
                    'actualizado' => 0
                );
                $this->detalle_ingreso_unidad_model->delete($where);

                $where = array(
                    'id_ingreso' => $post['id_ingreso'],
                    'status' => 0
                );
                $this->detalle_ingreso_model->eliminarDetalle($where);

                $where = array(
                    'ingreso' => $post['id_ingreso'],
                    'actualizado' => 0
                );
                $this->detalleingreso_especial_model->delete($where);
                /****************************************************/

            }

            /*actualizo a los nuevos( los que tienen estatus 1 o actualizado 1) o los que fueron modificados */
            $where = array(
                'id_ingreso' => $post['id_ingreso'],
                'status' => 1
            );
            $this->detalle_ingreso_model->update(array('status' => 0), $where);

            $where = array(
                'detalle_ingreso_unidad.ingreso_id' => $post['id_ingreso'],
            );
            $this->detalle_ingreso_unidad_model->update($where, array('actualizado' => 0));

            $where = array(
                'detalleingreso_especial.ingreso' => $post['id_ingreso'],
            );

            $this->detalleingreso_especial_model->update(array('actualizado' => 0), $where);
            /****************************************************/

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return $post['id_ingreso'];
            }
        }catch (Exception $e)
        {
            $this->db->devolverSinError();
            // If random_bytes() can't do the job, we can't either ...
            // There's no point in using fallbacks.
            log_message('error', $e->getMessage());
            return FALSE;
        }

	}


	function costo_promedio($accion, $ultimasuma, $ultimacantidadcaja, $costo_unitario, $costo_unitario_despues, $producto_id)
	{

		$costo_unitario = $costo_unitario != "" && $costo_unitario != null ? $costo_unitario : 0;
		if ($accion == "RESTAR") {
			//entra aqui cuando estoy editando la compra, independientemente si les modifique la cantidad o no, o si lo elimine de la compra o no
			// entonces siempre resto la cantidad que tenia -1 y la suma que tiene
			// menos lo que costaba la caja en ese momento, para agregar los nuevos valores que vienen y calcular el costo promedio
			if ($ultimasuma > 0) {
				$ultimasuma = $ultimasuma - $costo_unitario_despues;
				$ultimacantidadcaja = $ultimacantidadcaja - 1;

				$ultimasuma = $costo_unitario + $ultimasuma;
				$ultimacantidadcaja = 1 + $ultimacantidadcaja;

			} else {
				$ultimasuma = $costo_unitario + $ultimasuma;
				$ultimacantidadcaja = 1 + $ultimacantidadcaja;
			}
		} elseif ($accion == "ELIMINAR") {
			//aqui entra cuando elimine el producto de la compra, por lo tanto siempre le resto 1 y lo
			// que costaba la caja para cuando se compro esa unidad
			$ultimasuma = $ultimasuma - $costo_unitario_despues;
			$ultimacantidadcaja = $ultimacantidadcaja - 1;

		} else {
			//SUMAR
			//entra aqui cuando estoy en una compra nueva
			$ultimasuma = $costo_unitario + $ultimasuma;
			$ultimacantidadcaja = 1 + $ultimacantidadcaja;
		}

		$costo_promedio = 0;
		if ($ultimasuma > 0 && $ultimacantidadcaja > 0) {
			$costo_promedio = $ultimasuma / $ultimacantidadcaja;
		}

		$this->producto_model->solo_update(array('producto_id' => $producto_id),
			array(
				'costo_promedio' => $costo_promedio,
				'suma_costo_caja' => $ultimasuma,
				'cantidad_caja' => $ultimacantidadcaja)
		);

	}

	function setInventario($producto_id, $unidad, $cantidad, $kardexreferencia, $costo, $id_ingreso, $doc_numero, $cboProveedor,
						   $kardextipo, $operacion, $stockviejo, $stocknuevo, $cliente, $local, $control_invent, $porcentaje_impuesto, $costo_caja)
	{

        /**
         * $stockviejo y $stocknuevo, SIEMPRE deben de llegar aqui como un arreglo.
         */

		if ($control_invent != null && $control_invent != 0) {

			if ($operacion == "SUMA") {
				$return_inventario = $this->inventario_model->sumar_inventario($producto_id,
					$local, $unidad, $cantidad);
			} elseif ($operacion == "RESTA") {

				$return_inventario = $this->inventario_model->restar_inventario($producto_id,
					$local, $unidad, $cantidad);
			}

		} else {
			$stock = $this->inventario_model->get_stock_array($producto_id, $local, $unidad);
			//var_dump($stock);
			$stocknuevo = json_encode($stock['stockviejo_array']);
			$stockviejo = json_encode($stock['stockviejo_array']);
		}

        $isMayorCero = false;

        /**
         * Esto es para saber si ya viene el stock viejo, por parametro.
         */
        if(is_array($stockviejo) && count($stockviejo) > 0){
            $isMayorCero = true;
        }

        /**
         * Si $stockviejo o $stocknuevo son arreglos en este punto. los conveirto a un json_encode
         */
		if(is_array($stockviejo)){
            $stockviejo = json_encode($stockviejo);
        }

        if(is_array($stocknuevo)){
            $stocknuevo = json_encode($stocknuevo);
        }



		$this->kardex_model->set_kardex(
			$producto_id,
			$local,
			$unidad,
			$cantidad,
			$kardexreferencia,
			$this->session->userdata('nUsuCodigo'),
			$costo > 0 ? $costo : 0,
			$id_ingreso,
			$doc_numero,
			FACTURA,
			$cliente,
			$kardextipo,
            $isMayorCero==true ? $stockviejo : json_encode($return_inventario['stockviejo_array']),
            $isMayorCero==true  ? $stocknuevo : json_encode($return_inventario['stocknuevo_array']),
			$cboProveedor, $porcentaje_impuesto, $costo_caja);
	}

	function validarObseOPrepackAgregado($datosProducto, $post, $row, $unidad)
	{

		/*aqui valido, que cuando le asocio un producto a un obsequio, o varios productos a un prepack,
		valido si este producto obsequio o prepack, esta en la tabla de producto como un producto normal,
		 */
		$unidad_id_viene = $unidad['id'];
		$unidad_cantidad_viene = $unidad['cantidad'];
		$unidad_costo_viene = $unidad['costo'];
		if (isset($row['is_prepack'])) {

			if (count($row['is_prepack']) > 0 && $datosProducto['is_prepack'] == 0
				&& $post['statusViejo'] != INGRESO_PENDIENTE
			) {
				/*si entra aqui, tomando en cuenta de que aqui solo entra cuando es edicion de compra y cuado el estatus viejo de la compra
				es distinto de INGRESO_PENDIENTE,
				es por que el producto viene como un prepack desde la compra, pero en la tabla producto esta como producto normal,
				queriendo decir, que se esta editando la compra y que este producto no era un prepack, por lo que le resto de su inventario
				la cantidad que le fue ingresada al momento de guardarse la compra anteriormente */
				$operacion = "RESTA";
				$this->setInventario(
				    $row['producto_id'],
                    $unidad_id_viene,
                    $unidad_cantidad_viene,
					$post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA,
					$unidad_costo_viene / $unidad_cantidad_viene,
                    $post['id_ingreso'],
                    $post['doc_numero'],
                    $post['cboProveedor'],
					SALIDA,
                    $operacion,
                    $stockviejo = array(), $stocknuevo = array(),
					null, $this->session->userdata('id_local'),
                    $datosProducto['control_inven'],
					$datosProducto['porcentaje_impuesto'],
                    $datosProducto['costo_unitario']);

			}

		} elseif (isset($row['is_obsequio'])) {

			if (count($row['is_obsequio']) > 0 && $datosProducto['is_obsequio'] == 0
				&& $post['statusViejo'] != INGRESO_PENDIENTE
			) {
				/*si entra aqui, tomando en cuenta de que aqui solo entra cuando es edicion de compra y cuando el estatus viejo de esta compra
				es distinto de INGRESO_PENDIENTE,
							   es por que el producto viene como un obsequio desde la compra, pero en la tabla producto esta como producto normal,
							   queriendo decir, que se esta editando la compra y que este producto no era un obsequio, por lo que le resto de su inventario
							   la cantidad que le fue ingresada al momento de guardarse la compra anteriormente */
				$operacion = "RESTA";

				$this->setInventario(
				    $row['producto_id'],
                    $unidad_id_viene,
                    $unidad_cantidad_viene,
					$post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA,
					$unidad_costo_viene / $unidad_cantidad_viene,
                    $post['id_ingreso'],
                    $post['doc_numero'],
                    $post['cboProveedor'],
					SALIDA,
                    $operacion,
                    $stockviejo = array(), $stocknuevo = array(),
					null, $this->session->userdata('id_local'),
                    $datosProducto['control_inven'],
					$datosProducto['porcentaje_impuesto'],
                    $datosProducto['costo_unitario']);
			}
		}

	}

	function validarObsequioBorrado($datosProducto, $post, $row, $unidad)
	{
		//aqui valido si el producto fue un obsequio o un prepack, pero le quitaron los prductos que lo
		//componen o los que habian sido asociados, para entonces sumarle su inventario como un prducto normal
		$unidad_id_viene = $unidad['id'];
		$unidad_cantidad_viene = $unidad['cantidad'];
		$unidad_costo_viene = $unidad['costo'];
		if (isset($row['is_prepack'])) {

			if (count($row['is_prepack']) < 1 && $post['statusViejo'] != INGRESO_PENDIENTE) {

				$operacion = "SUMA";
				$this->setInventario(
				    $row['producto_id'],
                    $unidad_id_viene,
                    $unidad_cantidad_viene,
					$post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA,
					$unidad_costo_viene / $unidad_cantidad_viene,
                    $post['id_ingreso'],
                    $post['doc_numero'],
                    $post['cboProveedor'],
					ENTRADA,
                    $operacion,
                    $stockviejo = array(), $stocknuevo = array(),
					null, $this->session->userdata('id_local'),
                    $datosProducto['control_inven'],
					$datosProducto['porcentaje_impuesto'],
                    $datosProducto['costo_unitario']);

			}

		} elseif (isset($row['is_obsequio'])) {

			if (count($row['is_obsequio']) < 1 && $post['statusViejo'] != INGRESO_PENDIENTE) {

				$operacion = "SUMA";

				$this->setInventario(
				    $row['producto_id'],
                    $unidad_id_viene,
                    $unidad_cantidad_viene,
					$post['statusViejo'] == INGRESO_PENDIENTE ? COMPRA_A_ . $post['nombre_condiciones'] : MODIFICACION_COMPRA,
					$unidad_costo_viene / $unidad_cantidad_viene,
                    $post['id_ingreso'],
                    $post['doc_numero'],
                    $post['cboProveedor'],
					ENTRADA,
                    $operacion,
                    $stockviejo = array(), $stocknuevo = array(),
					null, $this->session->userdata('id_local'),
                    $datosProducto['control_inven'],
					$datosProducto['porcentaje_impuesto'],
                    $datosProducto['costo_unitario']);
			}
		}


	}

	/*
	 * CALCULA EL COSTO TOTAL DE UNA UNIDAD COMPRADA, CON SU DESCUENTO Y SU IVA
	$costoTotalSolo= el costo Total de la unidad que se compra, sin iva y sin descuentos
	$iva el % del iva que se va a calcular
	$descuento= si tiene algun descuento, se le coloca,(debe llegar en porcentaje)
	*/
	function calcular_total_final($costoTotalSolo, $iva, $descuento)
	{
		$calculo_impuesto = 0;
		if ($iva != "" && $iva > 0) {
			$calculo_impuesto = ($iva / 100);
			$calculo_impuesto = $costoTotalSolo * $calculo_impuesto;
		}
		$calculo = $costoTotalSolo + $calculo_impuesto;
		if ($descuento != "" && $descuento > 0) {
			$calculo = $calculo - ($calculo * ($descuento / 100));
		}
		$calculo = number_format($calculo, 2, '.', '');
		return $calculo;
	}

	/*
	 * CALCULA EL IVA TOTAL DE UN COSTO
	$costo= el costo que se le quire calcular el iva(sin iva y sin descuentos)
	$iva el % del iva que se va a calcular
	$descuento= si tiene algun descuento, se le coloca,(debe llegar en porcentaje si es que tuvo)
	*/
	function calcular_iva($costo, $iva, $descuento = false)
	{
		$calculo_impuesto = 0;
		$calculo = $costo;
		if ($descuento != "" && $descuento > 0 && $descuento != false) {

			$calculo = $calculo - ($calculo * ($descuento / 100));
		}

		if ($iva != "" && $iva > 0) {
			$calculo_impuesto = ($iva / 100);
			$calculo_impuesto = $calculo * $calculo_impuesto;
		}
		$calculo_impuesto = number_format($calculo_impuesto, 2, '.', '');
		return $calculo_impuesto;
	}

//guarda el producto obsequio original, al cual fue asociado el producto obsequio ejem: OBS.CREMA dental
	function detalleObsequio($row, $post, $id_detalleingreso)
	{

		for ($j = 0; $j < count($row['is_obsequio']); $j++) {


			/*el producto al final de todo esto siempre va a quedar en status 0*/
			/*cuando se actualiza el producto en la edicion de la compra, o
			cuando se esta editando la compra pero se esta insrtando un nuevo producto que no estaba en la compra,
			el status es 1.*/
			/*de resto, si es una compra nueva, todos los productos se gardan en estatus 0*/

			$where = array(
				'detalle_ingreso_id' => $id_detalleingreso,
				'detalleingreso_especial.producto_id' => $row['producto_id'],
				'producto_id_especial' => $row['is_obsequio'][$j]['producto_id'],
				'actualizado' => 0
			);

			//busco si ya guarde este producto en la compra
			$buscarProdEnDetalle = $this->detalleingreso_especial_model->getSoloDetalle($where);

			$status = 0;
			$tipo = "INSERTAR";


			if (count($buscarProdEnDetalle) > 0) {  //encontro

				$status = 1;
				$tipo = "ACTUALIZAR";
				$id_detalleingreso = $buscarProdEnDetalle[0]['detalle_ingreso_id'];
			} elseif (isset($post['actualizar']) and $post['actualizar'] == true) {

				$status = 1;
				$tipo = "INSERTAR";
			}

			//busco si este producto tiene unidades asociadas, si no tiene le creo caja
			$where = array('producto_id' => $row['is_obsequio'][$j]['producto_id']);
			if (count($this->unidades_model->solo_unidades_xprod($where)) < 1) {

				$datos = array(
					'producto_id' => $row['is_obsequio'][$j]['producto_id'],
					'id_unidad' => $row['is_obsequio[$j]']['unidad'],
					'unidades' => 1
				);
				$this->unidades_model->insert_unidades_producto($datos);
			}


			$costo_caja = $row['is_obsequio'][$j]['costo'] > 0 ? $row['is_obsequio'][$j]['costo'] /
				$row['is_obsequio'][$j]['cantidad_obsequio'] : $row['is_obsequio'][$j]['costo'];

			$datosProducto = $this->producto_model->get_by('producto_id', $row['is_obsequio'][$j]['producto_id']);

			//actualizo o guardo los datos en la tabla
			$this->saveDetalleIngreso_especial($row['is_obsequio'][$j]['unidad'], $row['is_obsequio'][$j]['cantidad_obsequio'],
				$costo_caja,
				$id_detalleingreso,
				$row['is_obsequio'][$j]['costo'], $row['producto_id'], $row['is_obsequio'][$j]['producto_id'], "OBSEQUIO",
				$status, $tipo, $post['id_ingreso'], $datosProducto['costo_unitario']);


			/*calculo el costo unitario*/
			/* $costo_unitario = $this->unidades_model->costo_unitario($row['is_obsequio'][$j]['producto_id'], $row['is_obsequio[$j]->unidad,
				 $costo_caja);


			 if ($costo_unitario != false) {
				 $this->producto_model->solo_update(array('producto_id' => $row['is_obsequio[$j]->producto_id),
					 array('costo_unitario' => $costo_unitario));
			 }*/

			if (count($buscarProdEnDetalle) > 0) {  //encontro

				//si entra aqui quiere decir que estoy editando la compra

				$cantidad = $buscarProdEnDetalle[0]['cantidad'];
				//cantidad es la cantidad vieja, unidad->cantidad es la nueva
				//pregunto si la cantidad que estoy ingresando nueva es menor que la que ya estaba guardada

				if (($cantidad != '' and $cantidad != $row['is_obsequio'][$j]['cantidad_obsequio']) ||
					($post['statusViejo'] == INGRESO_PENDIENTE)
				) {

					if ($post['status'] == INGRESO_COMPLETADO) {
						//cantidad es la cantidad vieja,
						//unidad->cantidad es la nueva
						//pregunto si la cantidad que estoy ingresando nueva es menor que la que ya estaba guardada
						if (($row['is_obsequio'][$j]['cantidad_obsequio'] > $cantidad) ||
							($post['statusViejo'] == INGRESO_PENDIENTE)
						) {

							$cantidadAsumar = $row['is_obsequio'][$j]['cantidad_obsequio'] - $cantidad;
							if ($post['statusViejo'] == INGRESO_PENDIENTE) {
								$cantidadAsumar = $row['is_obsequio'][$j]['cantidad_obsequio'];
							}

							$operacion = "SUMA";

							$this->setInventario(
							    $row['is_obsequio'][$j]['producto_id'],
                                $row['is_obsequio'][$j]['unidad'],
								$cantidadAsumar,
								MODIFICACION_COMPRA,
								$row['is_obsequio'][$j]['costo'],
                                $post['id_ingreso'],
                                $post['doc_numero'],
                                $post['cboProveedor'],
								ENTRADA,
                                $operacion,
                                $stockviejo = array(), $stocknuevo = array(), null,
								$this->session->userdata('id_local'), $datosProducto['control_inven'],
								$datosProducto['porcentaje_impuesto'], $datosProducto['costo_unitario']);

						} else {
							$operacion = "RESTA";

							$this->setInventario(
							    $row['is_obsequio'][$j]['producto_id'],
                                $row['is_obsequio'][$j]['unidad'],
								$cantidad - $row['is_obsequio'][$j]['cantidad_obsequio'],
								MODIFICACION_COMPRA,
								$row['is_obsequio'][$j]['costo'],
                                $post['id_ingreso'],
                                $post['doc_numero'],
                                $post['cboProveedor'],
								SALIDA,
                                $operacion,
                                $stockviejo = array(), $stocknuevo = array(), null,
								$this->session->userdata('id_local'), $datosProducto['control_inven'],
								$datosProducto['porcentaje_impuesto'], $datosProducto['costo_unitario']);
						}
					}
				}

			} else { //es un producto nuevo

				$operacion = "SUMA";
				//esto lo hago para saber el estatus que voy a guardar,
				//ya que cuando de sale de la pantalla de compras por error y hay datos cargados en la pantalla,
				//se guarda la compra pero en estatus INGRESO_PENDIENTE, para no afectar el inventario de los productos

				if ($post['status'] == INGRESO_COMPLETADO) {
					$this->setInventario(
					    $row['is_obsequio'][$j]['producto_id'],
                        $row['is_obsequio'][$j]['unidad'],
						$row['is_obsequio'][$j]['cantidad_obsequio'],
						COMPRA_A_ . $post['nombre_condiciones'] . " - " . INGRESO_OBSEQUIO,
						$row['is_obsequio'][$j]['costo'],
                        $post['id_ingreso'],
                        $post['doc_numero'],
                        $post['cboProveedor'],
						ENTRADA,
                        $operacion,
                        $stockviejo = array(),
                        $stocknuevo = array(), null,
						$this->session->userdata('id_local'), $datosProducto['control_inven'],
						$datosProducto['porcentaje_impuesto'], $datosProducto['costo_unitario']);
				}

			}

			return $costo_caja;
		}

	}

//guarda todo el detalle del prepack
	function detallePrepack($row, $post, $id_detalleingreso)
	{

		if (count($row['is_prepack']) < 1) {

			if (isset($post['actualizar']) and $post['actualizar'] == true) {
				/*si entra aqui es porque se este editando la compra y a este producto
				le quitaron todos los productos que tenian asociados al prepack, por lo que busco en la tabla si le fueron
				asociado productos, para devolverle el stock
				*/

				$where = array(
					'ingreso' => $post['id_ingreso'],
					'actualizado' => 0,
					'detalleingreso_especial.producto_id' => $row['producto_id'],
					'tipo' => "PREPACK",
					'detalle_ingreso_id' => $id_detalleingreso
				);

				$buscarDetalleUnidad = $this->detalleingreso_especial_model->getSoloDetalle($where);

				if (count($buscarDetalleUnidad) > 0) {

					//los devuelvo a su inventario que debe
					foreach ($buscarDetalleUnidad as $fila) {
						$datosProducto = $this->producto_model->get_by('producto_id', $fila['producto_id_especial']);

						$ultimasuma = $datosProducto['suma_costo_caja'];
						$ultimacantidadcaja = $datosProducto['cantidad_caja'];

						//aqui no importa el valor del costo unitario. Resto lo que costaba la caja para ese momento
						$this->costo_promedio("ELIMINAR", $ultimasuma, $ultimacantidadcaja, false,
							$fila['costo_uni'], $fila['producto_id_especial']);

						$operacion = "RESTA";
						if ($post['status'] == INGRESO_COMPLETADO) {
							$this->setInventario(
							    $fila['producto_id_especial'],
                                $fila['unidad_id'],
                                $fila['cantidad'],
								MODIFICACION_COMPRA,
								$fila['costo_uni'],
                                $post['id_ingreso'],
                                $post['doc_numero'],
                                $post['cboProveedor'],
								SALIDA,
                                $operacion,
                                $stockviejo = array(),
                                $stocknuevo = array(),
                                null,
								$this->session->userdata('id_local'), $datosProducto['control_inven'],
								$datosProducto['porcentaje_impuesto'], $datosProducto['costo_unitario']);
						}
					}
				}

				$this->detalleingreso_especial_model->delete($where);
			}

		} else {

			for ($j = 0; $j < count($row['is_prepack']); $j++) {

				/*el producto al final de todo esto siempre va a quedar en status 0*/
				/*cuando se actualiza el producto en la edicion de la compra, o
				cuando se esta editando la compra pero se esta insrtando un nuevo producto que no estaba en la compra,
				el status es 1.*/
				/*de resto, si es una compra nueva, todos los productos se gardan en estatus 0*/

				$where = array(
					'detalle_ingreso_id' => $id_detalleingreso,
					'detalleingreso_especial.producto_id' => $row['producto_id'],
					'producto_id_especial' => $row['is_prepack'][$j]['producto_id'],
					'actualizado' => 0
				);

				//busco si ya guarde este producto en la compra
				$buscarProdEnDetalle = $this->detalleingreso_especial_model->getSoloDetalle($where);

				$status = 0;
				$tipo = "INSERTAR";

				if (count($buscarProdEnDetalle) > 0) {  //encontro

					$status = 1;
					$tipo = "ACTUALIZAR";
					$id_detalleingreso = $buscarProdEnDetalle[0]['detalle_ingreso_id'];
				} elseif (isset($post['actualizar']) and $post['actualizar'] == true) {

					$status = 1;
					$tipo = "INSERTAR";
				}

				//busco si este producto tiene unidades asociadas
				$where = array('producto_id' => $row['is_prepack'][$j]['producto_id']);
				if (count($this->unidades_model->solo_unidades_xprod($where)) < 1) {

					$datos = array(
						'producto_id' => $row['is_prepack'][$j]['producto_id'],
						'id_unidad' => $row['is_prepack'][$j]['unidad_id'],
						'unidades' => 1
					);
					$this->unidades_model->insert_unidades_producto($datos);
				}

				$costo_caja = $row['is_prepack'][$j]['costo'] > 0 ? $row['is_prepack'][$j]['costo'] /
					$row['is_prepack'][$j]['cantidad'] : $row['is_prepack'][$j]['costo'];

				//busco los datos de este producto
				$datosProducto = $this->producto_model->get_by('producto_id', $row['is_prepack'][$j]['producto_id']);

				//actualizo o guardo los datos en la tabla
				$this->saveDetalleIngreso_especial($row['is_prepack'][$j]['unidad_id'], $row['is_prepack'][$j]['cantidad'],
					$costo_caja,
					$id_detalleingreso,
					$row['is_prepack'][$j]['costo'], $row['producto_id'], $row['is_prepack'][$j]['producto_id'], "PREPACK",
					$status, $tipo, $post['id_ingreso'], $datosProducto['costo_unitario']);

				/*calculo el costo unitario*/
				$costo_unitario = $this->unidades_model->costo_unitario($row['is_prepack'][$j]['producto_id'],
					$row['is_prepack'][$j]['unidad_id'],
					$costo_caja);

				/******************************/
				if ($costo_unitario != false) {
					$this->producto_model->solo_update(array('producto_id' => $row['is_prepack'][$j]['producto_id']),
						array('costo_unitario' => $costo_unitario));

					//TODO, PROBAR SI HACE BIEN EL CALCULO PARA TODAS LAS UNIDADES DEL PRODUCTO
					//Actualizo el costo de cada unidad, calculado segun el costo unitario de la caja
					$this->producto_model->updateCostosByProduct($row['is_prepack'][$j]['producto_id'], $costo_unitario);
					//fin del parche


				}


				if (count($buscarProdEnDetalle) > 0) {  //encontro

					//si entra aqui quiere decir que estoy editando la compra

					$cantidad = $buscarProdEnDetalle[0]['cantidad'];
					//cantidad es la cantidad vieja, $row['is_prepack[$j]->cantidad es la nueva
					//pregunto si la cantidad que estoy ingresando nueva es menor que la que ya estaba guardada


					if (($cantidad != '' and $cantidad != $row['is_prepack'][$j]['cantidad']) ||
						($post['statusViejo'] == INGRESO_PENDIENTE)
					) {

						if ($post['status'] == INGRESO_COMPLETADO) {


							$ultimasuma = $datosProducto['suma_costo_caja'];
							$ultimacantidadcaja = $datosProducto['cantidad_caja'];

							//le actualizo el costo promedio
							$this->costo_promedio("RESTAR", $ultimasuma, $ultimacantidadcaja, $costo_unitario,
								$buscarProdEnDetalle[0]['costo_uni'], $row['is_prepack'][$j]['producto_id']);


							$costo = $row['is_prepack'][$j]['costo'];
							$producto_id = $row['is_prepack'][$j]['producto_id'];
							$unidad_id = $row['is_prepack'][$j]['unidad_id'];
							if (($row['is_prepack'][$j]['cantidad'] > $cantidad) ||
								($post['statusViejo'] == INGRESO_PENDIENTE)
							) {

								$cantidadAsumar = $row['is_prepack'][$j]['cantidad'] - $cantidad;
								if ($post['statusViejo'] == INGRESO_PENDIENTE) {
									$cantidadAsumar = $row['is_prepack'][$j]['cantidad'];
								}

								$operacion = "SUMA";

								$this->setInventario(
								    $producto_id,
                                    $unidad_id,
									$cantidadAsumar,
									MODIFICACION_COMPRA,
									$costo,
                                    $post['id_ingreso'],
                                    $post['doc_numero'],
                                    $post['cboProveedor'],
									ENTRADA,
                                    $operacion,
                                    $stockviejo = array(),
                                    $stocknuevo = array(), null,
									$this->session->userdata('id_local'), $datosProducto['control_inven'],
									$datosProducto['porcentaje_impuesto'], $datosProducto['costo_unitario']);


							} else {

								$operacion = "RESTA";

								$this->setInventario(
								    $producto_id,
                                    $unidad_id,
									$cantidad - $row['is_prepack'][$j]['cantidad'],
									MODIFICACION_COMPRA,
									$costo,
                                    $post['id_ingreso'],
                                    $post['doc_numero'],
                                    $post['cboProveedor'],
									SALIDA,
                                    $operacion,
                                    $stockviejo = array(), $stocknuevo = array(), null,
									$this->session->userdata('id_local'), $datosProducto['control_inven'],
									$datosProducto['porcentaje_impuesto'], $datosProducto['costo_unitario']);
							}
						}
					}

				} else {


					//esto lo hago para saber el estatus que voy a guardar,
					//ya que cuando de sale de la pantalla de compras por error y hay datos cargados en la pantalla,
					//se guarda la compra pero en estatus INGRESO_PENDIENTE, para no afectar el inventario de los productos

					if ($post['status'] == INGRESO_COMPLETADO) {

						$ultimasuma = $datosProducto['suma_costo_caja'];
						$ultimacantidadcaja = $datosProducto['cantidad_caja'];

						//actualizo el costo promedio,
						$this->costo_promedio("SUMAR", $ultimasuma, $ultimacantidadcaja, $costo_unitario,
							0, $row['is_prepack'][$j]['producto_id']);

						$operacion = "SUMA";
						$this->setInventario(
						    $row['is_prepack'][$j]['producto_id'],
                            $row['is_prepack'][$j]['unidad_id'],
							$row['is_prepack'][$j]['cantidad'],
							COMPRA_A_ . $post['nombre_condiciones'] . " - " . INGRESO_PREPACK,
							$row['is_prepack'][$j]['costo'],
                            $post['id_ingreso'],
                            $post['doc_numero'],
                            $post['cboProveedor'],
							ENTRADA,
                            $operacion,
                            $stockviejo = array(), $stocknuevo = array(), null,
							$this->session->userdata('id_local'), $datosProducto['control_inven'],
							$datosProducto['porcentaje_impuesto'], $datosProducto['costo_unitario']);
					}
				}
			}
		}
	}

	function guardarDetalleCompra($idingreso, $producto_id, $total_producto, $bonificacion,
								  $descuento, $impuesto, $total_impuesto, $status, $tipo, $id_detalleingreso = false)
	{
		$detalleingreso = array(
			'id_ingreso' => $idingreso,
			'id_producto' => $producto_id,
			'total_detalle' => $total_producto,
			'porcentaje_bonificacion' => $bonificacion,
			'porcentaje_descuento' => $descuento,
			'impuesto_porcentaje' => $impuesto,
			'total_impuesto' => $total_impuesto,
			'status' => $status
		);

		if ($tipo == "ACTUALIZAR") {

			$where = array(
				'id_ingreso' => $idingreso,
				'id_producto' => $producto_id
			);
			if ($id_detalleingreso != false) {
				$where['id_detalle_ingreso'] = $id_detalleingreso;
			}
			return $this->detalle_ingreso_model->update($detalleingreso, $where);

		} else {

			return $id_detalleingreso = $this->detalle_ingreso_model->insertar($detalleingreso);
		}


	}

	function saveDetalleUnidad($unidad, $cantidad, $cuantoCosto, $id_detalleingreso,
							   $costoTotal, $impuesto, $id_ingreso, $actualizar, $tipo, $costabaestaunidad, $costounitarioantes,
							   $costounitariodespues, $costo_con_descuento, $totalfinalunidad)
	{

		//guarda el detalle de las unidades
		$datos = array(
			'unidad_id' => $unidad,
			'cantidad' => $cantidad,
			'costo' => $cuantoCosto, /*cuanto costo cada unidad*/
			'detalle_ingreso_id' => $id_detalleingreso,
			'costo_total' => $costoTotal,  /*el costo total sin impuesto y sin descuento*/
			'impuesto' => $impuesto,
			'actualizado' => $actualizar,
			'ingreso_id' => $id_ingreso,
			'costoestaunidad_antes' => $costabaestaunidad,
			'costo_unitario_antes' => $costounitarioantes,
			'costo_unitario_despues' => $costounitariodespues,
			'costo_con_descuento' => $costo_con_descuento,
			'total_final' => $totalfinalunidad
		);


		if ($tipo == "ACTUALIZAR") {

			$where = array(
				'detalle_ingreso_id' => $id_detalleingreso,
				'unidad_id' => $unidad
			);

			return $this->detalle_ingreso_unidad_model->update($where, $datos);


		} else {

			return $this->detalle_ingreso_unidad_model->insert($datos);
		}
	}

	function saveDetalleIngreso_especial($unidad, $cantidad, $cuantoCosto, $id_detalleingreso,
										 $costoTotal, $producto_id, $productoIdEspecial, $obseOprepack, $actualizado,
										 $operacion, $id_ingreso, $costo_unitario_antes)
	{
		//guarda el detalle de las unidades
		$datos = array(
			'unidad_id' => $unidad,
			'cantidad' => $cantidad,
			'costo_uni' => $cuantoCosto,
			'detalle_ingreso_id' => $id_detalleingreso,
			'costo_total' => $costoTotal,
			'producto_id_especial' => $productoIdEspecial,
			'producto_id' => $producto_id,
			'tipo' => $obseOprepack,
			'actualizado' => $actualizado,
			'ingreso' => $id_ingreso,
			'costo_unitario_antes' => $costo_unitario_antes
		);

		if ($operacion == "ACTUALIZAR") {

			$where = array(
				'detalle_ingreso_id' => $id_detalleingreso,
				'producto_id' => $producto_id,
				'producto_id_especial' => $productoIdEspecial
			);
			return $this->detalleingreso_especial_model->update($datos, $where);

		} else {

			return $id_detalleingreso = $this->detalleingreso_especial_model->insert($datos);
		}

	}


	function setCostoPromedio($costo)
	{

		$query_compra = $this->db->query("SELECT detalleingreso.*, ingreso.fecha_registro, unidades_has_producto.* FROM detalleingreso
                  JOIN ingreso ON ingreso.id_ingreso=detalleingreso.id_ingreso
                  JOIN unidades ON unidades.id_unidad=detalleingreso.unidad_medida
                  JOIN unidades_has_producto ON unidades_has_producto.id_unidad=detalleingreso.unidad_medida
                  AND unidades_has_producto.producto_id=detalleingreso.id_producto
                  WHERE detalleingreso.id_producto=" . $id_producto . " AND  fecha_registro=(SELECT MAX(fecha_registro) FROM ingreso
                  JOIN detalleingreso ON detalleingreso.id_ingreso=ingreso.id_ingreso WHERE detalleingreso.id_producto=" . $id_producto . ")  ");

		$result_ingreso = $query_compra->result_array();
		if (count($result_ingreso) > 0) {

			$calcular_costo_u = ($result_ingreso[0]['precio'] / $result_ingreso[0]['unidades']) * $unidad_maxima['unidades'];
			$promedio_compra = ($calcular_costo_u / $unidad_maxima['unidades']) * $unidad_form['unidades'];
		} else {
			$promedio_compra = 0;
		}
	}


	function update_inventario($campos, $wheres)
	{


		$this->db->trans_start();
		$this->db->where($wheres);
		$this->db->update('inventario', $campos);
		$this->db->trans_complete();


	}

//TODO CAMBIAR TODOS LOS DEMAS POR ESTE METODO
	public
	function traer_by_mejorado($select = false, $from = false, $join = false, $campos_join = false, $tipo_join, $where = false, $nombre_in, $where_in,
							   $nombre_or, $where_or,
							   $group = false,
							   $order = false, $retorno = false, $limit = false, $start = 0, $order_dir = false, $like = false, $where_custom)
	{
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
		$re = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
		$devolver = null;
		if ($retorno == "RESULT_ARRAY") {

			$devolver = $query->result_array();

			if (count($devolver) > 0) {
				$devolver[0]['total_afectados'] = $re;
			}
		} elseif ($retorno == "RESULT") {
			$devolver = $query->result();
			if (count($devolver) > 0) {
				$devolver[0]->total_afectados = $re;
			}

		} else {
			$devolver = $query->row_array();
			if (sizeof($devolver) > 0) {
				$devolver['total_afectados'] = $re;
			}
		}

		return $devolver;

	}

	public
	function traer_by($select = false, $from = false, $join = false, $campos_join = false, $tipo_join, $where = false, $nombre_in, $where_in,
					  $nombre_or, $where_or,
					  $group = false,
					  $order = false, $retorno = false)
	{
		if ($select != false) {
			$this->db->select($select);
			$this->db->from($from);
		}
		if ($join != false and $campos_join != false) {
			for ($i = 0; $i < count($join); $i++) {
				if ($tipo_join != false) {
					$this->db->join($join[$i], $campos_join[$i], $tipo_join[$i]);
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

		if ($retorno == "RESULT_ARRAY") {

			return $query->result_array();
		} elseif ($retorno == "RESULT") {
			return $query->result();

		} else {
			return $query->row_array();
		}

	}

	function anular_ingreso()
	{


		$this->load->model('detalle_ingreso_unidad/detalle_ingreso_unidad_model');
		$this->load->model('detalleingreso_especial/detalleingreso_especial_model');

		$this->db->trans_begin();

		$id_ingreso = $this->input->post('id');
		$local = $this->input->post('local');
		$whereIngreso = array(
			'id_ingreso' => $id_ingreso
		);

		$detalleUnidad = $this->detalle_ingreso_unidad_model->getDetalleUnidadDetalle($whereIngreso);


		foreach ($detalleUnidad as $detalleU) {

			if ($detalleU->is_prepack == 1 || $detalleU->is_obsequio == 1) {

				$where = array(
					'detalle_ingreso_id' => $detalleU->id_detalle_ingreso
				);

				$detalle_especial = $this->detalleingreso_especial_model->getSoloDetalle($where);

				foreach ($detalle_especial as $detalleE) {

					$return_inventario = $this->inventario_model->restar_inventario($detalleE['producto_id_especial'],
						$local, $detalleE['unidad_id'], $detalleE['cantidad']);

					$this->kardex_model->set_kardex(
						$detalleE['producto_id_especial'],
						$local,
						$detalleE['unidad_id'],
						$detalleE['cantidad'],
						ANULACION_COMPRA,
						$this->session->userdata('nUsuCodigo'),
						$detalleE['costo_uni'],
						$detalleU->id_ingreso,
						null,
						FACTURA,
						null,
						SALIDA,
						json_encode($return_inventario['stockviejo_array']),
						json_encode($return_inventario['stocknuevo_array']),
						null,
						$detalleE['impuesto_porcentaje'],
						$detalleE['costo_unitario']
					);
				}

			} else {

				$datosProducto = $this->producto_model->get_by('producto_id', $detalleU->id_producto);
				$ultimasuma = $datosProducto['suma_costo_caja'];
				$ultimacantidadcaja = $datosProducto['cantidad_caja'];

				//aqui no importa el valor del costo unitario
				$this->costo_promedio("ELIMINAR", $ultimasuma, $ultimacantidadcaja, $datosProducto['costo_unitario'],
					$detalleU->costo_unitario_despues, $detalleU->id_producto);


				$return_inventario = $this->inventario_model->restar_inventario($detalleU->id_producto,
					$local, $detalleU->unidad_id, $detalleU->cantidad);


				$this->kardex_model->set_kardex(
					$detalleU->id_producto,
					$local,
					$detalleU->unidad_id,
					$detalleU->cantidad,
					ANULACION_COMPRA,
					$this->session->userdata('nUsuCodigo'),
					$detalleU->costo,
					$detalleU->id_ingreso,
					null,
					SALIDA,
					null,
					ENTRADA,
					json_encode($return_inventario['stockviejo_array']),
					json_encode($return_inventario['stocknuevo_array']),
					null,
					$detalleU->impuesto_porcentaje,
					$detalleU->costo_unitario
				);
			}
		}


		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {


			$this->update_ingreso('ingreso', array('ingreso_status' => INGRESO_ANULADO), $whereIngreso);


			$this->db->trans_commit();
			return $id_ingreso;
		}

	}

	function eliminar_ingreso()
	{

		$this->load->model('detalle_ingreso_unidad/detalle_ingreso_unidad_model');
		$this->load->model('detalleingreso_especial/detalleingreso_especial_model');

		$this->db->trans_begin();
		$this->detalleingreso_especial_model->deleteByIngreso($this->input->post('id'));

		$where = array(
			'ingreso_id' => $this->input->post('id')
		);
		$this->detalle_ingreso_unidad_model->delete($where);

		$where = array(
			'id_ingreso' => $this->input->post('id')
		);
		$this->detalle_ingreso_model->eliminarDetalle($where);

		$this->db->delete('ingreso', $where);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {

			$this->db->trans_commit();
			return true;
		}
	}

// consulta si es posible hacer la devilucion de un ingreso
	function consultar_devolucion()
	{
		$cantidad_comparar = null;
		$cantidad_total = null;

		$this->db->trans_start();

		$this->db->trans_begin();

		$data = array();
		$id = $this->input->post('id');
		$local = $this->input->post('local');


		$sql_detalle_ingreso = $this->db->query("SELECT * FROM detalleingreso
JOIN producto ON producto.producto_id=detalleingreso.id_producto
LEFT JOIN unidades_has_producto ON unidades_has_producto.producto_id=producto.producto_id 
LEFT JOIN unidades ON unidades.id_unidad=unidades_has_producto.id_unidad
JOIN ingreso ON ingreso.`id_ingreso`=detalleingreso.`id_ingreso`
WHERE detalleingreso.id_ingreso='$id'");

		$query_detalle_ingreso = $sql_detalle_ingreso->result_array();
		$kardex = array();

		$devolver = true;
		for ($i = 0; $i < count($query_detalle_ingreso); $i++) {


			$local = $query_detalle_ingreso[$i]['local_id'];
			$unidad_maxima = $query_detalle_ingreso[$i]['unidades'];
			$unidad_minima = $query_detalle_ingreso[count($query_detalle_ingreso) - 1];

			$producto_id = $query_detalle_ingreso[$i]['producto_id'];
			$unidad = $query_detalle_ingreso[$i]['unidad_medida'];
			$cantidad_compra = $query_detalle_ingreso[$i]['cantidad'];

			$sql_inventario = $this->db->query("SELECT id_inventario, cantidad, fraccion
            FROM inventario where id_producto='$producto_id' and id_local='$local'");
			$inventario_existente = $sql_inventario->row_array();


			$id_inventario = $inventario_existente['id_inventario'];
			$cantidad_vieja = $inventario_existente['cantidad'];
			$fraccion_vieja = $inventario_existente['fraccion'];

			$query = $this->db->query("SELECT * FROM unidades_has_producto WHERE producto_id='$producto_id'");

			$unidades_producto = $query->result_array();

			foreach ($unidades_producto as $row) {
				if ($row['id_unidad'] == $unidad) {
					$unidad_form = $row;
				}
			}


			//var_dump($query_detalle_ingreso[$i]['unidades']);
			if ($cantidad_vieja >= 1) {

				$unidades_minimas_inventario = ($cantidad_vieja * $query_detalle_ingreso[$i]['unidades']) + $fraccion_vieja;
			} else {
				$unidades_minimas_inventario = $fraccion_vieja;
			}

			$unidades_minimas_detalle = $unidad_form['unidades'] * $cantidad_compra;


			$resta = $unidades_minimas_inventario - $unidades_minimas_detalle;

			if ($resta > 0) {
				$cont = 0;
				while ($resta >= $unidad_maxima) {
					$cont++;
					$resta = $resta - $unidad_maxima;
				}
				if ($cont < 1) {
					$cantidad_nueva = 0;
					$fraccion_nueva = $resta;
				} else {
					$cantidad_nueva = $cont;
					$fraccion_nueva = $resta;
				}
			} else {
				$cantidad_nueva = 0;
				$fraccion_nueva = 0;
			}

			$cantidad_total = ($cantidad_vieja * $query_detalle_ingreso[$i]['unidades']) + $fraccion_vieja;
			$cantidad_comparar = ($cantidad_compra * $unidad_form['unidades']);

			if ($cantidad_comparar <= $cantidad_total) {

			} else {
				return false;
			}


		}


		$this->db->trans_complete();


		$this->db->trans_off();
		return true;


	}

	function select_compra($fecInicio, $fecFin)
	{
		$this->db->select('*');
		$this->db->from('v_consulta_compras c');
		$this->db->where('c.FecRegistro >= ', $fecInicio);
		$this->db->where('c.FecRegistro <= ', $fecFin);
		$query = $this->db->get();
		return $query->result();
	}

	function costosDeCompra()
	{
		$this->db->join('producto', 'producto.producto_id=detalleingreso.id_producto', 'left');
		//$this->db->group_by("id_producto");
		$query = $this->db->get('detalleingreso');
		return $query->result_array();
	}

	function get_detalles_by($campo, $valor)
	{
		$this->db->select('*');
		$this->db->from('detalleingreso');
		$this->db->join('ingreso', 'ingreso.id_ingreso=detalleingreso.id_ingreso', 'left');
		$this->db->join('producto', 'producto.producto_id=detalleingreso.id_producto', 'left');
		$this->db->where($campo, $valor);
		$query = $this->db->get();
		return $query->result_array();
	}

	function get_ingresos_by($condicion)
	{
		$this->db->select('*');
		$this->db->from('ingreso');
		$this->db->join('proveedor', 'proveedor.id_proveedor=ingreso.int_Proveedor_id', 'left');
		$this->db->join('local', 'local.int_local_id=ingreso.local_id');
		$this->db->join('usuario', 'usuario.nUsuCodigo=ingreso.nUsuCodigo');
		//  $this->db->join('detalleingreso', 'detalleingreso.id_ingreso=ingreso.id_ingreso');
		$this->db->where($condicion);
		$this->db->order_by('id_ingreso');
		$query = $this->db->get();
		return $query->result();
	}


	public function compras_por_fecha($fechadesde, $fechahasta, $tipos_venta_select = "TODOS", $tipos_venta_text = "TODOS")
	{

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

		$where_custom = false;

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
		$from = "ingreso vb";
		$join = array();
		$campos_join = array();
		$tipo_join = array(null, null, null, null, 'left');

		$order = 'fecha_registro';
		$start = 0;
		$limit = false;

	

		$datas['data'] = $this->StatusCajaModel->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
        $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, 'asc', false, $where_custom);

		$array = array();

		$count = 0;
		$datajson = array();
		$impuestos = $this->impuestos_model->get_impuestos();
		foreach ($datas['data'] as $data) {

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
			$json[] = date('d-m-Y', strtotime($data['fecha_registro']));
			if ($tipos_venta_text != "TODOS") {
				$json[] = $tipos_venta_text;
			}


			foreach ($impuestos as $impuesto) {
				$totalimp = $this->getTotalesByImpuestos(
					$impuesto['id_impuesto'],
					array('DATE(fecha_registro)' => date('Y-m-d', strtotime($data['fecha_registro'])))
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
			$json[] = number_format($data['gravado'], 2, ',', '.');
			$json[] = number_format($data['excluido'], 2, ',', '.');
			$json[] = number_format($data['excluido'] + $data['gravado'], 2, ',', '.');
			$json[] = number_format($data['total'], 2, ',', '.');

			$datajson[] = $json;

			$count++;
		}

		$array['data'] = $datajson;

		return json_encode($array);
	}


	function getTotalesByImpuestos($impuestoId, $condicion = false)
	{


		$select = " SUM(detalleingreso.total_con_descuento) as subtotal, SUM(detalleingreso.total_impuesto) AS iva";


		$this->db->select($select);
		$this->db->join('detalleingreso', 'detalleingreso.id_ingreso=ingreso.id_ingreso');


		$this->db->join('impuestos', 'impuestos.porcentaje_impuesto=detalleingreso.impuesto_porcentaje');

		//$this->db->join('tipo_venta', 'tipo_venta.tipo_venta_id=venta_backup.venta_tipo');
		//$this->db->join('condiciones_pago', 'tipo_venta.condicion_pago=condiciones_pago.id_condiciones');

		//   $this->db->where('condiciones_pago.dias','0');
		$this->db->where("impuestos.id_impuesto", $impuestoId);
		$this->db->where("total_impuesto >", 0);

		$this->db->where($condicion);
		// $this->db->group_by('venta_id');
		$query = $this->db->get('ingreso');
		//echo $this->db->last_query();
		return $query->row_array();
	}


	function get_informe_det_ingreso($condicion)
	{
		$this->db->select('*');
		$this->db->from('ingreso');
		$this->db->join('proveedor', 'proveedor.id_proveedor=ingreso.int_Proveedor_id', 'left');
		$this->db->join('local', 'local.int_local_id=ingreso.local_id');
		$this->db->join('usuario', 'usuario.nUsuCodigo=ingreso.nUsuCodigo');
		$this->db->join('detalleingreso', 'detalleingreso.id_ingreso=ingreso.id_ingreso');
		$this->db->where($condicion);
		$this->db->group_by('DATE(fecha_registro)');
		$this->db->order_by('ingreso.id_ingreso');
		$query = $this->db->get();
		return $query->result();
	}

	function update_ingreso($tabla, $campos, $where)
	{
		$this->db->trans_start();
		$this->db->where($where);
		$this->db->update($tabla, $campos);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {

			return false;
		} else {
			return true;

		}
	}

	function cuadre_caja_egresos($where)
	{
		$w = array('DATE(fecha_registro) >=' => $where['fecha'],
			'DATE(fecha_registro) <=' => $where['fecha'],
			'ingreso_status' => COMPLETADO);
		$this->db->select('*,SUM(total_ingreso) as totalC');
		$this->db->where($w);
		$this->db->where('condiciones_pago.dias', 0);
		$this->db->from('ingreso');
		$this->db->join('condiciones_pago', 'condiciones_pago.id_condiciones=ingreso.pago');
		$query = $this->db->get();
		return $query->row_array();
	}

	function cuadre_caja_pagos($where)
	{
		$w = array('DATE(pagoingreso_fecha) >=' => $where['fecha'],
			'DATE(pagoingreso_fecha) <=' => $where['fecha']);
		$this->db->select('*,SUM(pagoingreso_monto) as montoIngreso');
		$this->db->where($w);
		$this->db->from('pagos_ingreso');

		$query = $this->db->get();
		return $query->row_array();
	}

	function acomodarTodasLasCompras()
	{
		$this->db->trans_begin();
		$where = array(
			'ingreso.id_ingreso>' => 0
		);
		$ingresos = $this->get_ingresos_by($where);

		$total_de_todoingresos = 0;
		if (count($ingresos) > 0) {
			foreach ($ingresos as $ingreso) {

				$detalles = $this->get_detalles_by('detalleingreso.id_ingreso', $ingreso->id_ingreso);
				if (count($detalles) > 0) {

					$total_ingreso = 0;
					foreach ($detalles as $detalle) {

						$total_con_descuento = 0;
						$where = array(
							'detalle_ingreso_id' => $detalle['id_detalle_ingreso']
						);
						$detalles_unidad = $this->detalle_ingreso_unidad_model->getDetalleUnidad($where);

						if (count($detalles_unidad) > 0) {

							foreach ($detalles_unidad as $detalle_unidad) {
								$detalle_unidad->costo = number_format($detalle_unidad->costo, 2, '.', '');
								$costo_con_descuento = $detalle_unidad->costo;

								/*si el producto tenia algun % de descuento, se lo calculo, para que afecte el costo unitario*/
								if ($detalle['porcentaje_descuento'] != "" && $detalle['porcentaje_descuento'] > 0) {

									/*calculo el costo de esta unidad con el descuento*/
									$costo_con_descuento = $detalle_unidad->costo - (($detalle['porcentaje_descuento'] * $detalle_unidad->costo) / 100);
									$total_con_descuento = $total_con_descuento + ($costo_con_descuento * $detalle_unidad->cantidad);
								} else {
									$total_con_descuento = $total_con_descuento + $detalle_unidad->costo_total;
								}

								/*el costo_total(sin iva ni descuento) + el impuesto*/
								$cuantodeimpuestoporunidad = $this->calcular_iva($detalle_unidad->costo, $detalle['impuesto_porcentaje'], $detalle['porcentaje_descuento']);

								/*calculo el total final del costo de esta unidad, con iva y con descuento si es que tuvo*/
								$totalfinalunidad = $this->calcular_total_final($detalle_unidad->costo_total, $detalle['impuesto_porcentaje'], $detalle['porcentaje_descuento']);

								$updateDetalleUnidad = array(
									'impuesto' => number_format($cuantodeimpuestoporunidad, 2, '.', ''),
									'costo_con_descuento' => number_format($costo_con_descuento, 2, '.', ''),
									'total_final' => number_format($totalfinalunidad, 2, '.', ''),
								);
								//$this->detalle_ingreso_unidad_model->update($where,$updateDetalleUnidad);

								$total_ingreso += $totalfinalunidad;
							}
						}

						/* $where=array(
							 'id_detalle_ingreso'=>$detalle['id_detalle_ingreso']
						 );
						 $updateDetalleUnidad=array(
							 'total_con_descuento'=>number_format($total_con_descuento, 2, '.', ''),);
						 $this->db->where($where);
						 $this->db->update('detalleingreso',$updateDetalleUnidad);*/

					}
					$total_de_todoingresos += $total_ingreso;
				}


				/*  $where=array(
					  'ingreso'=>$ingreso['id_ingreso']
				  );
				  $updateIngreso=array(
					  'total_con_descuento'=>number_format($total_ingreso, 2, '.', ''),);
				  $this->db->where($where);
				  $this->db->update('ingreso',$updateIngreso);*/

			}

		}
		var_dump($total_de_todoingresos);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}


	}


	function modificarcompraespecifico()
	{

		$this->load->model('detalle_ingreso_unidad/detalle_ingreso_unidad_model');
		$this->load->model('detalleingreso_especial/detalleingreso_especial_model');

		$this->db->trans_begin();

		$id_ingreso = $this->input->post('id');
		$local = $this->input->post('local');
		$whereIngreso = array(
			'id_ingreso' => $id_ingreso
		);

		$detalleUnidad = $this->detalle_ingreso_unidad_model->getDetalleUnidadDetalle($whereIngreso);


		foreach ($detalleUnidad as $detalleU) {

			$where = array(
				'id_detalle_ingreso' => $detalleU->detalle_ingreso_id
			);
			$getSoloDetalle = $this->detalle_ingreso_model->getSoloDetalle($where);

			$where = array(
				'id_producto' => $getSoloDetalle[0]['id_producto'],
				'id_unidad' => 1
			);
			$stockactual = $this->inventario_model->get_by($where);


			if ($detalleU->is_prepack == 1 || $detalleU->is_obsequio == 1) {

				$where = array(
					'detalle_ingreso_id' => $detalleU->id_detalle_ingreso
				);

				$detalle_especial = $this->detalleingreso_especial_model->getSoloDetalle($where);

				foreach ($detalle_especial as $detalleE) {

					//busco el inventario actual para esta unidad de este producto en este local
					$stockactual = $this->inventario_model->get_all_by(array(
						'id_producto' => $detalleE['producto_id_especial'],
						'id_local' => 1,
					));

					$where = array(
						'ckardexReferencia' => ANULACION_COMPRA,
						'cKardexProducto' => $detalleE['producto_id_especial'],
						'cKardexUnidadMedida' => 1,
						'cKardexIdOperacion' => $detalleE['ingreso']
					);
					$kardex = $this->kardex_model->getKardexRow($where);
					$stockanterior = json_decode($kardex->stockUManterior);

					$cantidadcajaanterior = 0;
					$i = 0;
					foreach ($stockanterior as $row) {
						if ($i == 0) {
							$cantidadcajaanterior = $row->cantidad;
						}
						$i++;
					}

					if ($stockactual[0]['cantidad'] < 1) {

						$stockactual[0]['cantidad'] = $cantidadcajaanterior;
					}

					$return_inventario = $this->inventario_model->procesarinventarioDesdeXCantidad($detalleE['producto_id_especial'],
						$local, $detalleE['unidad_id'], $detalleE['cantidad'], 'suma', $stockactual);

				}

			} else {


				$datosProducto = $this->producto_model->get_by('producto_id', $detalleU->id_producto);


				if (substr($datosProducto['producto_nombre'], 0, 3) != "OBS") {


					$ultimasuma = $datosProducto['suma_costo_caja'];
					$ultimacantidadcaja = $datosProducto['cantidad_caja'];

					//aqui no importa el valor del costo unitario
					$this->costo_promedio("ELIMINAR", $ultimasuma, $ultimacantidadcaja, $datosProducto['costo_unitario'],
						$detalleU->costo_unitario_despues, $detalleU->id_producto);

					//busco el inventario actual para esta unidad de este producto en este local
					$stockactual = $this->inventario_model->get_all_by(array(
						'id_producto' => $detalleU->id_producto,
						'id_local' => 1,
					));

					$where = array(
						'ckardexReferencia' => ANULACION_COMPRA,
						'cKardexProducto' => $detalleU->id_producto,
						'cKardexUnidadMedida' => 1,
						'cKardexIdOperacion' => $detalleU->ingreso_id
					);
					$kardex = $this->kardex_model->getKardexRow($where);
					$stockanterior = json_decode($kardex->stockUManterior);

					$cantidadcajaanterior = 0;
					$i = 0;
					foreach ($stockanterior as $row) {
						if ($i == 0) {
							$cantidadcajaanterior = $row->cantidad;
						}
						$i++;
					}

					$sumar = true;
					if ($cantidadcajaanterior < 1 && $stockactual[0]['cantidad'] < 0) {
						/*el stock actual esta e negativo*/
						$stockactual[0]['cantidad'] = $stockactual[0]['cantidad'];

					} elseif ($cantidadcajaanterior < 1 && $stockactual[0]['cantidad'] == 0) {
						/*no le sumo nada, porque tenia el sotck en 0 y actualmente esta en 0  */
						$sumar = false;
					} elseif ($cantidadcajaanterior > 0 && $stockactual[0]['cantidad'] < 0) {
						/*tenia stock mayor que 0 y actualmente esta en stock negativo */
						$stockactual[0]['cantidad'] = $cantidadcajaanterior;


					} elseif ($cantidadcajaanterior > 0 && $stockactual[0]['cantidad'] == 0) {
						/*tenia stock mayor que 0 y actualmente esta en 0 el stock*/
						$stockactual[0]['cantidad'] = $cantidadcajaanterior;
						$detalleU->cantidad = 0;

					} elseif ($cantidadcajaanterior < 1 && $stockactual[0]['cantidad'] > 0) {
						/*ya le hicieron una compra*/
						$stockactual[0]['cantidad'] = $stockactual[0]['cantidad'];

					} elseif ($cantidadcajaanterior > 0 && $stockactual[0]['cantidad'] > 0) {
						$stockactual[0]['cantidad'] = $stockactual[0]['cantidad'];
					}


					if ($sumar == true) {
						$return_inventario = $this->inventario_model->procesarinventarioDesdeXCantidad($detalleU->id_producto,
							$local, $detalleU->unidad_id, $detalleU->cantidad, 'suma', $stockactual);
					}

				} else {

				}
			}
		}


		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {

			$this->db->trans_rollback();
			return false;
		} else {

			$this->db->trans_commit();
			return $id_ingreso;
		}
	}

}
