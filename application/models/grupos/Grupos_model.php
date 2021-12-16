<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class grupos_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_grupos($result = 'ARRAY')
    {
        $this->db->select('grupos.*, grupos.nombre_grupo as text, grupos.id_grupo as id');
        $this->db->from('grupos');
        $this->db->where('estatus_grupo', 1);
        $this->db->order_by('nombre_grupo', 'asc');
        $query = $this->db->get();
        if ($result == 'ARRAY') {
            return $query->result_array();
        }
        if ($result == 'RESULT') {
            return $query->result();
        }

    }

    function get_by($campo, $valor)
    {
        $this->db->where($campo, $valor);
        $query = $this->db->get('grupos');
        return $query->row_array();
    }

    function get_bynivel($campo, $valor)
    {
        $this->db->where($campo, $valor);
        $query = $this->db->get('niveles_grupos');
        return $query->row_array();
    }

    function set_grupos($data)
    {


        $validar_nombre = sizeof($this->get_by('nombre_grupo', $data['nombre_grupo']));

        if ($validar_nombre < 1) {

            $this->db->trans_start();
            $this->db->insert('grupos', $data);
            $id=$this->db->insert_id();
            $this->db->trans_complete();

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return $id;
        } else {
            return NOMBRE_EXISTE;

        }
    }


    function update_grupos($grupo, $quehago = NULL)
    {


        $produc_exite = $this->get_by('nombre_grupo', $grupo['nombre_grupo']);
        $validar_nombre = sizeof($produc_exite);


        if ($validar_nombre < 1 or ($validar_nombre > 0 and ($produc_exite ['id_grupo'] == $grupo['id_grupo']))) {

            $this->db->trans_start();
            $this->db->where('id_grupo', $grupo['id_grupo']);
            $this->db->update('grupos', $grupo);
            if ($quehago != NULL) {
                $modificar = array("produto_grupo" => NULL);
                $this->db->where('produto_grupo', $grupo['id_grupo']);
                $this->db->update('producto', $modificar);
            }
            $this->db->trans_complete();

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return TRUE;
        } else {

            return NOMBRE_EXISTE;
        }
    }

    function insertproductohasgrupo($grupo){

        $this->db->trans_start();
        $this->db->insert('producto_has_grupo', $grupo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return $insert_id;
    }

    function get_productohasgrupo($where)
    {
        $this->db->where($where);
        $this->db->join('grupos', 'grupos.id_grupo=producto_has_grupo.grupo_id');

        $query = $this->db->get('producto_has_grupo');
        return $query->result();

    }


    function delete_productohasgrupo($where)
    {
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->delete('producto_has_grupo');
        $this->db->trans_complete();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;

    }


    function get_niveles($where=array(''))
    {
        $this->db->where($where);
        $this->db->where('estatus', 1);
        $query = $this->db->get('niveles_grupos');
        return $query->result();

    }

    function insert_nivelesgrupos($datos){


        $this->db->trans_start();
        $this->db->insert('niveles_grupos', $datos);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return $insert_id;
    }

    function update_nivelesgrupos($grupo, $quehago = NULL)
    {


        $produc_exite = $this->get_bynivel('nombre_nivel', $grupo['nombre_nivel']);
        $validar_nombre = sizeof($produc_exite);
        if ($validar_nombre < 1 or ($validar_nombre > 0 and ($produc_exite ['nivel_id'] == $grupo ['nivel_id']))) {

            $this->db->trans_start();
            $this->db->where('nivel_id', $grupo['nivel_id']);
            $this->db->update('niveles_grupos', $grupo);
            $this->db->trans_complete();

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return TRUE;
        } else {

            return NOMBRE_EXISTE;
        }
    }

    function getGrupos($where=array(''))
    {
        $this->db->where($where);
        $this->db->order_by('nombre_grupo', 'asc');
        $query = $this->db->get('grupos');
        return $query->result();
    }

    function insert($data)
    {

        $this->db->trans_start();
        $this->db->insert('grupos', $data);
        $id_componente = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return $id_componente;

    }

}
