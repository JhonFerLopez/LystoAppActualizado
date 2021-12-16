<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class comision_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    function get_all()
    {


        $query = $this->db->get('comision_vendedor');
        return $query->result_array();

    }

    function get_where($condicion)
    {

        $this->db->where($condicion);


        $query = $this->db->get('comision_vendedor');
        return $query->result_array();

    }


    function get_by($campo, $valor)
    {
        $this->db->where($campo, $valor);
        $query = $this->db->get('comision_vendedor');
        return $query->row_array();
    }


    function delete($where)
    {

        $this->db->trans_start();
        $this->db->trans_begin();
        $this->db->where($where);


        $this->db->delete('comision_vendedor');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }

        $this->db->trans_off();
    }


    function insert_bacth($data)
    {

        $this->db->trans_start();
        $this->db->trans_begin();


        $this->db->insert_batch('comision_vendedor', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }

        $this->db->trans_off();

    }

    function insert($data)
    {

        $this->db->trans_start();
        $this->db->trans_begin();


        $this->db->insert('comision_vendedor', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }

        $this->db->trans_off();

    }


}
