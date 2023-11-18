<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ajusteinventario_model extends CI_Model
{
	private $table = 'ajusteinventario';

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_all()
	{
		$this->db->select('*');
		$this->db->from('ajusteinventario');
		$this->db->join('local', 'ajusteinventario.local_id=local.int_local_id');
		$this->db->where('local.local_status', 1);
		$query = $this->db->get();
		return $query->result();
	}

	function get_movimiento_inventario($local = false, $tipo = false, $tipoajuste = false, $left = false)
	{
		if ($local != false) {
			$query = $this->db->where('local_id', $local);
			if ($tipo != '') {
				$query = $this->db->where('documento_tipo', $tipo);
			}
			if ($tipoajuste != '') {
				$query = $this->db->where('documento_id', $tipoajuste);
			}

			$query = $this->db->join('documentos_inventarios', 'documentos_inventarios.documento_id=ajusteinventario.tipo_ajuste');
			$query = $this->db->join('usuario', 'usuario.nUsuCodigo=ajusteinventario.usuario', 'left');
			$query = $this->db->get('ajusteinventario');
			return $query->result();
		}
	}

	function getAjusteInventario($where)
	{
		$this->db->where($where);
		$this->db->join('documentos_inventarios', 'documentos_inventarios.documento_id=ajusteinventario.tipo_ajuste');
		$this->db->join('usuario', 'usuario.nUsuCodigo=ajusteinventario.usuario', 'left');
		$query = $this->db->get('ajusteinventario');
		return $query->row_array();
	}

	function get_ajuste_inventario($local = false, $fechadesde = false)
	{
		$query = $this->db->select('ajusteinventario.*, usuario.username');
		$query = $this->db->from('ajusteinventario');
		if ($local != false) {
			$query = $this->db->where('local_id', $local);
		}

		if ($fechadesde != false) {
			$query = $this->db->where('DATE(fecha)>=', date('Y-m-d', strtotime($fechadesde)));
		}
		$query = $this->db->join('usuario', 'usuario.nUsuCodigo=ajusteinventario.usuario', 'left');
		$query = $this->db->get();

		return $query->result();
	}

	function get_by($campo, $valor)
	{
		$this->db->where($campo, $valor);
		$query = $this->db->get('ajusteinventario');
		return $query->row_array();
	}

	function set_ajuste($campos)
	{
		$this->db->trans_start();
		$this->db->insert('ajusteinventario', $campos);
		$ultimo_id = $this->db->insert_id();
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return $ultimo_id;
	}

	function delete($where)
	{
		$this->db->trans_start();
		$this->db->where($where);
		$this->db->delete($this->table);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return TRUE;
	}

	/**
	 * @param $cont  ->es el contador de las unidades, 0 para CAja, 1 para blister, 3 para unidad
	 * @param $cantidades
	 * @param $unidades
	 * @return array
	 * metodo que se utiliza al momento de hacer el registro de Fisico.
	 *
	 * Cuando es desde la app de customers, $cantidades no es un arreglo,
	 *
	 * Cuando es desde SID, llega un arreglo de las
	 * 3 unidades en el orden caja,blister,unidad y de
	 * las 3 cantidades(que pueden estar vacios o con 0 cada indice),
	 * donde el indice $unidades[0] es la caja, y $cantidades[0] es la cantidad escrita para Caja, y asi para las demas
	 */
	function getCantidad($cont, $cantidades, $unidades)
	{
		$cantidad_retorno = 0;

		if (is_array($cantidades)) {
			if (isset($cantidades[$cont]) && $cantidades[$cont] != '') {
				$cantidad_retorno = $cantidades[$cont];
			}
		} else {
			if ($cantidades != '') {
				$cantidad_retorno = $cantidades;
			}
		}

		return array('cantidad' => $cantidad_retorno);
	}

	/**
	 * @param int $id_local
	 * @param $usuario_id
	 * @param $productos
	 * @param $unidades
	 * @param $cantidades
	 * @param $ubicacion
	 * @param $fecha
	 * @param $INVENTARIO_UBICACION_REQUERIDO
	 * @return array
	 * guarda el registro fisico, tanto desde la APP CUSTOMER, y desde SID
	 */
	function saveRegistroFisico(
		$registro_desde,
		$id_local = 1,
		$usuario_id,
		$productos,
		$unidades,
		$cantidades,
		$ubicacion,
		$fecha,
		$INVENTARIO_UBICACION_REQUERIDO,
		$imprimir = false
	) {

		$todas_unidades = $this->unidades_model->get_unidades();
		$json = array();
		$error = false;
		$cont = 0;

		if (count($productos) > 0) {
			$this->db->trans_begin();
			$ajuste = array(
				'fecha' => $fecha,
				'tipo_ajuste' => null,
				'local_id' => $id_local,
				'usuario' => $usuario_id,
			);
			$inventario = array(
				'id_local' => $id_local
			);
			$id_ajuste = $this->ajusteinventario_model->set_ajuste($ajuste);

			foreach ($productos as $producto) {
				$productoactual = $this->producto_model->get_by('producto_id', $producto);
				if ($INVENTARIO_UBICACION_REQUERIDO == 1 && is_array($ubicacion) && empty($ubicacion[$cont])) {
					$this->db->devolverSinError();
					$error = "Debe seleccionar la ubicación en el producto" . $productoactual['producto_nombre'];
					$json['error'] = $error;
					return $json;
					break;
				}

				if (sizeof($productoactual) > 0) {
					if (isset($cantidades[$cont])) {
						$unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $producto));
						$cont_unidades = 0;
						/**
						 * si entra en la siguiente condicion es porque lo estoy reigtrando desde la aplicacion
						 */
						if ($registro_desde == REG_FISICO_APP) {
							$cantidad = $this->getCantidad($cont_unidades, $cantidades[$cont], $unidades[$cont]);
							$guardar = $this->continueRegistro(
								$cantidad['cantidad'],
								$unidades[$cont],
								$unidades_producto,
								$producto,
								$id_local,
								$id_ajuste,
								$productoactual,
								$usuario_id,
								$cont
							);

							if (isset($guardar['error'])) {
								break;
							}
						} else {
							foreach ($todas_unidades as $row_todas_unidades) {
								$cantidad = $this->getCantidad($cont_unidades, $cantidades[$cont], $unidades[$cont]);
								$guardar = $this->continueRegistro(
									$cantidad['cantidad'],
									$row_todas_unidades['id_unidad'],
									$unidades_producto,
									$producto,
									$id_local,
									$id_ajuste,
									$productoactual,
									$usuario_id,
									$cont
								);
								$cont_unidades++;

								if (isset($guardar['error'])) {
									break 2;
								}
							}
						}
					}
				}
				$cont++;
			}
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$json['error'] = 'Ha ocurrido un error al ajustar el inventario';
				$where = array('id_ajusteinventario' => $id_ajuste);
				$this->delete($where);
				$this->db->trans_rollback();
			} else {
				$this->session->set_flashdata('success', 'Operación realizada exitosamente');
				$json['success'] = 'Operación realizada exitosamente';
				$json['id'] = $id_ajuste;
				if ($imprimir == true) {
					$this->inventario_model->imprimirajuste($id_ajuste);
				}
			}
		} else {
			$json['error'] = "Debe ingresar Productos";
		}
		$json['coint'] = $cont;

		return $json;
	}

	function continueRegistro(
		$cantidad,
		$id_unidad,
		$unidades_producto,
		$producto,
		$id_local,
		$id_ajuste,
		$productoactual,
		$usuario_id,
		$cont
	) {
		$error = array();
		$cantidadmovimiento = $cantidad;

		if (isset($unidad_comprada)) {
			unset($unidad_comprada);
		}
		/**
		 * aqui verifico que el producto, tiene la unidad que esta recorriendo en estos momentos.
		 */
		foreach ($unidades_producto as $row) {
			if ($row['id_unidad'] == $id_unidad) {
				$unidad_comprada = $row;
			}
		}

		if ($cantidad !== '' && $cantidad !== null && isset($unidad_comprada)) {
			$costo_neto = $this->venta_model->calcularCostos($producto, $cantidadmovimiento, $id_unidad, COSTO_UNITARIO);
			$costo_neto = $costo_neto * $cantidadmovimiento;
			//el stock de esta unidad
			$local_inventario = array('id_producto' => $producto, 'id_local' => $id_local, 'id_unidad' => $id_unidad);
			$resultado = $this->inventario_model->get_by($local_inventario);
			$buscar = is_array($resultado)? sizeof($resultado): 0;

			if ($buscar < 1) {
				$inventario['id_producto'] = $producto;
				$inventario['id_unidad'] = $id_unidad;
				$inventario['cantidad'] = 0;
				$id_inventario = $this->inventario_model->set_inventario($inventario);
				$oldcantidad = 0;
			} else {
				$inventario['cantidad'] = $cantidad;
				$id_inventario = $resultado['id_inventario'];
				$oldcantidad = $resultado['cantidad'];
			}

			$tipokardex = ($cantidad > $oldcantidad) ? ENTRADA : SALIDA;

			if ($tipokardex == ENTRADA) {
				$cantidadmovimiento = $cantidad - $oldcantidad;
			} else {
				$cantidadmovimiento = $oldcantidad - $cantidad;
			}

			if ($oldcantidad != $cantidad) {
				$new_ubicacion = isset($ubicacion[$cont]) && !empty($ubicacion[$cont]) ? !empty($ubicacion[$cont]) : null;
				$detalles = array(
					'id_ajusteinventario' => $id_ajuste,
					'id_ubicacion' => $new_ubicacion,
					'cantidad_detalle' => $cantidad,
					'id_inventario' => $id_inventario,
					'old_cantidad' => $oldcantidad,
					'costo' => isset($costo_neto) ? $costo_neto : null,
				);
				$this->ajustedetalle_model->set_ajuste_detalle($detalles);

				//actualiza la ubicacion del producto
				if (isset($ubicacion[$cont])) {
					$this->producto_model->solo_update(
						array('producto_id' => $producto),
						array('producto_ubicacion_fisica' => $new_ubicacion)
					);
				}

				//Actualiza el costo de la caja del producto en base al costo de la uniad de la transaccion
				//var_dump($datos_producto);
				if ($productoactual['control_inven'] == 1) {
					if ($tipokardex == ENTRADA) {
						$return_inventario = $this->inventario_model->sumar_inventario(
							$producto,
							$id_local,
							$id_unidad,
							$cantidadmovimiento
						);
					} else {
						$return_inventario = $this->inventario_model->restar_inventario(
							$producto,
							$id_local,
							$id_unidad,
							$cantidadmovimiento
						);
					}
				}
				if (isset($return_inventario['error'])) {
					//valido si existe algun error en el $return_onventario
					$this->db->devolverSinError();
					$error['error'] = $return_inventario['error'] . $productoactual['producto_nombre'];
					return $error;
				}

				/****INSERTO EL KARDEX**/
				$this->kardex_model->set_kardex(
					$producto,
					$id_local,
					$id_unidad,
					$cantidadmovimiento,
					'REGISTRO DE FISICOS',
					$usuario_id,
					isset($costo_neto) ? $costo_neto : null,
					$id_ajuste,
					NULL,
					NULL,
					NULL,
					$tipokardex,
					json_encode($return_inventario['stockviejo_array']),
					json_encode($return_inventario['stocknuevo_array']),
					NULL,
					$productoactual['porcentaje_impuesto'],
					$productoactual['costo_unitario']
				);
			}
		}
		return $error;
	}

	/**
	 * @return mixed
	 * este método solo es usado para movimientos diarios, YA NO SE USA para REGISTRO DE FISICOS,
	 * para registro de fisicos, es: saveRegistroFisico
	 */
	function guardar()
	{
		$this->db->trans_begin();
		$INVENTARIO_UBICACION_REQUERIDO = $this->session->userdata('INVENTARIO_UBICACION_REQUERIDO');
		$id_productos = $this->input->post('id_producto');
		$id_local = $this->input->post('local');
		$tipo_ajuste = json_decode($this->input->post('tipodocumento'));

		$ajuste = array(
			'fecha' => date('Y-m-d', strtotime($this->input->post('fecha'))) . " " . date('H:i:s'),
			'tipo_ajuste' => !empty($tipo_ajuste) ? $tipo_ajuste->documento_id : null,
			'local_id' => $this->input->post('local'),
			'usuario' => $this->session->userdata('nUsuCodigo'),
		);
		$inventario = array(
			'id_local' => $this->input->post('local')
		);
		$id_ajuste = $this->ajusteinventario_model->set_ajuste($ajuste);
		$error = false;

		for ($i = 0; $i < count($id_productos); $i++) {
			$id_producto = $id_productos[$i];
			$datos_producto = $this->producto_model->get_by('producto_id', $id_producto);
			$id_unidades = $this->input->post('unidad_' . $id_productos[$i]);
			$cantidad_unidades = $this->input->post('cantidad_' . $id_productos[$i]);
			$ubicacion = $this->input->post('ubicacion_producto_' . $id_productos[$i]);

			if ($INVENTARIO_UBICACION_REQUERIDO == 1 && empty($ubicacion)) {
				$this->db->devolverSinError();
				$error = "Debe seleccionar la ubicación en el producto" . $datos_producto['producto_nombre'];
				$json['error'] = $error;
				return $json;
				break;
			}
			$total_stock_minimas = $this->inventario_model->stockUnidadesMinimas($id_producto, $this->input->post('local'));
			$unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $id_producto));
			$totalentabla = 0;

			for ($j = 0; $j < count($id_unidades); $j++) {
				if (isset($unidad_comprada)) {
					unset($unidad_comprada);
				}
				if ((isset($cantidad_unidades[$j]) && !empty($tipo_ajuste)) ||
					(empty($tipo_ajuste))
				) {
					$id_unidad = $id_unidades[$j];
					/**
					 * $cantidad_unidades[$j] debe tener algun valor distinto de vacio y mayor o igual a 0,
					 * si no es asi, entonces $cantidad=false, para que abajo no haga el proceso de guardar en kardex ni sumar
					 * inventario
					 */
					$cantidad = isset($cantidad_unidades[$j]) && $cantidad_unidades[$j] != '' && !empty($cantidad_unidades[$j]) ? $cantidad_unidades[$j] : false;
					$cantidadmovimiento = $cantidad;
					if (empty($tipo_ajuste)) {
						//REGISTRO FISICO
						//ESTO QUE RETORNA AQUI ES EL COSTO UNITARIO DE L AUNIDAD QUE SE ESTA MOVIENDO
						$costo_neto = $this->venta_model->calcularCostos($id_producto, $cantidadmovimiento, $id_unidades[$j], COSTO_UNITARIO);

						// Y AQUI MULTPLICA POR LA CANTIDAD , OSEA EL TOTAL DE LAS CANTIDADES
						$costo_neto = $costo_neto * $cantidadmovimiento;
						//OJO, ajusedetalle almacena el costo ttoal por item , es decir, costo unitario por cantidad
					} else {
						//MOVIMIENTO DIARIO
						$costo = $this->input->post('costo_' . $id_productos[$i]);
					}
					//Verifico si el producto paneja la unidad.
					if (empty($tipo_ajuste)) {
						if ($cantidad == "" || empty($cantidad)) {
							$cantidad = 0;
						}
					}
					// aqui busco el objeto de la uidad que compre
					foreach ($unidades_producto as $row) {
						if ($row['id_unidad'] == $id_unidad) {
							$unidad_comprada = $row;
						}
					}
					if (
						$cantidad !== false &&
						(!empty($cantidad) || $cantidad == 0) && isset($unidad_comprada)
					) {
						//el stock de esta unidad
						$local_inventario = array('id_producto' => $id_producto, 'id_local' => $id_local, 'id_unidad' => $id_unidad);
						$resultado = $this->inventario_model->get_by($local_inventario);

						//voy sumando las unidades minimas
						$totalentabla += $this->inventario_model->covertUnidadMinima(
							$unidades_producto,
							$id_unidad,
							$cantidad
						);

						$buscar = sizeof($resultado);
						$tipokardex = "";
						if (!empty($tipo_ajuste)) {
							$tipokardex = $tipo_ajuste->documento_tipo;
						}
						//  var_dump($resultado);
						if ($buscar < 1) {
							$inventario['id_producto'] = $id_producto;
							$inventario['id_unidad'] = $id_unidad;
							$inventario['cantidad'] = 0;
							$id_inventario = $this->inventario_model->set_inventario($inventario);
							$oldcantidad = 0;
						} else {
							$inventario['cantidad'] = $cantidad;
							$id_inventario = $resultado['id_inventario'];
							$oldcantidad = $resultado['cantidad'];
						}
						if (!empty($tipo_ajuste)) {
							$costo_neto = $costo[$j];
						}
						/**
						 * como el costo es opcional, si es =='', entonces lo coloco en 0 por defecto
						 */
						$costo_neto = $costo_neto == '' ? 0 : $costo_neto;
						$detalles = array(
							'id_ajusteinventario' => $id_ajuste,
							'id_ubicacion' => $ubicacion,
							'cantidad_detalle' => $cantidad,
							'id_inventario' => $id_inventario,
							'old_cantidad' => $oldcantidad,
							'costo' => isset($costo_neto) ? $costo_neto : null,
						);
						$this->ajustedetalle_model->set_ajuste_detalle($detalles);
						if (empty($tipo_ajuste)) { //ESTO ES UN REGISTRO DE FISICO TOCA CALCULAR SI ES ENTRADA O SALID
							$tipokardex = ($cantidad > $oldcantidad) ? ENTRADA : SALIDA;
							log_message('ERROR', "CATIDAD:" . $cantidad);
							log_message('ERROR', "RESULTADO:" . $oldcantidad);
							log_message('ERROR', "TIPO:" . $tipokardex);
							if ($tipokardex == ENTRADA) {
								$cantidadmovimiento = $cantidad - $oldcantidad;
							} else {
								$cantidadmovimiento = $oldcantidad - $cantidad;
							}
						} else {
							$tipokardex = $tipo_ajuste->documento_tipo;
						}

						//actualiza la ubicacion del producto
						if (!empty($ubicacion)) {
							$this->producto_model->solo_update(
								array('producto_id' => $id_producto),
								array('producto_ubicacion_fisica' => $ubicacion)
							);
						}

						//Actualiza el costo de la caja del producto en base al costo de la uniad de la transaccion
						if (!empty($tipo_ajuste)) {
							$this->inventario_model->update_costo_unitario(
								$costo_neto,
								$id_producto,
								$id_unidades[$j],
								$cantidad
							);
						}

						//var_dump($datos_producto);
						if ($datos_producto['control_inven'] == 1) {
							if ($tipokardex == ENTRADA) {
								$return_inventario = $this->inventario_model->sumar_inventario(
									$id_producto,
									$id_local,
									$id_unidad,
									empty($tipo_ajuste) ? $cantidadmovimiento : $cantidad
								);
							} else {
								$return_inventario = $this->inventario_model->restar_inventario(
									$id_producto,
									$id_local,
									$id_unidad,
									empty($tipo_ajuste) ? $cantidadmovimiento : $cantidad
								);
							}
						} else {
							$return_inventario = $this->inventario_model->get_stock_array($id_producto, $id_local, $id_unidad);
						}

						if (isset($return_inventario['error'])) {
							//valido si existe algun error en el $return_onventario
							$this->db->devolverSinError();
							$error['error'] = $return_inventario['error'] . $datos_producto['producto_nombre'];
							return $error;
							break 2;
						}

						/****INSERTO EL KARDEX**/
						if (empty($tipo_ajuste)) {
							$this->kardex_model->set_kardex(
								$id_producto,
								$id_local,
								$id_unidad,
								$cantidadmovimiento,
								'REGISTRO DE FISICOS', //MOSCA CON ESTO, HAY QUE CONSULTAR CON LIKE PORQUE ANTES SE GUARDABA CON UN ESPACIO EN BLANCO AL FINAL
								$this->session->userdata('nUsuCodigo'),
								$costo_neto, //ESTE COSTO NETO ESTA GUARDANDO EL TOTAL CANTIDAD POR COSTO UNITARIO, LO CUAL ESTA MAL PERO YA QUEDO ASI
								$id_ajuste,
								NULL,
								NULL,
								NULL,
								$tipokardex,
								json_encode($return_inventario['stockviejo_array']),
								json_encode($return_inventario['stocknuevo_array']),
								NULL,
								$datos_producto['porcentaje_impuesto'],
								$datos_producto['costo_unitario']
							);
						} else { // AQUI ES CUANDO ES UN REGISTRO DE MOVIMIENTO
							$this->kardex_model->set_kardex(
								$id_producto,
								$id_local,
								$id_unidad,
								$cantidadmovimiento,
								$tipo_ajuste->documento_nombre,
								$this->session->userdata('nUsuCodigo'),
								isset($costo_neto) ? $costo_neto : null, //ESTE COSTO NETO ESTA GUARDANDO EL TOTAL CANTIDAD POR COSTO UNITARIO, LO CUAL ESTA MAL PERO YA QUEDO ASI
								$id_ajuste,
								NULL,
								NULL,
								NULL,
								$tipo_ajuste->documento_tipo,
								json_encode($return_inventario['stockviejo_array']),
								json_encode($return_inventario['stocknuevo_array']),
								NULL,
								$datos_producto['porcentaje_impuesto'],
								$datos_producto['costo_unitario']
							);
						}
					}
				}
			}

			if (
				!empty($tipo_ajuste) && $tipo_ajuste->documento_tipo == SALIDA and $datos_producto['control_inven'] == 1
				and $totalentabla > $total_stock_minimas
			) {
				$error = "Ha ingresado una cantidad superior al stock actual, para el producto " . $datos_producto['producto_nombre'];
				$json['error'] = $error;
				return $json;
				break;
			}
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$json['error'] = 'Ha ocurrido un error al ajustar el inventario';
			$where = array('id_ajusteinventario' => $id_ajuste);
			$this->delete($where);
			$this->db->trans_rollback();
		} else {
			$this->session->set_flashdata('success', 'Operación realizada exitosamente');
			$json['success'] = 'Operación realizada exitosamente';
			$json['id'] = $id_ajuste;
		}
		return $json;
	}

	function anularMovim()
	{
		$this->load->model('documento_inventario/documento_inventario_model');
		$this->db->trans_begin();
		$id_ajusteinventario = $this->input->post('id_ajusteinventario');
		$ajustedetalle = $this->ajustedetalle_model->get_by($where = array('id_ajusteinventario' => $id_ajusteinventario), true);
		$ajusteinventario = $this->getAjusteInventario($where = array('id_ajusteinventario' => $id_ajusteinventario));
		$tipo_ajuste = $this->documento_inventario_model->get_by(array('documento_id' => $ajusteinventario['tipo_ajuste']));

		for ($i = 0; $i < count($ajustedetalle); $i++) {
			$inventario_id = $this->inventario_model->get_by(array('id_inventario' => $ajustedetalle[$i]->id_inventario));
			$id_producto = $inventario_id['id_producto'];
			$id_local = $ajusteinventario['local_id'];
			$id_ajuste = $id_ajusteinventario;
			$ubicacion = $ajustedetalle[$i]->id_ubicacion;
			$datos_producto = $this->producto_model->get_by('producto_id', $id_producto);
			$total_stock_minimas = $this->inventario_model->stockUnidadesMinimas($id_producto, $ajusteinventario['local_id']);

			$totalentabla = 0;
			$id_unidad = $inventario_id['id_unidad'];
			$cantidad = $ajustedetalle[$i]->cantidad_detalle;
			$cantidadmovimiento = $cantidad;
			$costo = $ajustedetalle[$i]->costo;

			//Verifico si el producto maneja la unidad.
			if (empty($tipo_ajuste)) {
				if ($cantidad == "" || empty($cantidad)) {
					$cantidad = 0;
				}
			}
			$unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $id_producto));
			// aqui busco el objeto de la uidad que compre
			foreach ($unidades_producto as $row) {
				if ($row['id_unidad'] == $id_unidad) {
					$unidad_comprada = $row;
				}
			}
			if ((!empty($cantidad) || $cantidad == 0) && isset($unidad_comprada)) {
				//el stock de esta unidad
				$local_inventario = array('id_producto' => $id_producto, 'id_local' => $id_local, 'id_unidad' => $id_unidad);
				$resultado = $this->inventario_model->get_by($local_inventario);

				//voy sumando las unidades minimas
				$totalentabla += $this->inventario_model->covertUnidadMinima(
					$unidades_producto,
					$id_unidad,
					$cantidad
				);
				$buscar = sizeof($resultado);

				if ($buscar < 1) {
					$inventario['id_producto'] = $id_producto;
					$inventario['id_unidad'] = $id_unidad;
					$inventario['cantidad'] = 0;
				} else {
					$inventario['cantidad'] = $cantidad;
				}
				$costo_neto = $costo;

				$where = array(
					'id_ajustedetalle' => $ajustedetalle[$i]->id_ajustedetalle
				);
				// $this->ajustedetalle_model->delete($where);
				$tipokardex = $tipo_ajuste['documento_tipo'];

				//actualiza la ubicacion del producto
				if (!empty($ubicacion)) {
					$this->producto_model->solo_update(
						array('producto_id' => $id_producto),
						array('producto_ubicacion_fisica' => $ubicacion)
					);
				}

				//Actualiza el costo de la caja del producto en base al costo de la uniad de la transaccion
				if (!empty($tipo_ajuste)) {
					$this->inventario_model->update_costo_unitario(
						$costo_neto,
						$id_producto,
						$id_unidad,
						$cantidad
					);
				}

				if ($datos_producto['control_inven'] == 1) {
					if ($tipokardex == ENTRADA) {
						//si el movimiento fue entrada, ahora le resto
						$return_inventario = $this->inventario_model->restar_inventario(
							$id_producto,
							$id_local,
							$id_unidad,
							empty($tipo_ajuste) ? $cantidadmovimiento : $cantidad
						);
					} else {
						//si fue salida, entonces le sumo al stock
						$return_inventario = $this->inventario_model->sumar_inventario(
							$id_producto,
							$id_local,
							$id_unidad,
							empty($tipo_ajuste) ? $cantidadmovimiento : $cantidad
						);
					}
				} else {
					$return_inventario = $this->inventario_model->get_stock_array($id_producto, $id_local, $id_unidad);
				}

				if (isset($return_inventario['error'])) {
					//valido si existe algun error en el $return_onventario
					$this->db->devolverSinError();
					$error['error'] = $return_inventario['error'] . $datos_producto['producto_nombre'];
					return $error;
					break 1;
				}

				$tipo_mov = SALIDA;
				if ($tipo_ajuste['documento_tipo'] == SALIDA) {
					$tipo_mov = ENTRADA;
				}

				/****INSERTO EL KARDEX**/
				// AQUI ES CUANDO ES UN REGISTRO DE MOVIMIENTO
				$this->kardex_model->set_kardex(
					$id_producto,
					$id_local,
					$id_unidad,
					$cantidadmovimiento,
					ANULACION_MOV_INV,
					$this->session->userdata('nUsuCodigo'),
					isset($costo_neto) ? $costo_neto : null, // AL IGUAL QUE EN LOS AJUSTES Y MOVIMEINTOS DIARIOS AQUI ENTRA EL TOTAL Y NO EL UNITARIO
					$id_ajuste,
					NULL,
					NULL,
					NULL,
					$tipo_mov,
					json_encode($return_inventario['stockviejo_array']),
					json_encode($return_inventario['stocknuevo_array']),
					NULL,
					$datos_producto['porcentaje_impuesto'],
					$datos_producto['costo_unitario']
				);
			}

			if (
				!empty($tipo_ajuste) && $tipo_ajuste['documento_tipo'] == SALIDA and $datos_producto['control_inven'] == 1
				and $totalentabla > $total_stock_minimas
			) {
				$error = "Ha ingresado una cantidad superior al stock actual, para el producto " . $datos_producto['producto_nombre'];
				$json['error'] = $error;
				return $json;
				break;
			}
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$json['error'] = 'Ha ocurrido un error al ajustar el inventario';
			$where = array('id_ajusteinventario' => $id_ajuste);
			$this->delete($where);
			$this->db->trans_rollback();
		} else {
			$this->session->set_flashdata('success', 'Operación realizada exitosamente');
			$json['success'] = 'Operación realizada exitosamente';
			$json['id'] = $id_ajuste;
		}
		return $json;
	}
}
