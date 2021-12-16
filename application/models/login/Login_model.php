<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class login_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('opciones/opciones_model');
        $this->load->library('session');
    }

    function verificar_usuario($data, $result='ROW_ARRAY')
    {

        $query = $this->db->where('username', $data['username']);
        $query = $this->db->where('var_usuario_clave', $data['password']);
        $query = $this->db->where('usuario.activo', 1);
        $query = $this->db->where('usuario.deleted', 0);
        $query = $this->db->join('grupos_usuarios', 'grupos_usuarios.id_grupos_usuarios=usuario.grupo');
        $query = $this->db->get('usuario');
        //return $query->row();
        if($result=='ROW') {
            return $query->row();
        }else{
            return $query->row_array();
        }
        /*$query = "select * from usuario where cUsuarioIDName='".$data['username']."' and cUsuarioClave='".$data['password']."'";
        $result = $this->db->query($query);
        return $result->result();*/
    }
  function checkSysExpDat()
    {

        $query = $this->db->where('config_key', 'SYS_EXP_DAT');
        $query = $this->db->get('configuraciones');
        return $query->row_array();

    }

    function traer_datos_sesion($condicion)
    {

        $query = $this->db->where($condicion);

        $query = $this->db->join('grupos_usuarios', 'grupos_usuarios.id_grupos_usuarios=usuario.grupo');
        $query = $this->db->get('usuario');
        //return $query->row();
        return $query->row_array();
        /*$query = "select * from usuario where cUsuarioIDName='".$data['username']."' and cUsuarioClave='".$data['password']."'";
        $result = $this->db->query($query);
        return $result->result();*/
    }


    private function session_destroy()
    {
        $this->session->sess_destroy();

    }

    function very_session()
    {

        if($this->session->userdata('nUsuCodigo')){
            $this->refresh_session();
            return $this->session->userdata('nUsuCodigo');
        }else{
            //$this->session_destroy();
            return false;
        }


    }

    public function  refresh_session()
    {
        /*alargo el tiempo de vida de la sesion*/

       // $this->session->sess_expiration = time() + 3600;
        $condicion = array(
            'nUsuCodigo' => $this->session->userdata('nUsuCodigo')

        );

        $rs = $this->traer_datos_sesion($condicion);

        if ($rs) {
            $this->session->set_userdata($rs);
            //$this->session->set_userdata('expira_sesion',1800);
            $configuraciones = $this->opciones_model->get_opciones();

            if ($configuraciones == TRUE) {
                foreach ($configuraciones as $configuracion) {

                    $clave = $configuracion['config_key'];
                    $data[$clave] = $configuracion['config_value'];

                }
            }
            $this->session->set_userdata($data);
        }
    }

}

?>