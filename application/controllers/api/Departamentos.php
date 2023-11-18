<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

use  Illuminate\Database\Capsule\Manager as DB;

class Departamentos extends REST_Controller
{
    protected $uid = null;

    function __construct()
    {
        parent::__construct();
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

        // ID ?
        if (!empty($auth_id)) {
            $this->uid = $auth_id;
        } else {
            $this->uid = null;
        }
    }

    public function allDepartmentSelect2_get(){
        try {

            $data=array();

            $search = $this->input->get('search');

            $data['departamentos'] = DepartamentoElo::where(function ($departamento) use ($search) {
                $departamento->where('estados_nombre', 'like', '%' . strtolower($search) . '%')
                    ->orWhere('estados_id', 'like', '%' . strtolower($search) . '%');
            })->select(DB::raw("estados_nombre as text,estados_id as id, pais_id "))
                ->limit(30)
                ->orderBy('estados_id', 'desc')
                ->get();

            $this->response($data, 200);

        } catch (Exception $e) {
            log_message('ERROR', 'Ocurrió un error al buscar los datos');
            log_message('ERROR', $e->getMessage());
            $this->response(array("message" => $e->getMessage()), 400);
        }
    }

}