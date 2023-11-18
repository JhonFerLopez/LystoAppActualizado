<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mike42\Escpos\Printer;

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class Notificaciones extends REST_Controller
{

    protected $uid = null;

    function __construct()
    {
        parent::__construct();

        $this->load->model('api/api_model', 'api');
        $this->load->model('opciones/opciones_model');
        //$this->very_auth();
    }

    /**
     * Metodo que envia un mensaje a los usuariios que estan en la app customers
     */
    function sendNotifToTopicAppCustomers_post()
    {

        try {
            $message = $this->input->post('message');
            $title = $this->input->post('title');
            $topic = $this->input->post('topic');
            $aplicacion = 'Clientes';
            $url = 'https://fcm.googleapis.com/fcm/send';

            /**
             * busco el certificado de la aplicacion, y el topic
             */
            $FRB_PROJECT_SERVERKEY = ConfiguracionesElo::where('config_key', 'FRB_PROJECT_SERVERKEY')->first();
            $FRB_APPANDROID_CONT_NOTIF = ConfiguracionesElo::where('config_key', 'FRB_APPANDROID_CONT_NOTIF')->first();
            $FRB_APPANDROID_LIMIT_CONT_NOTIF = ConfiguracionesElo::where('config_key', 'FRB_APPANDROID_LIMIT_CONT_NOTIF')->first();

            if ($FRB_PROJECT_SERVERKEY == NULL || $FRB_PROJECT_SERVERKEY == '') {
                $this->response([
                    'isSuccess' => false,
                    'message' => 'Debe configurar los redenciales del proyecto, clave del servidor',
                    'error' => true
                ], 200);
            }

            /**
             * verifico si ya existe el contador de nofiticaciones, ya que no se guarda en el panel de configuraciones
             */
            if ($FRB_APPANDROID_CONT_NOTIF == NULL) {
                $configuraciones = array();
                $configuraciones[] = array(
                    'config_key' => 'FRB_APPANDROID_CONT_NOTIF',
                    'config_value' => 0
                );
                $result = $this->opciones_model->guardar_configuracion($configuraciones);
                $FRB_APPANDROID_CONT_NOTIF = ConfiguracionesElo::where('config_key', 'FRB_APPANDROID_CONT_NOTIF')->first();
            }

            if (
                $FRB_APPANDROID_CONT_NOTIF != NULL
                &&
                $FRB_APPANDROID_LIMIT_CONT_NOTIF != NULL
                &&
                $FRB_APPANDROID_LIMIT_CONT_NOTIF['config_value'] != ''
                &&
                $FRB_APPANDROID_CONT_NOTIF['config_value'] != ''
                &&
                ($FRB_APPANDROID_CONT_NOTIF['config_value'] + 1) > $FRB_APPANDROID_LIMIT_CONT_NOTIF['config_value']
            ) {
                $limite = $FRB_APPANDROID_LIMIT_CONT_NOTIF['config_value'];
                $this->response([
                    'isSuccess' => false,
                    'message' => 'Ha sobrepasado el límite configurado para el envió de notificaciones (' . $limite . ')',
                    'error' => true
                ], 200);
            }

            /**
             * esto enviará el msj a todos los usuarios que esten bajo este topic
             */
            $fields = array(
                'to' => $topic,
                "topic"=> $topic,
                'notification' => array(
                    'title' => $title,
                    'body' => $message
                ),
               
                'data' => array(                            
                    "notification_foreground" => true
                )
            );

            $headers = array(
                'Authorization: key=' . $FRB_PROJECT_SERVERKEY['config_value'],
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

            $result = json_decode($result);


            if ((isset($result->success) && $result->success == 1) ||
                (isset($result->message_id) && $result->message_id != false)
            ) {

                NotificacionElo::create(
                    [
                        'fecha' => date('Y-m-d H:i:s'),
                        'titulo' => $title,
                        'mensaje' => $message,
                        'topic' => $topic,
                        'aplicacion' => $aplicacion
                    ]
                );

                /**
                 * si el mensaje fue enviado con éxito, actualizo el contador
                 */
                ConfiguracionesElo::where('config_key', 'FRB_APPANDROID_CONT_NOTIF')
                    ->update(['config_value' => ($FRB_APPANDROID_CONT_NOTIF['config_value'] + 1)]);

                $this->response([
                    'isSuccess' => true,
                    'message' => 'Mensaje enviado con éxito',
                    'error' => false,
                    'status' => 200,
                ], 200);
            } else {
                $this->response([
                    'isSuccess' => false,
                    'message' => 'Ha ocurrido un error en el envío del mensaje, por favor comunicarse con soporte',
                    'error' => true
                ], 200);
            }
        } catch (Exception $e) {

            $this->response([
                'isSuccess' => false,
                'message' => $e->getMessage(),
                'error' => 'Ha ocurrido un error, por favor comunicarse con soporte'
            ], 400);
        }
    }

    function very_auth()
    {
        $reqHeader = $this->input->request_headers();
        $key = very_key($reqHeader, $this->get(), $this->post(), $this->options());
        $auth_id = $this->api->getAuth($key);
        if (!empty($auth_id)) {
            $this->uid = $auth_id;
        } else {
            $this->response([
                'isSuccess' => false,
                'message' => 'No tiene permiso para obtener los datos',
                'error' => true,
                'status' => 400,
            ], 400);
        }
    }
}
