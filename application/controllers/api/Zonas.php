<?php

// Api Rest

require(APPPATH . '/libraries/REST_Controller.php');
use  Illuminate\Database\Capsule\Manager as DB;

class zonas extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();

        $this->load->model('zona/zona_model');
        $this->load->library('form_validation');

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
		$data['zonas'] = $this->zona_model->get_all();

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
		$data['zonas'] = $this->zona_model->get_by('zona_id', $id);

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(array(), 200);
        }
    }

    public function allBarriosSelect2_get(){
        try {

            $data=array();

            $search = $this->input->get('search');
            $ciudad_id= false;

            $data['barrios'] = BarrioElo::where(function ($barrio) use ($search) {
                $barrio->where('zona_nombre', 'like', '%' . strtolower($search) . '%')
                    ->orWhere('zona_id', 'like', '%' . strtolower($search) . '%');
            });

            if(
            $this->input->get('ciudad_id')
            ){
                $data['barrios'] = $data['barrios']->where('ciudad_id',$this->input->get('ciudad_id'));
            }
            $data['barrios'] = $data['barrios']->select(DB::raw("zona_nombre as text,zona_id as id, ciudad_id "))
                ->limit(30)
                ->orderBy('zona_id', 'desc')
                ->get();

            $this->response($data, 200);

        } catch (Exception $e) {
            log_message('ERROR', 'Ocurrió un error al buscar los datos');
            log_message('ERROR', $e->getMessage());
            $this->response(array("message" => $e->getMessage()), 400);
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
}