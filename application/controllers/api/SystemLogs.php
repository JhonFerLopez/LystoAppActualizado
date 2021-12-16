<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class SystemLogs extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/api_model', 'api');
        $this->load->model('cajas/StatusCajaModel');
        $this->load->model('cajas/cajas_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('metodosdepago/metodos_pago_model');
        $this->load->model('venta/venta_model');

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

    function index_get()
    {

    }

    function datatable_get()
    {


        $datajson = array();
        $datas = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';
        $usuario = isset($data['usuario']) ? $data['usuario'] : '';


        $where = array();
        if ($fechadesde != "") {
            $where['fecha >='] = date('Y-m-d', strtotime($fechadesde)) . " " . date('H:i:s', strtotime('0:0:0'));

        }

        if ($fechahasta != "") {
            $where['fecha <='] = date('Y-m-d', strtotime($fechahasta)) . " " . date('H:i:s', strtotime('23:59:59'));

        }


        if ($usuario != "") {
            $where['usuario'] = $usuario;
        }


        $search = $this->input->get('search');
        $buscar = $search['value'];
        $where_custom = false;
        if (!empty($search['value'])) {
            $where_custom = "(log_id LIKE '%" . $buscar . "%'
              or fecha LIKE '%" . $buscar . "%' 
              or tipo LIKE '%" . $buscar . "%'
              or data_before LIKE '%" . $buscar . "%'
              or data_after LIKE '%" . $buscar . "%'
              or tabla LIKE '%" . $buscar . "%')";
        }

        $nombre_or = false;
        $where_or = false;
        $nombre_in = false;
        $where_in = false;

        $group = false;

        $select = '*';

        $from = "system_logs";
        $join = array('usuario');
        $campos_join = array('usuario.nUsuCodigo = system_logs.usuario');
        $tipo_join = array('left');

        $ordenar = $this->input->get('order');
        $order = false;
        $order_dir = 'desc';
        if (!empty($ordenar)) {
            $order_dir = $ordenar[0]['dir'];
            if ($ordenar[0]['column'] == 0) {
                $order = 'log_id';
            }
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


        $datas['datos'] = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", $limit, $start, $order_dir, false, $where_custom);

        $array = array();

        $count = 0;


        foreach ($datas['datos'] as $data) {


            $json = array();


            $json[] = $data['log_id'];
            $json[] = $data['username'];
            $json[] = $data['ip'];
            $json[] = date('d-m-Y H:i:s', strtotime($data['fecha']));
            $json[] = $data['tabla'];
            $json[] = $data['tipo'];
            $json_array = json_decode($data['data_before']);
            $stringantes = '';
            try {
                foreach ($json_array as $key => $val) {
                    if (is_object($val) || is_array($val)) {
                        $stringantes .= $key . ' => ';
                        foreach ($val as $k => $v) {
                            $stringantes  .= $v.", ";
                        }
                    }else{
                        $stringantes .= $key . ' => ' . $val . " ";
                    }
                }
            } catch (Exception $exception) {

            }
            $json[] = $stringantes;
            $json_array = json_decode($data['data_after']);
            $stringantes = '';

               foreach ($json_array as $key => $val) {
                    if (is_object($val) || is_array($val)) {
                        $stringantes .= $key . ' => ';
                        foreach ($val as $k => $v) {

                            if (is_object($v) || is_array($v)) {
                                //$stringantes .= $key . ' => ';
                                foreach ($v as $j => $o) {

                                    $stringantes .= $o.", ";
                                }
                            }else{
                                $stringantes .= $v.", ";
                            }

                        }
                    }else{
                        $stringantes .= $key . ' => ' . json_decode($val) . " ";
                    }
                }

            $json[] = $stringantes;
            $datajson[] = $json;
            $count++;


        }


        $total = $this->venta_model->traer_by_mejorado($select, $from, $join, $campos_join, $tipo_join, $where,
            $nombre_in, $where_in, $nombre_or, $where_or, $group, $order, "RESULT_ARRAY", null, null, $order_dir, false, $where_custom);


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

    function get_errorlogs()
    {

        $result = array();
        $dir = './application/logs/';
        if (!is_dir($dir)) return array();
        $temp = scandir($dir);
        foreach ($temp as $img) {
            if (is_file($dir . $img))
                $result[] = $img;
        }
        natsort($result);

        return $result;
    }

    function datatableerror_get()
    {

        $logs_errores = $this->get_errorlogs();
        $datajson = array();
        $data = $this->input->get('data');
        $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
        $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';
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

        $cont = 0;

        for ($i = count($logs_errores) - 1; $i > 0; $i--) {

            $json = array();


            if (($limit == false) || ($limit != false and $cont < $limit)) {
                if (substr($logs_errores[$i], 0, 3) == "log") {

                    $fecha_archivo = substr($logs_errores[$i], 4, 10);
                    $fecha_archivo = date('Y-m-d', strtotime($fecha_archivo));

                    if ($fecha_archivo >= date('Y-m-d', strtotime($fechadesde)) &&
                        $fecha_archivo <= date('Y-m-d', strtotime($fechahasta))
                    ) {
                        $fechaformateada = '"' . $fecha_archivo . '"';
                        $json[] = "<a href='#' onclick='showerrorlog($fechaformateada)'>" . date('d-m-Y', strtotime($fecha_archivo)) . "</a>";
                        $datajson[] = $json;
                    }
                }
            }

            $cont++;
        }


        $total = count($datajson);

        $array['data'] = $datajson;
        $array['draw'] = $draw;//esto debe venir por post
        $array['recordsTotal'] = $total;
        $array['recordsFiltered'] = $total; // esto dbe venir por post

        if ($logs_errores) {
            $this->response($array, 200);
        } else {
            $this->response(array(), 200);
        }

    }


}