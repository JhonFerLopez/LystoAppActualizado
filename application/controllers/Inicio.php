<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class inicio extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('login/login_model', 'login');
        $this->load->model('local/local_model', 'local');
        $this->load->model('opciones/opciones_model');
        $this->load->model('cajas/StatusCajaModel');
        $this->load->model('api/api_model', 'apiModel');

        $this->load->library('session');
        $this->load->model('opciones/opciones_model');

    }

    function very_sesion()
    {

        $ver = $this->login->very_session();
        if ($ver == false) {
            echo json_encode(false);
        } else {
            echo json_encode($ver);
        }
    }

    function checkSysExpDat()
    {

        $remaingin = 0;
        $result = 'success';

        if ($this->session->userdata('nombre_grupos_usuarios') != 'PROSODE_ADMIN') {
            $ver = $this->login->checkSysExpDat();
            if (sizeof($ver) > 0) {

                /* $today = date('d-m-Y', time());
                 $exp = date('d-m-Y', strtotime($this->config->item('sid_system_expiration_date'))); //CONFIG VALUE
                 $expDate = date_create($exp);
                 $todayDate = date_create($today);
                 $diff = date_diff($todayDate, $expDate);

                 if ($diff->format("%R%a") > 0) {*/

                $today = date('d-m-Y', time());
                $exp = date('d-m-Y', strtotime($ver['config_value'])); //query result form database
                $expDate = date_create($exp);
                $todayDate = date_create($today);
                $diff = date_diff($todayDate, $expDate);
                $remaingin = $diff->format("%R%a days");
                if ($diff->format("%R%a") > -8) {
                    $remaingin = $diff->format("%R%a");
                } else {

                    $result = 'error';
                }

                /* } else {
                     $result = 'error';
                 }*/
            } else {
                $result = 'error';
            }
        }


        /*lo siguiente es para validar las notificaciones del control ambiental,
        no se hacen llamadas a BD sino todo esta en sesion, por lo tanto debe ser rapida la ejecucion */
        $horaactual = date('H');
        $minutoactual = date('i');
        $mensajecontrol = 'Debe realizar el control Ambiental. ';
        $session_control = json_decode($this->session->userdata('control_amb_actual'));
        $control_ambiental_hrsnot = json_decode($this->session->userdata('control_ambiental_hrsnot'));
        $notificaciones = false;
        $diahoy = date('d');
        if (isset($session_control[0])) {
            $notificaciones = $session_control[0]->notif_control_hoy;
        }

        $retornonotificacion = array();
        $cont = 0;
        $continuar = true;
        //recorro los datos de los 6 controles ambientales.
        $cont_continue = 2;
        if ($this->session->userdata('KEY_RECIBE_NOTIF_CONTROL_AMB') != '' &&
            $this->session->userdata('KEY_RECIBE_NOTIF_CONTROL_AMB') != null &&
            $this->input->post('tokenfirebase') != '0' &&
            $this->session->userdata('KEY_RECIBE_NOTIF_CONTROL_AMB') == $this->input->post('tokenfirebase')
        ) {

            if ($control_ambiental_hrsnot != NULL) {

                foreach ($control_ambiental_hrsnot as $hrsnot) {

                    //verifico si ya fue notificado.
                    if ($notificaciones != false && count($notificaciones) > 0) {
                        foreach ($notificaciones as $notif) {
                            if ($notif->item == $hrsnot->alias && $notif->fecha != null) {
                                continue 2;
                            }
                        }
                    }

                    //si no ha sido notificado, entonces valido si la hora es mayor para poder mostrarlo en la notificacion.
                    $amopm = substr($hrsnot->alias, strlen($hrsnot->alias) - 2, strlen($hrsnot->alias));
                    $hora_control = null;


                    if ($hrsnot->hora != NULL) {

                        $hora_control = $hrsnot->hora;
                        $cadena = date("H", strtotime($hora_control . ":00 " . $amopm));
                        if ($horaactual >= $cadena) {

                            if ($hrsnot->alias == 'humedad_relat_am' or $hrsnot->alias == 'humedad_relat_pm') {

                                if ($hrsnot->minutos != NULL) {

                                    //si tiene algun valor en minutos, pregunto si el minuto actual>= a los minutos configurados
                                    if ($minutoactual >= $hrsnot->minutos) {
                                        $retornonotificacion[$cont] = array();
                                        $retornonotificacion[$cont]['alias'] = '';
                                        $retornonotificacion[$cont]['mensaje'] = '';
                                        $retornonotificacion[$cont]['dia'] = '';
                                        $retornonotificacion[$cont]['control_id'] = '';

                                        //si entra aqui, la hora actual, es mayor y los minutos tambien son mayores (o iguales)
                                        $hora_control .= ":" . $hrsnot->minutos . " " . $amopm;
                                        $texto = "(" . $hrsnot->nombre . ". " . $hora_control . ")";
                                        $retornonotificacion[$cont]['alias'] = $hrsnot->alias;
                                        $retornonotificacion[$cont]['mensaje'] = $mensajecontrol . $texto;
                                        $retornonotificacion[$cont]['dia'] = $diahoy;
                                        $retornonotificacion[$cont]['control_id'] = $session_control[0]->control_ambiental_id;
                                        $cont++;

                                        //$this->mandarNotificacion($mensajecontrol . $texto);


                                    } else {

                                        //si entra aqui, quiere decir que el minuto actual es menor al configurado,
                                        //se muestra la notif cuando los minutos sean menores que los configurados,
                                        // y la hora actual sea mayor que la del config
                                        if ($horaactual > $cadena) {
                                            $retornonotificacion[$cont] = array();
                                            $retornonotificacion[$cont]['alias'] = '';
                                            $retornonotificacion[$cont]['mensaje'] = '';
                                            $retornonotificacion[$cont]['dia'] = '';
                                            $retornonotificacion[$cont]['control_id'] = '';

                                            $hora_control .= ":" . $hrsnot->minutos . " " . $amopm;
                                            $texto = "(" . $hrsnot->nombre . ". " . $hora_control . ")";
                                            $retornonotificacion[$cont]['alias'] = $hrsnot->alias;
                                            $retornonotificacion[$cont]['mensaje'] = $mensajecontrol . $texto;
                                            $retornonotificacion[$cont]['dia'] = $diahoy;
                                            $retornonotificacion[$cont]['control_id'] = $session_control[0]->control_ambiental_id;
                                            $cont++;
                                            //$this->mandarNotificacion($mensajecontrol . $texto);
                                        }
                                    }
                                } else {

                                    //aqui entra cuando los minutos estan en null, pñor lo tanto la hora sera en punto=00
                                    $retornonotificacion[$cont] = array();
                                    $retornonotificacion[$cont]['alias'] = '';
                                    $retornonotificacion[$cont]['mensaje'] = '';
                                    $retornonotificacion[$cont]['dia'] = '';
                                    $retornonotificacion[$cont]['control_id'] = '';

                                    $hora_control .= ":00 " . $amopm;
                                    $texto = "(" . $hrsnot->nombre . ". " . $hora_control . ")";
                                    $retornonotificacion[$cont]['alias'] = $hrsnot->alias;
                                    $retornonotificacion[$cont]['mensaje'] = $mensajecontrol . $texto;
                                    $retornonotificacion[$cont]['dia'] = $diahoy;
                                    $retornonotificacion[$cont]['control_id'] = $session_control[0]->control_ambiental_id;
                                    $cont++;
                                    //$this->mandarNotificacion($mensajecontrol . $texto);
                                }
                            }
                            $cont_continue++;
                        }
                    }
                }
            }
        }
        /******************************************************************************/

        echo json_encode(array('result' => $result, 'remaining' => $remaingin, 'notif_control_amb' => $retornonotificacion));
    }

    function mandarNotificacion($mensaje)
    {

		$API_ACCESS_KEY=$this->session->userdata('FRB_APPWEB_SERVERKEY');
		$url = 'https://fcm.googleapis.com/fcm/send';
		$registrationIds = array($this->session->userdata('KEY_RECIBE_NOTIF_CONTROL_AMB'));
// prepare the message

        $fields = array(
            'registration_ids' => $registrationIds,
            'data' => array(
                'notification' => array(
                    //'icon'=>base_url().'recursos/img/sid-login-01.png',
                    'title' => 'Control ambiental cverifique',
                    'body' => $mensaje))
        );
        $headers = array(
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

    }


    function renew_sesion()
    {
        $this->login->refresh_session();

    }

    public function index()
    {
        $data['lstLocal'] = $this->local->get_all();
        $configuraciones = $this->opciones_model->get_opciones();
        if ($configuraciones == TRUE) {
            foreach ($configuraciones as $configuracion) {

                $clave = $configuracion['config_key'];
                if ($clave == 'TIPO_EMPRESA') {

                    $data[$clave] = $configuracion['config_value'];
                }

            }
        }
        $tipo_empresa = isset($data['TIPO_EMPRESA']) ? $data['TIPO_EMPRESA'] : '';
        switch ($tipo_empresa) {
            case 'OTRO':
                $imgbanner = base_url() . 'recursos/img/banner-otros.png';
                $imglogo = base_url() . 'recursos/img/sidposlogin.png';
                break;
            default:
                $imgbanner = base_url() . 'recursos/plugins/images/login-register.jpg';
                $imglogo = base_url() . 'recursos/img/sid-login-01.png';
                break;
        }
        $data['imgbanner'] = $imgbanner;
        $data['imglogo'] = $imglogo;
        $this->load->view('login', $data);


        if ($this->session->userdata('nUsuCodigo')) {
            redirect(base_url() . 'principal');
        }

    }

    public
    function validarTema()
    {
        $ruta = array('tema' => $this->input->post('ruta'));
        $this->session->set_userdata($ruta);
        echo json_encode($ruta);
    }

    function validar_login()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('user', 'user', 'required');
            $this->form_validation->set_rules('pw', 'pw', 'required');
            if ($this->form_validation->run() == false) {
                echo validation_errors();
            } else {
                $password = md5($this->input->post('pw', true));
                $data = array(
                    'username' => $this->input->post('user', true),
                    'password' => $password
                );

                // Auth
                $auth = $this->login->verificar_usuario($data);

                if ($auth) {
                    $data = array();


                    // Nuevo Api Key
                    $data['api_key'] = $this->apiModel->new_api_key($auth['nUsuCodigo'], $level = false, $ignore_limits = false, $is_private_key = false, $ip_addresses = '');

                    // Session Data
                    $this->session->set_userdata($data);

                    $this->session->set_userdata($auth);


                    $cajas_abiertas = $this->StatusCajaModel->getAlBy(
                        array(
                            'apertura IS NOT null' => NULL,
                            'cierre IS null ' => NULL)
                    );
                    if (count($cajas_abiertas) > 0) {
                        foreach ($cajas_abiertas as $caja) {
                            if ($caja['cajero'] == $auth['nUsuCodigo']) {
                                $this->session->set_userdata('cajapertura', $caja['id']);
                                $this->session->set_userdata('caja_id', $caja['caja_id']);
                                $this->session->set_userdata('cajero_id', $auth['nUsuCodigo']);
                            };
                        }
                    }

                    $this->saveSesionControlAmb();

                    $configuraciones = $this->opciones_model->get_opciones();
                    if ($configuraciones == TRUE) {
                        foreach ($configuraciones as $configuracion) {
                            $index = $configuracion['config_key'];
                            $data[$index] = $configuracion['config_value'];
                            if ($configuracion['config_key'] == 'BODEGA_PRINCIPAL') {
                                $this->session->set_userdata('id_local', $configuracion['config_value']);
                            }
                        }
                    }

                    $data['msj'] = 'ok';

                } else {
                    $data['msj'] = 'no ok';
                }

                echo json_encode($data);
            }
        } else {
            echo json_encode(array('status' => 'failed', 'paraments' => 'Nombre de usuario o Contraseña invalida.'));
        }
    }
}
