<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class clasificacion_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_all()
	{
		$this->db->select('clasificacion.*, clasificacion.clasificacion_nombre as text, clasificacion.clasificacion_id as id');
		$this->db->from('clasificacion');
		$query = $this->db->where('deleted_at', null);
		$this->db->order_by('clasificacion_nombre', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function get_by($array)
	{
		$this->db->where($array);
		$query = $this->db->get('clasificacion');
		return $query->row_array();
	}

	function insert($data)
	{
		$this->db->trans_start();
		$this->db->insert('clasificacion', $data);
		$id_clasificacion = $this->db->insert_id();
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return $id_clasificacion;
	}

	function set($data)
	{
		$productExists = $this->get_by(
			array('clasificacion_nombre' => $data['clasificacion_nombre'], 'deleted_at' => null)
		);
		if (is_array($productExists)) {
			$validar_nombre = sizeof($productExists);
		} else {
			$validar_nombre = 0;
		}

		if ($validar_nombre < 1) {
			$this->db->trans_start();
			$this->db->insert('clasificacion', $data);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
				return FALSE;
			else
				return TRUE;
		} else {
			return NOMBRE_EXISTE;
		}
	}

	function update($grupo, $updateProducts = NULL)
	{
		$productExists = $this->get_by(
			array('clasificacion_nombre' => $grupo['clasificacion_nombre'], 'deleted_at' => null)
		);
		if (is_array($productExists)) {
			$validar_nombre = sizeof($productExists);
		} else {
			$validar_nombre = 0;
		}
		
		if (
			$validar_nombre < 1 || 
			($validar_nombre > 0 
			&& ($productExists['clasificacion_id'] == $grupo['clasificacion_id'])
			)
		) {
			$this->db->trans_start();
			$this->db->where('clasificacion_id', $grupo['clasificacion_id']);
			$this->db->update('clasificacion', $grupo);
			if ($updateProducts != NULL) {
				$modificar = array("producto_clasificacion" => NULL);
				$this->db->where('producto_clasificacion', $grupo['clasificacion_id']);
				$this->db->update('producto', $modificar);
			}
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
