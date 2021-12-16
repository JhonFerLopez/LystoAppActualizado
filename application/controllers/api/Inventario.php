<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

use  Illuminate\Database\Capsule\Manager as DB;

class Inventario extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/api_model', 'api');
        $this->load->model('kardex/kardex_model');
        $this->load->model('venta/venta_model');
        $this->load->model('producto/producto_model');
        $this->load->model('unidades/unidades_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('ajusteinventario/ajusteinventario_model');
        $this->load->model('opciones/opciones_model');
        $this->load->model('ajustedetalle/ajustedetalle_model');

        $this->very_auth();
    }

    function very_auth()
    {
        // Request Header
        $reqHeader = $this->input->request_headers();

        // Key
        $key = null;
        if (isset($reqHeader['X-api-key'])) {
            $key = $reqHeader['X-api-key'];
        } else if ($key_get = $this->get('x-api-key')) {
            $key = $key_get;
        } else if ($key_post = $this->post('x-api-key')) {
            $key = $key_post;
        } else {
            $key = null;
        }

        // Auth ID
        $auth_id = $this->api->getAuth($key);

        // ID ?
        if (!empty($auth_id)) {
            $this->uid = $auth_id;
        } else {
            $this->uid = null;
        }
    }

    public function index_get()
    {

        $fecha = $this->input->get('fecha');
        $local = $this->input->get('local');
        $array['data'] = $this->ajusteinventario_model->get_ajuste_inventario(false, $fecha);


        if ($array) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    // All
    public function valor_get()
    {

        try {

            $todoslosproductos = array();
            $where = array('producto_estatus' => 1, 'producto_activo' => 1);
            $search = $this->input->get('search');
            $buscar = $search['value'];
            $where_custom = false;
            if (!empty($buscar) && $buscar != '') {
                $where_custom = "(producto_id LIKE '%" . $buscar . "%'
              or producto_codigo_interno LIKE '%" . $buscar . "%'
              or producto_nombre LIKE '%" . $buscar . "%')";
            }

            $nombre_or = false;
            $where_or = false;
            $nombre_in = false;
            $where_in = false;
            $group = 'producto.producto_id';
            $select = 'producto.*, impuestos.porcentaje_impuesto as porcentaje_impuesto_producto';
            $from = "producto";
            $join = array('impuestos');
            $campos_join = array('impuestos.id_impuesto=producto.producto_impuesto');
            $tipo_join = array('left');
            $ordenar = $this->input->get('order');
            $order = 'producto_id';
            $order_dir = 'desc';
            if (!empty($ordenar)) {
                $order_dir = $order . ',' . $ordenar[0]['dir'];
                /*if ($ordenar[0]['column'] == 0) {
                    $order = 'dkardexFecha';
                }*/
            }

            $start = 0;
            $limit = false;
            $draw = $this->input->get('draw');
            if (!empty($draw)) {
                $start = $this->input->get('start');
                $limit = $this->get('length');
                if ($limit == '-1') {
                    $limit = false;
                }
            }


            $todoslosproductos = $this->producto_model->traer_by(
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


            //echo $this->db->last_query();
            $total = $todoslosproductos[0]['total_afectados'];


            //AHORA SI EMPIEZO CON EL KARDEX

            $datajson = array();

            $countttt = 0;
            foreach ($todoslosproductos as $prod) {


                $datas = array();

                $where = false;
                $search = $this->input->get('search');
                $buscar = $search['value'];
                $where_custom = false;

                $data = $this->input->get('data');
                $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
                $hoy = date('d-m-Y');
                $cantidades = "";
                $is_prepack = $prod['is_prepack'];
                /**
                 * Si la consulta es de la fecha actual
                 */
                if ($fechadesde == $hoy) {

                    $array = array();
                    $count = 0;
                    $producto_id = $prod['producto_id'];

                    $producto_cod = $prod['producto_codigo_interno'];
                    $producto_nombre = $prod['producto_nombre'];
                    $costo_total_producto = 0;

                    $iva = 0;

                    $porcentajeiva = is_numeric($prod['porcentaje_impuesto_producto']) ? $prod['porcentaje_impuesto_producto'] : 0;


                    $costo_caja_prod = is_numeric($prod['costo_unitario']) ? $prod['costo_unitario'] : 0;


                    $costo_caja = $costo_caja_prod;


                    $inventarios = $this->inventario_model->get_inventario_by_producto(
                        $producto_id,
                        $this->session->userdata('BODEGA_PRINCIPAL'),
                        'orden',
                        'asc'
                    );


                    //var_dump($inventarios);
                    foreach ($inventarios as $inventario) {
                        $costounidad = $this->producto_model->calcularCostoUnidad($inventario['id_unidad'], $costo_caja, $inventarios);

                        //   echo $costounidad;
                        $cantidad = $inventario['cantidad'];

                        $cantidades .= $inventario['abreviatura'] . ": " . $cantidad . " <br>";
                        $costo_total_unidad = $costounidad * $cantidad;

                        $costo_total_producto = $costo_total_producto + $costo_total_unidad;
                    }


                    if ($porcentajeiva != null && !empty($porcentajeiva)) {
                        $iva = ($costo_total_producto * $porcentajeiva) / 100;
                    }
                } else {
                    /**
                     * Cuando la fecha es distinta de hoy, osea consulto de dias anteriores
                     */


                    if ($fechadesde != "") {
                        $where_custom = '(date(dkardexFecha) <= \'' . date('Y-m-d', strtotime($fechadesde)) . '\' OR dkardexFecha IS NULL )';
                    }


                    $where['cKardexProducto'] = $prod['producto_id'];
                    $nombre_or = false;
                    $where_or = false;
                    $nombre_in = false;
                    $where_in = false;

                    $group = false;
                    $select = 'a.*,unidades.abreviatura';
                    $from = "kardex a";
                    $join = array('unidades');
                    $campos_join = array('unidades.id_unidad=a.cKardexUnidadMedida');
                    $tipo_join = array('LEFT');
                    $order = 'a.dkardexFecha, a.nkardex_id';
                    $order_dir = 'DESC';
                    $start = 0;
                    $limit = 1;
                    $draw = $this->input->get('draw');
                    if (!empty($draw)) {
                        $start = $this->input->get('start');
                        $limit = $this->get('length');
                        if ($limit == '-1') {
                            $limit = false;
                        }
                    }

                    $datas['kardex'] = $this->kardex_model->traer_by_mejorado(
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
                        $order,
                        "RESULT_ARRAY",
                        1,
                        $start,
                        $order_dir,
                        false,
                        $where_custom
                    );

                    $array = array();
                    $count = 0;
                    $producto_id = $prod['producto_id'];
                    $producto_cod = $prod['producto_codigo_interno'];
                    $producto_nombre = $prod['producto_nombre'];
                    $costo_total_producto = 0;

                    $iva = 0;
                    foreach ($datas['kardex'] as $data) {


                        $porcentajeiva = is_numeric($data['cKardexIvaPorcentaje']) ? $data['cKardexIvaPorcentaje'] : 0;

                        /**
                         * Agarro el precio de la caja que tenia al momento de hacer el movimiento
                         *
                         */
                        $costo_caja_kardex = $data['cKardexCostoCaja'];
                        $costo_caja_prod = is_numeric($prod['costo_unitario']) ? $prod['costo_unitario'] : 0;

                        if ($costo_caja_kardex != null && !empty($costo_caja_kardex)) {
                            $costo_caja = $costo_caja_kardex;
                        } else {

                            $costo_caja = $costo_caja_prod;
                        }

                        $cantidades = "";

                        $inventarios =
                            $this->inventario_model->get_inventario_by_producto($producto_id, $this->session->userdata('BODEGA_PRINCIPAL'), 'orden', 'asc');
                        $stokactual = json_decode($data['stockUMactual']);
                        if (!empty($stokactual)) {
                            foreach ($stokactual as $key1 => $value1) {

                                //debo saber si el producto forma parte de un prepack
                                $is_part_of_prepack = $this->paquete_has_prod_model->is_part_of_prepack($producto_id);

                                $cantidades .= $value1->nombre . ": " . $value1->cantidad . " <br>";
                                $cantidad = $value1->cantidad;
                                /**
                                 * Calculo el costo de cada unidad de medida del producto para ir sumando el total del costo del inventario
                                 * @key1 es el id de la unidad
                                 */

                                /**
                                 * FILTRASMOS  LOS TIPO SALIDA PORQUE EN VENTAS NO SE ALMACENA EL
                                 * COSTO DE LA CAJA SINO EL COSTO DE LA UNIDAD VENDIDA
                                 * AL IGUAL QUE LA DEVOLUCION
                                 */


                                if (
                                    $is_part_of_prepack < 1 // los productos que son parte de prepacks si almacenan el costo de la caja asi que los exluyo 
                                    //auqneur no tengo forma de saber si el producto era parte de u nprepack al momento de hacer el movimeinto
                                    &&
                                    (
                                        ($data['cKardexTipo'] == 'SALIDA' // LAS SALIDAS POR VENTA
                                            &&
                                            ($data['ckardexReferencia'] == VENTA_CONTADO
                                                ||
                                                $data['ckardexReferencia'] == VENTA_CREDITO))
                                        ||
                                        //LAS ENTRADAS POR DEVOLUCION DE VENTAS
                                        ($data['cKardexTipo'] == 'ENTRADA'
                                            && ($data['ckardexReferencia'] == 'ENTRADA POR ' . VENTA_DEVOLUCION
                                                || $data['ckardexReferencia'] == NOTA_DEBITO)))
                                ) {
                                    if ($costo_caja_kardex != null && !empty($costo_caja_kardex)) {
                                        $costo_unitario = $costo_caja_kardex;
                                    } else {

                                        $costo_unitario = $costo_caja_prod;
                                    }


                                    if ($data['cKardexUnidadMedida'] != '1') {
                                        //ESTO TRAE ES EL COSTO DE LA CAJA EN BASE A LA UNIDAD DE MEDIDA QUE SE MOVIÓ
                                        $costo_caja = $this->unidades_model->costo_unitario(
                                            $producto_id,
                                            $data['cKardexUnidadMedida'],
                                            $costo_unitario
                                        );
                                    } else {
                                        $costo_caja = $costo_unitario;
                                    }
                                } else if (
                                    //REGISTRO DE FISICOS ANTES SE ESTABA GUARDANDO CON UN ESPACIO EN BLANCO AL FINAL
                                    ($data['ckardexReferencia'] === 'REGISTRO DE FISICOS' || $data['ckardexReferencia'] === 'REGISTRO DE FISICOS ')
                                    ||
                                    (
                                        // SI ES DISTINTO A TODO LO DEMAS , OSEA ES UN MOVIMIENTO POR MOVIMIENTOS DIARIOS LOS CUALES SON PARAMETRIIZABLES
                                        $data['ckardexReferencia'] !== COMPRA_A_CONTADO
                                        &&
                                        $data['ckardexReferencia'] !== COMPRA_A_CREDITO
                                        &&
                                        $data['ckardexReferencia'] !== INGRESO_OBSEQUIO
                                        &&
                                        $data['ckardexReferencia'] !== INGRESO_PREPACK
                                        &&
                                        $data['ckardexReferencia'] !== SALIDA_OBSEQUIO
                                        &&
                                        $data['ckardexReferencia'] !== SALIDA_PREPACK
                                        &&
                                        $data['ckardexReferencia'] !== MODIFICACION_COMPRA
                                        &&
                                        $data['ckardexReferencia'] !== ENTRADA_MIGRACION
                                        &&
                                        $data['ckardexReferencia'] !== TRASLADO_MERCANCIA
                                        &&
                                        strpos($data['ckardexReferencia'], COMPRA_A_) === false
                                        &&
                                        $data['ckardexReferencia'] !== ANULACION_COMPRA
                                        &&
                                        $data['ckardexReferencia'] !== INGRESO_PENDIENTE
                                        &&
                                        $data['ckardexReferencia'] !== VENTA_CREDITO
                                        &&
                                        $data['ckardexReferencia'] !== VENTA_CONTADO
                                        &&
                                        $data['ckardexReferencia'] !== 'ENTRADA POR ' . VENTA_DEVOLUCION
                                        &&
                                        $data['ckardexReferencia'] !== 'SALIDA POR ' . PEDIDO_DEVUOLUCION
                                        &&
                                        $data['ckardexReferencia'] !== NOTA_DEBITO
                                        &&
                                        $data['ckardexReferencia'] !== VENTA
                                        &&
                                        $data['ckardexReferencia'] !== 'ANULACION DE VENTA')
                                ) {
                                    /***
                                     *CUANDO es un movimiento de inventario bien sea registro fisicos o mivimietnos diarios
                                     * es un caso especial ya que el kardexcostocaja que se almacena es el de antes de hacer el movimiento
                                     *por tal razon, vamos a usar el campo nKardexPrecioUnitario dividido entre nKardexCantidad
                                     * que en este caso tampoco se almacenaba correctamente porque en vez de almacenar el unitario almacena el total, por eso dividimos entre la cantidad
                                     */

                                    if (!empty($data['nKardexCantidad']) && floatval($data['nKardexCantidad']) > 0) {

                                        $cantidadmovida = floatval($data['nKardexCantidad']);

                                        //hago esto porque en registro fisico el kardex almacena la cantidad restante y no la total
                                        if ($data['ckardexReferencia'] === 'REGISTRO DE FISICOS' || $data['ckardexReferencia'] === 'REGISTRO DE FISICOS ') {
                                            $stokactual = json_decode($data['stockUMactual']);
                                            foreach ($stokactual as $key => $value) {
                                                if ($key == $data['cKardexUnidadMedida']) {
                                                    $cantidadmovida = $value->cantidad;
                                                }
                                            }
                                        }

                                        if ($cantidadmovida > 0) {
                                            $costo_unitario = floatval($data['nKardexPrecioUnitario']) / $cantidadmovida;

                                            //

                                            if ($data['cKardexUnidadMedida'] != '1') {


                                                $costo_caja = $this->unidades_model->costo_unitario(
                                                    $producto_id,
                                                    $data['cKardexUnidadMedida'],
                                                    $costo_unitario
                                                );
                                            } else {
                                                $costo_caja = $costo_unitario;
                                            }
                                        }
                                    }
                                }

                                //AHORA SI, CALCULO EL COSTO DE LA UNIDAD EN BASE AL COSTO DE LA CAJA
                                // el escenario normal (se almacena en kardex el costo de la caja)
                                //si es tipo entrada se almacena el costo de la caja asi debo calcular el costo de la unidad unitaria del movimeinto
                                $costounidad = $this->producto_model->calcularCostoUnidad($key1, $costo_caja, $inventarios);


                                $costo_total_unidad = $costounidad * $cantidad;
                                $costo_total_producto = $costo_total_producto + $costo_total_unidad;
                            }
                        }

                        if ($porcentajeiva != null && !empty($porcentajeiva)) {
                            $iva = ($costo_total_producto * $porcentajeiva) / 100;
                        }
                    }
                }


                $json = array();
                $json[] = $producto_cod;
                $json[] = $producto_nombre;
                $json[] = $cantidades;
                $json[] = number_format(ceil($costo_total_producto), 2, ',', '.');
                $json[] = number_format(ceil($iva), 2, ',', '.');
                $json[] = number_format(ceil($costo_total_producto + $iva), 2, ',', '.');

                $json['campos_sumar'] = array();
                $json['campos_sumar'][] = 2;
                $json['campos_sumar'][] = 3;
                $json['campos_sumar'][] = 4;
                $json['campos_sumar'][] = 5;

                $datajson[] = $json;

                $count++;
                $countttt++;
                if ($countttt > 1) {
                    //  break;
                }
            }


            $array['data'] = $datajson;
            $array['draw'] = $draw; //esto debe venir por post
            $array['recordsTotal'] = $total;
            $array['recordsFiltered'] = $total; // esto dbe venir por post

            if ($array) {
                $this->response($array, 200);
            } else {
                $this->response(array(), 200);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
            log_message("error", "Error:  " . $exception->getMessage());
        }
    }


    // All
    public function productosnoafectados_get()
    {


        $data = $this->input->get('data');
        $fechaIni = $data['fecha_desde'];
        //$kardexReference = '"ENTRADA POR AJUSTE DE INVENTARIO", "SALIDA POR AJUSTE DE INVENTARIO", "REGISTRO DE FISICOS"';
        $kardexReference = '"REGISTRO DE FISICOS"';


        $fechaFin = $data['fecha_hasta'];

        if (!empty($fechaIni)) {
            $fechaIni = date('Y-m-d', strtotime($fechaIni));
        }
        if (!empty($fechaFin)) {
            $fechaFin = date('Y-m-d', strtotime($fechaFin));
        }

        $start = 0;
        $limit = false;

        $bodega = $this->opciones_model->getByKey('BODEGA_PRINCIPAL');
        $local = $bodega['config_value'];

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
        $select = 'SQL_CALC_FOUND_ROWS null as rows2, producto.*, componentes.componente_nombre,  unidades_has_producto.id_unidad, unidades_has_producto.unidades,
             unidades.nombre_unidad, inventario.id_inventario, inventario.id_local, inventario.cantidad,
		 grupos.nombre_grupo, proveedor.proveedor_nombre, impuestos.nombre_impuesto, impuestos.porcentaje_impuesto,
       clasificacion.clasificacion_nombre,producto.producto_clasificacion,
         tipo_producto.tipo_prod_nombre, ubicacion_fisica.ubicacion_nombre';
        $from = "producto";
        $join = array(
            'grupos', 'proveedor', 'impuestos', '(SELECT DISTINCT inventario.id_producto, inventario.id_inventario, inventario.cantidad, inventario.id_local FROM inventario ORDER by id_inventario DESC ) as inventario',
            'unidades_has_producto', 'unidades', 'clasificacion', 'tipo_producto', 'ubicacion_fisica', 'producto_has_componente', 'componentes',
            'producto_codigo_barra'
        );

        $campos_join = array(
            'grupos.id_grupo=producto.produto_grupo',
            'proveedor.id_proveedor=producto.producto_proveedor', 'impuestos.id_impuesto=producto.producto_impuesto', 'inventario.id_producto=producto.producto_id',
            'unidades_has_producto.producto_id=producto.producto_id', 'unidades.id_unidad=unidades_has_producto.id_unidad',
            'clasificacion.clasificacion_id = producto.producto_clasificacion',
            'tipo_producto.tipo_prod_id = producto.producto_tipo', 'ubicacion_fisica.ubicacion_id = producto.producto_ubicacion_fisica', 'producto_has_componente.producto_id=producto.producto_id',
            'componentes.componente_id=producto_has_componente.componente_id', 'producto_codigo_barra.producto_id=producto.producto_id'
        );
        $tipo_join = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left');


        $search = $this->input->get('search');
        $buscar = $search['value'];

        $where_custom = false;
        $like = false;
        if (!empty($buscar)) {

            $buscarcod = $buscar;
            if (is_numeric($buscar)) {
                $buscarcod = restCod($buscar);
            }


            $where_custom = " (producto.producto_id = '" . $buscarcod . "' or producto.producto_nombre LIKE '%" . $buscar . "%'
           or grupos.nombre_grupo LIKE '%" . $buscar . "%'
            or unidades.nombre_unidad LIKE '%" . $buscar . "%'
            or producto.producto_codigo_interno LIKE '%" . $buscar . "%'
            or clasificacion.clasificacion_nombre LIKE '%" . $buscar . "%'
            or tipo_producto.tipo_prod_nombre LIKE '%" . $buscar . "%'
            or producto.producto_sustituto LIKE '%" . $buscar . "%'
            or producto.producto_mensaje LIKE '%" . $buscar . "%'
            or componentes.componente_nombre LIKE '%" . $buscar . "%'
            or ubicacion_fisica.ubicacion_nombre LIKE '%" . $buscar . "%'
            or codigo_barra LIKE '%" . $buscar . "%'
            )";
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
                $order = 'producto.producto_nombre';
            }
        }

        $group = 'producto.producto_id';

        $data = $this->producto_model->traer_by(
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
            $like,
            $where_custom
        );


        //  $data = $this->producto_model->get_all_by(array());

        $contadorcero = 0;

        //AHORA SI EMPIEZO CON EL KARDEX

        $datajson = array();
        $todasunidades = $this->unidades_model->get_unidades();
        $draw = $this->input->get('draw');
        $selectinventario = 'inventario.cantidad';

        foreach ($data as $dat) {

            $existe = $this->venta_model->get_parcheInventario($fechaIni, $fechaFin, $kardexReference, $dat['producto_id']);

            try {

                // echo '<br><br>Num rows: '.$cantidad;


                if ($existe[0]->contador <= 0) {

                    $contadorcero++;

                    /// $this->inventario_model->updateInventarioforProduct($dat['producto_id'], 0);

                    $json = array();

                    $json[] = $dat['producto_codigo_interno'];
                    $json[] = $dat['producto_nombre'];
                    $json[] = $dat['costo_unitario'];
                    $json[] = $dat['nombre_impuesto'];
                    //todas las unidades

                    if (count($todasunidades) > 0) {


                        foreach ($todasunidades as $todas) {

                            $cantidad = 0;
                            $existencia = $this->inventario_model->get_inventario_by_producto_and_unidad(
                                $dat['producto_id'],
                                $local,
                                $todas['id_unidad'],
                                $selectinventario
                            );

                            if (isset($existencia['cantidad'])) {
                                $cantidad = $existencia['cantidad'];
                            }
                            $json[] = $cantidad;
                        }
                    }


                    $datajson[] = $json;
                }
            } catch (Exception $e) {
            }
        }


        $array['data'] = $datajson;
        $array['draw'] = $draw; //esto debe venir por post
        $array['recordsTotal'] = isset($data[0]['total_afectados']) ? $data[0]['total_afectados'] : 0;
        $array['recordsFiltered'] = isset($data[0]['total_afectados']) ? $data[0]['total_afectados'] : 0; // esto dbe venir por post

        if ($array) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    public function rep_inv_transacciones_get()
    {


        try {


            $data = $this->input->get('data');
            $fechaIni = $data['fecha_desde'];
            $fechaFin = $data['fecha_hasta'];
            $local_id = $data['local_id'];

            $datajson = array();
            $costoventa = DetalleVentaUnidadElo::whereHas('detalle_venta',
                function ($detalle_venta) use ($fechaIni, $fechaFin, $local_id) {
                    $detalle_venta->whereHas('venta',
                        function ($venta) use ($fechaIni, $fechaFin, $local_id) {
                            $venta->where('venta_status', 'COMPLETADO');
                            if (!empty($fechaIni)) {
                                $venta->where('fecha', '>=', date('Y-m-d', strtotime($fechaIni)) . " " . date('H:i:s', strtotime('0:0:0')));
                            }
                            if (!empty($fechaFin)) {
                                $venta->where('fecha', '<=', date('Y-m-d', strtotime($fechaFin)) . " " . date('H:i:s', strtotime('0:0:0')));
                            }

                            if (!empty($local_id) && $local_id != '') {
                                $venta->where('local_id', $local_id);
                            }

                        });
                })->sum(DB::raw('costo*cantidad'));

            $datajson[0] = array();
            $datajson[0][0] = 'VENTAS';
            $datajson[0][1] = 'SALIDA';
            $datajson[0][2] = number_format(ceil($costoventa), 2, ',', '.');


            $costoingreso = DetalleIngresoUnidadElo::whereHas('detalleingreso',
                function ($detalleingreso) use ($fechaIni, $fechaFin, $local_id) {
                    $detalleingreso->whereHas('ingreso', function ($ingreso) use ($fechaIni, $fechaFin, $local_id) {
                        $ingreso->where('ingreso_status', 'COMPLETADO');
                        if (!empty($fechaIni)) {
                            $ingreso->where('fecha_registro', '>=', date('Y-m-d', strtotime($fechaIni)) . " " . date('H:i:s', strtotime('0:0:0')));
                        }
                        if (!empty($fechaFin)) {
                            $ingreso->where('fecha_registro', '<=', date('Y-m-d', strtotime($fechaFin)) . " " . date('H:i:s', strtotime('0:0:0')));
                        }
                        if (!empty($local_id) && $local_id != '') {
                            $ingreso->where('local_id', $local_id);
                        }
                    });
                })->sum('costo_total');

            $datajson[1] = array();
            $datajson[1][0] = 'INGRESOS';
            $datajson[1][1] = 'ENTRADA';
            $datajson[1][2] = number_format(ceil($costoingreso), 2, ',', '.');



            $costo = AjusteDetalleElo::whereHas('ajusteinventario',
                function ($ajusteinventario) use ($fechaIni, $fechaFin, $local_id) {

                    $ajusteinventario->whereNull('tipo_ajuste');

                    if (!empty($fechaIni)) {
                        $ajusteinventario->where('fecha', '>=', date('Y-m-d', strtotime($fechaIni)) . " " . date('H:i:s', strtotime('0:0:0')));
                    }
                    if (!empty($fechaFin)) {
                        $ajusteinventario->where('fecha', '<=', date('Y-m-d', strtotime($fechaFin)) . " " . date('H:i:s', strtotime('0:0:0')));
                    }

                    if (!empty($local_id) && $local_id != '') {
                        $ajusteinventario->where('local_id', $local_id);
                    }

                })->sum('costo');

            $datajson[2] = array();
            $datajson[2][0] = 'REGISTRO DE FÍSICOS';
            $datajson[2][1] = 'ENTRADA';
            $datajson[2][2] = number_format(ceil($costo), 2, ',', '.');


            $cont = count($datajson);
            foreach (DocumentoInventarioElo::whereNotNull('deleted_at')->get() as $row) {
                $datajson[$cont] = array();
                $datajson[$cont][0] = $row->documento_nombre;
                $datajson[$cont][1] = $row->documento_tipo;
                $costo = AjusteDetalleElo::whereHas('ajusteinventario',
                    function ($ajusteinventario) use ($fechaIni, $fechaFin, $local_id, $row) {

                        $ajusteinventario->where('tipo_ajuste', $row->documento_id);

                        if (!empty($fechaIni)) {
                            $ajusteinventario->where('fecha', '>=', date('Y-m-d', strtotime($fechaIni)) . " " . date('H:i:s', strtotime('0:0:0')));
                        }
                        if (!empty($fechaFin)) {
                            $ajusteinventario->where('fecha', '<=', date('Y-m-d', strtotime($fechaFin)) . " " . date('H:i:s', strtotime('0:0:0')));
                        }

                        if (!empty($local_id) && $local_id != '') {
                            $ajusteinventario->where('local_id', $local_id);
                        }

                    })->sum('costo');

                $datajson[$cont][2] = number_format(ceil($costo), 2, ',', '.');

                $cont++;
            }


            $draw = $this->input->get('draw');
            $array['data'] = $datajson;
            $array['draw'] = $draw; //esto debe venir por post
            $array['recordsTotal'] = count($datajson);
            $array['recordsFiltered'] = count($datajson); // esto dbe venir por post

            if ($array) {
                $this->response($array, 200);
            } else {
                $this->response(array(), 200);
            }

        } catch (Exception $e) {
            $this->response(array(
                'success' => false,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ), 200);
        }
    }

    function data_print_movimiento_get()
    {

        $id = $this->input->get('id');
        $data = $this->ajustedetalle_model->get_ajuste_by_inventario($id);
        $data = (array)$data;
        $this->response($data, 200);
    }
}
