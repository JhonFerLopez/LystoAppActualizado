<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class clientes extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();

        $this->load->model('cliente/cliente_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('api/api_model', 'api');
        $this->load->model('venta/venta_model', 'venta');
        $this->load->model('afiliado/afiliado_descuentos', 'afiliado_descuentos');
        $this->load->library('form_validation');

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

    // All
    public function index_get()
    {
        $vendedor = $this->input->get('vendedor');


        // Pagination Result
        $total = $this->cliente_model->count_all();

        $datas = array();

        $where = array();

        if ($vendedor != '') {
            $isadmin = $this->usuario_model->buscar_id($vendedor);
            if ($isadmin->admin == 1) {
                $vendedor = '';
            }
        }

        $datajson=array();


        $where['cliente.cliente_status'] = '1';

        $search = $this->input->get('search');
        $select2 = $this->input->get('select2');

        if (!empty($select2)) {

            $buscar = $search;
        } else {
            $buscar = $search['value'];
        }


        $where_custom = false;
        if (!empty($search['value'])) {
            $where_custom = "(cliente.id_cliente LIKE '%" . $buscar . "%' or cliente.nombres LIKE '%" . $buscar . "%'
            or cliente.apellidos LIKE '%" . $buscar . "%'
            or cliente.identificacion LIKE '%" . $buscar . "%' or cliente.direccion LIKE '%" . $buscar . "%'
           
            or telefono LIKE '%" . $buscar . "%')";
        }
        //  var_dump($like);

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;
        $select = 'distinct(cliente.id_cliente),cliente.*, ciudades.*,estados.*,pais.*, grupos_cliente.*, zonas.*';
        $from = "cliente";
        $join = array('ciudades', 'estados', 'pais', 'grupos_cliente', 'zonas');
        $campos_join = array('ciudades.ciudad_id=cliente.ciudad_id', 'ciudades.estado_id=estados.estados_id',
            'pais.id_pais=estados.pais_id', 'grupos_cliente.id_grupos_cliente=cliente.grupo_id', 'zonas.zona_id=cliente.id_zona');
        $tipo_join = array(null, null, null, null, 'left');

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'id_cliente';
            }
            if ($ordenar[0]['column'] == 1) {
                $order = 'nombres';
            }
            if ($ordenar[0]['column'] == 2) {
                $order = 'apellidos';
            }
            if ($ordenar[0]['column'] == 3) {
                $order = 'identificacion';
            }


        }


        $start = 0;
        $limit = false;
        $draw = $this->input->get('draw');
        if (!empty($draw)) {

            $start = $this->input->get('start');
            $limit = $this->get('length');
            if($limit=='-1'){
                $limit=false;
            }
            //$order=
        }


        // echo $limit;
        // var_dump($this->input->get(null));

        $datas['clientes'] = $this->cliente_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);
        // ($vendedor, $filter, $page, $limit);

        $total = $this->cliente_model->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, false, $order, "RESULT_ARRAY", false, $start, $order_dir, false, $where_custom);

        $array = array();
        $array['clientes_total'] = $total;
        $array['clientes'] = array();
        $array['clientesjson'] = array();

        $count = 0;
        foreach ($datas['clientes'] as $data) {
            $arr = $data;
            $clientjson = array();
            $clientjson[] = $data['id_cliente'];

            $clientjson[] = $data['codigo_interno'];
            $clientjson[] = $data['nombres'];
            $clientjson[] = $data['apellidos'];
            $clientjson[] = $data['identificacion'];

            $clientjson[] = $data['direccion'];


            $clientjson[] = '<div class="btn-group"> <a class="btn btn-default" data-toggle="tooltip" 
                title="Editar" data-original-title="fa fa-comment-o"
                href="#" onclick="Cliente.editar(' . $data['id_cliente'] . ');"> <i class="fa fa-edit"></i>
                </a><a class="btn btn-default" data-toggle="tooltip"  title="Eliminar" data-original-title="fa fa-comment-o"
                onclick="Cliente.borrar(' . $data['id_cliente'] . ',\'' . $data['nombres'] . '\');">   <i class="fa fa-trash-o"></i> </a> </div>';


            $arr['id_cliente'] = $data['id_cliente'];
            $arr['id'] = $data['id_cliente'];
            $arr['nombre'] = $data['nombres'] . " " . $data['apellidos'];

            $where = array('venta.id_cliente' => $data['id_cliente']);
            $where['condiciones_pago.dias >'] = '0';
            $nombre_or = false;
            $where_or = false;
            $nombre_in[0] = 'var_credito_estado';
            $where_in[0] = array('DEBE', 'A_CUENTA');
            $nombre_in[1] = 'venta_status';
            $where_in[1] = array('ENTREGADO', 'DEVUELTO PARCIALMENTE', 'COMPLETADO');
            $select = 'venta.venta_id, venta.id_cliente, nombres,fecha, total,var_credito_estado, sum(dec_credito_montodebito) as suma, documento_venta.*,
            nombre_condiciones';
            $from = "venta";
            $join = array('credito', 'cliente', 'documento_venta', 'tipo_venta', 'condiciones_pago');
            $campos_join = array('credito.id_venta=venta.venta_id', 'cliente.id_cliente=venta.id_cliente',
                'documento_venta.id_venta=venta.venta_id', 'tipo_venta.tipo_venta_id=venta.venta_tipo', 'condiciones_pago.id_condiciones=tipo_venta.condicion_pago');
            $tipo_join = false;
            $result['lstVenta'] = $this->venta->traer_by($select, $from, $join, $campos_join, $tipo_join, $where,
                $nombre_in, $where_in, $nombre_or, $where_or, false, false, "RESULT_ARRAY");

            $arr['saldo_actual'] = $result['lstVenta'][0]['suma'];

            $aprobaos = $this->venta->count_by('venta_status', Array(PEDIDO_ENTREGADO, PEDIDO_DEVUELTO));
            $rechazads = $this->venta->count_by('venta_status', Array(PEDIDO_RECHAZADO));
            $arr['cantidad_aprobados'] = $aprobaos['count'];
            $arr['cantidad_rechazado'] = $rechazads['count'];
            $array['clientes'][] = $arr;
            $datajson[]  = $clientjson;

            $count++;
        }




        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = sizeof($total);
        $array['recordsFiltered'] = sizeof($total); // esto dbe venir por post

        if ($datas) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    // Show
    public function ver_get()
    {
        $id = $this->get('id');
        if (empty($id)) {
            $this->response(array(), 200);
        }

        $data['cliente'] = $this->cliente_model->get_by('id_cliente', $id);
        if ($data['cliente']['afiliado'] != null) {
            $data['cliente']['afiliado_descuentos'] = $this->afiliado_descuentos->get_all_by(array('afiliado_id' => $data['cliente']['afiliado']));
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    // Save
    public function create_get()
    {
        //$this->request->method;
        $this->form_validation->set_rules('identificacion', '', 'required');
        $this->form_validation->set_rules('email', '', 'required');
        $this->form_validation->set_rules('razon_social', '', 'required');

        /* if ($this->form_validation->run() === false) {
             $this->response(array('status' => 'failed', 'errors' => 'Ingrese los parametros requeridos'), 400);
         } else {*/
        $vendedor_id = $this->input->get('vendedor');
        $cliente['ciudad_id'] = $this->input->get('ciudad_id', true);
        $cliente['codigo_postal'] = $this->input->get('codigo_postal', true);
        $cliente['descuento'] = $this->input->get('descuento', true);
        $cliente['direccion'] = $this->input->get('direccion', true);
        $cliente['direccion2'] = $this->input->get('direccion2', true);
        $cliente['email'] = $this->input->get('email', true);
        $cliente['grupo_id'] = $this->input->get('grupo_id', true);
        $cliente['representante'] = $this->input->get('representante', true);
        $cliente['limite_credito'] = $this->input->get('limite_credito', true);
        $cliente['razon_social'] = $this->input->get('razon_social', true);
        $cliente['identificacion'] = $this->input->get('identificacion', true);
        $cliente['pagina_web'] = $this->input->get('pagina_web', true);
        $cliente['telefono1'] = $this->input->get('telefono1', true);
        $cliente['telefono2'] = $this->input->get('telefono2', true);
        $cliente['nota'] = $this->input->get('nota', true);
        $cliente['latitud'] = $this->input->get('latitud', true);
        $cliente['longitud'] = $this->input->get('longitud', true);
        $cliente['id_zona'] = $this->input->get('id_zona', true);
        $cliente['vendedor_a'] = !empty($vendedor_id) ? $vendedor_id : null;
        $cliente['cliente_status'] = 1;

        $get = $this->input->get(null);
        if (!empty($get)) {
            $save = $this->cliente_model->insertar($cliente);
            if ($save === false) {
                $this->response(array('status' => 'failed', 'errors' => $save));
            } else {
                if ($save === true) {
                    $this->response(array('status' => 'success'));
                } else {
                    $this->response(array('status' => 'failed', 'errors' => $save));
                }
            }
        } else {
            $this->response(array('status' => 'failed',
                'errors' => 'No han llegado parametros'
            ));
        }
        //}
    }

    // Update
    public function update_get()
    {
        $vendedor_id = $this->input->get('vendedor');
        $cliente['ciudad_id'] = $this->input->get('ciudad_id', true);
        $cliente['codigo_postal'] = $this->input->get('codigo_postal', true);
        $cliente['descuento'] = $this->input->get('descuento', true);
        $cliente['direccion'] = $this->input->get('direccion', true);
        $cliente['direccion2'] = $this->input->get('direccion2', true);
        $cliente['email'] = $this->input->get('email', true);
        $cliente['grupo_id'] = $this->input->get('grupo_id', true);
        $cliente['representante'] = $this->input->get('representante', true);
        $cliente['limite_credito'] = $this->input->get('limite_credito', true);
        $cliente['razon_social'] = $this->input->get('razon_social', true);
        $cliente['identificacion'] = $this->input->get('identificacion', true);
        $cliente['pagina_web'] = $this->input->get('pagina_web', true);
        $cliente['telefono1'] = $this->input->get('telefono1', true);
        $cliente['telefono2'] = $this->input->get('telefono2', true);
        $cliente['nota'] = $this->input->get('nota', true);
        $cliente['latitud'] = $this->input->get('latitud', true);
        $cliente['longitud'] = $this->input->get('longitud', true);
        $cliente['id_zona'] = $this->input->get('id_zona', true);
        $cliente['vendedor_a'] = !empty($vendedor_id) ? $vendedor_id : null;
        $cliente['id_cliente'] = $this->input->get('id_cliente', true);

        $post = $this->input->get(null, true);
        if (!empty($post)) {
            $save = $this->cliente_model->update($cliente);
            if ($save === false) {
                $this->response(array('status' => 'failed', 'errors' => $save));
            } else {
                if ($save === true) {
                    $this->response(array('status' => 'success'));
                } else {
                    $this->response(array('status' => 'failed', 'errors' => $save));
                }
            }
        } else {
            $this->response(array('status' => 'failed'));
        }
    }

    /*metodo que busca los clientes, especialmente utilizados para buscalos por ajax*/
    public function clientesselect2_get(){
        $data = array();
        $buscar = "";
        if (isset($_GET['search'])) {
            $buscar = $_GET['search'];
        }

        $data = $this->cliente_model->getClientesSelect2($buscar);
        $this->response($data, 200);

    }
}