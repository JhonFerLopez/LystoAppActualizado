<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class systemLogsModel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    function get_all()
    {
        $this->db->select('*');
        $this->db->join('usuario','usuario.nUsuCodigo=system_logs.usuario');
        $this->db->from('system_logs');
        $query = $this->db->get();

        return $query->result();
    }

    function insert($cliente)
    {

        $this->db->insert('system_logs', $cliente);
        $id = $this->db->insert_id();
        return $id;
    }


}
