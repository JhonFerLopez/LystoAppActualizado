<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class DocumentoInventario extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();

        $this->load->model('documento_inventario/documento_inventario_model');

        $this->load->model('api/api_model', 'api');
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

        if (!empty($auth_id)) {
            $this->uid = $auth_id;
        } else {
            $this->uid = null;
        }
    }

    // All
    public function index_get()
    {
        $data = array();
        $data['documentos'] = $this->documento_inventario_model->get_all();

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    // Show
    public function ver_get()
    {
        $id = $this->get('documento_id');
        if (empty($id)) {
            $this->response(array(), 200);
        }

        $data = array();
        $data['documento'] = $this->documento_inventario_model->get_by('documento_id', $id);

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    function show_by_tipo_get()
    {
        $tipo = $this->get('tipo');
        if (empty($tipo)) {
            $this->response(array(), 200);
        }
        $data = array();
        $data['documentos'] = $this->documento_inventario_model->get_all_by(array('documento_tipo'=> $tipo));
        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(array(), 200);
        }

    }

// Save
    public
    function create_get()
    {
        //
    }

// Update
    public
    function update_get()
    {
        //
    }
}