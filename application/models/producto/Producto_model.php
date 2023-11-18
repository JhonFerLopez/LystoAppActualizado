<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class producto_model extends CI_Model
{
	private $tabla = 'producto';
	private $id = 'producto_id';
	private $status = 'producto_estatus';
	private $nombre = 'producto_nombre';
	private $descripcion = 'producto_descripcion';
	private $proveedor = 'producto_proveedor';
	private $grupo = 'produto_grupo';
	private $stock_min = 'producto_stockminimo';
	private $impuesto = 'producto_impuesto';
	private $producto_activo = 'producto_activo';
	private $_producto;

	function __construct()
	{
		parent::__construct();
		$this->_producto = new ProductoElo();
		$this->load->model('producto_barra/producto_barra_model');
		$this->load->model('producto_componente/producto_componente_model');
		$this->load->model('producto/paquete_has_prod_model');
	}

	function get_all_by_local_producto($local, $precio)
	{
		$this->db->distinct();
		$this->db->select('unidades_has_precio.precio, 
			producto.producto_nombre as nombre, ' . $this->tabla . '.*, 
			unidades.nombre_unidad, inventario.id_inventario, 
			inventario.id_local, inventario.cantidad, inventario.fraccion,
			grupos.nombre_grupo, proveedor.proveedor_nombre, 
			impuestos.nombre_impuesto,grupos.id_grupo');
		$this->db->from($this->tabla);
		$this->db->join('unidades_has_precio', 'unidades_has_precio.id_producto=producto.producto_id', 'left');
		$this->db->join('grupos', 'grupos.id_grupo=producto.' . $this->grupo, 'left');
		$this->db->join('proveedor', 'proveedor.id_proveedor=producto.' . $this->proveedor, 'left');
		$this->db->join('impuestos', 'impuestos.id_impuesto=producto.' . $this->impuesto, 'left');
		$this->db->join('(SELECT DISTINCT inventario.id_producto, 
			inventario.id_inventario, inventario.cantidad, inventario.fraccion, 
			inventario.id_local FROM inventario 
			WHERE inventario.id_local=' . $local . ' 
			ORDER by id_inventario DESC ) as inventario', 
			'inventario.id_producto=producto.' . $this->id, 'left');
		$this->db->join('ubicacion_fisica', 'ubicacion_fisica.ubicacion_id = producto.producto_ubicacion_fisica', 'left');

		$this->db->group_by('producto_id');
		$this->db->order_by('nombre_grupo', 'asc');

		$where_in = array('0', '1');
		$where = array(
			$this->status => 1
		);
		$this->db->where_in($this->status, $where_in);
		$this->db->where($where);
		if ($precio == 1 or $precio == 2) {
			$this->db->where('precio > 0');
			$this->db->where('unidades_has_precio.id_unidad IS NOT NULL');
		}
		if ($precio == 0) {
			$this->db->where('precio < 1');
			$this->db->where('precio < 1  OR unidades_has_precio.id_unidad ="" OR unidades_has_precio.id_unidad IS NULL');
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

	function solo_insertar($datos)
	{
		$this->db->trans_start();
		$this->db->insert($this->tabla, $datos);
		$id_producto = $this->db->insert_id();
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return $id_producto;
	}

	function solo_update($where, $datos)
	{
		$this->db->trans_start();
		$this->db->where($where);
		$this->db->update($this->tabla, $datos);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
	}

	function insertar(
		$producto
		,$medidas
		,$unidades
		,$codigos_barra
		,$stock_minimo
		,$stock_maximo
		,$componentes
		,$productos_paquete
	){
		$this->load->model('producto_barra/producto_barra_model');
		/*$validar_nombre = sizeof($this->get_by('producto_nombre', $producto['producto_nombre']));
        if ($validar_nombre < 1) {*/
		$this->db->trans_start();
		$this->db->insert($this->tabla, $producto);
		$id_producto = $this->db->insert_id();

		/********************guardo los grupos*******************/
		$grupos = $this->input->post('input_grupo');
		$this->guardarGruposProducto($grupos, $id_producto);
		/***************************************/

		/*guardo los codigos de barra*/
		$this->producto_barra_model->guardar($codigos_barra, $id_producto);
		$this->producto_componente_model->guardar($componentes, $id_producto);
		if ($id_producto) {
			$this->paquete_has_prod_model->guardar($productos_paquete, $id_producto);
		}

		$countunidad = 0;
		/*busco todas las condiciones de pago ue ahora van a reemplazar a la tabla precios*/
		$this->db->where('status_condiciones', 1);
		$query = $this->db->get('condiciones_pago');
		$condiciones_de_pago = $query->result_array();
		$unidad_has_precio = array();

		//medidas son las unidades de medida que estan siendo enviadas desde el form
		if ($medidas != false) {
			foreach ($medidas as $medida) {
				if (isset($unidades[$countunidad]) and $unidades[$countunidad] != 0 and $unidades[$countunidad] != "") {
					$unidad_has_producto = array(
						"id_unidad" => $medidas[$countunidad],
						"producto_id" => $id_producto,
						"unidades" => $unidades[$countunidad],
						// "orden" => $countunidad + 1,
						"stock_minimo" => $stock_minimo[$medidas[$countunidad]],
						"stock_maximo" => $stock_maximo[$medidas[$countunidad]],
					);

					$countprecio = 0;

					$precios_valor = $this->input->post('precio_valor_' . $countunidad);
					$precios_id = $this->input->post('precio_id_' . $countunidad);
					$utilidades = $this->input->post('utilidad_' . $countunidad);
					$precio_minimo = $this->input->post('precio_minimo_' . $medidas[$countunidad]);
					$precio_maximo = $this->input->post('precio_maximo_' . $medidas[$countunidad]);

					foreach ($condiciones_de_pago as $condicion_pago) {
						if (isset($precios_id[$countprecio])) {
							$unidad_has_precio = array(
								"id_condiciones_pago" => $precios_id[$countprecio],
								"id_unidad" => $medidas[$countunidad],
								"id_producto" => $id_producto,
								"precio" => $precios_valor[$countprecio] != false && $precios_valor[$countprecio] != "" ?
									$precios_valor[$countprecio] : null
							);

							if ($utilidades[$countprecio] != false && $utilidades[$countprecio] != "") {
								$unidad_has_precio['utilidad'] = $utilidades[$countprecio];
							}

							if (is_array($precio_minimo)) {
								if ($precio_minimo[$condicion_pago['id_condiciones']] != false && $precio_minimo[$condicion_pago['id_condiciones']] != "") {
									$unidad_has_precio['precio_minimo'] = $precio_minimo[$condicion_pago['id_condiciones']];
								}
							}
							if (is_array($precio_maximo)) {
								if ($precio_maximo[$condicion_pago['id_condiciones']] != false && $precio_maximo[$condicion_pago['id_condiciones']] != "") {
									$unidad_has_precio['precio_maximo'] = $precio_maximo[$condicion_pago['id_condiciones']];
								}
							}
							$this->db->insert('unidades_has_precio', $unidad_has_precio);
						}
						$countprecio++;
					}
					$this->db->insert('unidades_has_producto', $unidad_has_producto);
				}
				$countunidad++;
			}
		}

		$this->updateCostosByProduct($id_producto, $producto['costo_unitario']);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return $id_producto;
	}

	function getCorrelativo()
	{
		$this->db->select_max('producto_codigo_interno');
		$result = $this->db->get($this->tabla)->row();
		return $result;
	}

	function guardarGruposProducto($grupos, $producto_id)
	{
		//elimino los grupos
		$where = array(
			'producto_id' => $producto_id
		);
		$this->grupos_model->delete_productohasgrupo($where);
		if ($grupos != NULL && $grupos != '' && count($grupos) > 0) {

			foreach ($grupos as $grupo) {
				if ($grupo != "") {
					$guardargrupo = array(
						'producto_id' => $producto_id,
						'grupo_id' => $grupo
					);
					$this->grupos_model->insertproductohasgrupo($guardargrupo);
				}
			}
		}
	}

	function update($producto, $medidas, $unidades, $codigos_barra, $stock_minimo, $stock_maximo, $componentes, $productos_paquete)
	{
		$this->load->model('producto_barra/producto_barra_model');
		$this->db->trans_start();
		$this->db->where($this->id, $producto['producto_id']);
		$this->db->update($this->tabla, $producto);

		$where = array(
			'producto_id' => $producto['producto_id']
		);
		$this->producto_barra_model->delete($where);
		$this->producto_barra_model->guardar($codigos_barra, $producto['producto_id']);
		$this->producto_componente_model->delete($where);
		$this->paquete_has_prod_model->delete(array(
			'paquete_id' => $producto['producto_id']
		));

		if ($producto['is_paquete']) {
			$this->paquete_has_prod_model->guardar($productos_paquete, $producto['producto_id']);
		}
		$this->producto_componente_model->guardar($componentes, $producto['producto_id']);

		$countunidad = 0;
		/*busco todas las condiciones de pago ue ahora van a reemplazar a la tabla precios*/
		$this->db->where('status_condiciones', 1);
		$query = $this->db->get('condiciones_pago');
		$condiciones_de_pago = $query->result_array();
		$id_producto = $producto['producto_id'];

		$this->db->where('producto_id', $id_producto);
		$query = $this->db->get('unidades_has_producto');
		$unidadesexistentes = $query->result_array();

		if ($medidas != false) {
			foreach ($medidas as $medida) {
				if (isset($unidades[$countunidad]) and $unidades[$countunidad] != 0 and $unidades[$countunidad] != "") {
					if (isset($medidas[$countunidad])) {
						$unidad_has_producto = array(
							"id_unidad" => $medidas[$countunidad],
							"producto_id" => $id_producto,
							"unidades" => $unidades[$countunidad],
							//  "orden" => $countunidad + 1,
							"stock_minimo" => $stock_minimo[$medidas[$countunidad]],
							"stock_maximo" => $stock_maximo[$medidas[$countunidad]]
						);

						$this->db->where('id_unidad', $medidas[$countunidad]);
						$this->db->where('producto_id', $id_producto);
						$query = $this->db->get('unidades_has_producto');
						$unidadexiste = $query->num_rows();

						if ($unidadexiste < 1) {
							$this->db->insert('unidades_has_producto', $unidad_has_producto);
						} else {
							$this->db->where('id_unidad', $medidas[$countunidad]);
							$this->db->where('producto_id', $id_producto);
							$this->db->update('unidades_has_producto', $unidad_has_producto);
						}

						$this->updateCostosByProduct($id_producto, $producto['costo_unitario']);
						$countprecio = 0;

						$precios_valor = $this->input->post('precio_valor_' . $countunidad);
						$precios_id = $this->input->post('precio_id_' . $countunidad);
						$utilidades = $this->input->post('utilidad_' . $countunidad);

						$precio_minimo = $this->input->post('precio_minimo_' . $medidas[$countunidad]);
						$precio_maximo = $this->input->post('precio_maximo_' . $medidas[$countunidad]);

						foreach ($condiciones_de_pago as $condicion_pago) {

							if (isset($precios_id[$countprecio])) {
								$unidad_has_precio = array();

								$unidad_has_precio = array(
									"id_condiciones_pago" => $precios_id[$countprecio],
									"id_unidad" => $medidas[$countunidad],
									"id_producto" => $id_producto,
									"precio" => $precios_valor[$countprecio] != false && $precios_valor[$countprecio] != "" ?
										$precios_valor[$countprecio] : null
								);

								$utilidades[$countprecio] != false && $utilidades[$countprecio] != ""
									? $unidad_has_precio['utilidad'] = $utilidades[$countprecio] : null;

								//Precio minimo
								if (
										isset($condicion_pago['id_condiciones'], $precio_minimo[$condicion_pago['id_condiciones']])
										&& $precio_minimo[$condicion_pago['id_condiciones']] !== false
										&& $precio_minimo[$condicion_pago['id_condiciones']] !== ""
								) {
										$unidad_has_precio['precio_minimo'] = $precio_minimo[$condicion_pago['id_condiciones']];
								}
								//Precio maximo
								if (
									isset($condicion_pago['id_condiciones'], $precio_maximo[$condicion_pago['id_condiciones']])
									&& $precio_maximo[$condicion_pago['id_condiciones']] !== false
									&& $precio_maximo[$condicion_pago['id_condiciones']] !== ""
								) {
										$unidad_has_precio['precio_maximo'] = $precio_maximo[$condicion_pago['id_condiciones']];
								}

								$this->db->where('id_condiciones_pago', $precios_id[$countprecio]);
								$this->db->where('id_unidad', $medidas[$countunidad]);
								$this->db->where('id_producto', $id_producto);
								$query = $this->db->get('unidades_has_precio');
								$existeprecio = $query->num_rows();

								if ($existeprecio < 1) {
									$this->db->insert('unidades_has_precio', $unidad_has_precio);
								} else {
									$this->db->where('id_condiciones_pago', $precios_id[$countprecio]);
									$this->db->where('id_unidad', $medidas[$countunidad]);
									$this->db->where('id_producto', $id_producto);
									$this->db->update('unidades_has_precio', $unidad_has_precio);
								}
							}
							$countprecio++;
						}
					}
				} else {
					$this->db->where('id_unidad', $medidas[$countunidad]);
					$this->db->where('producto_id', $id_producto);
					$this->db->delete('unidades_has_producto');

					$this->db->where('id_unidad', $medidas[$countunidad]);
					$this->db->where('id_producto', $id_producto);
					$this->db->delete('unidades_has_precio');
				}
				$countunidad++;
			}
		}

		foreach ($unidadesexistentes as $ue) {
			$borrarunidad = TRUE;
			$countunidad = 0;
			if ($medidas != false) {
				foreach ($medidas as $medida) {
					if (isset($medidas[$countunidad])) {
						if ($ue['id_unidad'] == $medidas[$countunidad] && $ue['producto_id'] == $id_producto) {
							$borrarunidad = FALSE;
						}
					}
					$countunidad++;
				}
			}

			if ($borrarunidad == TRUE or $medidas == false) {
				$this->db->where('id_unidad', $ue['id_unidad']);
				$this->db->where('producto_id', $id_producto);
				$this->db->delete('unidades_has_producto');

				$this->db->where('id_unidad', $ue['id_unidad']);
				$this->db->where('id_producto', $id_producto);
				$this->db->delete('unidades_has_precio');
			}
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return TRUE;
	}

	function guardarParamRap($post)
	{
		try {
			ModelTransaction::beginTransaction();
			$todasUnidades = $this->unidades_model->get_unidades();
			$condiciones = $this->condiciones_pago_model->get_all();

			foreach (json_decode($post['lst_producto']) as $row) {
				if ($row != null && $row->producto_id) {
					$producto_id = $row->producto_id;
					$producto_before = $this->_producto->where('producto_id', $row->producto_id)->with($this->_producto->allTablesRelations())->first();

					//para guardar el contenido interno
					if (isset($row->contenido_interno) && count($row->contenido_interno) > 0) {
						$contenido_interno_viejo = $producto_before->contenido_interno;
						//elimino para guardar
						if ($producto_before->contenido_interno->count() > 0) {
							$producto_before->contenido_interno()->detach();
						}
						for ($i = 0; $i < count($row->contenido_interno); $i++) {
							if ($row->contenido_interno[$i]->cantidad != "") {
								$datos = array(
									'id_unidad' => $row->contenido_interno[$i]->id,
									'unidades' => $row->contenido_interno[$i]->cantidad
								);
								foreach ($contenido_interno_viejo as $contenido_interno_temp) {
									if ($contenido_interno_temp->pivot->id_unidad == $row->contenido_interno[$i]->id) {
										$datos['stock_minimo'] = $contenido_interno_temp->pivot->stock_minimo;
										$datos['stock_maximo'] = $contenido_interno_temp->pivot->stock_maximo;
										$datos['costo'] = $contenido_interno_temp->pivot->costo;
									}
								}
								$producto_before->contenido_interno()->attach($producto_id, $datos);
							}
						}
					}
					
					if (isset($row->precios) && is_object($row->precios)) {
						for ($i = 0; $i < count($condiciones); $i++) {
							for ($m = 0; $m < count($todasUnidades); $m++) {
								if (isset($row->precios->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']})) {
									$preciosProducto = array();
									$utilidad = $row->precios->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->utilidad == "" ? null :
										$row->precios->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->utilidad;
									$precio = $row->precios->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->utilidad == "" ? null :
										$row->precios->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio;

									$buscarPrecios = array(
										'id_unidad' => $todasUnidades[$m]['id_unidad'],
										'id_condiciones_pago' => $condiciones[$i]['id_condiciones']
									);
									/**
									 * busco si ya habia un precio para la condicion $buscarPrecios
									 */
									$preciosProducto = $producto_before->precios()->where($buscarPrecios)->first();

									if ($utilidad == null && $precio == null) {
										//quiere decir que llegaron los dos vacios, por lo que los elimino
										$preciosProducto->delete();
									} elseif ($preciosProducto !== NULL) {
										//actualizo, si ya tiene la unidad
										$preciosProducto->id_unidad = $todasUnidades[$m]['id_unidad'];
										$preciosProducto->id_condiciones_pago = $condiciones[$i]['id_condiciones'];
										$preciosProducto->utilidad = $utilidad;
										$preciosProducto->precio = $precio;
										$preciosProducto->save();
									} else {
										//si no tiene esta unidad, la inserto
										$datos = array(
											'id_unidad' => $todasUnidades[$m]['id_unidad'],
											'id_producto' => $producto_id,
											'id_condiciones_pago' => $condiciones[$i]['id_condiciones'],
											'utilidad' => $utilidad,
											'precio' => $precio
										);
										$producto_before->precios()->create($datos);
									}
								}
							}
						}
					}

					$arrProd = array();
					if (isset($row->codigos_barra) && count($row->codigos_barra) > 0) {
						$producto_before->codigos_barra()->delete();
						foreach ($row->codigos_barra as $barra) {
							if ($barra != "") {
								$producto_before->codigos_barra()->create(array('producto_id' => $producto_id, 'codigo_barra' => $barra));
							}
						}
					}

					if (isset($row->comision)) {
						$producto_before->producto_comision = $row->comision == "" ? null : $row->comision;
					}

					if (isset($row->precio_abierto) && $row->precio_abierto != "") {
						if ($row->precio_abierto->is_enabled == true) {
							$producto_before->precio_abierto = 1;
							for ($i = 0; $i < count($condiciones); $i++) {
								for ($m = 0; $m < count($todasUnidades); $m++) {
									if (isset($row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']})) {
										$preciosProducto = array();
										$preciominimo = null;
										$preciomaximo = null;

										if (
											isset($row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_minimo)
											and
											$row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_minimo == ""
										) {
											$preciominimo = "";
										} elseif (
											isset($row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_minimo) and
											$row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_minimo != ""
										) {
											$preciominimo = $row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_minimo;
										}

										if (
											isset($row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_maximo)
											and $row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_maximo == ""
										) {
											$preciomaximo = "";
										} elseif (
											isset($row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_maximo) and
											$row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_maximo != ""
										) {
											$preciomaximo = $row->precio_abierto->{$condiciones[$i]['id_condiciones']}->{$todasUnidades[$m]['id_unidad']}->precio_maximo;
										}
										$buscarPrecios = array();
										$buscarPrecios = array(
											'id_unidad' => $todasUnidades[$m]['id_unidad'],
											'id_condiciones_pago' => $condiciones[$i]['id_condiciones']
										);
										/**
										 * busco si ya habia un precio para la condicion $buscarPrecios
										 */
										$preciosProducto = $producto_before->precios()->firstOrNew($buscarPrecios);
										$datos = array();

										if ($preciominimo === null) {
										} else {
											$preciosProducto->precio_minimo = $preciominimo == "" ? null : $preciominimo;
										}

										if ($preciomaximo === null) {
										} else {
											$preciosProducto->precio_maximo = $preciomaximo == "" ? null : $preciomaximo;
										}

										/**
										 * Pregunto, si al menos uno de los dos precios es distinto de null, para guardarlo
										 */
										if ($preciosProducto->precio_minimo != NULL || $preciosProducto->precio_maximo != NULL) {
											$preciosProducto->save();
										}
										unset($preciosProducto);
									}
								}
							}
						} else {
							$producto_before->precio_abierto = 0;
						}
					}

					if (isset($row->grupo)) {
						$producto_before->produto_grupo = $row->grupo == "" ? null : $row->grupo;
					}

					if (isset($row->tipo)) {
						$producto_before->producto_tipo = $row->tipo == "" ? null : $row->tipo;
					}

					if (isset($row->ubicacion_fisica)) {
						$producto_before->producto_ubicacion_fisica = $row->ubicacion_fisica == "" ? null : $row->ubicacion_fisica;
					}

					if (isset($row->impuestos)) {
						$producto_before->producto_impuesto = $row->impuestos == "" ? null : $row->impuestos;
					}

					if (isset($row->tipo_item_dian)) {
						$producto_before->fe_type_item_identification_id = $row->tipo_item_dian == "" ? null : $row->tipo_item_dian;
					}

					$producto_before->save();
					$producto_after = $this->_producto->where('producto_id', $producto_id)->with($this->_producto->allTablesRelations())->first();

					$log = array(
						'usuario' => $this->session->userdata('nUsuCodigo'),
						'ip' => $_SERVER['REMOTE_ADDR'],
						'fecha' => date('Y-m-d H:i:s'),
						'tabla' => 'PRODUCTO - PARAMETRIZACION RAPIDA',
						'tipo' => LOG_UPDATE,
						'data_before' => json_encode($producto_before),
						'data_after' => json_encode($producto_after),
					);
					$this->systemLogsModel->insert($log);
				}
			}

			ModelTransaction::commit();
			return true;
		} catch (Exception $e) {
			log_message('error', 'Error en parametrización rápida: ' . $e->getMessage());
			ModelTransaction::rollback();
			return false;
		}
	}

	function getAnyCondition($where)
	{
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get('producto');
		return $query->row_array();
	}

	function get_by($campo, $valor)
	{
		$this->db->select('producto.*, impuestos.porcentaje_impuesto');
		$this->db->where($campo, $valor);
		$this->db->join('impuestos', 'impuestos.id_impuesto=producto.producto_impuesto', 'left');
		$query = $this->db->get('producto');
		return $query->row_array();
	}

	function get_onlyCostoUnitario($campo, $valor)
	{
		$this->db->select('producto.costo_unitario');
		$this->db->where($campo, $valor);
		$query = $this->db->get('producto');
		return $query->row_array();
	}

	function get_all_by($where, $order_colum = false, $oder_desc = false)
	{
		//trae solo los datos de la tabla producto
		$this->db->where($where);

		if ($order_colum != false) {
			$this->db->order_by($order_colum, $oder_desc);
		}
		$query = $this->db->get('producto');
		return $query->result_array();
	}

	function delete($producto)
	{
		$this->db->trans_start();
		$this->db->where($this->id, $producto['producto_id']);
		$this->db->update($this->tabla, $producto);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return TRUE;
	}

	function estadoDelProducto($valor)
	{
		$query = $this->db->query("SELECT `id_detalle`,unidadesV.`nombre_unidad` as nombreUnidadV, `id_venta`, ventas.`id_producto` as productoV, ventas.`precio` as precioV,
        ventas.`cantidad` as cantidadV, ventas.`unidad_medida` as unidadV, ingreso.`unidad_medida` as unidadI, unidadesI.`nombre_unidad` as nombreUnidadI,
			`detalle_importe`, `detalle_costo_promedio`, `detalle_utilidad`, ingreso.precio as precioI,ingreso.id_producto as productoI, ingreso.id_detalle_ingreso as detalleI
			FROM `detalle_venta` as ventas
			LEFT JOIN unidades as unidadesV ON unidadesV.id_unidad=ventas.unidad_medida
			LEFT JOIN detalleingreso as ingreso ON ingreso.id_producto = ventas.id_producto
			LEFT JOIN unidades as unidadesI ON unidadesI.id_unidad=ingreso.unidad_medida
			WHERE ventas.id_producto = " . $valor . " ORDER BY id_detalle,detalleI DESC LIMIT 1");
		return $query->result_array();
	}

	function estado_producto_est($valor)
	{
		$query = $this->db->query("SELECT SUM(detalle_utilidad) AS utilidad, count(id_producto) as cantidad_vendida,
        SUM(detalle_importe) AS promedio
        FROM `detalle_venta`
        WHERE detalle_venta.id_producto = " . $valor . " ");
		return $query->result_array();
	}

	function cantidad_comprada($valor)
	{
		$query = $this->db->query("SELECT  count(id_producto) as cantidad_comprada
        FROM `detalleingreso`
        WHERE detalleingreso.id_producto = " . $valor . " ");
		return $query->row_array();
	}

	function get_by_id($id)
	{
		$query = $this->db->where('producto_id', $id);
		$this->db->join('grupos', 'grupos.id_grupo=producto.' . $this->grupo, 'left');
		$this->db->join('proveedor', 'proveedor.id_proveedor=producto.' . $this->proveedor, 'left');
		$this->db->join('impuestos', 'impuestos.id_impuesto=producto.' . $this->impuesto, 'left');
		$query = $this->db->get('producto');
		return $query->row_array();
	}

	function select_all_producto2()
	{
		$this->db->select($this->tabla . '.* , ubicacion_fisica.ubicacion_nombre,
		grupos.nombre_grupo, proveedor.proveedor_nombre, impuestos.nombre_impuesto, impuestos.porcentaje_impuesto');
		$this->db->from($this->tabla);
		$this->db->join('grupos', 'grupos.id_grupo=producto.' . $this->grupo, 'left');
		$this->db->join('proveedor', 'proveedor.id_proveedor=producto.' . $this->proveedor, 'left');
		$this->db->join('impuestos', 'impuestos.id_impuesto=producto.' . $this->impuesto, 'left');
		$this->db->join('clasificacion', 'clasificacion.clasificacion_id=producto.producto_clasificacion', 'left');
		$this->db->join('tipo_producto', 'tipo_producto.tipo_prod_id=producto.producto_tipo', 'left');
		$this->db->join('ubicacion_fisica', 'ubicacion_fisica.ubicacion_id=producto.producto_ubicacion_fisica', 'left');
		$this->db->where($this->status . ' !=', '0');
		$this->db->where($this->producto_activo . ' !=', '0');
		$this->db->order_by($this->id, 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all_producto()
	{
		$this->db->select($this->tabla . '.* , ubicacion_fisica.ubicacion_nombre,
		grupos.nombre_grupo, proveedor.proveedor_nombre, impuestos.nombre_impuesto, impuestos.porcentaje_impuesto');
		$this->db->from($this->tabla);
		$this->db->join('grupos', 'grupos.id_grupo=producto.' . $this->grupo, 'left');
		$this->db->join('proveedor', 'proveedor.id_proveedor=producto.' . $this->proveedor, 'left');
		$this->db->join('impuestos', 'impuestos.id_impuesto=producto.' . $this->impuesto, 'left');
		$this->db->join('clasificacion', 'clasificacion.clasificacion_id=producto.producto_clasificacion', 'left');
		$this->db->join('tipo_producto', 'tipo_producto.tipo_prod_id=producto.producto_tipo', 'left');
		$this->db->join('ubicacion_fisica', 'ubicacion_fisica.ubicacion_id=producto.producto_ubicacion_fisica', 'left');
		$this->db->where($this->status . ' !=', '0');
		$this->db->where($this->producto_activo . ' !=', '0');
		$this->db->order_by($this->id, 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all_catalogo($limit, $start)
	{
		$this->db->select('*');
		$this->db->from('catalogo');
		if ($limit != false) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	function buscar_pro_catalogo($where)
	{
		$this->db->select('*');
		$this->db->from('catalogo');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result_array();
	}

	function buscar_id($id)
	{
		$this->db->select('producto.*,impuestos.porcentaje_impuesto, 
			impuestos.tipo_calculo as tipo_impuesto, 
			otroimp.porcentaje_impuesto as porcentaje_otro_impuesto, 
			otroimp.tipo_calculo as tipo_otro_impuesto, 
			impuestos.fe_impuesto,otroimp.fe_impuesto as  fe_otro_impuesto ');
		$this->db->from('producto');
		$this->db->join('impuestos', 'impuestos.id_impuesto=producto.' . $this->impuesto, 'left');
		$this->db->join('impuestos as otroimp', 'otroimp.id_impuesto=producto.otro_impuesto', 'left');
		$this->db->where('producto_id', $id);
		//$this->db->where('impuestos.estatus_impuesto',1);
		$query = $this->db->get();
		return $query->row_array();
	}

	function get_all_by_local($local, $activo = false, $producto = false)
	{
		$this->db->distinct();
		$this->db->select($this->tabla . '.*,  inventario.id_inventario, inventario.id_local, inventario.cantidad, 
		 grupos.nombre_grupo, proveedor.proveedor_nombre, impuestos.nombre_impuesto, impuestos.porcentaje_impuesto');
		$this->db->from($this->tabla);
		$this->db->join('grupos', 'grupos.id_grupo=producto.' . $this->grupo, 'left');
		$this->db->join('proveedor', 'proveedor.id_proveedor=producto.' . $this->proveedor, 'left');
		$this->db->join('impuestos', 'impuestos.id_impuesto=producto.' . $this->impuesto, 'left');
		$this->db->join('(SELECT DISTINCT inventario.id_producto, inventario.id_inventario, inventario.cantidad,  inventario.id_local FROM inventario WHERE inventario.id_local=' . $local . '  ORDER by id_inventario DESC ) as inventario', 'inventario.id_producto=producto.' . $this->id, 'left');

		$this->db->group_by('producto_id');

		$this->db->where($this->status, '1');

		if ($activo) {
			$this->db->where($this->producto_activo, '1');
		}
		if ($producto != false) {
			$this->db->where('producto.producto_id', $producto);
		}

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

	//FE === FACTURACION ELECTRONICA
	function get_fe_typeitems()
	{
		$this->db2 = $this->load->database('second', TRUE);
		$query = $this->db2->get('type_item_identifications');
		return $query->result();
	}

	public
	function count_all($filter = null)
	{
		// Filter
		$this->db->where('producto_estatus', 1);
		$query = $this->db->get('producto');
		// Total Count
		return $query->num_rows();
	}

	public function count_all_catalogo($filter = null)
	{
		$query = $this->db->get('catalogo');
		// Total Count
		return $query->num_rows();
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
		$retorno = false,
		$limit = false,
		$start = 0,
		$order_dir = false,
		$like = false,
		$where_custom
	) {

		//el false, despues del $select es para poder hacer el query que trae cuantas filas hubiese afectado sin el LIMIT
		if ($select != false) {
			$this->db->select($select, false);
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
		//  $devolver = $query->result_array();
		//var_dump($devolver);
		//este es el query que dice cuantas filas hubiese afectado sin el limit,
		//esto se hace para no tener que volver a hacer el query y contar los resultados
		//en el select debe venir  SQL_CALC_FOUND_ROWS
		$re = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;

		//lo envio en el primer arreglo
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

	public function traer_by_mejorado(
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
	function total_result(
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
					$this->db->join($join[$i], $campos_join[$i], $tipo_join[$i]);
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

		if ($group != false) {
			$this->db->group_by($group);
		}
		$query = $this->db->get();

		// echo $this->db->last_query();
		if ($retorno == "RESULT_ARRAY") {
			return $query->result_array();
		} elseif ($retorno == "RESULT") {
			return $query->result();
		} else {
			return $query->row_array();
		}
	}

	function autocomplete_marca($term)
	{
		$this->db->select('var_producto_marca as label');
		$this->db->from('producto');
		$this->db->like('var_producto_marca', $term);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Este metodo calcula el costo de una unidad de medida basandose en el osto de la caja
	 * @unidad: unidad de medida a calcular
	 * @costounitariocaja costo de la caja
	 * @unidades_producto unidades de medida que tiene el producto
	 */
	function calcularCostoUnidad($unidad, $costounitariocaja, $unidades_producto)
	{
		$encontro = false;
		$costoEstaunidad = "";
		$contIntCaja = "";

		foreach ($unidades_producto as $row) {
			$contenido_estaunidad = $row['unidades'];
			if ($contenido_estaunidad != null & !empty($contenido_estaunidad)) {
				if ($row['orden'] == 1) {
					//guardo el contenido interno de caja
					$contIntCaja = $contenido_estaunidad;
				}

				$unidad_arr = $row['id_unidad'];
				if ($unidad_arr == $unidad) {
					$encontro = true;
					/**
					 * Es el mismo precio de la caja
					 */
					if ($row['orden'] == 1) {
						$costoEstaunidad = $costounitariocaja;
					}
					/**
					 * Calculo el costo del blister
					 */
					if ($row['orden'] == 2) {
						$costoEstaunidad = $costounitariocaja / $contenido_estaunidad;
					}
					/**
					 * precio de la unidad
					 */
					if ($row['orden'] == 3) {
						$costoEstaunidad = $costounitariocaja / $contIntCaja;
					}
				}
			}
		}

		//si encontro algun contenido interno en lst_producto, retorno cuanto cuesta la unidad
		if ($encontro == true) {
			return $costoEstaunidad;
		} else {
			return false;
		}
	}

	/**
	 *Este metodo actualza el costo de cada unidad de medida segun el costo de la caja
	 * @producto_id id del producto
	 * @costo_caja el costo de la caja
	 */
	public function updateCostosByProduct($producto_id, $costo_caja)
	{
		$where = array(
			'producto_id' => $producto_id
		);
		$unidades_producto = $this->unidades_model->solo_unidades_xprod($where);
		if (count($unidades_producto) > 0 && $costo_caja != false) {
			foreach ($unidades_producto as $unidad) {
				$costoestaunidad = $this->calcularCostoUnidad($unidad['id_unidad'], $costo_caja, $unidades_producto);
				$unidad_has_producto = array();
				$unidad_has_producto['costo'] = $costoestaunidad;
				$this->db->where('id_unidad', $unidad['id_unidad']);
				$this->db->where('producto_id', $producto_id);
				$this->db->update('unidades_has_producto', $unidad_has_producto);
			}
		}
	}

	function dataProdPorPropiedad()
	{
		$data = array();
		if ($this->input->post('categoria') == "GRUPO") {
			$where = array(
				"produto_grupo" => $this->input->post('subcategoria'),
				'producto.producto_estatus' => 1
			);
			$data['productos'] = $this->pd->get_all_by($where, 'producto_nombre', 'asc');
			$data['subcategoria'] = $this->grupos_model->get_by('id_grupo', $this->input->post('subcategoria'));
			$data['subcategoria'] = $data['subcategoria']['nombre_grupo'];
		}

		if ($this->input->post('categoria') == "CLASIFICACION") {
			$where = array(
				"producto_clasificacion" => $this->input->post('subcategoria'),
				'producto.producto_estatus' => 1
			);
			$data['productos'] = $this->pd->get_all_by($where, 'producto_nombre', 'asc');
			$data['subcategoria'] = $this->clasificacion_model->get_by(array('clasificacion_id' => $this->input->post('subcategoria')));
			$data['subcategoria'] = $data['subcategoria']['clasificacion_nombre'];
		}

		if ($this->input->post('categoria') == "TIPO") {
			$where = array(
				"producto_tipo" => $this->input->post('subcategoria'),
				'producto.producto_estatus' => 1
			);
			$data['productos'] = $this->pd->get_all_by($where, 'producto_nombre', 'asc');
			$data['subcategoria'] = $this->tipo_producto_model->get_by(array('tipo_prod_id' => $this->input->post('subcategoria')));
			$data['subcategoria'] = $data['subcategoria']['tipo_prod_nombre'];
			$tipos = $this->tipo_producto_model->get_all();
			$data['tipos'] = $tipos;
		}

		if ($this->input->post('categoria') == "COMPONENTE") {
			$where = array(
				"producto_has_componente.componente_id" => $this->input->post('subcategoria'),
				'producto.producto_estatus' => 1
			);
			$data['productos'] = $this->producto_componente_model->getProductoByComponent($where, 'producto_nombre', 'asc');
			$data['subcategoria'] = $this->componentes_model->get_by(array('componente_id' => $this->input->post('subcategoria')));
			$data['subcategoria'] = $data['subcategoria']['componente_nombre'];
		}

		if ($this->input->post('categoria') == "UBICACION_FISICA") {
			$where = array(
				"producto_ubicacion_fisica" => $this->input->post('subcategoria'),
				'producto.producto_estatus' => 1
			);
			$data['productos'] = $producto = $this->pd->get_all_by($where, 'producto_nombre', 'asc');
			$data['subcategoria'] = $this->ubicacion_fisica_model->get_by(array('ubicacion_id' => $this->input->post('subcategoria')));
			$data['subcategoria'] = $data['subcategoria']['ubicacion_nombre'];
			$ubicaciones = $this->ubicacion_fisica_model->get_all();
			$data['ubicaciones'] = $ubicaciones;
		}

		if ($this->input->post('categoria') == "IMPUESTO") {
			$where = array(
				"producto_impuesto" => $this->input->post('subcategoria'),
				'producto.producto_estatus' => 1
			);
			$data['productos'] = $this->pd->get_all_by($where, 'producto_nombre', 'asc');
			$data['subcategoria'] = $this->impuestos_model->get_by('id_impuesto', $this->input->post('subcategoria'));
			$data['subcategoria'] = $data['subcategoria']['nombre_impuesto'];
		}
		return $data;
	}
}
