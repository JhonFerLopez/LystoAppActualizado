<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');
use  Illuminate\Database\Capsule\Manager as DB;

class ciudades extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();

        $this->load->model('estado/estado_model');

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
		$data['ciudades'] = $this->estado_model->get_all();

        if ($data) {
            $this->response($data, 200);
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

        $data = array();
		$data['ciudades'] = $this->estado_model->get_by('estado_id', $id);

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    public function get_by_pais_get()
    {
        $id = $this->get('pais');
        if (empty($id)) {
            $this->response(array(), 200);
        }

		$data = array();
        $data['ciudades'] = $this->estado_model->get_all_by('pais_id', $id);

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    // Save
    public function create_get()
    {
        //
    }

    // Update
    public function update_get()
    {
        //
    }

    public function allCiudadadesSelect2_get(){
        try {

            $data=array();

            $search = $this->input->get('search');
            $departamento_id= false;

            $data['ciudades'] = CiudadElo::where(function ($ciudad) use ($search) {
                $ciudad->where('ciudad_nombre', 'like', '%' . strtolower($search) . '%')
                    ->orWhere('ciudad_id', 'like', '%' . strtolower($search) . '%');
            });

            if(
                $this->input->get('departamento_id')
            ){
                $data['ciudades'] = $data['ciudades']->where('estado_id',$this->input->get('departamento_id'));
            }
            $data['ciudades'] = $data['ciudades']->select(DB::raw("ciudad_nombre as text,ciudad_id as id, estado_id "))
                ->limit(30)
                ->orderBy('ciudad_id', 'desc')
                ->get();

            $this->response($data, 200);

        } catch (Exception $e) {
            log_message('ERROR', 'Ocurrió un error al buscar los datos');
            log_message('ERROR', $e->getMessage());
            $this->response(array("message" => $e->getMessage()), 400);
        }
    }
}