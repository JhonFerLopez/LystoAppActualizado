<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Mike42\Escpos\Printer;

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

use  Illuminate\Database\Capsule\Manager as DB;

class productos extends REST_Controller
{
    private $columnas = array();

    protected $uid = null;

    function __construct()
    {
        parent::__construct();

        $this->load->model('producto/producto_model');
        $this->load->model('tipo_producto/tipo_producto_model');
        $this->load->model('marca/marcas_model');
        $this->load->model('linea/lineas_model');
        $this->load->model('familia/familias_model');
        $this->load->model('grupos/grupos_model');
        $this->load->model('proveedor/proveedor_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('escalas/escalas_model', 'escalas');
        $this->load->model('bonificaciones/bonificaciones_model', 'bonificacion');
        $this->load->model('columnas/columnas_model');
        $this->load->model('local/local_model');
        $this->load->model('unidades/unidades_model');
        $this->load->model('opciones/opciones_model');
        $this->load->library('form_validation');
        $this->load->model('api/api_model', 'api');
        $this->load->model('componentes/componentes_model');
        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $this->load->model('grupos/grupos_model');
        $this->load->model('producto_barra/producto_barra_model');
        $this->load->model('ajustedetalle/ajustedetalle_model');

        $this->columnas = $this->columnas_model->get_by('tabla', 'producto');
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->library('ReceiptPrint');
        $this->very_auth();
    }

    function get_fotos($id)
    {
        $result = array();
        $dir = './uploads/' . $id . '/';
        if (!is_dir($dir)) return array();
        $temp = scandir($dir);
        foreach ($temp as $img) {
            if (is_file($dir . $img))
                $result[] = $img;
        }
        natsort($result);

        return $result;

    }

    function very_auth()
    {

        $reqHeader = $this->input->request_headers();

        $key = very_key($reqHeader, $this->get(), $this->post(), $this->options());

        $auth_id = $this->api->getAuth($key);
        if (!empty($auth_id)) {
            $this->uid = $auth_id;
        } else {
            $this->uid = null;
        }
    }

    function index_post()
    {


    }

    public function index_get()
    {
        $datas = array();
        $local = $this->get('local');
        if (!empty($local)) {
            $productos = $this->producto_model->get_all_by_local($local, true, false);
            foreach ($productos as $producto) {
                $producto['producto_id_cero'] = sumCod($producto['producto_id']);
                $producto['producto_id'] = $producto['producto_id'];
                if (sizeof($producto['precios']) > 0) {
                    $bonificaciones = $this->bonificacion->get_all_by_condiciones($producto['producto_id']);
                    foreach ($bonificaciones as $bono) {
                        $bono['bonificaciones_has_producto'] = $this->bonificacion->bonificaciones_has_producto('id_bonificacion', $bono['id_bonificacion']);
                        $producto['bonificaciones'][] = $bono;
                    }
                    $producto['escalas'] = $this->escalas->get_by('producto', $producto['producto_id'], true);
                    $unidades = $this->unidades_model->get_by_producto($producto['producto_id']);
                    $producto['existencia'] = 0;
                    if (isset($unidades[0])) {
                        $maxima_unidades = $unidades[0]['unidades'];
                        $cantidad_total = ($producto['cantidad'] * $maxima_unidades) + $producto['fraccion'];
                        $producto['existencia'] = $cantidad_total;
                    }
                    $datas['productos'][] = $producto;
                }
            }
        } else {
            $productos = $this->producto_model->select_all_producto();
            foreach ($productos as $producto) {
                $producto['producto_id'] = $producto['producto_id'];
                $producto['producto_id_cero'] = sumCod($producto['producto_id']);
                $datas['productos'][] = $producto;
            }
        }

        if ($datas) {
            $this->response($datas, REST_Controller::HTTP_OK);
        } else {
            $this->response(array(), REST_Controller::HTTP_OK);
        }
    }

    public function existencia_get()
    {
        $local = $this->get('local');
        $unidad = $this->get('unidad');
        $cantidad_get = $this->get('cantidad');
        $producto = $this->get('producto');
        if (empty($local)) {
            $this->response(array(), 200);
        }
        $id = false;
        if (!empty($producto)) {
            $id = $producto;
        }
        $data = array();
        $data['productos'] = $this->producto_model->get_all_by_local($local, true, $id);
        foreach ($data['productos'] as $producto) {
            $producto['producto_id'] = $producto['producto_id'];
            $producto['producto_id_cero'] = sumCod($producto['producto_id']);
            $datas['productos'][] = $producto;
        }
        $cantidad_comparar = null;
        $cantidad_total = null;
        $get_unidades = null;
        if (!empty($producto) && !empty($data['productos'])) {

            $unidades = $this->unidades_model->get_by_producto($id);
            $unidad_maxima = $unidades[0]['nombre_unidad'];
            $maxima_unidades = $unidades[0]['unidades'];
            $unidad_minima = $unidades[sizeof($unidades) - 1]['nombre_unidad'];
            $cantidad = $data['productos'][0]['cantidad'];
            $fraccion = $data['productos'][0]['fraccion'];
            $cantidad_total = ($data['productos'][0]['cantidad'] * $maxima_unidades) + $data['productos'][0]['fraccion'];
            foreach ($unidades as $u) {
                if ($u['id_unidad'] == $unidad)
                    $get_unidades = $u['unidades'];
            }
            $cantidad_comparar = ($cantidad_get * $get_unidades);
        }

        if (empty($producto)) {
            if ($data) {
                $this->response($data, 200);
            } else {
                $this->response(array(), 200);
            }
        } else {
            if ($cantidad_comparar <= $cantidad_total) {
                $this->response(true, 200);
            } else {
                $this->response(false, 200);
            }
        }
    }

    // Save
    public function create_post()
    {
        $this->form_validation->set_rules('nombre', '', 'required|trim|xss_clean');

        if ($this->form_validation->run() === false) {
            $this->response(array('status' => 'failed', 'errors' => validation_errors()), 400);
        } else {
            $post = $this->input->post(null, true);
            if (!empty($post)) {
                $save = $this->metodos_pago_model->insertar($post);
                if ($save === false) {
                    $this->response(array('status' => 'failed'));
                } else {
                    $this->response(array('status' => 'success'));
                }
            }
        }
    }

    // Update
    public function update_post()
    {
        $this->form_validation->set_rules('nombre', '', 'required|trim|xss_clean');

        if ($this->form_validation->run() === false) {
            $this->response(array('status' => 'failed', 'errors' => validation_errors()), 400);
        } else {
            $post = $this->input->post(null, true);
            if (!empty($post)) {
                //$post['id'] = $id;
                $update = $this->metodos_pago_model->update($post);
                if ($update === false) {
                    $this->response(array('status' => 'failed'));
                } else {
                    $this->response(array('status' => 'success'));
                }
            }
        }
    }


    function specialSearch_post()
    {

        $this->specialSearch_get();
    }

    function specialSearch_get()
    {

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $where_custom = false;
        $where = array();

        $local = $this->input->get('local');


        $drogueria_id = $this->input->get('drogueria_id');

        if (empty($local)) {
            $local = $this->session->userdata('id_local');
        }


        $select = 'producto.producto_id,producto.producto_nombre,producto.producto_codigo_interno,producto.producto_codigo_interno,
            producto.costo_unitario, producto.producto_ubicacion_fisica,produto_grupo, producto.producto_comision,
            producto_tipo,producto.producto_impuesto, producto.is_paquete, costo_unitario,
		    impuestos.nombre_impuesto, impuestos.porcentaje_impuesto,ubicacion_fisica.ubicacion_nombre';
        $from = "producto";

        $tipo_join = array('left', 'left', 'left');

        $join = array('impuestos', 'ubicacion_fisica', 'producto_codigo_barra');

        $campos_join = array(
            'impuestos.id_impuesto=producto.producto_impuesto',
            'ubicacion_fisica.ubicacion_id = producto.producto_ubicacion_fisica',
            'producto_codigo_barra.producto_id=producto.producto_id'
        );

        $constock = $this->input->get('constock');

        if ($constock != null and $constock != "false") {

            $tipo_join[count($tipo_join)] = '';
            $join[count($join)] = '(SELECT DISTINCT * FROM inventario WHERE cantidad>0 AND 
            inventario.id_local=' . $local . '  ORDER by id_inventario DESC ) as inventario';
            $campos_join[count($campos_join)] = 'inventario.id_producto=producto.producto_id';

            //esto es para que solo busque el stock pero que ademas tenga configurada las unidades correspondientes
            //al stock
            $tipo_join[count($tipo_join)] = '';
            $join[count($join)] = 'unidades_has_producto';
            $campos_join[count($campos_join)] = '`unidades_has_producto`.`producto_id`=`inventario`.`id_producto` AND 
            `unidades_has_producto`.`id_unidad`=`inventario`.`id_unidad`';
        }

        $is_paquete = $this->input->get('is_paquete');
        if ($is_paquete != null) {
            $where['is_paquete'] = $this->input->get('is_paquete');
        }

        $is_obsequio = $this->input->get('is_obsequio');
        if ($is_obsequio != null) {
            $where['is_obsequio'] = $this->input->get('is_obsequio');
        }

        $is_prepack = $this->input->get('is_prepack');
        if ($is_prepack != null) {
            $where['is_prepack'] = $this->input->get('is_prepack');
        }

        // Pagination Result
        $array = array();
        $array['productos'] = array();
        $array['unidades_productos'] = array();

        $total = $this->producto_model->count_all();
        $start = 0;
        $limit = false;
        if (!empty($drogueria_id)) {
            $bodega = $this->opciones_model->getByKey('BODEGA_PRINCIPAL');
            $local = $bodega['config_value'];
        }
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->input->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }

        $where['producto_activo'] = 1;
        $where['producto_estatus'] = 1;
        // $where['estatus_impuesto'] = 1;

        $search = $this->input->get('search');

        if (isset($search['value'])) {
            $buscar = $search['value'];
        } else {
            $buscar = $search;
        }


        if (!empty($buscar)) {
            $buscarcod = $buscar;
            if (is_numeric($buscar)) {
                $buscarcod = restCod($buscar);
            }


            //si $where_custom es distinto de falso, concateno, quiere decir que viene con algun dato
            //si no es falso, entonces no cocnateno, sino qe le digo que $wherecustom es igual a ese string
            if ($where_custom != false) {
                $where_custom .= " and (producto.producto_id = '" . $buscarcod . "' or producto.producto_nombre LIKE '%" . $buscar . "%' 
                or codigo_barra LIKE '%" . $buscar . "%' or producto_codigo_interno LIKE '%" . $buscar . "%')";
            } else {
                $where_custom = "(producto.producto_id = '" . $buscarcod . "' or producto.producto_nombre LIKE '%" .
                    $buscar . "%' or codigo_barra LIKE '%" . $buscar . "%' or producto_codigo_interno LIKE '%" . $buscar . "%')";
            }
        }


        $group = 'producto_id';
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
            false,
            "RESULT_ARRAY",
            $limit,
            $start,
            false,
            false,
            $where_custom
        );

        $guardar_unidades = array();
        $new_array = array();

        foreach ($productos as $producto) {

            $new_prod = $producto;
            $new_prod[] = $producto;
            $componentes = $this->componentes_model->get_all_by_user($producto['producto_id']);
            $new_prod['componentes'] = $componentes;

            $new_prod['existencia'] = $this->inventario_model->get_inventario_by_producto($producto['producto_id'], $local);
            $new_prod['precios'] = $this->unidades_has_precio_model->get_all_by_producto($producto['producto_id']);
            $where = array('unidades_has_producto.producto_id' => $producto['producto_id']);
            $contenido_interno = $this->unidades_model->solo_unidades_xprod($where);
            if ($producto['producto_codigo_interno'] == '0') {
                $guardar_unidades[$producto['producto_id']] = $contenido_interno;
            } else {
                $guardar_unidades[$producto['producto_codigo_interno']] = $contenido_interno;
            }


            $new_prod['unidades'] = $contenido_interno;
            array_push($new_array, $new_prod);
        }

        $array['contenido_interno'] = $guardar_unidades;
        $array['productos'] = $new_array;
        $array['draw'] = $draw; //esto debe venir por post
        $array['drogueria_id'] = $drogueria_id;
        $array['recordsTotal'] = $total;
        $array['recordsFiltered'] = $total; // esto dbe venir por post
        $this->response($array, 200);
    }


    function specialSearchLazzy_get()
    {

        $OPTIONS_CATEGORY_FILTER = array(
            'CLASIFICACION',
            'TIPO',
            'COMPONENTE',
            'GRUPO',
            'UBICACION_FISICA',
            'IMPUESTO',
        );

        /**
         * filters_name, debe venir en mayuscula, para mejor resultado
         */
        $filters_name = $this->input->get('filters_name');
        $filters_value = $this->input->get('filters_value');
        $in_offer = $this->input->get('in_offer');

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $where_custom = false;
        $where = array();
        $order = false;
        $data = $this->input->get('data');
        $local = isset($data['local']) ? $data['local'] : '';
        $operacion = isset($data['operacion']) ? $data['operacion'] : '';

        $drogueria_id = isset($data['drogueria_id']) ? $data['drogueria_id'] : '';

        if (empty($local)) {
            $local = $this->session->userdata('id_local');
        }

        $select = 'SQL_CALC_FOUND_ROWS producto.producto_id as total_filas,producto.in_offer, producto.producto_id,producto.producto_nombre,
		producto.producto_codigo_interno,producto.producto_codigo_interno,
            producto.costo_unitario, producto.producto_ubicacion_fisica,produto_grupo, producto.producto_comision,
            producto_tipo,producto.producto_impuesto, producto.is_paquete,producto.producto_descuentos,producto.porcentaje_descuento,
             costo_unitario,fe_type_item_identification_id,
		    impuestos.nombre_impuesto, impuestos.porcentaje_impuesto,ubicacion_fisica.ubicacion_nombre';
        $from = "producto";

        $tipo_join = array('left', 'left', 'left');

        $join = array('impuestos', 'ubicacion_fisica', 'producto_codigo_barra');

        $campos_join = array(
            'impuestos.id_impuesto=producto.producto_impuesto',
            'ubicacion_fisica.ubicacion_id = producto.producto_ubicacion_fisica',
            'producto_codigo_barra.producto_id=producto.producto_id'
        );

        $constock = $data['constock'];

        if ($constock != null and $constock != "false") {

            $tipo_join[count($tipo_join)] = 'left';
            $join[count($join)] = 'inventario';
            $campos_join[count($campos_join)] = '`inventario`.`id_producto`=`producto`.`producto_id`  ';

            //esto es para que solo busque el stock pero que ademas tenga configurada las unidades correspondientes
            //al stock
            $tipo_join[count($tipo_join)] = 'left';
            $join[count($join)] = 'unidades_has_producto';
            $campos_join[count($campos_join)] = '`unidades_has_producto`.`producto_id`=`inventario`.`id_producto` AND 
            `unidades_has_producto`.`id_unidad`=`inventario`.`id_unidad`';


        }

        $is_paquete = isset($data['is_paquete']) ? $data['is_paquete'] : null;
        if ($is_paquete != null) {
            $where['is_paquete'] = $data['is_paquete'];;
        }

        $is_obsequio = isset($data['is_obsequio']) ? $data['is_obsequio'] : null;
        if ($is_obsequio != null) {
            $where['is_obsequio'] = $data['is_obsequio'];
        }

        $is_prepack = isset($data['is_prepack']) ? $data['is_prepack'] : null;
        if ($is_prepack != null) {
            $where['is_prepack'] = $data['is_prepack'];
        }


        // Pagination Result
        $array = array();
        $array['productos'] = array();
        $array['unidades_productos'] = array();


        $start = 0;
        $limit = false;
        if (!empty($drogueria_id)) {
            $bodega = $this->opciones_model->getByKey('BODEGA_PRINCIPAL');
            $local = $bodega['config_value'];

        }
        $draw = $this->input->get('draw');

        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->input->get('length');
            if ($limit == '-1') {
                $limit = false;
            }
        }

        $where['producto_activo'] = 1;
        $where['producto_estatus'] = 1;
        // $where['estatus_impuesto'] = 1;

        $search = $this->input->get('search');
        $buscar = $search['value'];

        if (empty($buscar)) {

            if (isset($data['search'])) {
                $buscar = $data['search'];
            }
        }


        if ($constock != null and $constock != "false") {

            if ($where_custom != false) {
                $where_custom .= " and  ((inventario.cantidad>0 AND unidades_has_producto.`producto_id` 
IS NOT NULL AND inventario.id_local=" . $local . ") OR `is_paquete` =1) ";
            } else {
                $where_custom = "  ((inventario.cantidad>0 AND unidades_has_producto.`producto_id` 
IS NOT NULL AND  inventario.id_local=" . $local . ") OR `is_paquete` =1) ";
            }

        }


        if (!empty($buscar)) {
            $buscarcod = $buscar;
            if (is_numeric($buscar)) {
                $buscarcod = restCod($buscar);
            }


            //si $where_custom es distinto de falso, concateno, quiere decir que viene con algun dato
            //si no es falso, entonces no cocnateno, sino qe le digo que $wherecustom es igual a ese string
            if ($where_custom != false) {
                $where_custom .= " and (producto.producto_id = '" . $buscarcod . "' or producto.producto_nombre LIKE '%" . $buscar . "%' 
                or codigo_barra LIKE '%" . $buscar . "%' or producto_codigo_interno LIKE '%" . $buscar . "%')";
            } else {
                $where_custom = "(producto.producto_id = '" . $buscarcod . "' or producto.producto_nombre LIKE '%" .
                    $buscar . "%' or codigo_barra LIKE '%" . $buscar . "%' or producto_codigo_interno LIKE '%" . $buscar . "%')";
            }
        }
        $group = 'producto_id';

        if ($filters_name != NULL) {
            $cont = 0;
            $nombre_in = array();
            $where_in = array();
            foreach ($filters_name as $filter_name) {


                if ($filter_name == 'CLASIFICACION') {
                    $select .= ',clasificacion.clasificacion_nombre';
                    $tipo_join[] = 'inner';
                    $join[] = 'clasificacion';
                    $campos_join[] = 'producto.producto_clasificacion=clasificacion.clasificacion_id';
                    $where['producto.producto_clasificacion'] = $filters_value[$cont];
                }

                if ($filter_name == 'TIPO') {
                    $select .= ',tipo_producto.tipo_prod_nombre';
                    $tipo_join[] = 'inner';
                    $join[] = 'tipo_producto';
                    $campos_join[] = 'producto.producto_tipo=tipo_producto.tipo_prod_id';
                    $where['producto.producto_tipo'] = $filters_value[$cont];
                }

                if ($filter_name == 'COMPONENTE') {

                    $tipo_join[] = 'inner';
                    $join[] = 'producto_has_componente';
                    $campos_join[] = 'producto_has_componente.producto_id=producto.producto_id';
                    $where['producto_has_componente.componente_id'] = $filters_value[$cont];
                }

                if ($filter_name == 'GRUPO') {
                    $select .= ',grupos.nombre_grupo';
                    $tipo_join[] = 'inner';
                    $join[] = 'grupos';
                    $campos_join[] = 'producto.produto_grupo=grupos.id_grupo';
                    $where['producto.produto_grupo'] = $filters_value[$cont];
                }

                if ($filter_name == 'UBICACION_FISICA') {
                    $where['producto.producto_ubicacion_fisica'] = $filters_value[$cont];
                }

                if ($filter_name == 'IMPUESTO') {
                    $where['producto.producto_impuesto'] = $filters_value[$cont];
                }
                $cont++;
            }
        }


        if ($in_offer == 1) {
            $where['producto.in_offer'] = 1;
        }

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
            false,
            "RESULT_ARRAY",
            $limit,
            $start,
            false,
            false,
            $where_custom
        );


        //$total = $this->producto_model->count_all();

        $guardar_unidades = array();
        $new_array = array();
        $new_array_datatable = array();
        $unidades = $this->unidades_model->get_unidades();
        $total_productos = isset($productos[0]) ? $productos[0]['total_afectados'] : 0;

        $columnasToProd = VentaColumnasProductosElo::all();

        foreach ($productos as $producto) {

            $new_prod = $producto;


            $componentes = $this->componentes_model->get_all_by_user($producto['producto_id']);
            $new_prod['componentes'] = $componentes;

            $new_prod['existencia'] = $this->inventario_model->get_inventario_by_producto(
                $producto['producto_id'],
                $local,
                'cantidad'
            );


            $condicionPagoOffer = $this->condiciones_pago_model->get_by('is_offer', 1);


            if (sizeof((array)$condicionPagoOffer) > 0) {
                $new_prod['oferta'] = $this->unidades_has_precio_model->getPreciosByProdAndCondicionPago($producto['producto_id'], $condicionPagoOffer['id_condiciones']);
            }
            $where = array('unidades_has_producto.producto_id' => $producto['producto_id']);
            $contenido_interno = $this->unidades_model->solo_unidades_xprod($where);
            if ($producto['producto_codigo_interno'] == '0') {
                $guardar_unidades[$producto['producto_id']] = $contenido_interno;
            } else {
                $guardar_unidades[$producto['producto_codigo_interno']] = $contenido_interno;
            }


            $new_prod['unidades'] = $contenido_interno;
            //precios al contado para la app
            $new_prod['precios_contado'] = $this->unidades_has_precio_model->getPreciosByProdAndCondicionPago($producto['producto_id'], 1);


            $comisiona = '';
            if ($producto['producto_comision'] != null && $producto['producto_comision'] != '0') {
                $comisiona = 'comisiona';
            }


            $new_prod_datatable = array();


            $new_prod_datatable[] = $producto['producto_id'];

            $componente_nombre = '';
            foreach ($componentes as $componente) {
                $componente_nombre .= $componente['componente_nombre'] . " ";
            }
            $disponible = false;
            foreach ($unidades as $unidad) {
                foreach ($new_prod['existencia'] as $unidad2) {
                    if ($unidad2['id_unidad'] == $unidad['id_unidad']) {
                        $canuni = $unidad2['cantidad'];
                        if ($canuni > 0) {
                            $disponible = true;
                        }
                    }
                };
            };

            $yaentroenunidades = false;
            $cont=0;
            if ($columnasToProd) {

                foreach ($columnasToProd as $columna) {

                    if ($columna->mostrar == 1) {

                        if (
                            $yaentroenunidades == false
                            &&
                            ($columna->nombre_columna == 'cant'
                                ||
                                $columna->nombre_columna == 'precio'
                                ||
                                $columna->nombre_columna == 'porcent_utilidad'
                            )

                        ) {
                            $yaentroenunidades = true;

                            foreach ($unidades as $unidad) {


                                    $canuni = '-';
                                    $precio = '-';
                                    $porcent_utilidad = '-';
                                    foreach ($new_prod['existencia'] as $unidad2) {
                                        if ($unidad2['id_unidad'] == $unidad['id_unidad']) {
                                            $canuni = $unidad2['cantidad'];
                                            if ($canuni > 0) {
                                                $disponible = true;
                                            }
                                            $precio = $unidad2['precio'];
                                            $porcent_utilidad = $unidad2['utilidad'];
                                        }
                                    };



                                if($columna->nombre_columna == 'cant'){
                                    $new_prod_datatable[] = $canuni;
                                    if($columnasToProd[6]->mostrar==1) $new_prod_datatable[] = $precio;
                                    if($columnasToProd[7]->mostrar==1) $new_prod_datatable[] = $porcent_utilidad;
                                }

                                if($columna->nombre_columna == 'precio'){
                                    $new_prod_datatable[] = $precio;
                                    if($columnasToProd[6]->mostrar==1) $new_prod_datatable[] = $porcent_utilidad;
                                }

                                if($columna->nombre_columna == 'porcent_utilidad'){
                                    $new_prod_datatable[] = $porcent_utilidad;
                                }

                            }

                        }else{
                            if($columna->nombre_columna=='codigo')$new_prod_datatable[] = $producto['producto_codigo_interno'];
                            if($columna->nombre_columna=='nombre')$new_prod_datatable[] = $producto['producto_nombre'];
                            if($columna->nombre_columna=='ubicacion_fisica')$new_prod_datatable[] = $producto['ubicacion_nombre'];
                            if($columna->nombre_columna=='impuesto')$new_prod_datatable[] = !empty($producto['porcentaje_impuesto']) ? $producto['porcentaje_impuesto'] . " %" : '-';
                            if($columna->nombre_columna=='principio_activo')$new_prod_datatable[] = $componente_nombre;
                            if($columna->nombre_columna=='porcent_comision')$new_prod_datatable[] = $producto['producto_comision'];
                        }

                    }
                    $cont++;
                }
            }




            $new_prod_datatable['DT_RowId'] = $producto['producto_id'];


            $new_prod_datatable['producto_nombre'] = $producto['producto_nombre'];
            $new_prod_datatable['producto_codigo_interno'] = $producto['producto_codigo_interno'];
            $new_prod_datatable['producto_impuesto'] = $producto['porcentaje_impuesto'] != null ? $producto['porcentaje_impuesto'] : 'null';
            $new_prod_datatable['producto_impuesto_id'] = $producto['producto_impuesto'] != null ? $producto['producto_impuesto'] : 'null';
            $new_prod_datatable['costo_unitario'] = $producto['costo_unitario'];
            $new_prod_datatable['producto_ubicacion_fisica'] = $producto['producto_ubicacion_fisica'];
            $new_prod_datatable['producto_tipo'] = $producto['producto_tipo'];
            $new_prod_datatable['produto_grupo'] = $producto['produto_grupo'];
            $new_prod_datatable['class'] = $comisiona;
            $new_prod_datatable['porcentaje_descuento'] = $producto['porcentaje_descuento'];
            $new_prod['images'] = $this->get_fotos((int)$producto['producto_id']);
            $new_prod['disponible'] = $disponible;
            array_push($new_array, $new_prod);
            array_push($new_array_datatable, $new_prod_datatable);
        }

        $array['data'] = $new_array_datatable;
        $array['contenido_interno'] = $guardar_unidades;
        $array['productos'] = $new_array;
        $array['draw'] = $draw; //esto debe venir por post
        $array['drogueria_id'] = $drogueria_id;
        $array['recordsTotal'] = $total_productos;
        $array['recordsFiltered'] = $total_productos; // esto dbe venir por post
        $this->response($array, 200);
    }


    function dataTables_get()
    {

        $datajson = array();
        $columnas = $this->columnas;
        $data = $this->input->get('data');
        $local = isset($data['local']) ? $data['local'] : '';

        $control_inventario = isset($data['control_inventario']) ? $data['control_inventario'] : '';
        $control_inventario_diario = isset($data['control_inventario_diario']) ? $data['control_inventario_diario'] : '';
        $drogueria_id = isset($data['drogueria_id']) ? $data['drogueria_id'] : '';
        $produto_grupo = isset($data['produto_grupo']) ? $data['produto_grupo'] : '';
        $producto_activo = isset($data['producto_activo']) ? $data['producto_activo'] : '';


        // Pagination Result
        $array = array();

        $array['productos'] = array();


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

        if (!empty($drogueria_id)) {
            $bodega = $this->opciones_model->getByKey('BODEGA_PRINCIPAL');
            $local = $bodega['config_value'];
        }


        if (!empty($local)) {

            $where = array();
            $where['producto_estatus'] = 1;

            if ($control_inventario != '') {
                $where['control_inven'] = $control_inventario;

                if ($control_inventario == "null") {
                    $where['control_inven'] = NULL;
                }
            }

            if ($control_inventario_diario != '') {
                $where['control_inven_diario'] = $control_inventario_diario;

                if ($control_inventario_diario == "null") {
                    $where['control_inven_diario'] = NULL;
                }
            }



            if ($produto_grupo != '') {
                $where['produto_grupo'] = $produto_grupo;

                if ($produto_grupo == "null") {
                    $where['produto_grupo'] = NULL;
                }
            }
            if ($producto_activo != '') {
                $where['producto_activo'] = $producto_activo;

                if ($producto_activo == "null") {
                    $where['producto_activo'] = NULL;
                }
            }


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
                'grupos', 'proveedor', 'impuestos', '(SELECT DISTINCT inventario.id_producto, inventario.id_inventario, inventario.cantidad, inventario.id_local FROM inventario WHERE inventario.id_local=' . $local . '  ORDER by id_inventario DESC ) as inventario',
                'unidades_has_producto', 'unidades', 'clasificacion', 'tipo_producto', 'ubicacion_fisica', 'producto_has_componente', 'componentes',
                'producto_codigo_barra'
            );

            $campos_join = array(
                'grupos.id_grupo=producto.produto_grupo',
                'proveedor.id_proveedor=producto.producto_proveedor', 'impuestos.id_impuesto=producto.producto_impuesto', 'inventario.id_producto=producto.producto_id',
                'unidades_has_producto.producto_id=producto.producto_id', 'unidades.id_unidad=unidades_has_producto.id_unidad',
                'clasificacion.clasificacion_id = producto.producto_clasificacion',
                'tipo_producto.tipo_prod_id = producto.producto_tipo', 'ubicacion_fisica.ubicacion_id = producto.producto_ubicacion_fisica', 'producto_has_componente.producto_id=producto.producto_id',
                'componentes.componente_id=producto_has_componente.componente_id',
                'producto_codigo_barra.producto_id=producto.producto_id'
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
                if ($ordenar[0]['column'] == 3) {
                    $order = 'clasificacion.clasificacion_nombre';
                }
                if ($ordenar[0]['column'] == 4) {
                    $order = 'tipo_producto.tipo_prod_nombre';
                }
                if ($ordenar[0]['column'] == 5) {
                    $order = 'producto.producto_sustituto';
                }
                if ($ordenar[0]['column'] == 6) {
                    $order = 'producto.producto_mensaje';
                }
                if ($ordenar[0]['column'] == 7) {
                    $order = 'componentes.componente_nombre';
                }
                if ($ordenar[0]['column'] == 8) {
                    $order = 'ubicacion_fisica.ubicacion_nombre';
                }
                if ($ordenar[0]['column'] == 9) {
                    $order = 'unidades.nombre_unidad';
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
                $like,
                $where_custom
            );
        } else {
            $productos = $this->producto_model->select_all_producto();
        }

        $contInternos = array();
        $todasunidades = $this->unidades_model->get_unidades();
        $selectinventario = 'inventario.cantidad';

        $condicionesDePago = CondicionesPagoElo::where('status_condiciones', 1)->get();

        foreach ($productos as $producto) {
            $PRODUCTOjson = array();
            //esta variable es creada para guardar datos pero por su nombre, ya que con $PRODUCTOjson se agrega por numeros: value[0]
            // y segun el orden de las columnas
            $PRODUCTOporcolumna = array();
            $producto_id = $producto['producto_id'];
            $contador = 0;
            foreach ($columnas as $col) {

                if ((array_key_exists($col->nombre_columna, $producto)
                        or $col->nombre_columna == 'producto_componente'
                        or $col->nombre_columna == 'producto_codigo_barra'
                        or $col->nombre_columna == 'producto_precios') and $col->mostrar == TRUE) {


                    if ($col->nombre_columna != 'producto_activo') {
                        $PRODUCTOjson[$contador] = "";
                        if ($contador == 0) {
                            if (!empty($local)) {
                                $PRODUCTOjson[$contador] .= "<input type='hidden' name='producto_id_columna' value='$producto_id' />";
                            }
                        }
                        if ($col->nombre_columna == 'producto_id') {
                            $PRODUCTOjson[$contador] .= $producto['producto_id'];
                            $contador++;
                        } else {

                            if ($col->nombre_columna == 'producto_componente') {

                                $componentes = $this->componentes_model->get_all_by_user($producto['producto_id']);
                                if (sizeof($componentes) > 0) {
                                    $componentes_string = '';

                                    foreach ($componentes as $com) {
                                        $componentes_string .= ' ' . $com['componente_nombre'];
                                    }
                                    $PRODUCTOjson[$contador] = $componentes_string;
                                } else {
                                    $PRODUCTOjson[$contador] = " ";
                                }
                                $contador++;
                            } elseif ($col->nombre_columna == 'producto_codigo_barra') {

                                $whereBarra = array('producto_id' => $producto['producto_id']);
                                $barras = $this->producto_barra_model->get_codigo_barra($whereBarra);

                                if (sizeof($barras) > 0) {
                                    $barras_string = '';

                                    foreach ($barras as $row) {
                                        $barras_string .= ' ' . $row['codigo_barra'];
                                    }
                                    $PRODUCTOjson[$contador] = $barras_string;
                                } else {
                                    $PRODUCTOjson[$contador] = " ";
                                }
                                $contador++;

                            } elseif ($col->nombre_columna == 'producto_precios') {

                                if ($condicionesDePago) {

                                    if (count($todasunidades) > 0) {

                                        foreach ($condicionesDePago as $condicionPago) {

                                            foreach ($todasunidades as $todas) {

                                                $buscarPrecio = UnidadesHasPrecioElo::where('id_unidad', $todas['id_unidad'])
                                                    ->where('id_producto', $producto['producto_id'])
                                                    ->where('id_condiciones_pago', $condicionPago->id_condiciones)
                                                    ->first();

                                                if ($buscarPrecio) {

                                                    $PRODUCTOjson[$contador] = $buscarPrecio->utilidad;
                                                    $contador++;

                                                    $PRODUCTOjson[$contador] = $buscarPrecio->precio;
                                                    $contador++;
                                                } else {
                                                    $PRODUCTOjson[$contador] = '';
                                                    $contador++;

                                                    $PRODUCTOjson[$contador] = '';
                                                    $contador++;
                                                }

                                            }
                                        }
                                    }
                                }

                            } else {
                                $PRODUCTOjson[$contador] .= isset($producto[$col->nombre_join]) ? $producto[$col->nombre_join] : '';
                                $contador++;
                            }
                        }



                    }
                }

            }

            $where = array(
                'producto_id' => $producto['producto_id']
            );
            $contInternos[] = $this->unidades_model->solo_unidades_xprod($where);

            //todas las unidades
            if (count($todasunidades) > 0) {

                foreach ($todasunidades as $todas) {

                    $cantidad = 0;

                    $existencia = $this->inventario_model->get_inventario_by_producto_and_unidad(
                        $producto['producto_id'],
                        $local,
                        $todas['id_unidad'],
                        $selectinventario
                    );

                    if ($existencia != NULL && isset($existencia['cantidad'])) {
                        $cantidad = $existencia['cantidad'];
                    }
                    $PRODUCTOjson[] = $cantidad;
                }
            }


            $imagen = $this->get_fotos((int)$producto['producto_id']);
            $ruta_imagen = "uploads/" . $producto['producto_id'] . "/";
            if (count($imagen) > 0) {

                $PRODUCTOjson[] = ' <a href="#" class="img_show"
                                                   data-src="' . base_url() . $ruta_imagen . $imagen[0] . '">
                                                    <img width="70px" height="90px"
                                                         src="' . base_url() . $ruta_imagen . $imagen[0] . '">
                                                </a>';
            } else {
                $PRODUCTOjson[] = "";
            }

            $PRODUCTOjson[] = ($producto['producto_activo'] == 1) ? "Activo" : "Inactivo";

            $datajson[] = $PRODUCTOjson;

            //para guardarlos pero por indices de nombres vease ingreso.js
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // $PRODUCTOporcolumna['pedir_fecha_venc'] = $producto['pedir_fecha_venc'] ? $producto['pedir_fecha_venc'] : "";
            $PRODUCTOporcolumna['porcentaje_impuesto'] = $producto['porcentaje_impuesto'] ? $producto['porcentaje_impuesto'] : "";
            $PRODUCTOporcolumna['costo_unitario'] = $producto['costo_unitario'] ? $producto['costo_unitario'] : "";
            $PRODUCTOporcolumna['unidades_unidadmaxima'] = $producto['unidades'] ? $producto['unidades'] : "";
            $array['productos'][] = $PRODUCTOporcolumna;
        }

        $array['contInternos'] = $contInternos;
        $array['data'] = $datajson;
        $array['draw'] = $draw; //esto debe venir por post
        $array['recordsTotal'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 0;
        $array['recordsFiltered'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 0; // esto dbe venir por post


        $this->response($array, 200);

    }


    function productosComisionan_get()
    {

        $datajson = array();
        $columnas = $this->columnas;
        $data = $this->input->get('data');

        // Pagination Result
        $array = array();

        $array['productos'] = array();


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
        $where['producto_comision<>'] = null;


        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $select = 'SQL_CALC_FOUND_ROWS null as rows2, producto.*, componentes.componente_nombre,  unidades_has_producto.id_unidad, unidades_has_producto.unidades,
             unidades.nombre_unidad,
		 grupos.nombre_grupo, proveedor.proveedor_nombre, impuestos.nombre_impuesto, impuestos.porcentaje_impuesto,
       clasificacion.clasificacion_nombre,producto.producto_clasificacion,
         tipo_producto.tipo_prod_nombre, ubicacion_fisica.ubicacion_nombre';
        $from = "producto";
        $join = array(
            'grupos', 'proveedor', 'impuestos',
            'unidades_has_producto', 'unidades', 'clasificacion', 'tipo_producto', 'ubicacion_fisica', 'producto_has_componente', 'componentes'
        );

        $campos_join = array(
            'grupos.id_grupo=producto.produto_grupo',
            'proveedor.id_proveedor=producto.producto_proveedor', 'impuestos.id_impuesto=producto.producto_impuesto',
            'unidades_has_producto.producto_id=producto.producto_id', 'unidades.id_unidad=unidades_has_producto.id_unidad',
            'clasificacion.clasificacion_id = producto.producto_clasificacion',
            'tipo_producto.tipo_prod_id = producto.producto_tipo', 'ubicacion_fisica.ubicacion_id = producto.producto_ubicacion_fisica', 'producto_has_componente.producto_id=producto.producto_id',
            'componentes.componente_id=producto_has_componente.componente_id'
        );
        $tipo_join = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left');


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
            if ($ordenar[0]['column'] == 3) {
                $order = 'producto.producto_comision';
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
            $like,
            $where_custom
        );


        $contInternos = array();

        foreach ($productos as $producto) {
            $PRODUCTOjson = array();

            $PRODUCTOjson[] = $producto['producto_id'];
            $PRODUCTOjson[] = $producto['producto_codigo_interno'];
            $PRODUCTOjson[] = $producto['producto_nombre'];
            $PRODUCTOjson[] = $producto['producto_comision'];

            $datajson[] = $PRODUCTOjson;
        }

        $array['contInternos'] = $contInternos;
        $array['data'] = $datajson;
        $array['draw'] = $draw; //esto debe venir por post
        $array['recordsTotal'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 0;
        $array['recordsFiltered'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 0; // esto dbe venir por post


        $this->response($array, 200);
    }


    function productosUnidades_get()
    {  //para listar todos los productos en registro de fisicos  todos los productos y por grupo

        $datajson = array();
        $data = $this->input->get('data');
        $local = isset($data['local']) ? $data['local'] : '';
        $grupo = isset($data['grupo']) ? $data['grupo'] : '';
        $tipo = isset($data['tipo']) ? $data['tipo'] : '';
        $is_paquete = isset($data['is_paquete']) ? $data['is_paquete'] : '';
        $is_obsequio = isset($data['is_obsequio']) ? $data['is_obsequio'] : '';
        $is_prepack = isset($data['is_prepack']) ? $data['is_prepack'] : '';

        // Pagination Result
        $array = array();
        $array['productos'] = array();
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

        if ($grupo != "") {
            $where['produto_grupo'] = $grupo;
        }


        if ($is_paquete != "") {
            $where['is_paquete'] = $is_paquete;
        }

        if ($is_obsequio != null) {
            $where['is_obsequio'] = $is_obsequio;
        }

        if ($is_prepack != "") {
            $where['is_prepack'] = $is_prepack;
        }

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $select = 'SQL_CALC_FOUND_ROWS null as rows2, producto.*';
        $from = "producto";
        $join = array('producto_codigo_barra');
        $campos_join = array('producto_codigo_barra.producto_id=producto.producto_id');
        $tipo_join = array('left');

        $where_custom = false;
        $like = false;
        $ordenar = false;
        $order = false;
        $order_dir = 'desc';
        $group = 'producto.producto_id';

        $search = $this->input->get('search');
        $buscar = $search['value'];

        if (!empty($buscar)) {

            $buscarcod = $buscar;
            if (is_numeric($buscar)) {
                $buscarcod = restCod($buscar);
            }


            $where_custom = " (producto.producto_id = '" . $buscarcod . "' or producto.producto_nombre LIKE '%" . $buscar . "%'
          or producto.producto_codigo_interno LIKE '%" . $buscar . "%' or codigo_barra LIKE '%" . $buscar . "%')";
        }


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
            $like,
            $where_custom
        );

        $todasunidades = $this->unidades_model->get_unidades();


        foreach ($productos as $producto) {

            $PRODUCTOjson = array();
            $con = 0;

            $PRODUCTOjson[$con] = $producto['producto_id'] . '<input
             type="hidden" name="id_producto[]" id="id_producto_' . $producto['producto_id'] . '" value="' . $producto['producto_id'] . '">';
            $con++;
            $PRODUCTOjson[$con] = $producto['producto_nombre'] . '<input type="hidden" name="nombre_producto[' . $producto['producto_id'] . ']"
             value="' . $producto['producto_nombre'] . '">';
            $con++;

            $whereunidades = array('producto_id' => $producto['producto_id']);
            $contenido_interno = $this->unidades_model->solo_unidades_xprod($whereunidades);

            //todas las unidades
            if (count($todasunidades) > 0) {

                foreach ($todasunidades as $todas) {

                    $tienelauniad = false;
                    foreach ($contenido_interno as $unidad) {
                        if ($unidad['id_unidad'] == $todas['id_unidad']) {
                            $tienelauniad = true;
                        }
                    }
                    $readonly = '';
                    if ($tienelauniad == false) {
                        $readonly = 'readonly';
                    }
                    $disabled = '';
                    if ($tienelauniad == false) {
                        $disabled = 'disabled';
                    }


                    $PRODUCTOjson[$con] = '<input ' . $readonly . '  ' . $disabled . ' type="text" 
                     class="form-control" onkeydown="return soloNumeros(event);" ';
                    if ($tipo != 'todos') {

                        $PRODUCTOjson[$con] .= 'onkeyup="AjusteInventario.validaStock(this,event,' . $producto['producto_id'] . ',' . $todas['id_unidad'] . ')" ';
                    }
                    $PRODUCTOjson[$con] .= ' id="cantidad_' . $producto['producto_id'] . '_' . $todas['id_unidad'] . '"
                      name="cantidad_' . $producto['producto_id'] . '[]" value="" > 
                        <input ' . $readonly . '  type="hidden" class="form-control" 
                        name="unidad_' . $producto['producto_id'] . '[]" value="' . $todas['id_unidad'] . '">';
                    $con++;
                }
            }

            $datajson[] = $PRODUCTOjson;
        }


        $array['data'] = $datajson;
        $array['draw'] = $draw; //esto debe venir por post
        $array['recordsTotal'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 0;
        $array['recordsFiltered'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 0;

        $this->response($array, 200);
    }


    function paramRap_get()
    {  //para listar todos los productos en registro de fisicos  todos los productos y por grupo

        $datajson = array();
        $data = $this->input->get('data');
        $local = isset($data['local']) ? $data['local'] : '';

        // Pagination Result
        $array = array();
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
        $select = 'SQL_CALC_FOUND_ROWS null as rows2, producto.*';
        $from = "producto";

        $join = array('producto_codigo_barra');
        $campos_join = array('producto_codigo_barra.producto_id=producto.producto_id');
        $tipo_join = array('left');

        $ordenar = false;
        $order = false;
        $order_dir = 'desc';
        $group = 'producto.producto_id';

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
            or producto.producto_codigo_interno LIKE '%" . $buscar . "%'
            or producto.producto_sustituto LIKE '%" . $buscar . "%'
            or producto.producto_mensaje LIKE '%" . $buscar . "%' or codigo_barra LIKE '%" . $buscar . "%'
            )";
        }


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
            $like,
            $where_custom
        );

        $todasunidades = $this->unidades_model->get_unidades();

        $condiciones = array();
        if (isset($data['precios']) || isset($data['precio_abierto'])) {

            $condiciones = $this->condiciones_pago_model->get_all();
        }

        $grupos = array();
        if (isset($data['grupo'])) {
            $grupos = $this->grupos_model->get_grupos();
        }


        $tipos = array();
        if (isset($data['tipo'])) {
            $tipos = $this->tipo_producto_model->get_all();
        }

        $ubicacion_fisica = array();
        if (isset($data['ubicacion_fisica'])) {
            $ubicacion_fisica = $this->ubicacion_fisica_model->get_all();
        }

        $impuestos = array();
        if (isset($data['impuestos'])) {
            $impuestos = $this->impuestos_model->get_impuestos();
        }

        $tipos_item_dian = array();
        if (isset($data['tipo_item_dian'])) {
            if (!empty($this->session->userdata('FACT_E_ALLOW') and $this->session->userdata('FACT_E_ALLOW') === '1')) {
                $tipos_item_dian = $this->producto_model->get_fe_typeitems();
            }
        }


        $cont = 0;

        $array['productos'] = array();
        $array['contenidos_internos'] = new StdClass;
        $con_contenido_in = 1;

        if (count($productos) > 0) {
            foreach ($productos as $producto) {

                $array['productos'][$cont] = $producto['producto_id'];
                $contenido_interno = array();
                $precios = array();
                $PRODUCTOjson = array();
                $con = 0;

                $PRODUCTOjson[$con] = $producto['producto_id'] . '<input
             type="hidden" name="id_producto[]" value="' . $producto['producto_id'] . '">';
                $con++;

                $PRODUCTOjson[$con] = $producto['producto_codigo_interno'];
                $con++;

                $PRODUCTOjson[$con] = $producto['producto_nombre'] . '<input type="hidden" name="nombre_producto[' . $producto['producto_id'] . ']"
             value="' . $producto['producto_nombre'] . '" id="nombre_producto_' . $producto['producto_id'] . '" >';
                $con++;


                //busco los contenidos internos para guardarlos
                $whereunidades = array('producto_id' => $producto['producto_id']);
                $contenido_interno = $this->unidades_model->solo_unidades_xprod($whereunidades);
                if (count($contenido_interno) > 0) {
                    //esto es para ir guardando los contenidos internos del producto
                    $array['contenidos_internos']->{$producto['producto_id']} = new StdClass;
                    $arr = new StdClass;
                    $arr = $contenido_interno;
                    $arr['producto_id'] = $producto['producto_id'];
                    $array['contenidos_internos']->{$producto['producto_id']} = $arr;
                    $array['contenidos_internos']->size = $con_contenido_in;
                    $con_contenido_in++;
                }


                if (isset($data['precios']) || isset($data['precio_abierto'])) {
                    $where = array(
                        'id_producto' => $producto['producto_id'],
                    );
                    $precios = $this->unidades_has_precio_model->get_all_where($where);
                }


                if (isset($data['contenido_interno'])) {
                    $cont_unidades = 0;
                    foreach ($todasunidades as $todas) {

                        $tienelauniad = false;
                        $contenido = "";

                        if (count($contenido_interno) > 0) {

                            foreach ($contenido_interno as $unidad) {
                                if ($unidad['id_unidad'] == $todas['id_unidad']) {
                                    $tienelauniad = true;
                                    $contenido = $unidad['unidades'];
                                }
                            }
                        }
                        $readonly = '';


                        if (($cont_unidades > 0 && $contenido == "") || ($cont_unidades == 2)) {

                            $readonly = "readonly";
                        }

                        $PRODUCTOjson[$con] = '<input ' . $readonly . '  type="text" 
                     class="form-control" onkeydown="return soloNumeros(event);" 
                     id="contenido_in' . $producto['producto_id'] . '_' . $cont_unidades . '"
                      onkeyup="Producto.cont_paramrap(this,' . $cont_unidades . ',' . $producto['producto_id'] . ',' . $todas['id_unidad'] . ')"
                      name="contenido_in' . $producto['producto_id'] . '[' . $todas['id_unidad'] . ']" 
                      value="' . $contenido . '" >';
                        $con++;

                        $cont_unidades++;
                    }
                }

                if (isset($data['precios'])) {
                    $con_cond = 0;
                    foreach ($condiciones as $condicion) {
                        $cont_unidades = 0;
                        foreach ($todasunidades as $todas) {

                            $precio = "";
                            $utilidad = "";
                            foreach ($precios as $row) {
                                if (
                                    $row['id_unidad'] == $todas['id_unidad'] and
                                    $row['id_condiciones_pago'] == $condicion['id_condiciones']
                                ) {
                                    if ($row['precio'] != null) {
                                        $precio = $row['precio'];
                                    }

                                    if ($row['utilidad'] != null) {
                                        $utilidad = $row['utilidad'];
                                    }
                                }
                            }
                            $tienelaunidad = false;
                            if (count($contenido_interno) > 0) {
                                foreach ($contenido_interno as $unidad) {
                                    if ($unidad['id_unidad'] == $todas['id_unidad']) {
                                        $tienelaunidad = true;
                                    }
                                }
                            }
                            $readonly = '';
                            if ($tienelaunidad == false) {
                                $readonly = 'readonly';

                            }

                            $costo_unitario = "";
                            if ($producto['costo_unitario'] == null || $producto['costo_unitario'] == "") {
                                $costo_unitario = "''";

                            } else {
                                $costo_unitario = $producto['costo_unitario'];
                            }

                            $producto_impuesto = "";
                            if ($producto['producto_impuesto'] == null || $producto['producto_impuesto'] == "") {
                                $producto_impuesto = "''";

                            } else {
                                $porcentimpuesto = $this->impuestos_model->get_by('id_impuesto', $producto['producto_impuesto']);
                                if (sizeof($porcentimpuesto) > 0) {
                                    $producto_impuesto = $porcentimpuesto['porcentaje_impuesto'];
                                } else {
                                    $producto_impuesto = "''";
                                }
                            }

                            $PRODUCTOjson[$con] = '<input type="text" ' . $readonly . ' 
							name="utilidad_' . $cont . '_' . $con_cond . '[]"
                            id="utilidad_' . $producto['producto_id'] . '_' . $todas['id_unidad'] . '_' . $condicion['id_condiciones'] . '"
                             class="form-control" value="' . $utilidad . '" onkeydown="return soloDecimal(this, event)" 
                              onkeyup="Producto.calcular_precio_rap(event,this,' . $producto['producto_id'] . ',' . $todas['id_unidad'] . ',' . $costo_unitario . ',' . $producto_impuesto . ',' . $condicion['id_condiciones'] . ',' . $cont_unidades . ')" 
                             >';
                            $con++;

                            $PRODUCTOjson[$con] = '<input type="text" ' . $readonly . ' name="precio_valor_' . $cont . '_' . $con_cond . '[]"
                            id="precio_valor_' . $producto['producto_id'] . '_' . $todas['id_unidad'] . '_' . $condicion['id_condiciones'] . '"
                             class="form-control" value="' . $precio . '" 
                              onkeyup="Producto.calcular_utilidad_rap(event,this,' . $producto['producto_id'] . ',' . $todas['id_unidad'] . ',' . $costo_unitario . ',' . $producto_impuesto . ',' . $condicion['id_condiciones'] . ',' . $cont_unidades . ')" 
                                onkeydown="return soloDecimal(this, event)">
                             <input type="hidden" name="precio_id_' . $cont . '_' . $con_cond . '[]"  class="form-control" 
                             value="' . $condicion['id_condiciones'] . '" >';
                            $con++;
                            $cont_unidades++;
                        }

                        $con_cond++;
                    }
                }


                if (isset($data['codigos_barra'])) {

                    $PRODUCTOjson[$con] = '<input  type="text" 
                     class="form-control codigo_barra" 
                     onkeydown="return soloNumeros(event),Producto.inputsearch_barra(event,' . $producto['producto_id'] . ')" 
                     id="codigo_barra' . $producto['producto_id'] . '"
                      name="codigo_barra' . $producto['producto_id'] . '" 
                      value="" placeholder="Presione enter para ver" style="width:225px !important" >';
                    $con++;
                }

                if (isset($data['comision'])) {
                    $PRODUCTOjson[$con] = '<input  type="text" 
                     class="form-control" onkeydown="return soloDecimal(this,event);" 
                     id="comision_' . $producto['producto_id'] . '"
                      name="comision_' . $producto['producto_id'] . '" 
                      value="' . $producto['producto_comision'] . '"
                      data-tipo="comision"
                        onkeyup="Producto.otrosDatosRap(this,' . $producto['producto_id'] . ')">';
                    $con++;
                }

                if (isset($data['precio_abierto'])) {

                    $checked = "";
                    if ($producto['precio_abierto'] != null && $producto['precio_abierto'] > 0) {
                        $checked = 'checked="true"';
                    }
                    /* var_dump($producto['producto_id']);
                     var_dump($producto['precio_abierto']);
                     var_dump($style);*/

                    $PRODUCTOjson[$con] = '<input  type="checkbox" 
                     class="form-control" ' . $checked . '
                     id="precio_abierto_' . $producto['producto_id'] . '"
                      name="precio_abierto_' . $producto['producto_id'] . '" 
                      value="' . $producto['producto_id'] . '" 
                      data-tipo="precio_abierto"
                       data-costo_unitario="' . $producto['costo_unitario'] . '"
                       onclick="Producto.checkPrecioAbParamRap(this)" >';
                    $con++;

                    $con_cond = 0;

                    // var_dump($producto['producto_id']);
                    foreach ($condiciones as $condicion) {
                        $cont_unidades = 0;
                        foreach ($todasunidades as $todas) {

                            $preciominimo = "";
                            $preciomaximo = "";
                            foreach ($precios as $row) {
                                if (
                                    $row['id_unidad'] == $todas['id_unidad'] and
                                    $row['id_condiciones_pago'] == $condicion['id_condiciones']
                                ) {
                                    if ($row['precio_minimo'] != null) {
                                        $preciominimo = $row['precio_minimo'];
                                    }

                                    if ($row['precio_maximo'] != null) {
                                        $preciomaximo = $row['precio_maximo'];
                                    }
                                }
                            }

                            $display = "block";
                            $tienelaunidad = false;
                            if (count($contenido_interno) > 0) {
                                foreach ($contenido_interno as $unidad) {
                                    if ($unidad['id_unidad'] == $todas['id_unidad']) {
                                        $display = "none";
                                        $tienelaunidad = true;
                                    }
                                }
                            }

                            // var_dump($tienelaunidad);
                            if (
                                $producto['precio_abierto'] != null && $producto['precio_abierto'] > 0 &&
                                $tienelaunidad == true
                            ) {
                                ///var_dump("se metio 1");
                                $display = "block";
                                // var_dump($display);
                            }

                            if (
                                $producto['precio_abierto'] != null && $producto['precio_abierto'] > 0 &&
                                $tienelaunidad == false
                            ) {

                                $display = "none";
                            }

                            if ($producto['precio_abierto'] == null || $producto['precio_abierto'] < 1) {
                                $display = "none";
                            }
                            //var_dump($display);

                            $PRODUCTOjson[$con] = '<input type="text" 
                                 style="display:' . $display . '" name="precio_minimo_' . $producto['producto_id'] . '_' . $con_cond . '[]"
                            id="precio_minimo_' . $producto['producto_id'] . '_' . $todas['id_unidad'] . '_' . $condicion['id_condiciones'] . '"
                             class="form-control" value="' . $preciominimo . '" onkeydown="return soloDecimal(this, event)" 
                             data-tipo="precio_minimo"
                            onkeyup="Producto.preciAbiertosParamrap(this,' . $producto['producto_id'] . ',' . $todas['id_unidad'] . ',' . $condicion['id_condiciones'] . ')" 
                            >';
                            $con++;


                            $PRODUCTOjson[$con] = '<input type="text" 
                                 style="display:' . $display . '"  name="precio_maximo_' . $producto['producto_id'] . '_' . $con_cond . '[]"
                            id="precio_maximo_' . $producto['producto_id'] . '_' . $todas['id_unidad'] . '_' . $condicion['id_condiciones'] . '"
                             class="form-control" value="' . $preciomaximo . '" onkeydown="return soloDecimal(this, event)" 
                             data-tipo="precio_maximo"
                             onkeyup="Producto.preciAbiertosParamrap(this,' . $producto['producto_id'] . ',' . $todas['id_unidad'] . ',' . $condicion['id_condiciones'] . ')"
                             >';
                            $con++;


                        }
                        $con_cond++;
                    }

                }

                if (isset($data['grupo'])) {
                    $PRODUCTOjson[$con] = '<select 
                     class="form-control cho"
                     id="grupo_' . $producto['producto_id'] . '"
                      name="grupo_' . $producto['producto_id'] . '" 
                       data-tipo="grupo"
                        onchange="Producto.otrosDatosRap(this,' . $producto['producto_id'] . ')">';
                    $PRODUCTOjson[$con] .= '<option value="">Seleccione</option>';
                    foreach ($grupos as $row) {

                        $PRODUCTOjson[$con] .= '<option value="' . $row['id_grupo'] . '" ';
                        if ($producto['produto_grupo'] == $row['id_grupo']) {
                            $PRODUCTOjson[$con] .= ' selected ';

                        }
                        $PRODUCTOjson[$con] .= ' >' . $row['nombre_grupo'] . '</option>';

                    }

                    $con++;

                }


                if (isset($data['tipo'])) {
                    $PRODUCTOjson[$con] = '<select 
                     class="form-control cho"
                     id="tipo_' . $producto['producto_id'] . '"
                      name="tipo_' . $producto['producto_id'] . '" 
                       data-tipo="tipo"
                        onchange="Producto.otrosDatosRap(this,' . $producto['producto_id'] . ')">';
                    $PRODUCTOjson[$con] .= '<option value="">Seleccione</option>';
                    foreach ($tipos as $row) {

                        $PRODUCTOjson[$con] .= '<option value="' . $row['tipo_prod_id'] . '" ';
                        if ($producto['producto_tipo'] == $row['tipo_prod_id']) {
                            $PRODUCTOjson[$con] .= ' selected ';

                        }
                        $PRODUCTOjson[$con] .= ' >' . $row['tipo_prod_nombre'] . '</option>';

                    }

                    $con++;

                }

                if (isset($data['ubicacion_fisica'])) {
                    $PRODUCTOjson[$con] = '<select 
                     class="form-control cho"
                     id="ubicacion_' . $producto['producto_id'] . '"
                      name="ubicacion_' . $producto['producto_id'] . '"  
                       data-tipo="ubicacion"
                        onchange="Producto.otrosDatosRap(this,' . $producto['producto_id'] . ')">';
                    $PRODUCTOjson[$con] .= '<option value="">Seleccione</option>';
                    foreach ($ubicacion_fisica as $row) {

                        $PRODUCTOjson[$con] .= '<option value="' . $row['ubicacion_id'] . '" ';
                        if ($producto['producto_ubicacion_fisica'] == $row['ubicacion_id']) {
                            $PRODUCTOjson[$con] .= ' selected ';

                        }
                        $PRODUCTOjson[$con] .= ' >' . $row['ubicacion_nombre'] . '</option>';
                    }
                    $con++;
                }

                if (isset($data['impuestos'])) {
                    $PRODUCTOjson[$con] = '<select 
                     class="form-control cho"
                     id="impuestos_' . $producto['producto_id'] . '"
                      name="impuestos_' . $producto['producto_id'] . '"  
                       data-tipo="impuestos" 
                       
                        onchange="Producto.otrosDatosRap(this,' . $producto['producto_id'] . ')">';
                    $vacio = "''";
                    $PRODUCTOjson[$con] .= '<option value="" data-porcentaje_impuesto="' . $vacio . '">Seleccione</option>';
                    foreach ($impuestos as $row) {

                        $PRODUCTOjson[$con] .= '<option value="' . $row['id_impuesto'] . '"  
                        data-porcentaje_impuesto="' . $row['porcentaje_impuesto'] . '" ';
                        if ($producto['producto_impuesto'] == $row['id_impuesto']) {
                            $PRODUCTOjson[$con] .= ' selected ';
                        }
                        $PRODUCTOjson[$con] .= ' >' . $row['nombre_impuesto'] . '</option>';
                    }
                    $con++;
                }


                if (isset($data['tipo_item_dian'])) {

                    $PRODUCTOjson[$con] = '<select 
                     class="form-control cho"
                     id="tipo_item_dian_' . $producto['producto_id'] . '"
                      name="tipo_item_dian_' . $producto['producto_id'] . '"  
                       data-tipo="tipo_item_dian"
                        onchange="Producto.otrosDatosRap(this,' . $producto['producto_id'] . ')">';
                    $PRODUCTOjson[$con] .= '<option value="">Seleccione</option>';
                    foreach ($tipos_item_dian as $row) {

                        $PRODUCTOjson[$con] .= '<option value="' . $row->id . '" ';
                        if ($producto['fe_type_item_identification_id'] == $row->id) {
                            $PRODUCTOjson[$con] .= ' selected ';
                        }
                        $PRODUCTOjson[$con] .= ' >' . $row->name . '</option>';
                    }
                }


                $cont++;
                $datajson[] = $PRODUCTOjson;
            }
        }

        $array['data'] = $datajson;
        $array['draw'] = $draw; //esto debe venir por post
        $array['recordsTotal'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 1;
        $array['recordsFiltered'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 1;


        $this->response($array, 200);

    }


    function updateCostos_post()
    {
        //Esto es un parche para ajustar los costos de los productos que se crearon antes del migrate 20171120103201

        try {

            $where = array(
                'producto_id >' => 0
            );
            $productos = $this->producto_model->get_all_by($where);

            if (count($productos) > 0) {
                foreach ($productos as $producto) {
                    $where = array(
                        'producto_id' => $producto['producto_id']
                    );
                    $unidades_producto = $this->unidades_model->solo_unidades_xprod($where);

                    if (count($unidades_producto) > 0 && $producto['costo_unitario'] != '') {
                        foreach ($unidades_producto as $unidad) {

                            $costoestaunidad = $this->producto_model->calcularCostoUnidad($unidad['id_unidad'], $producto['costo_unitario'], $unidades_producto);
                            $unidad_has_producto = array(
                                "costo" => $costoestaunidad,
                            );

                            $this->db->where('id_unidad', $unidad['id_unidad']);
                            $this->db->where('producto_id', $producto['producto_id']);
                            $this->db->update('unidades_has_producto', $unidad_has_producto);
                        }
                    }
                }
            }
            //Fin de la actualizacion del costo
            //fin del parche

            $this->response(array("result" => OK), 200);
        } catch (Exception $e) {
            log_message('ERROR', 'ocurrio un error al ejecutar el parche de productos costo');
            log_message('ERROR', $e->getMessage());
            $this->response(array("result" => FAIL, "mesagge" => $e->getMessage()), 200);
        }

    }

    function updateCostosPromedio_post()
    {
        //Esto es un parche para ajustar los costos promedio de los productos que se crearon antes del migrate 20171120103201

        try {
            $this->load->model('detalle_ingreso_unidad/detalle_ingreso_unidad_model');
            $this->load->model('ingreso/ingreso_model');
            $this->load->model('detalleingreso_especial/detalleingreso_especial_model');

            $where = array(
                'producto_id >' => 0
            );
            $productos = $this->producto_model->get_all_by($where);

            if (count($productos) > 0) {
                foreach ($productos as $producto) {

                    $where = array(
                        'id_producto' => $producto['producto_id'],
                    );
                    $buscarDetalleUnidad = $this->detalle_ingreso_unidad_model->getDetalleUnidadDetalle($where);

                    $ultimasuma = 0;
                    $ultimacantidadcaja = 0;

                    $costo_unitario = $producto['costo_unitario'];

                    $where = array(
                        'producto_id' => $producto['producto_id']
                    );
                    $unidadesviejas = $this->unidades_model->solo_unidades_xprod($where);


                    $pasoprimeravez = false;
                    if (count($buscarDetalleUnidad) > 0) {


                        foreach ($buscarDetalleUnidad as $detalleunidad) {

                            if ($pasoprimeravez == true) {
                                //lo hago por cada unidad, ya que por cada unidad, actualizo el costo unitario del producto.
                                $productoactual = $this->producto_model->get_by('producto_id', $producto['producto_id']);

                                $ultimasuma = $productoactual['suma_costo_caja'];
                                $ultimacantidadcaja = $productoactual['cantidad_caja'];

                            } else {
                                $pasoprimeravez = true; //esto lo hago para que ya en la segunda iteracion, no entre aqui.
                            }


                            if ($detalleunidad->costo_unitario_despues != NULL) {
                                $costo_unitario = $detalleunidad->costo_unitario_despues;

                            } else {
                                $costo_unitario_antes = $costo_unitario;

                                $where = array(
                                    'ingreso' => $detalleunidad->id_ingreso,
                                    'tipo' => "OBSEQUIO",
                                    'producto_id_especial' => $detalleunidad->id_producto
                                );
                                $detalleespecial = $this->detalleingreso_especial_model->getDetalleEspecialDetalle($where);


                                $costo_unitario_despues = $this->unidades_model->costo_unitario(
                                    $producto['producto_id'],
                                    $detalleunidad->unidad_id,
                                    $detalleunidad->costo
                                );


                                $costabaestaunidad = false;
                                if ($detalleunidad->costoestaunidad_antes == NULL) {
                                    $costabaestaunidad = $this->producto_model->calcularCostoUnidad(
                                        $detalleunidad->unidad_id,
                                        $costo_unitario,
                                        $unidadesviejas
                                    );
                                }

                                $datos = array(
                                    'costoestaunidad_antes' => $costabaestaunidad,
                                    'costo_unitario_antes' => $costo_unitario_antes,
                                    'costo_unitario_despues' => $costo_unitario_despues,
                                );
                                $this->db->where('detalle_ingreso_unidad_id', $detalleunidad->detalle_ingreso_unidad_id);
                                $this->db->update('detalle_ingreso_unidad', $datos);

                                $costo_unitario = $costo_unitario_despues;
                            }

                            //actualizo el costo promedio
                            $this->ingreso_model->costo_promedio(
                                "SUMAR",
                                $ultimasuma,
                                $ultimacantidadcaja,
                                $costo_unitario,
                                0,
                                $producto['producto_id']
                            );
                        }
                    } else {

                        $this->db->where('producto_id', $producto['producto_id']);
                        $this->db->update('producto', array(
                            'suma_costo_caja' => 0,
                            'cantidad_caja' => 0
                        ));
                    }


                }
            }
            //Fin de la actualizacion del costo
            //fin del parche

            $this->response(array("result" => OK), 200);
        } catch (Exception $e) {
            log_message('ERROR', 'ocurrio un error al ejecutar el parche de productos costo');
            log_message('ERROR', $e->getMessage());
            $this->response(array("result" => FAIL, "mesagge" => $e->getMessage()), 200);
        }

    }

    private function calcular_impuesto($calculo, $iva)
    {
        /**esta funcion retorna el calculo ya realizado de un monto en base al porcentaje del impuesto*/

        $calculo_impuesto = ($iva / 100);
        $calculo_impuesto = floatval($calculo) * floatval($calculo_impuesto);
        $calculo = floatval($calculo) + floatval($calculo_impuesto);
        return $calculo;
    }

    function updatePrecios_post()
    {
        $post = $this->input->post();
        try {

            $productos = $this->producto_model->select_all_producto();

            $tipo_producto = $this->input->post('tipo_producto');
            $precio_base = $this->input->post('precio_base');
            $redondeo = $this->input->post('redondeo');

            if (count($productos) > 0) {
                foreach ($productos as $producto) {

                    if ($producto['costo_unitario'] != '0' && $producto['costo_unitario'] != '') {

                        if (in_array($producto['producto_tipo'], $tipo_producto) || in_array('-1', $tipo_producto)) {
                            $tipocalculo = $post['tipo_calculo'];

                            $where = array(
                                'producto_id' => $producto['producto_id']
                            );
                            $unidades_producto = $this->unidades_model->solo_unidades_xprod($where);


                            if (count($unidades_producto) > 0 && $producto['costo_unitario'] != '') {
                                foreach ($unidades_producto as $unidad) {

                                    if ($precio_base == 'COSTO') {
                                        $calculo = $this->producto_model->calcularCostoUnidad($unidad['id_unidad'], $producto['costo_unitario'], $unidades_producto);

                                    } else {
                                        $un = $this->unidades_has_precio_model->get_by_unidad_and_producto($producto['producto_id'], $unidad['id_unidad']);

                                        $calculo = 0;
                                        if (isset($un[0]['precio'])) {
                                            $calculo = $un[0]['precio'];
                                            $utilidadd = $un[0]['utilidad'];
                                        }
                                    }

                                    $precio_actulizar = $post['precio_actulizar'];
                                    $porcentaje = $post['porcentaje'];
                                    $iva = $producto['porcentaje_impuesto'];
                                    if ($tipocalculo == 'FINANCIERO') {

                                        if ($iva != "") {
                                            $calculo = $this->calcular_impuesto($calculo, $iva);
                                        }

                                        $calculo_utilidad = $porcentaje - 100;
                                        $calculo_utilidad = abs($calculo_utilidad);
                                        $calculo_utilidad = $calculo_utilidad / 100;
                                        $calculo = $calculo / $calculo_utilidad;
                                        $calculo = ($calculo / 100) * 100;
                                        $nuevoprecio = $calculo;
                                    } else {

                                        $calculo_utilidad = ($porcentaje / 100);

                                        $calculo_utilidad = $calculo * $calculo_utilidad;
                                        $calculo = $calculo + $calculo_utilidad;
                                        // calculo = Math.round(parseFloat(calculo));
                                        $nuevoprecio = $calculo;
                                    }

                                    $nuevoprecio = $this->ceiling($nuevoprecio, $redondeo);
                                    $unidad_has_precio = array(
                                        "precio" => $nuevoprecio,
                                        "utilidad" => ($precio_base == 'COSTO') ? $porcentaje : $utilidadd,
                                    );

                                    $where = array(
                                        'id_unidad' => $unidad['id_unidad'],
                                        'id_condiciones_pago' => $precio_actulizar,
                                        'id_producto' => $producto['producto_id']
                                    );
                                    $this->db->where($where);
                                    $query = $this->db->get('unidades_has_precio');
                                    $results = $query->row_array();


                                    if (sizeof($results) > 0) {
                                        $this->db->where($where);
                                        $this->db->update('unidades_has_precio', $unidad_has_precio);
                                    } else {

                                        $unidad_has_precio = array(
                                            "precio" => $nuevoprecio,
                                            "utilidad" => $porcentaje,
                                            'id_unidad' => $unidad['id_unidad'],
                                            'id_condiciones_pago' => $precio_actulizar,
                                            'id_producto' => $producto['producto_id']
                                        );

                                        $this->db->insert('unidades_has_precio', $unidad_has_precio);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            //Fin de la actualizacion del costo
            //fin del parche

            $this->response(array("result" => OK), 200);
        } catch (Exception $e) {
            log_message('ERROR', 'Ocurrio un error al ejecutar la actualizacion, puede que algunos productos no se hayan actulizado');
            log_message('ERROR', $e->getMessage());
            $this->response(array("result" => FAIL, "mesagge" => 'Ocurrio un error al ejecutar la actualizacion, puede que algunos productos no se hayan actulizado\''), 200);
        }

    }


    private function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
    }

    function searchProdApp_post()
    {

        $this->searchProdApp_get();
    }

    function searchProdApp_get()
    {


        $OPTIONS_CATEGORY_FILTER = array
        ('CLASIFICACION',
            'TIPO',
            'COMPONENTE',
            'GRUPO',
            'UBICACION_FISICA',
            'IMPUESTO',
        );

        /**
         * filters_name, debe venir en mayuscula, para mejor resultado
         */
        $filters_name = $this->input->get('filters_name');
        $filters_value = $this->input->get('filters_value');

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $where_custom = false;
        $where = array();
        $select = 'producto.producto_id,producto.producto_nombre,producto.producto_codigo_interno';
        $from = "producto";
        $tipo_join = array('left');
        $join = array('producto_codigo_barra');
        $campos_join = array(
            'producto_codigo_barra.producto_id=producto.producto_id'
        );

        // Pagination Result
        $array = array();
        $array['productos'] = array();
        $array['unidades_productos'] = array();

        $where['producto_activo'] = 1;
        $where['producto_estatus'] = 1;

        if ($filters_name != NULL) {
            $cont = 0;
            $nombre_in = array();
            $where_in = array();
            foreach ($filters_name as $filter_name) {

                if ($filter_name == 'CLASIFICACION') {
                    $select .= ',clasificacion.clasificacion_nombre';
                    $tipo_join[] = 'inner';
                    $join[] = 'clasificacion';
                    $campos_join[] = 'producto.producto_clasificacion=clasificacion.clasificacion_id';
                    $where['producto.producto_clasificacion'] = $filters_value[$cont];
                }

                if ($filter_name == 'TIPO') {
                    $select .= ',tipo_producto.tipo_prod_nombre';
                    $tipo_join[] = 'inner';
                    $join[] = 'tipo_producto';
                    $campos_join[] = 'producto.producto_tipo=tipo_producto.tipo_prod_id';
                    $where['producto.producto_tipo'] = $filters_value[$cont];
                }

                if ($filter_name == 'COMPONENTE') {

                    $tipo_join[] = 'inner';
                    $join[] = 'producto_has_componente';
                    $campos_join[] = 'producto_has_componente.producto_id=producto.producto_id';
                    $where['producto_has_componente.componente_id'] = $filters_value[$cont];
                }

                if ($filter_name == 'GRUPO') {
                    $select .= ',grupos.nombre_grupo';
                    $tipo_join[] = 'inner';
                    $join[] = 'grupos';
                    $campos_join[] = 'producto.produto_grupo=grupos.id_grupo';
                    $where['producto.produto_grupo'] = $filters_value[$cont];
                }

                if ($filter_name == 'UBICACION_FISICA') {
                    $select .= ',ubicacion_fisica.ubicacion_nombre';
                    $tipo_join[] = 'inner';
                    $join[] = 'ubicacion_fisica';
                    $campos_join[] = 'producto.producto_ubicacion_fisica=ubicacion_fisica.ubicacion_id';
                    $where['producto.producto_ubicacion_fisica'] = $filters_value[$cont];
                }

                if ($filter_name == 'IMPUESTO') {
                    $select .= ',impuestos.nombre_impuesto';
                    $tipo_join[] = 'inner';
                    $join[] = 'impuestos';
                    $campos_join[] = 'producto.producto_impuesto=impuestos.id_impuesto';
                    $where['producto.producto_impuesto'] = $filters_value[$cont];
                }
                $cont++;
            }
        }


        // $where['estatus_impuesto'] = 1;
        $search = "";
        if ($this->input->get('search')) {
            $search = $this->input->get('search');
        } elseif ($this->input->post('search')) {
            $search = $this->input->post('search');
        }

        $buscar = $search;
        if (!empty($buscar)) {

            $buscarcod = $buscar;
            if (is_numeric($buscar)) {
                $buscarcod = restCod($buscar);
            }


            //si $where_custom es distinto de falso, concateno, quiere decir que viene con algun dato
            //si no es falso, entonces no cocnateno, sino qe le digo que $wherecustom es igual a ese string
            if ($where_custom != false) {
                $where_custom .= " and (producto.producto_id = '" . $buscarcod . "' or producto.producto_nombre LIKE '%" . $buscar . "%' 
                or codigo_barra LIKE '%" . $buscar . "%' or producto_codigo_interno LIKE '%" . $buscar . "%')";

            } else {
                $where_custom = "(producto.producto_id = '" . $buscarcod . "' or producto.producto_nombre LIKE '%" .
                    $buscar . "%' or codigo_barra LIKE '%" . $buscar . "%' or producto_codigo_interno LIKE '%" . $buscar . "%')";
            }
        }


        $group = 'producto_id';
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
            false,
            "RESULT_ARRAY",
            false,
            false,
            false,
            false,
            $where_custom
        );

        $guardar_unidades = array();
        $new_array = array();

        foreach ($productos as $producto) {

            $new_prod = $producto;
            $where = array('unidades_has_producto.producto_id' => $producto['producto_id']);
            $contenido_interno = $this->unidades_model->solo_unidades_xprod($where);
            if ($producto['producto_codigo_interno'] == '0') {
                $guardar_unidades[$producto['producto_id']] = $contenido_interno;
            } else {
                $guardar_unidades[$producto['producto_codigo_interno']] = $contenido_interno;
            }
            $new_prod['unidades'] = $contenido_interno;
            array_push($new_array, $new_prod);
        }
        $array['productos'] = $new_array; // esto dbe venir por post
        $this->response($array, 200);
    }

    function saveProdCantidad_post()
    {
        $this->saveProdCantidad_get();
    }

    function saveProdCantidad_get()
    {

        $this->load->model('ajusteinventario/ajusteinventario_model');
        $this->load->model('kardex/kardex_model');
        $this->load->model('venta/venta_model');
        $this->load->model('ajustedetalle/ajustedetalle_model');

        $id_local = 1;
        $usuario_id = $this->input->post('usuario_id');
        $productos = $this->input->post('producto_id');
        $unidades = $this->input->post('unidad_id');
        $cantidades = $this->input->post('cantidad');
        $ubicacion = array();
        $fecha = date('Y-m-d H:i:s');
        $INVENTARIO_UBICACION_REQUERIDO = false;
        $imprimir = false;
        $json = $this->ajusteinventario_model->saveRegistroFisico(REG_FISICO_APP, $id_local,
            $usuario_id, $productos, $unidades, $cantidades, $ubicacion, $fecha,
            $INVENTARIO_UBICACION_REQUERIDO, $imprimir);
        $this->response($json, 200);
    }


    function productos_mas_vendidos_data_get()
    {

        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';
        $filtrarpor = isset($data['filtrarpor']) ? $data['filtrarpor'] : '';

        $condicion = array();
        $condicion['venta_status'] = COMPLETADO;
        if ($fechadesde != "") {
            $condicion['date(fecha) >= '] = date('Y-m-d', strtotime($fechadesde));
            $data['fecha_desde'] = date('Y-m-d', strtotime($fechadesde));
        }
        if ($fechahasta != "") {
            $condicion['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));
            $data['fecha_hasta'] = date('Y-m-d', strtotime($fechahasta));
        }


        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $select = 'SQL_CALC_FOUND_ROWS null as rows2, 
		count(detalle_venta.id_producto)as contador,detalle_venta.id_detalle,detalle_venta.id_venta,
		 detalle_venta.id_producto,producto.producto_nombre, producto.producto_codigo_interno';
        $from = "venta";
        $join = array('detalle_venta', 'producto', 'detalle_venta_unidad');
        $campos_join = array(
            'detalle_venta.id_venta=venta.venta_id',
            'producto.producto_id=detalle_venta.id_producto',
            '`detalle_venta`.`id_detalle`=`detalle_venta_unidad`.`detalle_venta_id`'
        );

        $where_custom = false;
        $order_dir = 'desc';
        $order = 'contador';
        $group = 'detalle_venta.id_producto';
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


        $productos = $this->producto_model->traer_by(
            $select,
            $from,
            $join,
            $campos_join,
            false,
            $condicion,
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


        $unidades = $this->unidades_model->get_unidades();
        $datajson = array();

        foreach ($productos as $prod) {

            $where = array('unidades_has_producto.producto_id' => $prod['id_producto']);
            $contenido_interno = $this->unidades_model->solo_unidades_xprod($where);


            $condicion = array('detalle_venta.id_producto' => $prod['id_producto']);
            $condicion['venta_status'] = COMPLETADO;
            if ($fechadesde != "") {
                $condicion['date(fecha) >= '] = date('Y-m-d', strtotime($fechadesde));
                $data['fecha_desde'] = date('Y-m-d', strtotime($fechadesde));
            }
            if ($fechahasta != "") {
                $condicion['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));
                $data['fecha_hasta'] = date('Y-m-d', strtotime($fechahasta));
            }
            $select = 'SQL_CALC_FOUND_ROWS null as rows2, detalle_venta.id_detalle,
		 detalle_venta.id_producto,producto.producto_nombre, producto.producto_codigo_interno,
		 detalle_venta_unidad.cantidad,detalle_venta_unidad.unidad_id';
            $from = "venta";
            $join = array('detalle_venta', 'producto', 'detalle_venta_unidad');
            $campos_join = array(
                'detalle_venta.id_venta=venta.venta_id',
                'producto.producto_id=detalle_venta.id_producto',
                '`detalle_venta`.`id_detalle`=`detalle_venta_unidad`.`detalle_venta_id`'
            );
            $order = 'detalle_venta.id_producto';
            $order_dir = 'desc';
            $start = 0;
            $limit = false;
            $detalle_venta_unidad = $this->producto_model->traer_by(
                $select,
                $from,
                $join,
                $campos_join,
                false,
                $condicion,
                $nombre_in,
                $where_in,
                $nombre_or,
                $where_or,
                false,
                $order,
                "RESULT_ARRAY",
                $limit,
                $start,
                $order_dir,
                false,
                $where_custom
            );


            $json = array();
            $json['unidades'] = $unidades;
            $json['total_unidades_minimas'] = 0;
            foreach ($detalle_venta_unidad as $detalleunidad) {

                $convertominima = $this->inventario_model->covertUnidadMinima(
                    $contenido_interno,
                    $detalleunidad['unidad_id'],
                    $detalleunidad['cantidad']
                );
                $json['total_unidades_minimas'] = $json['total_unidades_minimas'] + $convertominima;
                $conttodas_unidades = 0;
                foreach ($json['unidades'] as $rowt) {

                    if (!isset($json['unidades'][$conttodas_unidades]['total_vendido'])) {
                        $json['unidades'][$conttodas_unidades]['total_vendido'] = 0;
                    }
                    if ($rowt['id_unidad'] == $detalleunidad['unidad_id']) {

                        $json['unidades'][$conttodas_unidades]['total_vendido'] = $json['unidades'][$conttodas_unidades]['total_vendido'] + $detalleunidad['cantidad'];
                    }

                    $conttodas_unidades++;
                }
            }

            $representarUnMinToAllUn = $this->representarUnMinToAllUn($unidades, $contenido_interno, $json['total_unidades_minimas']);

            $json[] = $prod['producto_codigo_interno'];
            $json[] = $prod['producto_nombre'];
            $json[] = $prod['contador'];

            foreach ($json['unidades'] as $rowunidades) {
                $total_vendido = 0;
                if (isset($rowunidades['total_vendido'])) {
                    $total_vendido = $rowunidades['total_vendido'];
                }
                $json[] = $total_vendido;
            }

            foreach ($representarUnMinToAllUn as $row) {
                $json[] = $row['cantidad'];
            }

            $datajson[] = $json;
        }


        $array['data'] = $datajson;
        $array['draw'] = $draw; //esto debe venir por post
        $array['recordsTotal'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 1;
        $array['recordsFiltered'] = isset($productos[0]['total_afectados']) ? $productos[0]['total_afectados'] : 1;


        $this->response($array, 200);
    }

    //representa en caja blister y unidad, un total de unidades minimas del producto.
    function representarUnMinToAllUn($unidades, $unidades_producto, $total_unidades_minimas)
    {
        //declaro el objeto de cada unidad
        $caja = $unidades_producto[0];
        $sizeof = sizeof($unidades_producto);
        $unidades[0]['cantidad'] = 0;
        $unidades[1]['cantidad'] = 0;
        $unidades[2]['cantidad'] = 0;

        if ($sizeof == 3) {
            $blister = $unidades_producto[1];
            $unidad = $unidades_producto[2];
        } else if ($sizeof == 2) {
            $unidad = $unidades_producto[1];
        }


        if ($total_unidades_minimas >= $caja['unidades']) {

            $unidades[0]['cantidad'] = intval($total_unidades_minimas / $caja['unidades']);

        } else {
            $unidades[0]['cantidad'] = 0;

            if ($total_unidades_minimas < -9 && $total_unidades_minimas) {

                $unidades[0]['cantidad'] = "-" . (intval(abs($total_unidades_minimas) / $caja['unidades']));
            }
        }

        $modulo = abs($total_unidades_minimas) % $caja['unidades'];

        if (isset($unidad['unidades']) and $modulo >= $unidad['unidades']) {

            $unidades[1]['cantidad'] = intval(($modulo) / $unidad['unidades']);
            $unidades[2]['cantidad'] = ($modulo) % $unidad['unidades'];

            if ($unidad['unidades'] == '1') {

                $unidades[2]['cantidad'] = $modulo;
            }

            if ($total_unidades_minimas < 0) {

                $unidades[1]['cantidad'] = "-" . $unidades[1]['cantidad'];
                $unidades[2]['cantidad'] = "-" . $unidades[2]['cantidad'];
            }

        } else {

            $unidades[2]['cantidad'] = $total_unidades_minimas % $caja['unidades'];

        }

        if (!isset($blister)) {
            $unidades[1]['cantidad'] = 0;
        }
        if (!isset($unidad)) {
            $unidades[2]['cantidad'] = 0;
        }
        return $unidades;
    }

    function imprimirajuste_get($id = '')
    {
        try {
            if (empty($id)) {
                $id = $this->input->get('id');
            }

            $this->inventario_model->imprimirajuste($id);

        } catch
        (Exception $e) {
            log_message("error", "Error: Could not print. Message " . $e->getMessage());
            echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
            $this->receiptprint->close_after_exception();
        }
    }

    function productToSelect2_get()
    {

        try {

            $data=array();

            $search = $this->input->get('search');
            $productos = ProductoElo::with('contenido_interno')->where('producto_estatus',1)->where('producto_activo',1)
                ->where(function ($productos) use ($search) {
                    $productos->where('producto_nombre', 'like', '%' . strtolower($search) . '%')
                        ->orWhere('producto_codigo_interno', 'like', '%' . strtolower($search) . '%')
                        ->orWhere('producto_id', 'like', '%' . strtolower($search) . '%');
                })
                ->select(DB::raw("CONCAT(producto_codigo_interno, '-', producto_nombre) as text,producto_id as id, producto.* "))
                ->limit(30)
                ->orderBy('producto_id', 'desc')
                ->get();
            $data['productos'] = $productos;
            $this->response($data, 200);

        } catch (Exception $e) {
            log_message('ERROR', 'Ocurrió un error al buscar los productos para el select2');
            log_message('ERROR', $e->getMessage());
            $this->response(array("message" => $e->getMessage()), 400);
        }
    }
}
