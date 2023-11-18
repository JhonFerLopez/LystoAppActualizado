<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Builder;

class producto extends MY_Controller
{

    private $columnas = array();
    private $_producto;

    function __construct()
    {
        parent::__construct();
        $this->load->model('producto/producto_model');
        $this->load->model('producto/paquete_has_prod_model');
        $this->load->model('marca/marcas_model');
        $this->load->model('linea/lineas_model');
        $this->load->model('familia/familias_model');
        $this->load->model('grupos/grupos_model');
        $this->load->model('subfamilia/subfamilias_model');
        $this->load->model('subgrupos/subgrupos_model');
        $this->load->model('proveedor/proveedor_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('venta/venta_model');
        $this->load->model('drogueria_relacionada/drogueria_relacionada_model');

        $this->load->model('unidades/unidades_model');
        $this->load->model('bonificaciones/bonificaciones_model');
        $this->load->model('descuentos/descuentos_model');
        $this->load->model('columnas/columnas_model');

        $this->load->model('local/local_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->load->model('clasificacion/clasificacion_model');
        $this->load->model('tipo_producto/tipo_producto_model', 'tipo_producto');
        $this->load->model('producto_barra/producto_barra_model');
        $this->load->model('componentes/componentes_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('system_logs/systemLogsModel');

        //$this->load->library('Pdf');
        //$this->load->library('phpExcel/PHPExcel.php');
        $this->very_sesion();

        $this->_producto = new ProductoElo();

        $this->columnas = $this->columnas_model->get_by('tabla', 'producto');
    }

    function index()
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }


        $data['locales'] = $this->local_model->get_all();
        $data['unidades'] = $this->unidades_model->get_unidades();
        $data['droguerias_relacionadas'] = $this->drogueria_relacionada_model->get_all();
        $data['grupos'] = $this->grupos_model->get_grupos();
        $data['precios'] = $this->condiciones_pago_model->get_all();
        $data['tipos_producto'] = $this->tipo_producto->get_all();
        $data['columnas'] = $this->columnas;
        $data['condicionesDePago'] = CondicionesPagoElo::where('status_condiciones', 1)->get();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/producto/producto', $data, true);

        /**
         * aqui se puede ver, como se puede decir, que relaciones quieres que traiga el query,
         * incluso una condicion dentro de una tabla relacionada, y un with, dentro de otro with
         *
          $productos= ProductoElo::where('producto_id',11279)
            ->with(['grupo','paquetes' => function ($paquete) {
            $paquete->with('producto_paquete');
            }])->first();
         *
          $productos= ProductoElo::with(['grupo','paquetes' => function ($paquete) {
            $paquete->with('producto_paquete');
            }])->get();
         */

        //$productos= ProductoElo::with(ProductoElo::allTablesRelations())->get();


        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }

    }

    function reporteEstado()
    {
        $data['productos'] = $this->producto_model->select_all_producto();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/producto/reporteEstadoProducto', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }

    }

    function estadoProducto($id = FALSE)
    {

        if ($id != FALSE) {

            //  $precio_venta = $this->precios_model->get_by('nombre_precio', 'Precio Venta');
            $data['producto_unidad'] = $this->unidades_model->get_by_producto($id);

            //var_dump($data);
            $select = '*';
            $from = "unidades_has_precio";
            $join = false;
            $campos_join = false;
            $tipo_join = false;

            $group = false;
            $where = array(
                'id_unidad' => $data['producto_unidad'][0]['id_unidad'],
                'id_producto' => $id,
                //'id_precio' => $precio_venta['id_precio']
            );
            $data['precio_venta'] = $this->unidades_has_precio_model->traer_by($select, $from, $join,
                $campos_join, $tipo_join, $where, false, false, false, false, $group, false, "ROW_ARRAY");

            $select = 'count(id_producto) as cantidad_bonificada';
            $from = "detalle_venta";
            $where = array(
                'id_producto' => $id,
                'bono' => 1
            );
            $data['cantidad_comprada'] = $this->producto_model->cantidad_comprada($id);
            $data['producto_bonificado'] = $this->venta_model->traer_by($select, $from, $join,
                $campos_join, $tipo_join, $where, false, false, false, false, $group, false, "ROW_ARRAY");

            $data['datos_producto'] = $this->producto_model->estado_producto_est($id);

        }
        $this->load->view('menu/ventas/estadoProducto', $data);
    }

    function evolucionCostos($id = FALSE)
    {

        if ($id != FALSE) {

            //$precio_venta = $this->precios_model->get_by('nombre_precio', 'Precio Venta');
            $data['productos'] = $this->producto_model->estadoDelProducto($id);

        }
        $this->load->view('menu/ventas/evolucionDeCostos', $data);

    }

    function stock($tipo)
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }
        $data['droguerias_relacionadas'] = $this->drogueria_relacionada_model->get_all();
        $data['unidades'] = $this->unidades_model->get_unidades();
        $data['locales'] = $this->local_model->get_all();
        $data["lstProducto"] = $this->producto_model->get_all_by_local($data["locales"][0]["int_local_id"], true);
        $data['columnas'] = $this->columnas;
        $data['tipo'] = $tipo;
        $dataCuerpo['cuerpo'] = $this->load->view('menu/producto/stock', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function validar_existencia_barra()
    {

        $producto_id = $this->input->post('producto_id');
        $codigo_barra = $this->input->post('codigo_barra');

        $where = array(
            'codigo_barra' => $codigo_barra
        );
        return $this->get_codigos_barras($where, $producto_id);

    }


    function validar_codigo_interno()
    {
        $json = array();

        $codigo_interno = $this->input->post('codigo_interno');
        $producto = $this->input->post('id');
        $where = array(
            'producto_codigo_interno' => trim($codigo_interno),
            'producto_estatus' => 1
        );
        $validar = $this->producto_model->getAnyCondition($where);

        if (($producto == "" and $validar!=NULL) ||
            ($validar!=NULL and $producto != "" and $validar['producto_id'] != $producto)
        ) {
            $json['encontro'] = true;
            $json['datos'] = $this->producto_model->get_by('producto_codigo_interno', $codigo_interno);
        } else {
            $json['encontro'] = false;
        }


        echo json_encode($json);

    }

    function barra_por_producto()
    {
        $producto_id = $this->input->post('producto_id');

        $where = array(
            'producto_id' => $producto_id
        );
        return $this->get_codigos_barras($where, $producto_id);
    }

    function get_codigos_barras($where, $producto_id)
    {
        $validar['barras'] = $this->producto_barra_model->get_codigo_barra($where);

        if (isset($validar['barras'][0])) {

            if ($producto_id != "") {

                if ($producto_id != $validar['barras'][0]['producto_id']) {

                    $validar['error'] = "El codigo de barra ingresado ya existe";
                }
            } else {
                $validar['error'] = "El codigo de barra ingresado ya existe";
            }
        }

        echo json_encode($validar);

    }


    function get_json_catalogo_coopidrogas()
    {

        $columnas = array(
            'id',
            'producto_codigo_interno',
            'producto_nombre',
            'costo_corriente',
            'costo_real',
            'iva',
            'nombre_laboratorio',
            'codigo_laboratorio',
            'bonificacion'
        );
        // Pagination Result
        $array = array();
        $array['productosjson'] = array();

        $total = $this->producto_model->count_all_catalogo();
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


        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $select = 'SQL_CALC_FOUND_ROWS null as rows2, catalogo.*';
        $from = "catalogo";
        $search = $this->input->get('search');

        // $columns = $this->input->get('columns');
        $buscar = $search['value'];
        $where_custom = false;
        if (!empty($buscar)) {
            $where_custom = "(producto_codigo_interno LIKE '%" . $buscar . "%' 
            or producto_nombre LIKE '%" . $buscar . "%' 
            or costo_corriente LIKE '%" . $buscar . "%' or costo_real LIKE '%" . $buscar . "%'
            or iva LIKE '%" . $buscar . "%' or nombre_laboratorio LIKE '%" . $buscar . "%'
            or codigo_laboratorio LIKE '%" . $buscar . "%' or bonificacion LIKE '%" . $buscar . "%' 
            or producto_codigo_barra LIKE '%" . $buscar . "%')";
        }

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];

            if ($ordenar[0]['column'] == 0) {
                $order = 'producto_codigo_interno';
            }

            if ($ordenar[0]['column'] == 1) {
                $order = 'producto_nombre';
            }

            if ($ordenar[0]['column'] == 2) {
                $order = 'costo_corriente';
            }
            if ($ordenar[0]['column'] == 3) {
                $order = 'costo_real';
            }
            if ($ordenar[0]['column'] == 4) {
                $order = 'iva';
            }
            if ($ordenar[0]['column'] == 6) {
                $order = 'nombre_laboratorio';
            }
            if ($ordenar[0]['column'] == 7) {
                $order = 'codigo_laboratorio';
            }
            if ($ordenar[0]['column'] == 8) {
                $order = 'bonificacion';
            }

        }

        $productos = $this->producto_model->traer_by($select, $from, false, false, false, false,
            $nombre_in, $where_in, $nombre_or, $where_or, false, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);


        foreach ($productos as $producto) {
            $PRODUCTOjson = array();
            $producto_id = $producto['id'];
            $codigo = $producto['producto_codigo_interno'];
            $contador = 0;

            foreach ($columnas as $col) {

                if ($col != 'id') {
                    $PRODUCTOjson[$contador] = "";
                    $PRODUCTOjson[$contador] .= isset($producto[$col]) ? $producto[$col] : '';

                    $contador++;

                }
            }
            $PRODUCTOjson[] .= "<input type='hidden' name='producto_id_catalogo[]' value='$producto_id' />
            <input type='radio' name='producto_codigo_catalogo[]' data-codigo='" . $codigo . "' onclick='seleccionar_catalogo(" . $codigo . ")' value='$codigo' />";
            $array['productosjson'][] = $PRODUCTOjson;
        }

        $array['data'] = $array['productosjson'];
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = $total;
        $array['recordsFiltered'] = $total; // esto dbe venir por post

        echo json_encode($array);
    }

    function agregar($id = FALSE)
    {

        $data["lineas"] = $this->lineas_model->get_lineas();
        $data["grupos"] = $this->grupos_model->get_grupos();
        $data["proveedores"] = $this->proveedor_model->select_all_proveedor();

        $data["impuestos"] = $this->impuestos_model->get_impuestos();
        $data["unidades"] = $this->unidades_model->get_unidades();
        $data["tipos_producto"] = $this->tipo_producto->get_all();

        //estos 3 van a almacenarse en cache
        $data["clasificaciones"] = $this->clasificacion_model->get_all();
        $data["ubicaciones"] = $this->ubicacion_fisica_model->get_all();
        $data["componentes"] = $this->componentes_model->get_all();

        $data['promociones'] = array();
        $data['descuentos'] = array();
        $data['condiciones'] = $this->condiciones_pago_model->get_all();

        $data['columnas'] = $this->columnas;
        $data['precios_producto'] = array();
        $fe_typeitems = [];
        if (!empty($this->session->userdata('FACT_E_ALLOW') and $this->session->userdata('FACT_E_ALLOW') === '1')) {
            $fe_typeitems = $this->producto_model->get_fe_typeitems();
        }

        $data['fe_typeitems'] = $fe_typeitems;

        if ($this->session->userdata('CORRELATIVO_PRODUCTO') != "NO"
        && $this->session->userdata('CORRELATIVO_PRODUCTO') != "") {

            $this->load->model('opciones/opciones_model');
            $correlativo = $this->opciones_model->getByKey("CORRELATIVO_PRODUCTO");
            $data['correlativo'] = $correlativo['config_value'] + 1;
        }

        if ($id != FALSE) {
            $data['paquete_has_prod'] = $this->paquete_has_prod_model->get_all_by_prod_grouped($id);

            $data['producto'] = $this->producto_model->get_by_id($id);
            //$data['promociones'] = $this->bonificaciones_model->get_all_by_condiciones($id);
            //$data['descuentos'] = $this->descuentos_model->descuentoProducto('producto_id', $id);
            $data['unidades_producto'] = $this->unidades_model->get_by_producto($id);
            $data['componentes_producto'] = $this->componentes_model->get_all_by_user($id);
            $where = array(
                'id_producto' => $id,
                'id_local' => $this->session->userdata('id_local')
            );
            $data['inventarios'] = $this->inventario_model->get_all_by($where);
            $where = array(
                'id_producto' => $id,
            );
            $data['precios_producto'] = $this->unidades_has_precio_model->get_all_where($where);
            $duplicar = $this->input->post('duplicar');
            if (!empty($duplicar)) {
                $data['duplicar'] = 1;
                $data['promociones'] = array();
                $data['descuentos'] = array();
            }
            $data['images'] = $this->get_fotos((int)$id);
        }
        $this->load->view('menu/producto/form', $data);
    }

    function ver_catalogo_coopidrogras()
    {
        /*este metodo retorna la vista donde se listan todos los productos del catalogo coopidrogas*/
        $vistaACargar = "";
        if ($this->input->post('vistaACargar')) {

            $vistaACargar = $this->input->post('vistaACargar');
        }

        $data['vistaACargar'] = $vistaACargar;
        $this->load->view('menu/producto/ver_catalogo_coopidrogras', $data);
    }

    function producto_catalogo()
    {
        /*este metodo busca un producto en la tabla catalogo en especifico*/
        $data = array();
        $producto_id = $this->input->post('producto');
        $where = array(
            'producto_codigo_interno' => $producto_id
        );

        $data['res'] = $this->producto_model->buscar_pro_catalogo($where);

        echo json_encode($data);
    }

    function ver_imagen($id = false)
    {
        if ($id != FALSE) {
            $data['producto'] = $this->producto_model->get_by_id($id);
            $data['images'] = $this->get_fotos((int)$id);
            $this->load->view('menu/producto/ver_imagen', $data);
            //var_dump( $data['precios_producto']);
        }


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

    function eliminarimg()
    {
        $producto = $this->input->post('producto_id');
        $nombre = $this->input->post('nombre');
        $dir = './uploads/' . $producto . '/' . $nombre;
        $borrar = unlink($dir);

        if ($borrar != false) {
            $json['success'] = "Se ha eliminado exitosamente";
        } else {
            $json['error'] = "Ha ocurrido un error al eliminar la imagen";
        }

        echo json_encode($json);


    }


    function verunidades($id = FALSE)
    {


        $data["grupos"] = $this->grupos_model->get_grupos();
        $data["proveedores"] = $this->proveedor_model->select_all_proveedor();
        $data["impuestos"] = $this->impuestos_model->get_impuestos();
        $data["unidades"] = $this->unidades_model->get_unidades();

        $data['columnas'] = $this->columnas;
        $data['condiciones'] = $this->condiciones_pago_model->get_all();
        // var_dump($data['columnas']);
        $data['precios_producto'] = array();
        if ($id != FALSE) {
            $data['producto'] = $this->producto_model->get_by_id($id);

            $data['unidades_producto'] = $this->unidades_model->get_by_producto($id);
            $data['precios_producto'] = array();
            $countunidad = 0;

            $duplicar = $this->input->post('duplicar');
            if (!empty($duplicar)) {
                $data['duplicar'] = 1;
            }
            foreach ($data['unidades_producto'] as $unidad) {

                $precios = $this->unidades_has_precio_model->get_by_unidad_and_producto($id, $unidad['id_unidad'], false);
                $countprecio = 0;

                if (sizeof($precios) > 0) {
                    foreach ($precios as $precio) {

                        $preciodata[$countprecio] = $precio;

                        $countprecio++;
                    }
                    $data['precios_producto'][$countunidad] = $preciodata;
                }

                $countunidad++;
            }
            //var_dump( $data['precios_producto']);
        }

        $this->load->view('menu/producto/formunidades', $data);


    }


    function consultarStock()
    {
        $data["lstProducto"] = $this->producto_model->select_all_producto();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/mantenimiento/consultarProducto', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    function registrar()
    {
        $this->form_validation->set_rules('nombre', 'nombre', 'requiered');
        $this->form_validation->set_rules('stockmin', 'stockmin', 'requiered');

        $this->load->library('upload');


        if ($this->form_validation->run() == false):
            $json['error'] = validation_errors();
        else:


            $where = array(
                'producto_codigo_interno' => trim($this->input->post('producto_codigo_interno')),
                'producto_estatus' => 1
            );
            $validar = $this->producto_model->getAnyCondition($where);

            /**
             * aqui valido si el codigo interno existe
             */
            $id = $this->input->post('id');
            if (
                (
                    !empty($id) && $validar != NULL && isset($validar['producto_id'])
                    && $validar['producto_id'] != $id
                )
                ||
                (
                    empty($id) && $validar != NULL && isset($validar['producto_id'])
                )
            ) {
                $json['error'] = 'El código interno ingresado, ya existe';
                echo json_encode($json);
                exit();
            }


            $grupo = $this->input->post('produto_grupo');

            $proveedor = $this->input->post('producto_proveedor');
            $impuesto = $this->input->post('producto_impuesto_costos');
            $otro_impuesto = $this->input->post('otro_impuesto');
            $cualidad = $this->input->post('producto_cualidad');
            $producto_activo = $this->input->post('producto_activo');
            $desccripcion = $this->input->post('producto_descripcion');
            $nota = $this->input->post('producto_nota');

            $producto_codigo_interno = $this->input->post('producto_codigo_interno');
            $producto_sustituto = $this->input->post('producto_sustituto');
            $producto_tipo = $this->input->post('producto_tipo');
            $producto_nivel_rotacion = $this->input->post('producto_nivel_rotacion');
            $producto_ubicacion_fisica = $this->input->post('producto_ubicacion_fisica');
            $producto_clasificacion = $this->input->post('producto_clasificacion');

            $producto_mensaje = $this->input->post('producto_mensaje');

            $is_pquete = $this->input->post('is_paquete');
            $in_offer = $this->input->post('in_offer');

            $producto = array(

                'producto_nombre' => strtoupper($this->input->post('producto_nombre')),

                'produto_grupo' => !empty($grupo) ? $grupo : null,
                'producto_proveedor' => !empty($proveedor) ? $proveedor : null,
                'producto_impuesto' => !empty($impuesto) ? $impuesto : null,
                'otro_impuesto' => !empty($otro_impuesto) ? $otro_impuesto : null,
                'costo_unitario' => $this->input->post('costo_unitario'),
                'fe_type_item_identification_id' => $this->input->post('fe_type_item_identification_id'),
                'producto_activo' => $producto_activo == "NO" ? 0 : 1,
                'producto_estatus' => 1,

                'producto_clasificacion' => empty($producto_clasificacion) ? null : $producto_clasificacion,
                'producto_tipo' => empty($producto_tipo) ? null : strtoupper($producto_tipo),
                'producto_sustituto' => empty($producto_sustituto) ? null : strtoupper($producto_sustituto),
                'producto_mensaje' => empty($producto_mensaje) ? null : strtoupper($producto_mensaje),

                'producto_ubicacion_fisica' => empty($producto_ubicacion_fisica) ? null : strtoupper($producto_ubicacion_fisica),
                ////datos de la pestana unidades y precios
                //'costo_promedio' => $this->input->post('costo_promedio')?$this->input->post('costo_promedio'):null,
                //'producto_descuentos' => $this->input->post('producto_descuentos')?$this->input->post('producto_descuentos'):null,
                //'costo_cargue' => $this->input->post('costo_cargue')?$this->input->post('costo_cargue'):null,
                'producto_codigo_interno' => strtoupper($producto_codigo_interno),

                'producto_comision' => $this->input->post('producto_comision') ? $this->input->post('producto_comision') : null,
                //'producto_bonificaciones' => $this->input->post('producto_bonificaciones')?$this->input->post('producto_bonificaciones'):null,
                //'porcentaje_descuento' => $this->input->post('porcentaje_descuento')?$this->input->post('porcentaje_descuento'):null,
                'precio_abierto' => $this->input->post('precio_abierto') ? $this->input->post('precio_abierto') : null,

                'porcentaje_costo' => $this->input->post('porcentaje_costo') ? $this->input->post('porcentaje_costo') : null,
                'control_inven' => $this->input->post('control_inventario') ? $this->input->post('control_inventario') : 0,
                'control_inven_diario' => $this->input->post('control_inventario_diario') ? $this->input->post('control_inventario_diario') : 0,

                'is_paquete' => !empty($is_pquete) ? 1 : 0,
                'in_offer' => !empty($in_offer) ? 1 : 0

            );


            $medidas = $this->input->post('medida');
            $unidades = $this->input->post('unidad');
            // $metrosCubicos = $this->input->post('metros_cubicos');
            $codigos_barra = $this->input->post('codigos_barra');
            $componentes = $this->input->post('producto_componente');
            $productos_paquete = $this->input->post('productos_paquete');


            $stock_minimo = $this->input->post('stock_minimo');
            $stock_maximo = $this->input->post('stock_maximo');


            $productos_paquete_array = array();


            if (!empty($is_pquete)) {
                $costo_unitario_nuevo = 0;

                if ($productos_paquete != false) {

                    for ($i = 0; $i < count($productos_paquete); $i++) {
                        $id_unidades = $this->input->post('unidad_' . $productos_paquete[$i]);
                        $cantidad_unidades = $this->input->post('cantidad_' . $productos_paquete[$i]);

                        $where = array(
                            'producto_id' => $productos_paquete[$i]
                        );
                        $unidades_producto = $this->unidades_model->solo_unidades_xprod($where);
                        $prod = $this->producto_model->get_by_id($productos_paquete[$i]);
                        $costo_unitario = $prod['costo_unitario'];


                        for ($j = 0; $j < count($id_unidades); $j++) {
                            if (isset($cantidad_unidades[$j]) && !empty($cantidad_unidades[$j])) {
                                $prod_paquete = array(
                                    'prod_id' => $productos_paquete[$i]
                                );
                                $id_unidad = $id_unidades[$j];
                                $cantidad = $cantidad_unidades[$j];
                                $prod_paquete['unidad_id'] = $id_unidad;
                                $prod_paquete['cantidad'] = $cantidad;
                                array_push($productos_paquete_array, $prod_paquete);

                                $costoestaunidad = $this->producto_model->calcularCostoUnidad($id_unidades[$j], $costo_unitario, $unidades_producto);

                                if ($costoestaunidad != false) {
                                    $costo_unitario_nuevo += $costoestaunidad * $cantidad;
                                }
                            }
                        }

                    }
                    $producto['costo_unitario'] = $costo_unitario_nuevo;
                }
            }


            if (empty($id)) {
                $producto_before= $this->_producto->with($this->_producto->allTablesRelations())->first();

                $rs = $this->producto_model->insertar($producto, $medidas, $unidades, $codigos_barra, $stock_minimo, $stock_maximo, $componentes, $productos_paquete_array);
                $id = $rs;
            } else {

                $producto['producto_id'] = $id;
                $producto_before= $this->_producto->where('producto_id',$id)->with($this->_producto->allTablesRelations())->first();
                $rs = $this->producto_model->update($producto, $medidas, $unidades, $codigos_barra, $stock_minimo, $stock_maximo, $componentes, $productos_paquete_array);

            }

            $where = array('producto_id' => $id);
            $producto['contenido_interno'] = $this->unidades_model->solo_unidades_xprod($where);
            $producto['codigo_barra'] = $this->producto_barra_model->get_codigo_barra($where);

            $where = array('id_producto' => $id);
            $producto['precios'] = $this->unidades_has_precio_model->get_all_where($where);

            $where = array('paquete_id' => $id);
            $producto['paquete'] = $this->paquete_has_prod_model->get_where($where);

            //elimino la cache de los productos
            $this->db->cache_delete('api', 'Productos');

            if ($rs != false and $rs !== NOMBRE_EXISTE) {

                //actualizo el progresivo o correlativo
                $this->load->model('opciones/opciones_model');
                $correlativo = $this->opciones_model->getByKey("CORRELATIVO_PRODUCTO");

                if ($correlativo['config_value'] != "NO" && strtoupper($producto_codigo_interno) == ($correlativo['config_value'] + 1)) {
                    $configuraciones = array(
                        'config_key' => "CORRELATIVO_PRODUCTO",
                        'config_value' => $correlativo['config_value'] + 1
                    );
                    $where = array(
                        'config_key' => "CORRELATIVO_PRODUCTO",
                    );

                    $this->opciones_model->update($where, $configuraciones);
                }


                if (!empty($_FILES) and $_FILES['userfile']['size'] != '0') {
                    $files = $_FILES;
                    $contador = 1;
                    $mayor = 0;
                    $directorio = './uploads/' . $id . '/';
                    if (is_dir($directorio)) {
                        $arreglo_img = scandir($directorio);
                        natsort($arreglo_img);
                        $mayor = array_pop($arreglo_img);
                        $mayor = substr($mayor, 0, -4);
                    } else {
                        $arreglo_img[0] = ".";
                    }
                    $sumando = 1;
                    for ($j = 0; $j < count($files['userfile']['name']); $j++) {
                        if ($files['userfile']['name'][$j] != "") {
                            if ($arreglo_img[0] == ".") {
                                $contador = $mayor + ($sumando);
                                $sumando++;
                            }
                            $_FILES ['userfile'] ['name'] = $files ['userfile'] ['name'][$j];
                            $_FILES ['userfile'] ['type'] = $files ['userfile'] ['type'][$j];
                            $_FILES ['userfile'] ['tmp_name'] = $files ['userfile'] ['tmp_name'][$j];
                            $_FILES ['userfile'] ['error'] = $files ['userfile'] ['error'][$j];
                            $_FILES ['userfile'] ['size'] = $files ['userfile'] ['size'][$j];
                            $size = getimagesize($_FILES ['userfile'] ['tmp_name']);
                            switch ($size['mime']) {
                                case "image/jpeg":
                                    $extension = "jpg";
                                    break;
                                case "image/png":
                                    $extension = "png";
                                    break;
                                case "image/bmp":
                                    $extension = "bmp";
                                    break;
                            }
                            $this->upload->initialize($this->set_upload_options($id, $contador, $extension));
                            $this->upload->do_upload();
                            $contador++;
                        } else {

                        }
                    }
                }

                $producto_after= $this->_producto->where('producto_id',$id)->with($this->_producto->allTablesRelations())->first();

                //esto lo hago porque desde compra creo un input que se llama estasencompra
                //para saber cuando estoy creando un producto desde compra.
                if ($this->input->post('estasencompra') and $this->input->post('estasencompra') == 1) {
                    $json['savingProductoCompra'] = true;
                }

                $json['producto_id'] = $id;
                $json['success'] = 'El producto se ha guardado de forma exitosa';

                $log = array(
                    'usuario' => $this->session->userdata('nUsuCodigo'),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'fecha' => date('Y-m-d H:i:s'),
                    'tabla' => 'PRODUCTO',
                    'tipo' => empty($this->input->post('id')) ? LOG_INSERT : LOG_UPDATE,
                    'data_before' => empty($this->input->post('id')) ? null : json_encode($producto_before),
                    'data_after' => json_encode($producto_after),
                );
                $this->systemLogsModel->insert($log);

            } else {
                $json['error'] = 'Ocurrió un error al guardar el producto';
            }

            if ($rs === NOMBRE_EXISTE) {
                $this->session->set_flashdata('error', NOMBRE_EXISTE);
                $json['error'] = NOMBRE_EXISTE;
            }

        endif;

        echo json_encode($json);

    }

    function set_upload_options($id, $contador, $extension)
    {
        // upload an image options
        $this->load->helper('path');
        $dir = './uploads/' . $id . '/';

        if (!is_dir('./uploads/')) {
            mkdir('./uploads/', 0755);
        }
        if (!is_dir($dir)) {
            mkdir($dir, 0755);
        }
        $config = array();
        $config ['upload_path'] = $dir;
        //$config ['file_path'] = './prueba/';
        $config ['allowed_types'] = $extension;
        $config ['max_size'] = '0';
        $config ['overwrite'] = TRUE;
        $config ['file_name'] = $contador;

        return $config;
    }

    function eliminar()
    {
        $id = $this->input->post('id');
        $product = $this->producto_model->get_by_id($id);
        $nombre = $product['producto_nombre'];

        $where = array(
            'id_producto' => $id,
            'id_local' => $this->session->userdata('id_local')
        );
        $validainventario = $this->inventario_model->get_all_by($where);
        $tieneinventario = false;
        foreach ($validainventario as $row) {

            if ($row['cantidad'] > 0) {
                $tieneinventario = true;
            }
        }

        if (count($validainventario) < 1 || $tieneinventario == false) {


            $producto_before= $this->_producto->where('producto_id',$id)->with($this->_producto->allTablesRelations())->first();

            $producto = array(
                'producto_id' => $id,
                'producto_nombre' => $nombre . time(),
                'producto_estatus' => 0
            );

            $data['resultado'] = $this->producto_model->delete($producto);

            if ($data['resultado'] != FALSE) {
                $where = array('producto_id' => $id);
                $borrar_barra = $this->producto_barra_model->delete($where);

                $json['success'] = 'Se ha eliminado exitosamente';
                $producto_after= $this->_producto->where('producto_id',$id)->with($this->_producto->allTablesRelations())->first();

                $log = array(
                    'usuario' => $this->session->userdata('nUsuCodigo'),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'fecha' => date('Y-m-d H:i:s'),
                    'tabla' => 'PRODUCTO',
                    'tipo' => LOG_DELETE,
                    'data_before' => json_encode($producto_before),
                    'data_after' => json_encode($producto_after),
                );
                $this->systemLogsModel->insert($log);

            } else {
                $json['error'] = 'ha ocurrido un error al eliminar el producto';
            }

        } else {
            $json['error'] = 'El producto no se puede eliminar porque posee stock';
        }

        echo json_encode($json);
    }

    function uproducto_modelate()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('producto_id', true);
            $producto = array(
                'var_producto_nombre' => $this->input->post('nombre_udp', true),
                'dec_producto_cantidad' => $this->input->post('stock_udp', true),
                'dec_producto_preciocompra' => $this->input->post('precio_comp_udp', true),
                'dec_producto_precioventa' => $this->input->post('precio_vent_udp', true),
                'dec_producto_utilidad' => $this->input->post('utilidad_udp', true),
                'var_producto_descripcion' => $this->input->post('desc_udp', true),
                'int_producto_unidmed' => $this->input->post('cboUnidMed_udp', true),
                'nCatCodigo' => $this->input->post('cboCategoria_udp', true),
                'var_producto_marca' => $this->input->post('cboTipoTelf_udp', true),
                'var_producto_codproveedor' => $this->input->post('codprodprov_udp', true),
                'dec_producto_stockminimo' => $this->input->post('stockmin_udp', true),
                'var_producto_estado' => '1'
            );
            $rs = $this->producto_model->uproducto_modelate($id, $producto);
            if ($rs) {
                echo "actualizo";
            } else {
                echo "no actualizo";
            }
        } else {
            redirect(base_url() . 'producto/', 'refresh');
        }
    }

    function buscar_id()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $producto_model = $this->producto_model->buscar_id($id);
            echo json_encode($producto_model);
        } else {
            redirect(base_url() . 'producto/', 'refresh');
        }
    }

    function get_by_proveedor()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $producto_model = $this->producto_model->get_all_by(array('producto_proveedor' => $id));
            header('Content-Type: application/json');
            echo json_encode($producto_model);
        } else {
            redirect(base_url() . 'producto/', 'refresh');
        }
    }

    function costos_json($id)
    {

        $data['ventasG'] = $this->venta_model->ventas_grafica($id);
        $data['comprasG'] = $this->venta_model->compras_grafica($id);
        for ($i = 1; $i <= 12; $i++) {
            $c['cant' . $i] = 0;
            $c['cantC' . $i] = 0;
            $result['producto' . $i] = 0;
            $result['productoComp' . $i] = 0;
        }
        foreach ($data['ventasG'] as $ventasG) {
            $anio = date('Y');
            $fecha = date('Y-n-d', strtotime($ventasG['fecha']));
            for ($i = 1; $i <= 12; $i++) {
                if (($fecha >= $anio . '-' . $i . '-01') AND ($fecha <= $anio . '-' . $i . '-31')) {
                    $c['cant' . $i]++;
                    $result['producto' . $i] = $result['producto' . $i] + $ventasG['precio'];

                }

            }

        }
        foreach ($data['comprasG'] as $comprasG) {
            $anio = date('Y');
            $fecha = date('Y-n-d', strtotime($comprasG['fecha_registro']));
            for ($i = 1; $i <= 12; $i++) {
                if (($fecha >= $anio . '-' . $i . '-01') AND ($fecha <= $anio . '-' . $i . '-31')) {
                    $c['cantC' . $i]++;
                    $result['productoComp' . $i] = $result['productoComp' . $i] + $comprasG['precio'];

                }

            }

        }

        $data_grafico = array();
        $ventas = array();
        $compras = array();

        for ($i = 1; $i <= 12; $i++) {
            if ($c['cant' . $i] == 0) {
                $c['cant' . $i] = 1;
                $newData['data'] = array(array(1, 12));
                $newData = array();
                $newData[] = $i;
                $newData[] = intval(0); // ESto es el valor del eje X
                $ventas[] = $newData;
            } else {
                $newData['data'] = array(array(1, 12));
                $newData = array();
                $newData[] = $i;
                $newData[] = intval($result['producto' . $i] / $c['cant' . $i]); // ESto es el valor del eje X
                $ventas[] = $newData;
            }
        }
        $data_grafico['ventas'] = $ventas;

        for ($i = 1; $i <= 12; $i++) {
            if ($c['cantC' . $i] == 0) {
                $c['cantC' . $i] = 1;
                $newData['data'] = array(array(1, 12));
                $newData = array();
                $newData[] = $i;
                $newData[] = intval(0); // ESto es el valor del eje X
                $compras[] = $newData;
            } else {
                $newData['data'] = array(array(1, 12));
                $newData = array();
                $newData[] = $i;
                $newData[] = intval($result['productoComp' . $i] / $c['cantC' . $i]); // ESto es el valor del eje X
                $compras[] = $newData;
            }
        }

        $data_grafico['compras'] = $compras;
        echo json_encode($data_grafico);

    }


    function autocomplete_marca()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->producto_model->autocomplete_marca($this->input->get('term', true)));
        } else {
            redirect(base_url() . 'producto/', 'refresh');
        }
    }

    function editcolumnas()
    {
        $data['columnas'] = $this->columnas;
        $this->load->view('menu/producto/columnas', $data);
    }


    function listaprecios()
    {

        /* $data['lstProducto'] = $this->producto_model->get_all_by_local_producto($this->session->userdata('id_local'));


         $data['productos'] = $this->unidades_has_precio_model->get_precio_has_producto();*/

        //$data['precios'] = $this->precios_model->get_precios();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/producto/listaprecios', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    function listaprecios_json()
    {

        $pago = $this->input->get('pago');

        // Pagination Result
        $array = array();
        $array['productosjson'] = array();

        $local = $this->session->userdata('id_local');
        $total = $this->producto_model->count_all();
        $start = 0;
        $limit = false;


        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->input->get('length');
        }


        $where = array();
        $where['producto_activo'] = 1;
        $where['producto_estatus'] = 1;
        if ($pago == 2) {
            $where = 'producto_activo = 1 AND producto_estatus = 1 AND precio > 0  AND unidades_has_precio.id_unidad IS NOT NULL';
        }
        if ($pago == 1) {
            $where = 'producto_activo = 1 AND producto_estatus = 1 AND precio > 0  AND unidades_has_precio.id_unidad IS NOT NULL';
        } elseif ($pago == 0) {
            $where = 'producto_activo = 1 AND producto_estatus = 1 AND precio < 1  OR unidades_has_precio.id_unidad = "" OR unidades_has_precio.id_unidad IS NULL';
        }
        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $select = 'unidades_has_precio.precio,producto.producto_nombre as nombre, producto.*, unidades_has_producto.id_unidad, unidades.nombre_unidad, inventario.id_inventario, inventario.id_local, inventario.cantidad, inventario.fraccion ,lineas.nombre_linea,
		 marcas.nombre_marca, familia.nombre_familia, grupos.nombre_grupo, proveedor.proveedor_nombre, impuestos.nombre_impuesto,grupos.id_grupo';
        $from = "producto";
        $join = array('unidades_has_precio', 'lineas', 'marcas', 'familia', 'grupos', 'proveedor', 'impuestos', '(SELECT DISTINCT inventario.id_producto, inventario.id_inventario, inventario.cantidad, inventario.fraccion, inventario.id_local FROM inventario WHERE inventario.id_local=' . $local . '  ORDER by id_inventario DESC ) as inventario',
            'unidades_has_producto', 'unidades', 'subgrupo', 'subfamilia');

        $campos_join = array('unidades_has_precio.id_producto=producto.producto_id', 'lineas.id_linea=producto.producto_linea', 'marcas.id_marca=producto.producto_marca',
            'familia.id_familia=producto.producto_familia', 'grupos.id_grupo=producto.produto_grupo',
            'proveedor.id_proveedor=producto.producto_proveedor', 'impuestos.id_impuesto=producto.producto_impuesto', 'inventario.id_producto=producto.producto_id',
            'unidades_has_producto.producto_id=producto.producto_id ', 'unidades.id_unidad=unidades_has_producto.id_unidad', 'subgrupo.id_subgrupo = producto.producto_subgrupo',
            'subfamilia.id_subfamilia = producto.producto_subfamilia');
        $tipo_join = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left');


        $search = $this->input->get('search');
        $columns = $this->input->get('columns');
        $buscar = $search['value'];
        $where_custom = false;
        if (!empty($search['value'])) {
            $buscarcod = $buscar;
            if (is_numeric($buscar)) {
                $buscarcod = restCod($buscar);
            }
            $where_custom = "(producto.producto_id LIKE '%" . $buscarcod . "%' or producto.producto_nombre LIKE '%" . $buscar . "%'
            or marcas.nombre_marca LIKE '%" . $buscar . "%' or grupos.nombre_grupo LIKE '%" . $buscar . "%'
            or subgrupo.nombre_subgrupo LIKE '%" . $buscar . "%' or familia.nombre_familia LIKE '%" . $buscar . "%'
            or subfamilia.nombre_subfamilia LIKE '%" . $buscar . "%' or lineas.nombre_linea LIKE '%" . $buscar . "%'
            or unidades.nombre_unidad LIKE '%" . $buscar . "%')";
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
                $order = 'producto.producto_nombre';
            }
            if ($ordenar[0]['column'] == 2) {
                $order = 'marcas.nombre_marca ';
            }
            if ($ordenar[0]['column'] == 3) {
                $order = 'familia.nombre_familia';
            }
            if ($ordenar[0]['column'] == 4) {
                $order = 'subfamilia.nombre_subfamilia';
            }
            if ($ordenar[0]['column'] == 5) {
                $order = 'lineas.nombre_linea';
            }

            if ($ordenar[0]['column'] == 7) {
                $order = 'unidades.nombre_unidad';
            }

        }

        $group = 'producto_id';


        $lstProducto = $this->producto_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);


        // $lstProducto = $this->producto_model->get_all_by_local_producto($this->session->userdata('id_local'));

        //$precios = $this->precios_model->get_precios();
        if ($pago == 2) {
            $productos = $this->unidades_has_precio_model->get_precio_has_producto();
        }
        if ($pago == 1) {
            $condicion = "AND precio > 0  AND unidades_has_precio.id_unidad IS NOT NULL";
            $productos = $this->unidades_has_precio_model->get_precio_has_producto_list($condicion);
        } elseif ($pago == 0) {
            $condicion = "AND precio < 1  OR unidades_has_precio.id_unidad ='' OR unidades_has_precio.id_unidad IS NULL";
            $productos = $this->unidades_has_precio_model->get_precio_has_producto_list($condicion);
        }


        if (count($lstProducto) > 0) {
            foreach ($lstProducto as $row) {
                $PRODUCTOjson = array();

                $PRODUCTOjson[] = sumCod($row['producto_id']);
                $PRODUCTOjson[] = $row['producto_nombre'];
                $PRODUCTOjson[] = $row['nombre_grupo'];


                foreach ($precios as $precio) {

                    $unidades = "";

                    foreach ($productos as $producto) {
                        if ($row['producto_id'] == $producto['id_producto']) {
                            if ($producto['id_precio'] == $precio['id_precio'] and $producto['id_grupo'] == $row['id_grupo']) {

                                $unidades .= $producto['nombre_unidad'] . ": " . number_format($producto['precio'], 2) . " <br>";

                            }
                        }
                    }


                }
                $PRODUCTOjson[] = $unidades;


                $array['productosjson'][] = $PRODUCTOjson;
            }


        }

        $array['data'] = $array['productosjson'];
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = $total;
        $array['recordsFiltered'] = $total; // esto dbe venir por post
        echo json_encode($array);


    }

    function guardarParamRap()
    {

        $json = array();
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('lst_producto') && count(json_decode($this->input->post('lst_producto'))) > 0) {

                $rs = $this->producto_model->guardarParamRap($this->input->post());

                if ($rs != false) {
                    $json['success'] = 'Solicitud Procesada con exito';

                } else {
                    $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
                }

            } else {

                $json['error'] = 'No ha realizado ningun cambio';
            }
        } else {

            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }
        echo json_encode($json);
    }

    function getPreciosByProd()
    {
        $producto = $this->input->post('producto');
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->unidades_has_precio_model->get_all_by_producto($producto));
        } else {
            redirect(base_url() . 'producto / ', 'refresh');
        }
    }

    function getByCodigo()
    {
        $codigo = $this->input->post('codigo');
        if ($this->input->is_ajax_request()) {
            $data['datos'] = array();
            $data['datos'] = $this->producto_model->get_by('producto_codigo_interno', $codigo);
            if ($data['datos'] == null) {
                $data['datos'] = array();
            }
            echo json_encode($data);

        } else {
            redirect(base_url() . 'producto / ', 'refresh');
        }
    }

    function getPreciosByProdAndCondicionPago()
    {
        $producto = $this->input->post('producto');
        $condicion_pago = $this->input->post('condicion_pago_id');

        if ($this->input->is_ajax_request()) {


            echo json_encode($this->unidades_has_precio_model->getPreciosByProdAndCondicionPago($producto, $condicion_pago));

        } else {
            redirect(base_url() . 'producto / ', 'refresh');
        }
    }

    function guardarcolumnas()
    {
        $columnas_id = $this->input->post('columna_id');

        $result = $this->columnas_model->insert($columnas_id);

        if ($result != FALSE) {

            $json['success'] = 'Se ha guardaro la configuracion';

        } else {

            $json['error'] = 'ha ocurrido un error al guardar';
        }

        echo json_encode($json);

    }

    function getSoloProductos()
    {
        if ($this->input->is_ajax_request()) {

            $where = array(
                'producto_estatus!=' => '0',
                'producto_activo!=' => '0'
            );
            $data['productos'] = $this->producto_model->get_all_by($where);
            echo json_encode($data);
        } else {
            redirect(base_url() . 'producto/', 'refresh');
        }
    }

    function paramrap()
    {  //esta es la vista de parametrizacion rapida

        $data['locales'] = $this->local_model->get_all();
        $data['unidades'] = $this->unidades_model->get_unidades();
        $data['grupos'] = $this->grupos_model->get_grupos();
        $data['tipos'] = $this->tipo_producto->get_all();
        $data['droguerias_relacionadas'] = $this->drogueria_relacionada_model->get_all();
        $data['condiciones'] = $this->condiciones_pago_model->get_all();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/producto/paramrap', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


}
