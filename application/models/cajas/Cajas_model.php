<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class cajas_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_all()
    {
        $this->db->where('status', 1);
        $query = $this->db->select('*');
        $query = $this->db->get('caja');

        return $query->result_array();

    }

    function get_by($campo, $valor)
    {
        $this->db->where($campo, $valor);
        $query = $this->db->get('caja');
        return $query->row_array();
    }


    function get_where($where)
    {
        $this->db->where($where);
        $query = $this->db->get('caja');
        return $query->row_array();
    }


    function insertar($cajas)
    {

        $this->db->trans_start();
        if ($this->db->insert('caja', $cajas)) {
            $id_caja = $this->db->insert_id();


            $this->db->trans_complete();

            return $id_caja;
        } else {
            return false;
        }
    }


    function update($cajas)
    {
        $this->db->trans_start();

        $this->db->where('caja.caja_id', $cajas['caja_id']);

        if ($this->db->update('caja', $cajas)) {

            $this->db->trans_complete();

            return true;
        } else {
            return false;
        }

    }

    function get_all_user()
    {
        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('activo', 1);
        //     $this->db->where('deleted', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

}