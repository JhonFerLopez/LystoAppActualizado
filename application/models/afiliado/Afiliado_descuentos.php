<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class afiliado_descuentos extends CI_Model
{
	private $table = 'afiliado_descuentos';
	private $tipo_prod_id = 'tipo_prod_id';
	private $id = 'id';
	private $unidad_id = 'unidad_id';
	private $pocentaje = 'pocentaje';

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_all()
	{
		$query = $this->db->where($this->deleted, null);
		$this->db->order_by($this->nombre, 'asc');
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	function get_by($array)
	{
		$this->db->where($array);
		$query = $this->db->get($this->table);
		return $query->row_array();
	}

	function get_all_by($array)
	{
		$this->db->where($array);
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	function set($data)
	{
		$this->db->trans_start();
		$this->db->insert($this->table, $data);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return TRUE;
	}

	function set_batch($data)
	{
		$this->db->trans_begin();
		$this->db->delete($this->table, array('afiliado_id' => $data[0]['afiliado_id']));

		foreach ($data as $desc) {
			$this->db->insert($this->table, $desc);
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return TRUE;
	}

	function update($grupo, $updateProducts = NULL)
	{
		$nameExists = $this->get_by(array($this->nombre => $grupo[$this->nombre], $this->deleted => null));
		if (is_array($nameExists)) {
			$validar_nombre = sizeof($nameExists);
		} else {
			$validar_nombre = 0;
		}

		if ($validar_nombre < 1 || ($validar_nombre > 0 && ($nameExists[$this->id] == $grupo[$this->id]))) {
			$this->db->trans_start();
			$this->db->where($this->id, $grupo[$this->id]);
			$this->db->update($this->table, $grupo);
			if ($updateProducts != NULL) {
				$modificar = array("producto_ubicacion_fisica" => NULL);
				$this->db->where('producto_ubicacion_fisica', $grupo[$this->id]);
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
