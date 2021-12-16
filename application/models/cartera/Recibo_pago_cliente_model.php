<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class recibo_pago_cliente_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

    function insertar($recibo)
    {
        $this->db->trans_start();
        $this->db->insert('recibo_pago_cliente', $recibo);
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
        $query = $this->db->get('recibo_pago_cliente');
        return $query->row();
    }

    function anular($recibo, $usu_id, $caja){
	    $this->db->where('recibo_id', $recibo);
	    $this->db->set('anulado', 1);
	    $this->db->set('usu_anulado', $usu_id);
	    $this->db->set('cuadre_caja_id_anulado', $caja);
	    $this->db->set('fecha_anulado', date('Y-m-d H:i:s'));
	    return $this->db->update('recibo_pago_cliente');
    }
}