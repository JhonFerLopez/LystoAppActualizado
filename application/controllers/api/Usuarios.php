<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class Usuarios extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('usuariosgrupos/usuarios_grupos_model');
        $this->load->model('api/api_model', 'apiModel');
        $this->load->model('usuario/usuario_model');
        $this->load->library('user_agent');
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

    public function getUsuariosByRolNomb_post()
    {
        $nombre_grupo=$this->input->post('rol');

        $result['usuarios'] = $this->usuarios_grupos_model->getUsuariosByRolNomb($nombre_grupo);
        if ($result)
            $this->response($result, 200);
        else
            $this->response(array(), 200);
    }

    public function getDomiciliarios_post()
    {

        $result['usuarios'] = $this->usuarios_grupos_model->getUsuariosByRol();
        if ($result)
            $this->response($result, 200);
        else
            $this->response(array(), 200);
    }

    public function getPosiDomiciliario_post()
    {

        $grupoDomiciliario = $this->usuarios_grupos_model->get_by('nombre_grupos_usuarios','DOMICILIARIO');
        if(sizeof($grupoDomiciliario)>0){

            $where=array(
                'grupo' =>$grupoDomiciliario['id_grupos_usuarios']
            );
            $result['usuarios'] = $this->usuario_model->getPosiciones($where);
        }else{
            $result['error']="Debe crear un rol llamado DOMICILIARIO";
        }

        if ($result)
            $this->response($result, 200);
        else
            $this->response(array(), 200);
    }





}