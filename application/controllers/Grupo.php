<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class grupo extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('grupos/grupos_model');
        $this->very_sesion();
    }


    function index()
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }
        $data['lstMovimiento'] = array();
        $data['grupos'] = GrupoElo::where('estatus_grupo', 1)
            ->orderBy('nombre_grupo', 'asc')->get();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/grupo/grupos', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }

    }

    function niveles()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }

        $data['niveles'] = NivelesGrupoElo::where('estatus', 1)->get();;
        $dataCuerpo['cuerpo'] = $this->load->view('menu/grupo/niveles', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }

    }

    function form($id = FALSE)
    {

        $data = array();
        if ($id != FALSE) {
            $data['grupo'] = GrupoElo::where('id_grupo', $id)->first();
        }
        $data['niveles'] = NivelesGrupoElo::where('estatus', 1)->get();
        $this->load->view('menu/grupo/form', $data);
    }

    function formnivel($id = FALSE)
    {

        $data = array();
        if ($id != FALSE) {
            $data['nivel'] = NivelesGrupoElo::where('nivel_id', $id)->first();
        }

        $this->load->view('menu/grupo/formnivel', $data);
    }


    function guardar()
    {

        try {
            $grupoByName = GrupoElo::where('nombre_grupo', strtoupper($this->input->post('nombre')))
                ->where('estatus_grupo',1)->first();
            $grupo = GrupoElo::where('id_grupo', $this->input->post('id'))->first();

            if ($grupo):
                if ($grupoByName && $grupo->id_grupo != $grupoByName->id_grupo):
                    $json['error'] = NOMBRE_EXISTE;
                    echo json_encode($json);
                    exit();
                endif;
            else:
                if ($grupoByName):
                    $json['error'] = NOMBRE_EXISTE;
                    echo json_encode($json);
                    exit();
                endif;
            endif;

            if ($grupo):
                $grupo->nombre_grupo = strtoupper($this->input->post('nombre'));
                $grupo->estatus_grupo = 1;
                $grupo->save();
            else:
                GrupoElo::create([
                    'nombre_grupo' => strtoupper($this->input->post('nombre')),
                    'estatus_grupo' => 1
                ]);
            endif;

            $json['success'] = 'Solicitud Procesada con exito';
            echo json_encode($json);
        } catch (Exception $exception) {
            $json['error'] = $exception->getMessage();
            echo json_encode($json);
        }
    }

    function guardarnivel()
    {

        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');
        $nivel = $this->input->post('nivel');
        $grupo = array(
            'nombre_nivel' => strtoupper($nombre),
            'estatus' => 1,
            'nivel' => $nivel + 0
        );
        if (empty($id)) {
            $data['resultado'] = $this->grupos_model->insert_nivelesgrupos($grupo);
        } else {
            $grupo['nivel_id'] = $id;
            $data['resultado'] = $this->grupos_model->update_nivelesgrupos($grupo);
        }


        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Solicitud Procesada con exito';

        } else {

            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }

        if ($data['resultado'] === NOMBRE_EXISTE) {
            //$this->session->set_flashdata('error', NOMBRE_EXISTE);
            $json['error'] = NOMBRE_EXISTE;
        }
        echo json_encode($json);
    }

    function eliminar()
    {

        $grupo = GrupoElo::where('id_grupo', $this->input->post('id'))->first();
        if ($grupo):
            $grupo->nombre_grupo=$this->input->post('nombre'). time();
            $grupo->estatus_grupo=0;
            if ($grupo->save()):
                $json['success'] = 'Se ha Eliminado exitosamente';
            else:
                $json['error'] = 'Ha ocurrido un error al eliminar el grupo';
            endif;
            echo json_encode($json);
        else:
            $json['error'] = 'El grupo no existe';
            echo json_encode($json);
        endif;

    }

    function eliminarnivel()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $grupo = array(
            'nivel_id' => $id,
            'nombre_nivel' => $nombre . time(),
            'estatus' => 0
        );

        $data['resultado'] = $this->grupos_model->update_nivelesgrupos($grupo);

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Se ha Eliminado exitosamente';


        } else {

            $json['error'] = 'Ha ocurrido un error al eliminar el nivel';
        }

        echo json_encode($json);
    }

    function getGruposByNivel()
    {  //trae los grupos por un nivel especifico

        $nivel_id = $this->input->post('nivel');

        $where = array(
            'grupos.nivel_id' => $nivel_id,
            'niveles_grupos.estatus' => 1
        );
        $json['grupos'] = $this->grupos_model->getGrupos($where);

        echo json_encode($json);

    }

    /*solo retorna los grupos en json*/
    function getGruposJson()
    {
        $json = $this->grupos_model->get_grupos();
        echo json_encode($json);
    }

}