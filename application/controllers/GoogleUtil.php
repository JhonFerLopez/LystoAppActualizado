<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GoogleUtil extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */


    function __construct()
    {
        parent::__construct();


        $this->load->model('opciones/opciones_model');

    }

    public function auth()
    {

        //echo $this->google->getLibraryVersion();

        $client = new Google_Client();

        $client->setApplicationName("SID Sistema integral de droguerías");

        //obtengo los datos de la aplicacion prosode
        $cliente_secret = $this->opciones_model->getByKey("CLIENTE_SECRET_DRIVE");
        $cliente_secret = json_decode($cliente_secret['config_value'], true);
        $cliente_secret = (array)$cliente_secret;

        $client->setAuthConfig($cliente_secret);


        //  $client->setAuthConfig('188234470297-ltbftqeikhtcaoe92lr0cqo34rbvj3pa.apps.googleusercontent.com_secreto_cliente.json');
        $client->setAccessType("offline");
        $client->setIncludeGrantedScopes(true);   // incremental auth
        $client->setRedirectUri(base_url() . 'GoogleUtil/auth');
        $client->addScope([Google_Service_Drive::DRIVE,
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/userinfo.email']);


        if (!isset($_GET['code'])) {
            $auth_url = $client->createAuthUrl();
            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        } else {

            $authobject = $client->authenticate($_GET['code']);

            $_SESSION['access_token'] = $client->getAccessToken();

            $oauth2 = new Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();


            $configuraciones[] = array(
                'config_key' => 'GOOGLE_CLENT_AVATAR',
                'config_value' => $userInfo->picture
            );

            $configuraciones[] = array(
                'config_key' => 'GOOGLE_CLENT_USERNAME',
                'config_value' => $userInfo->email
            );

            $configuraciones[] = array(
                'config_key' => 'GOOGLE_CLENT_ACCES_TOKEN',
                'config_value' => $userInfo->email
            );

            $configuraciones[] = array(
                'config_key' => 'GOOGLE_CLENT_ACCES_TOKEN',
                'config_value' => json_encode($client->getAccessToken())
            );

            $configuraciones[] = array(
                'config_key' => 'GOOGLE_CLENT_ACCOUNT_ID',
                'config_value' => $userInfo->id
            );

            $configuraciones[] = array(
                'config_key' => 'GOOGLE_CLENT_AUTH_DATA',
                'config_value' => json_encode($authobject)
            );

            $configuraciones[] = array(
                'config_key' => 'GOOGLE_CLENT_USER_INFO',
                'config_value' => json_encode($userInfo)
            );


            $result = $this->opciones_model->guardar_configuracion($configuraciones);


            if ($result) {
                $json['success'] = 'Las configuraciones se han guardado exitosamente';
            } else {
                $json['error'] = 'Ha ocurido un error al guardar las configuraciones';
            }


           header('Location: ' . filter_var(base_url(), FILTER_SANITIZE_URL));
        }
    }


}
