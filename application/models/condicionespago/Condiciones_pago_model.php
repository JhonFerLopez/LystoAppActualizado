<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Condiciones_pago_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_all()
	{
		$query = $this->db->where('status_condiciones', 1);
		$query = $this->db->get('condiciones_pago');
		return $query->result_array();
	}

	function get_by($campo, $valor)
	{
		$this->db->where($campo, $valor);
		$query = $this->db->get('condiciones_pago');
		return $query->row_array();
	}

	function insertar($condicionespago)
	{
		$nombre = $this->input->post('nombre_condiciones');
		$nombreExists = $this->get_by('nombre_condiciones', $nombre);
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;

		if ($validar_nombre < 1) {
			$this->db->trans_begin();
			$this->db->insert('condiciones_pago', $condicionespago);
			if ($this->db->trans_status() === FALSE)
				return $this->db->_error_message();
			else
				$this->db->trans_commit();
			return TRUE;
		} else {
			return NOMBRE_EXISTE;
		}
	}

	function update($condicionespago)
	{
		$nombreExists = $this->get_by('nombre_condiciones', $condicionespago['nombre_condiciones']);
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;

		if ($validar_nombre < 1 or ($validar_nombre > 0 and ($nombreExists['id_condiciones'] == $condicionespago['id_condiciones']))) {
			$this->db->trans_start();
			$this->db->where('id_condiciones', $condicionespago['id_condiciones']);
			$this->db->update('condiciones_pago', $condicionespago);
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
