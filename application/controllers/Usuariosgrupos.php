<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ERROR | E_PARSE);
class usuariosgrupos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
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

        $data['grupos'] = $this->usuarios_grupos_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/usuariosgrupos/usuariosgrupos', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        }else{
            $this->load->view('menu/template', $dataCuerpo);
        }


    }

    function form($id = FALSE)
    {

        $data = array();
        $data['perm_list']=array();
        /**
         * Obtengo todos los permisos
         */
        $data['perm_list'] = $this->usuarios_grupos_model->get_all_perms();

        if ($id != FALSE) {
            /**
             * Busco el rol
             */
            $data['role'] = $this->usuarios_grupos_model->get_role($id);

            /**
             * Busco los permisos que tiene este rol.
             * Devuelve False, si no se le ha configuado ningun permiso
             */
            $perms = $this->usuarios_grupos_model->get_role_perms($id);

            if (is_array($perms)) {

                /**
                 * Si el rol tiene permisos, entonces los recorro y guardo en la variable var_opcion_usuario_estado,
                 * si consiguio o no, de la lista de sus permisos
                 */
                foreach ($data['perm_list'] as $perm) {
                    /**
                     * in_array — Comprueba si un valor existe en un array
                     */
                    $perm->var_opcion_usuario_estado = in_array($perm, $perms);
                }
            } else {
                /**
                 * Entra aqui si no tiene ningun permiso este rol
                 */

                foreach ($data['perm_list'] as &$perm) {
                    $perm->var_opcion_usuario_estado = FALSE;
                }
            }


            $data['usuariosgrupos'] = $this->usuarios_grupos_model->get_by('id_grupos_usuarios', $id);

        } else {
            foreach ($data['perm_list'] as &$perm) {
                $perm->var_opcion_usuario_estado = FALSE;
            }

        }
        $this->load->view('menu/usuariosgrupos/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('id');

        $usuariosgrupos = array(
            'nombre_grupos_usuarios' => strtoupper($this->input->post('nombre_grupos_usuarios')),
            'status_grupos_usuarios' => 1,
        );

        if (empty($id)) {
            $resultado = $this->usuarios_grupos_model->insert_role($usuariosgrupos);
        } else {
            //$usuariosgrupos['id_grupos_usuarios'] = $id;
            //$resultado = $this->usuarios_grupos_model->update($usuariosgrupos);
            $resultado = $this->usuarios_grupos_model->edit_role($id, $usuariosgrupos) && $this->usuarios_grupos_model->edit_role_perms($id, $this->input->post('perms'));
        }

        if ($resultado !=FALSE) {
            if($resultado===NOMBRE_EXISTE){
                //  $this->session->set_flashdata('error', NOMBRE_EXISTE);
                $json['error']= NOMBRE_EXISTE;
            }else{
                $json['success']= 'Solicitud Procesada con exito';
            }

        } else {
            $json['error']= 'Ha ocurrido un error al procesar la solicitud';
        }

        echo json_encode($json);

    }


    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $usuariosgrupos = array(
            'id_grupos_usuarios' => $id,
            'nombre_grupos_usuarios' => $nombre . time(),
            'status_grupos_usuarios' => 0

        );

        $data['resultado'] = $this->usuarios_grupos_model->update($usuariosgrupos);

        if ($data['resultado'] != FALSE) {

            $json['success']= 'Se ha eliminado exitosamente';


        } else {

            $json['error']= 'Ha ocurrido un error al eliminar el Grupo';
        }

        echo json_encode($json);
    }


}
