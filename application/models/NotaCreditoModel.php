<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class notaCreditoModel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }



    function insert($data)
    {

        $this->db->trans_start();
        $this->db->insert('credit_note', $data);
        $id_zona = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return $id_zona;
    }



}
