<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Mike42\Escpos\Printer;

class inventario extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('inventario/inventario_model');
		$this->load->model('producto/producto_model');
		$this->load->model('ajusteinventario/ajusteinventario_model');
		$this->load->model('ajustedetalle/ajustedetalle_model');
		$this->load->model('documento_inventario/documento_inventario_model');
		$this->load->model('kardex/kardex_model');
		$this->load->model('local/local_model');
		$this->load->model('unidades/unidades_model');
		$this->load->model('columnas/columnas_model');
		$this->load->model('venta/venta_model');
		$this->load->model('detalle_ingreso/detalle_ingreso_model');
		$this->load->model('kardex/kardex_model');
		$this->load->model('cliente/cliente_model');
		$this->load->model('unidades_has_precio/unidades_has_precio_model');
		$this->load->model('ubicacion_fisica/ubicacion_fisica_model');
		$this->load->model('kardex/kardex_model');
		$this->load->model('grupos/grupos_model');
		$this->load->model('producto/paquete_has_prod_model');
		$this->load->model('opciones/opciones_model');
		$this->columnas = $this->columnas_model->get_by('tabla', 'producto');
		$this->load->library('ReceiptPrint');
		$this->very_sesion();
	}

	function ajuste()
	{
		if ($this->session->flashdata('success') != FALSE) {
			$data['success'] = $this->session->flashdata('success');
		}
		if ($this->session->flashdata('error') != FALSE) {
			$data['error'] = $this->session->flashdata('error');
		}
		$data['documentos_inventario'] = $this->documento_inventario_model->get_all();
		$data['ajustes'] = array();
		$data['locales'] = $this->local_model->get_all();
		$dataCuerpo['cuerpo'] = $this->load->view('menu/inventario/ajuste', $data, true);
		if ($this->input->is_ajax_request()) {
			echo $dataCuerpo['cuerpo'];
		} else {
			$this->load->view('menu/template', $dataCuerpo);
		}
	}

	function ajustehistorial()
	{
		if ($this->session->flashdata('success') != FALSE) {
			$data['success'] = $this->session->flashdata('success');
		}
		if ($this->session->flashdata('error') != FALSE) {
			$data['error'] = $this->session->flashdata('error');
		}

		$data['ajustes'] = array();
		$data['locales'] = $this->local_model->get_all();
		$dataCuerpo['cuerpo'] = $this->load->view('menu/inventario/ajustehistorial', $data, true);
		if ($this->input->is_ajax_request()) {
			echo $dataCuerpo['cuerpo'];
		} else {
			$this->load->view('menu/template', $dataCuerpo);
		}
	}

	function ajusteinventario_by_local()
	{
		if ($this->input->post('id_local') != "seleccione") {
			$local = $this->input->post('id_local');
			$tipo = $this->input->post('tipo');
			$tipoajuste = $this->input->post('tipoajuste');
			$soloajuste = $this->input->post('soloajuste');

			if ($soloajuste == true) {
				//aqui solo entra en registro de fisico -> historial
				$data['ajustes'] = $this->ajusteinventario_model->get_ajuste_inventario($local);
				$this->load->view('menu/inventario/lista_ajustes_historial', $data);
			} else {
				$data['ajustes'] = $this->ajusteinventario_model->get_movimiento_inventario($local, $tipo, $tipoajuste);
				$this->load->view('menu/inventario/lista_ajustes', $data);
			}
		}
	}

	function addajuste($tipo)
	{
		$data['locales'] = $this->local_model->get_all();
		$data["ubicaciones"] = $this->ubicacion_fisica_model->get_all();
		$data['documentos_inventario'] = $this->documento_inventario_model->get_all();
		$data['INVENTARIO_UBICACION_REQUERIDO'] = $this->session->userdata('INVENTARIO_UBICACION_REQUERIDO');
		$productos = array();
		$data['productos'] = $productos;

		if ($tipo == 'byGroup') {
			$data['grupos'] = $this->grupos_model->get_grupos();
		}
		$data['unidades_medida'] = $this->unidades_model->get_unidades();
		$data['tipo'] = $tipo;

		$data['columnasToProd'] = VentaColumnasProductosElo::all();

		$this->load->view('menu/inventario/addajuste', $data);
	}

	function movimiento()
	{
		if ($this->session->flashdata('success') != FALSE) {
			$data['success'] = $this->session->flashdata('success');
		}
		if ($this->session->flashdata('error') != FALSE) {
			$data['error'] = $this->session->flashdata('error');
		}
		$data['locales'] = $this->local_model->get_all();
		$data['unidades'] = $this->unidades_model->get_unidades();
		$dataCuerpo['cuerpo'] = $this->load->view('menu/inventario/movimiento', $data, true);
		if ($this->input->is_ajax_request()) {
			echo $dataCuerpo['cuerpo'];
		} else {
			$this->load->view('menu/template', $dataCuerpo);
		}
	}

	function kardex($id = false, $local = false, $documento_fiscal = false)
	{
		if ($id != FALSE) {
			$data['producto'] = $id;
			$order_ingreso = "dkardexFecha DESC";
			if ($local != "TODOS") {
				$order = "dkardexFecha DESC,cKardexAlmacen";
				$where = array(
					'cKardexProducto' => $id,
					'cKardexAlmacen' => $local
				);
				$data['local'] = $this->local_model->get_by('int_local_id', $local);
			} else {
				$order = "dkardexFecha DESC";
				$where = array('cKardexProducto' => $id);
				$data['local'] = $local;
			}
			if ($documento_fiscal != false) {
				$data['operacion'] = "FISCAL";
				$data['fiscal'] = $this->kardex_model->getKardexFiscal($where, $order_ingreso);
				$where['cKardexOperacion !='] = 'VENTA';
			} else {

				$data['operacion'] = "INTERNO";
			}
			$data['kardex'] = $this->kardex_model->getKardex($where, $order_ingreso);
			if (isset($data['fiscal'])) {
				$kardex = $data['kardex'];
				foreach ($data['fiscal'] as $fiscal) {
					array_push($kardex, $fiscal);
				}
				$data['kardex'] = $kardex;
			}
		}
		$this->load->view('menu/inventario/formMovimiento', $data);
	}

	function getbylocal()
	{
		$local = $this->input->post('local');
		if ($local != "TODOS") {
			if ($this->session->userdata('MOSTRAR_SIN_STOCK') == 1) {
				$where = 'orden = 1 and (id_local =' . $local . ' or id_local IS NULL)';
			} else {
				$where = 'orden = 1 and id_local =' . $local;
			}
		} else {
			$where = array('orden' => 1);
		}
		$datas = array();
		$produtos = $this->inventario_model->getIventarioProducto($where);
		foreach ($produtos as $producto) {

			$producto['producto_id'] = $producto['producto_id'];
			$producto['producto_id_cero'] = sumCod($producto['producto_id']);
			$datas['productos'][] = $producto;
		}
		echo json_encode($datas['productos']);
	}

	function getbyJson()
	{
		$data = $this->input->get('data');
		$local = $data['local'];
		$array = array();
		if ($local != "TODOS") {
			$where = 'orden = 1 and (id_local =' . $local . ' or id_local IS NULL)';
		} else {
			$where = array('orden' => 1);
		}

		$total = $this->producto_model->count_all();
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
		$where = array();
		$where['producto_activo'] = 1;
		$where['producto_estatus'] = 1;
		$nombre_or = false;
		$where_or = false;
		$nombre_in = false;
		$where_in = false;
		$select = 'producto.*, producto_codigo_barra.codigo_barra, producto_codigo_barra.producto_id as producto_id_barra ';
		$from = "producto";
		$join = array('producto_codigo_barra');
		$campos_join = array('producto_codigo_barra.producto_id=producto.producto_id');
		$tipo_join = array('left');
		$search = $this->input->get('search');
		$buscar = $search['value'];
		$where_custom = false;
		$columns = $this->input->get('columns');
		if (!empty($search['value'])) {
			$buscarcod = $buscar;
			if (is_numeric($buscar)) {
				$buscarcod = restCod($buscar);
			}
			$where_custom = "(producto.producto_id LIKE '%" . $buscarcod . "%' or producto.producto_nombre LIKE '%" . $buscar . "%'
            or producto.producto_codigo_interno LIKE '%" . $buscarcod . "%'  or codigo_barra LIKE '%" . $buscar . "%'  )";
		}
		$ordenar = $this->input->get('order');
		$order = false;
		$order_dir = 'desc';
		if (!empty($ordenar)) {
			$order_dir = $ordenar[0]['dir'];
			if ($ordenar[0]['column'] == 0) {
				$order = 'producto.producto_id';
			}
			if ($ordenar[0]['column'] == 1) {
				$order = 'producto.producto_codigo_interno';
			}
			if ($ordenar[0]['column'] == 2) {
				$order = 'producto.producto_nombre ';
			}
		}

		$group = 'producto.producto_id';
		$productos = $this->producto_model->traer_by(
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

		$todasunidades = $this->unidades_model->get_unidades();
		$selectinventario = 'inventario.cantidad';
		foreach ($productos as $pd) :
			$PRODUCTOjson = array();
			//inventario del producto
			$PRODUCTOjson[] = sumCod($pd['producto_id']);
			$PRODUCTOjson[] = $pd['producto_codigo_interno'];
			$PRODUCTOjson[] = $pd['producto_nombre'];

			//todas las unidades
			if (count($todasunidades) > 0) {
				foreach ($todasunidades as $todas) {
					$cantidad = 0;
					//inventario del producto
					$existencia = $this->inventario_model->get_inventario_by_producto_and_unidad(
						$pd['producto_id'],
						$local,
						$todas['id_unidad'],
						$selectinventario
					);
					log_message('ERROR', $pd['producto_id']);
					log_message('ERROR', $local);
					log_message('ERROR', $todas['id_unidad']);

					if (isset($existencia['cantidad'])) {
						$cantidad = $existencia['cantidad'];
					}
					$PRODUCTOjson[] = $cantidad;
				}
			}
			$PRODUCTOjson[] = '<div class="btn-group"> <a class="btn btn-default" data-toggle="tooltip"
                title="Ver movimientos" data-original-title="fa fa-comment-o"
                href="#" onclick="KARDEXINTERNO(' . $pd['producto_id'] . ')"> Ver movimientos';
			$array['data'][] = $PRODUCTOjson;
		endforeach;

		$array['draw'] = $draw; //esto debe venir por post
		$array['recordsTotal'] = $total;
		$array['recordsFiltered'] = $total; // esto dbe venir por post
		header("Content-type:application/json");
		echo json_encode($array);
	}

	function existencia_producto()
	{
		$this->load->view('menu/inventario/existencia_producto');
	}

	function buscarproducto()
	{
		$id = $this->input->post('id');
		$data = $this->producto_model->get_by_id($id);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function verajuste($id = FALSE)
	{
		$data = array();
		$data['detalles'] = $this->ajustedetalle_model->get_ajuste_by_inventario($id);
		$this->load->view('menu/inventario/verajuste', $data);
	}

	function vistapreviaAjuste($id = FALSE)
	{
		$data = array();
		$data['detalles'] = $this->ajustedetalle_model->get_ajuste_by_inventario($id);
		$this->load->view('menu/inventario/imprimirAjuste', $data);
	}

	function informeDiferencia($id = FALSE)
	{
		$data = array();
		$detalles_array = array();
		$detalles = $this->ajustedetalle_model->get_ajuste_by_inventario($id, 'REGISTRO DE FISICOS');

		foreach ($detalles as $detalle) {
			$unidad = $detalle->id_unidad;
			$cantidad = $detalle->nKardexCantidad;
			$producto = $detalle->producto_id;
			$detalle->costo_unitario = $this->venta_model->calcularCostos(
				$producto,
				$cantidad,
				$unidad,
				COSTO_UNITARIO
			);
			$detalle->costo_diferencia = $detalle->costo_unitario * $cantidad;
			array_push($detalles_array, $detalle);
		}
		$data['detalles'] = $detalles_array;
		$this->load->view('menu/inventario/informeDiferencias', $data);
	}

	function directPrintAjuste($id = FALSE)
	{
		try {
			$total = 0;

			$data = $this->ajustedetalle_model->get_ajuste_by_inventario($id);
			$data = (array)$data;
			$printer = $this->receiptprint->connectUsb($this->session->userdata('IMPRESORA'), $this->session->userdata('USUARIO_IMPRESORA'), $this->session->userdata('PASSWORD_IMPRESORA'), $this->session->userdata('WORKGROUP_IMPRESORA'));
			/* Initialize */
			$printer->initialize();
			$printer->feed(1);

			$movimiento = ($data[0]->tipo_ajuste == null) ? 'REGISTRO DE FISICOS' : $data[0]->documento_tipo;
			$printer->text('Movimiento Nº  ' . $id . "\n");
			$printer->text('Fecha:  ' . $data[0]->fecha . "\n");
			$printer->text('Movimiento:  ' . $movimiento . "\n");
			$printer->text('Usuario:  ' . $data[0]->username . "\n");
			$printer->text("----------------------------------------\n");
			$printer->text(str_pad("Cod", 5));
			$printer->text(str_pad("Nombre", 18));
			$printer->text(str_pad("Uni", 10));
			$printer->text(str_pad("Cant", 10));
			//if ($data[0]->tipo_ajuste != null) {
			$printer->text("Costo");
			//}
			$printer->feed(1);
			$printer->text("----------------------------------------\n");
			foreach ($data as $inv) {
				if ($inv->cantidad_detalle > 0) {
					$printer->text(str_pad($inv->producto_id, 5));
					$printer->text(str_pad($inv->producto_nombre, 18) . "\n");
					$printer->text(str_pad($inv->abreviatura, 10));
					$printer->text(str_pad($inv->cantidad_detalle, 10));
					// if ($data[0]->tipo_ajuste != null) {
					$printer->text($inv->costo);
					//}
					$printer->feed(1);
					$total = $total + $inv->costo;
				}
			}
			$printer->text("----------------------------------------\n");
			$printer->text(str_pad('TOTAL', 20));
			$printer->text(MONEDA . " " . $total . "\n"); //TODO PREGUNTAR A GIOVANNI SI ESTE TOTAL VA
			$printer->feed(5);
			$printer->cut(Printer::CUT_FULL, 10); //corta el papel
			// $printer->pulse(); // abre la caja registradora
			/* Close printer */
			$printer->close();
			echo json_encode(array('result' => "success"));
		} catch (Exception $e) {
			log_message("error", "Error: Could not print. Message " . $e->getMessage());
			echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
			$this->ReceiptPrint->close_after_exception();
		}
	}

	function directPrintInformeDiferencia($id = FALSE)
	{
		try {
			$total = 0;
			$data = $this->ajustedetalle_model->get_ajuste_by_inventario($id, 'REGISTRO DE FISICOS');
			$data = (array)$data;
			$printer = $this->receiptprint->connectUsb($this->session->userdata('IMPRESORA'), $this->session->userdata('USUARIO_IMPRESORA'), $this->session->userdata('PASSWORD_IMPRESORA'), $this->session->userdata('WORKGROUP_IMPRESORA'));
			/* Initialize */
			$printer->initialize();
			$printer->feed(1);
			$movimiento = ($data[0]->tipo_ajuste == null) ? 'REGISTRO DE FISICOS' : $data[0]->tipo_ajuste;
			$printer->text('Movimiento Nº  ' . $id . "\n");
			$printer->text('Fecha:  ' . $data[0]->fecha . "\n");
			$printer->text('Movimiento:  ' . $movimiento . "\n");
			$printer->text('Usuario:  ' . $data[0]->usuario_username . "\n");
			$printer->text("----------------------------------------\n");
			$printer->text(str_pad("Cod", 5));
			$printer->text(str_pad("Nombre", 18));
			$printer->text(str_pad("Uni", 5));
			$printer->text(str_pad("Cant", 5));
			$printer->text(str_pad("Antes", 5));
			$printer->text(str_pad("Despues", 5));
			if ($data[0]->tipo_ajuste != null) {
				$printer->text("Costo");
			}
			$printer->feed(1);
			$printer->text("----------------------------------------\n");
			$detalles_array = array();

			foreach ($data as $detalle) {
				$unidad = $detalle->id_unidad;
				$cantidad = $detalle->nKardexCantidad;
				$producto = $detalle->producto_id;
				$detalle->costo_unitario = $this->venta_model->calcularCostos($producto, $cantidad, $unidad, COSTO_UNITARIO);
				$detalle->costo_diferencia = $detalle->costo_unitario * $cantidad;
				array_push($detalles_array, $detalle);
			}

			foreach ($detalles_array as $inv) {
				$printer->text(str_pad($inv->producto_id, 5));
				$printer->text(str_pad($inv->producto_nombre, 18));
				$printer->text(str_pad($inv->abreviatura, 5));
				$printer->text(str_pad($inv->cKardexTipo, 5));
				$printer->text(str_pad($inv->cantidad_detalle, 5));
				$printer->text(str_pad(number_format($inv->nKardexCantidad, 2), 5));
				$printer->text(str_pad(number_format($inv->costo_diferencia, 2, ',', '.'), 5));
				$printer->text(str_pad($inv->cKardexTipo, 5));
				if ($data[0]->tipo_ajuste != null) {
					//$printer->text($inv->costo);
				}
				$printer->feed(1);
				$total = $total + $inv->costo;

				$stokactual = json_decode($inv->stockUManterior);
				foreach ($stokactual as $key => $value) {
					if ($key == $inv->id_unidad) {
						$stok = $value->cantidad;
					}
				}
				$printer->text(str_pad($stok, 5));
				$stokactual = json_decode($inv->stockUMactual);
				foreach ($stokactual as $key => $value) {
					if ($key == $inv->id_unidad) {
						$stok = $value->cantidad;
					}
				}
				$printer->text(str_pad($stok, 5));
			}
			$printer->text("----------------------------------------\n");
			// $printer->text(str_pad('TOTAL', 20));
			//$printer->text(MONEDA . " " . $total . "\n"); //TODO PREGUNTAR A GIOVANNI SI ESTE TOTAL VA

			$printer->feed(5);
			$printer->cut(Printer::CUT_FULL, 10); //corta el papel
			//   $printer->pulse(); // abre la caja registradora
			/* Close printer */
			$printer->close();

			echo json_encode(array('result' => "success"));
		} catch (Exception $e) {
			log_message("error", "Error: Could not print. Message " . $e->getMessage());
			echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
			$this->ReceiptPrint->close_after_exception();
		}
	}

	private function transFormUnidadesRegistro($productos, $post)
	{
		$unidades = array();
		$cantidades = array();
		$ubicacion = array();
		foreach ($productos as $producto) {
			$unidades[] = $post['unidad_' . $producto];
			$cantidades[] = $post['cantidad_' . $producto];
			$ubicacion[] = isset($post['ubicacion_producto_' . $producto])?$post['ubicacion_producto_' . $producto]: [];
		}
		return array('unidades' => $cantidades, 'cantidades' => $cantidades, 'ubicacion' => $ubicacion);
	}

	function guardar()
	{
		$json = array();
		/**
		 * si no existe tipodocumento, quiere decir que es un registro de fisico
		 */
		if (!$this->input->post('tipodocumento')) {
			$id_local = $this->input->post('local');
			$productos = $this->input->post('id_producto');
			$usuario_id = $this->session->userdata('nUsuCodigo');
			$transformar = $this->transFormUnidadesRegistro($productos, $this->input->post());
			$post = '{"producto_id":["33504","33504","33504"],"unidad_id":["1","0","3"],"cantidad":["","","2"],"usuario_id":"14"}';
			$unidades = $transformar['unidades'];
			$cantidades = $transformar['cantidades'];
			$ubicacion = $transformar['ubicacion'];

			$fecha = date('Y-m-d', strtotime($this->input->post('fecha'))) . " " . date('H:i:s');
			$INVENTARIO_UBICACION_REQUERIDO = $this->session->userdata('INVENTARIO_UBICACION_REQUERIDO');

			$imprimir = false;
			$json = $this->ajusteinventario_model->saveRegistroFisico(
				REG_FISICO_SID,
				$id_local,
				$usuario_id,
				$productos,
				$unidades,
				$cantidades,
				$ubicacion,
				$fecha,
				$INVENTARIO_UBICACION_REQUERIDO,
				$imprimir
			);
		} else {
			$json = $this->ajusteinventario_model->guardar();
		}
		echo json_encode($json);
	}

	//anula un movimiento de inventario
	function anularMovim()
	{
		$json = array();
		$json = $this->ajusteinventario_model->anularMovim();
		echo json_encode($json);
	}

	//devuelve el stock de un producto, en modo normal, y en unidades minimas
	function soloStock()
	{
		$data = array();
		$producto = $this->input->post('producto');
		$local = $this->input->post('local_id');
		$data['stockMinimas'] = $this->inventario_model->stockUnidadesMinimas($producto, $local);
		$data['stock'] = $this->inventario_model->get_inventario_by_producto($producto, $local);
		$data['unidades_producto'] = $unidades_producto = $this->unidades_model->solo_unidades_xprod(
			array('producto_id' => $producto)
		);

		if ($this->input->is_ajax_request()) {

			echo json_encode($data);
		} else {
			redirect('productos');
		}
	}

	function converUnidadesMinimas($producto, $unidad_id, $cantidad)
	{
		$unidades_producto = $this->unidades_model->solo_unidades_xprod(
			array(
				'producto_id' => $producto,
				'unidades_has_producto.id_unidad' => $unidad_id
			)
		);
		$total_minima = 0;
		$total_minima += $this->inventario_model->covertUnidadMinima($unidades_producto, $unidad_id, $cantidad);

		if ($this->input->is_ajax_request()) {
			$data['total_minima'] = $total_minima;

			echo json_encode($data);
		} else {
			redirect('productos');
		}
	}

	//DEVUELVE PRECIOS Y EXISTENCIA
	function buscarExistenciayPrecios()
	{
		//$this->input->post('getprecios') es la condicion de pago que quieras. Si quieres solo los precios debe venir con=soloprecios
		$condicion_pago = $this->input->post('getprecios');
		//esto lo hago porque en ingresos puedo tener el codigo interno o el producto id,
		//si se sigue haciendo de amanera normal, solo envia por post $this->input->post('producto') y entra en la segunda condicion
		if ($this->input->post('producto_codigo_interno')) {
			$producto = $this->producto_model->get_by('producto_codigo_interno', $this->input->post('producto_codigo_interno'));
			if (count($producto) > 0) {
				$producto = $producto['producto_id'];
			} else {
				$producto = 0;
			}
		} else {
			$producto = $this->input->post('producto');
		}

		//para poder recibir un local, donde buscar el stock
		$local = $this->input->post('local') != null ? $this->input->post('local') : $this->session->userdata('id_local');

		$is_paquete = $this->input->post('is_paquete');
		$selectinventario = '*';
		if ($is_paquete == '1') {
			$productos = $this->paquete_has_prod_model->get_all_by_prod_grouped($producto);
			$exitenciaarray = array();
			foreach ($productos as $prod) {
				$existen = array();
				$produto = $prod;
				$unidades = array();
				foreach ($prod['unidades'] as $unidad) {
					$unidad = $unidad;
					$unidad['stock'] = $this->inventario_model->get_inventario_by_producto_and_unidad(
						$prod['prod_id'],
						$local,
						$unidad['unidad_id'],
						$selectinventario
					);
					array_push($unidades, $unidad);
				}
				$produto['unidades'] = $unidades; //estas son las unidades que tiene el paquete
				$produto['existencia'] = $this->inventario_model->get_inventario_by_producto($prod['prod_id'], $local); //uidades de meddia que tiene el producto con su stock
				array_push($exitenciaarray, $produto);
			}
			$existencia = $exitenciaarray;
		} else {
			$existencia = $this->inventario_model->get_inventario_by_producto($producto, $local);
		}

		$precios = array();

		if ($condicion_pago != null) {
			$precios = $this->unidades_has_precio_model->getPreciosByProdAndCondicionPago($producto, $condicion_pago);
		}
		if ($condicion_pago == "soloprecios") {
			$precios = $this->unidades_has_precio_model->get_all_where(array('id_producto' => $producto));
		}

		$where = array(
			'producto_id' => $producto
		);
		$costo_unitario = $this->producto_model->getAnyCondition($where);

		if ($this->input->is_ajax_request()) {
			$data['costo_unitario'] = (
				isset($costo_unitario) && 
				is_array($costo_unitario) && 
				isset($costo_unitario['costo_unitario'])
			)?
				$costo_unitario['costo_unitario']
			:0;
			$data['stock'] = $existencia;
			$data['precios'] = $precios;
			header("Content-type:application/json");
			echo json_encode($data);
		} else {
			redirect('productos');
		}
	}

	function view_reporte()
	{
		if ($this->input->post('id_local') != "seleccione") {
			$local = $this->input->post('id_local');
			$tipo = $this->input->post('tipo');
			$porcentaje = 30;
			if ($tipo == "MINIMA") {
				$arreglo = "SELECT * FROM inventario JOIN producto ON producto.`producto_id`=inventario.`id_producto`
 JOIN local ON local.`int_local_id`=inventario.`id_local` WHERE id_local='$local'
AND cantidad <= producto_stockminimo";
			} elseif ($tipo == "ALTA") {

				$arreglo = "SELECT * FROM inventario JOIN producto ON producto.`producto_id`=inventario.`id_producto`
  JOIN local ON local.`int_local_id`=inventario.`id_local` WHERE id_local='$local'
AND cantidad >= producto_stockminimo + (producto_stockminimo * 30)/100";
			} elseif ($tipo == "BAJA") {
				$arreglo = "SELECT * FROM inventario JOIN producto ON producto.`producto_id`=inventario.`id_producto`
  JOIN local ON local.`int_local_id`=inventario.`id_local` WHERE id_local='$local'
AND cantidad < producto_stockminimo + (producto_stockminimo * 30)/100 and cantidad > producto_stockminimo ";
			}

			$data['inventarios'] = $this->inventario_model->get_all_by_array($arreglo);;
			$data['tipo_reporte'] = $tipo;
			$data['local'] = $local;
			$this->load->view('menu/inventario/lista_reporte', $data);
		}
	}

	function existencia_minima()
	{
		$data['locales'] = $this->local_model->get_all();
		$data['tipo'] = "MINIMA";
		$dataCuerpo['cuerpo'] = $this->load->view('menu/inventario/reportes', $data, true);
		if ($this->input->is_ajax_request()) {
			echo $dataCuerpo['cuerpo'];
		} else {
			$this->load->view('menu/template', $dataCuerpo);
		}
	}

	function existencia_alta()
	{
		$data['locales'] = $this->local_model->get_all();
		$data['tipo'] = "ALTA";
		$dataCuerpo['cuerpo'] = $this->load->view('menu/inventario/reportes', $data, true);
		if ($this->input->is_ajax_request()) {
			echo $dataCuerpo['cuerpo'];
		} else {
			$this->load->view('menu/template', $dataCuerpo);
		}
	}

	function valorinventario()
	{
		$data = array();
		$dataCuerpo['cuerpo'] = $this->load->view('menu/inventario/valorinventario', $data, true);
		echo $dataCuerpo['cuerpo'];
	}

	function productosnoafectados()
	{
		$todasunidades = $this->unidades_model->get_unidades();
		$data = array('unidades' => $todasunidades);
		$dataCuerpo['cuerpo'] = $this->load->view('menu/inventario/productosnoafectados', $data, true);

		echo $dataCuerpo['cuerpo'];
	}

	function existencia_baja()
	{
		$data['locales'] = $this->local_model->get_all();
		$data['tipo'] = "BAJA";
		$dataCuerpo['cuerpo'] = $this->load->view('menu/inventario/reportes', $data, true);
		if ($this->input->is_ajax_request()) {
			echo $dataCuerpo['cuerpo'];
		} else {
			$this->load->view('menu/template', $dataCuerpo);
		}
	}

	function rep_inv_transacciones()
	{
		$todasunidades = $this->unidades_model->get_unidades();
		$locales = LocalElo::where('local_status', 1)->get();
		$data = array(
			'unidades' => $todasunidades,
			'locales' => $locales
		);
		$dataCuerpo['cuerpo'] = $this->load->view('menu/reportes/inventario/reportes_transacciones/index', $data, true);

		echo $dataCuerpo['cuerpo'];
	}

	/*********Esta funcion es un parche para actualizar todos los stock negativos a cero, en teoria nunca debe haber stock negativo****/
	function parhceStockNegativo()
	{
		$productos = $this->producto_model->get_all_by(array());
		foreach ($productos as $producto) {
			$inventarios = $this->inventario_model->get_all_by(array('id_producto' => $producto['producto_id']));
			foreach ($inventarios as $inv) {
				$this->inventario_model->updateOrInsertInventario(
					$inv['id_producto'],
					$inv['id_unidad'],
					$inv['id_local'],
					$inv['id_inventario'],
					0
				);
			}
		}
	}
}
