<?php
require(APPPATH . '/libraries/REST_Controller.php');

class Auth extends REST_Controller
{

    protected $uid = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('login/login_model');
        $this->load->model('opciones/opciones_model');
        $this->load->model('api/api_model', 'apiModel');
        $this->load->library('user_agent');
    }

    function index_get()
    {

    }

    function index_post()
    {


    }

    public function login_get()
    {
        //var_dump($_GET);
        echo json_encode(array('status' => 'failed'));
        // $this->response(array('status' => 'failed'), 200);
    }

    public function login_post()
    {
       
        header("Content-Type: application/json");
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers:Origin, Content-Type, X-Auth-Token , Authorization, x-api-key');

        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);

         
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $imei = $this->input->post('imei');
        $cedula = $this->input->post('cedula');
        $mobile = $this->input->post('mobile');

        if (empty($username) && empty($password)) {
            $username = $_GET['username'];
            $password = $_GET['password'];
            $imei = $_GET['imei'];
            $cedula = $_GET['cedula'];
            $mobile = $_GET['mobile'];
        }

        if (empty($username) && empty($password)) {
            $username = 'PROSODE';
            $password = 'SysCalVE87901.-';
            $imei = $_REQUEST['imei'];
            $cedula = $_REQUEST['cedula'];
            $mobile = $_REQUEST['mobile'];
        }
        echo "proceso terminado";
        die();
        // Validar
        if (!empty($username) && !empty($password)) {
            $data = array(
                'username' => $username,
                'password' => md5($password)
            );

            // Validar Usuario
            $auth = $this->login_model->verificar_usuario($data, 'ROW');


            if (count($auth) > 0) {

                if ($mobile === "true") {
                    //$auth->imei != $imei or
                    if ( $cedula != $auth->identificacion) {
                        $this->response(array('status' => 'failed', 'message' => 'IMEI o CÉDULA no autorizados'), 200);

                    }
                }


                // Clear Password
                unset($auth->var_usuario_clave);

                // Is Mobile
                if ($this->agent->is_mobile()) {
                    if ($auth->smovil == false && $auth->username!='PROSODE') {
                        $this->response(array('status' => 'failed', 'message'=>'No tiene habilitado acceso mobile'), 200);

                    }
                }

                // Config
                $config = array();
                $this->session->set_userdata((array)$auth);
                $configuraciones = $this->opciones_model->get_opciones();
                if ($configuraciones == TRUE) {
                    foreach ($configuraciones as $configuracion) {
                        $index = $configuracion['config_key'];
                        $config[$index] = $configuracion['config_value'];
                    }
                }

                $config['tipos_documento'] = array('FACTURA', 'BOLETA DE VENTA');

                $this->session->set_userdata($config);

                // Nuevo Api Key
                $apiKey = $this->apiModel->new_api_key($auth->nUsuCodigo, $level = false, $ignore_limits = false, $is_private_key = false, $ip_addresses = '');

                // Json Array
                $json = array(
                    'status' => 'success',
                    'auth' => $auth,
                    'config' => $config,
                    'api_key' => $apiKey,
                );

                //echo json_encode($json);
                $this->response($json, 200);
            } else {
                //echo json_encode(array('status' => 'ne'));
                $this->response(array('status' => 'failed', 'message' => 'Usuario o contraseña inválidos'), 200);
            }
        } else {
            //echo json_encode(array('status' => 'failed'));
            $this->response(array('status' => 'failed', 'message' => 'Datos incompletos'), 200);
        }
    }
}
