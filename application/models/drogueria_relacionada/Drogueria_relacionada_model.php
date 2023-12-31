<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class drogueria_relacionada_model extends CI_Model
{

    private $table = 'droguerias_relacionadas';
    private $nombre = 'drogueria_nombre';
    private $id = 'drogueria_id';
    private $drogueria_domain = 'drogueria_domain';
    private $deleted = 'deleted_at';

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_all()
    {
        $query = $this->db->where($this->deleted, NULL);
        $this->db->order_by($this->nombre, 'asc');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    function get_by($array)
    {
        $this->db->where($array);
        $query = $this->db->get($this->table);
        return ($query->row_array()? $query->row_array() : array());
    }

    function set($data)
    {
        $validar_nombre = sizeof($this->get_by(array($this->nombre => $data[$this->nombre], $this->deleted => NULL)));
        if ($validar_nombre < 1) {

                $this->db->trans_start();
                $this->db->insert($this->table, $data);
                $insert_id = $this->db->insert_id();
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE)
                    return FALSE;
                else
                    return $insert_id;

        } else {
            return NOMBRE_EXISTE;
        }
    }

    function update($grupo)
    {
        $produc_exite = $this->get_by(array($this->nombre => $grupo[$this->nombre], $this->deleted => null));
        $validar_nombre = sizeof($produc_exite);


        if ($validar_nombre < 1 or ($validar_nombre > 0 and ($produc_exite [$this->id] == $grupo [$this->id]))) {

                $this->db->trans_start();
                $this->db->where($this->id, $grupo[$this->id]);
                $this->db->update($this->table, $grupo);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE)
                    return FALSE;
                else
                    return TRUE;
        } else {
            return NOMBRE_EXISTE;
        }
    }


    function softDelete($data)
    {
        $this->db->trans_start();
        $this->db->where($this->id, $data[$this->id]);
        $this->db->update($this->table, $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

}
