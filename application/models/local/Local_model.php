<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class local_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $query = $this->db->where('local_status', 1);
        $query = $this->db->get('local');
        return $query->result_array();
    }


    function get_all_result()
    {
        $query = $this->db->where('local_status', 1);
        $query = $this->db->get('local');
        return $query->result();
    }

    function get_by($campo, $valor)
    {
        $this->db->where($campo, $valor);
        $query = $this->db->get('local');
        return $query->row_array();
    }

    function insertar($local)
    {
        $nombre = $this->input->post('local_nombre');

        $validar_nombre = $this->get_by('local_nombre', $nombre);


        if ($validar_nombre==NULL) {
            $this->db->trans_start();
            $this->db->insert('local', $local);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return TRUE;
        } else {
            return NOMBRE_EXISTE;
        }
    }

    function update($local)
    {


        $local_existe = $this->get_by('local_nombre', $local['local_nombre']);

        if (
            $local_existe == NULL
            ||
            (
                $local_existe!=NULL
                and
                ($local_existe ['int_local_id'] == $local ['int_local_id'])
            )
        ) {
            $this->db->trans_start();
            $this->db->where('int_local_id', $local['int_local_id']);
            $this->db->update('local', $local);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return TRUE;
        } else {
            return NOMBRE_EXISTE;
        }
    }


}
