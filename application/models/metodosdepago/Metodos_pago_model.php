<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class metodos_pago_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_all()
	{
		$query = $this->db->where('deleted_at', null);
		$query = $this->db->get('metodos_pago');
		return $query->result_array();
	}
	function get_all_by($where)
	{
		$query = $this->db->where('deleted_at', null);
		$query = $this->db->where($where);
		$query = $this->db->get('metodos_pago');
		return $query->result_array();
	}

	function get_by($campo, $valor)
	{
		$this->db->where($campo, $valor);
		$query = $this->db->get('metodos_pago');
		return $query->row_array();
	}

	function insertar($metodospago)
	{
		$nombre = $metodospago['nombre_metodo'];
		$nombreExists = $this->get_by('nombre_metodo', $nombre);
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;

		if ($validar_nombre < 1) {
			$this->db->trans_start();
			$this->db->insert('metodos_pago', $metodospago);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
				return FALSE;
			else
				return TRUE;
		} else {
			return NOMBRE_EXISTE;
		}
	}

	function update($metodospago)
	{
		$nombreExists = $this->get_by('nombre_metodo', $metodospago['nombre_metodo']);
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;
		
		if ($validar_nombre < 1 || ($validar_nombre > 0 && ($nombreExists['id_metodo'] == $metodospago['id_metodo']))) {
			$this->db->trans_start();
			$this->db->where('id_metodo', $metodospago['id_metodo']);
			$this->db->update('metodos_pago', $metodospago);

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
