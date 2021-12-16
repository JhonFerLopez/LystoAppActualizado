<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class recibo_pago_proveedor_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

    function insertar($recibo)
    {
        $this->db->trans_start();
        $this->db->insert('recibo_pago_proveedor', $recibo);
        $id=$this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else{
            return  $id;
        }
    }
    function get_next_id(){
        $this->db->select('recibo_id');
        $this->db->order_by('recibo_id','desc');
        $this->db->limit(1);
        $query = $this->db->get('recibo_pago_proveedor');
        return $query->row();
    }
}