<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class clientes_grupos_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_all()
	{
		$query = $this->db->where('status_grupos_cliente', 1);
		$query = $this->db->get('grupos_cliente');
		return $query->result_array();
	}

	function get_by($campo, $valor)
	{
		$this->db->where($campo, $valor);
		$query = $this->db->get('grupos_cliente');
		return $query->row_array();
	}

	function insertar($clientesgrupos)
	{
		$nombre = $clientesgrupos['nombre_grupos_cliente'];
		$nombreExists = $this->get_by('nombre_grupos_cliente', $nombre);		
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;

		if ($validar_nombre < 1) {
			$this->db->trans_start();
			$this->db->insert('grupos_cliente', $clientesgrupos);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
				return FALSE;
			else
				return TRUE;
		} else {
			return NOMBRE_EXISTE;
		}
	}

	function update($clientesgrupos)
	{
		$nombreExists = $this->get_by('nombre_grupos_cliente', $clientesgrupos['nombre_grupos_cliente']);
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;
		
		if ($validar_nombre < 1 || ($validar_nombre > 0 && ($nombreExists['id_grupos_cliente'] == $clientesgrupos['id_grupos_cliente']))) {
			$this->db->trans_start();
			$this->db->where('id_grupos_cliente', $clientesgrupos['id_grupos_cliente']);
			$this->db->update('grupos_cliente', $clientesgrupos);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
				return FALSE;
			else
				return TRUE;
		} else {
			echo NOMBRE_EXISTE;
		}
	}
}
