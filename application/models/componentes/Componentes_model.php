<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class componentes_model extends CI_Model
{
	private $table = 'componentes';
	private $nombre = 'componente_nombre';
	private $id = 'componente_id';
	private $deleted = 'deleted_at';

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_all()
	{
		$this->db->select($this->table . '.*, ' . $this->table . '.' . $this->nombre . ' as text, ' . $this->table . '.' . $this->id . ' as id');
		$this->db->from($this->table);
		$query = $this->db->where($this->deleted, null);
		$this->db->order_by($this->nombre, 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function get_by($array)
	{
		$this->db->where($array);
		$query = $this->db->get($this->table);
		return $query->row_array();
	}

	function insert($data)
	{
		$this->db->trans_start();
		$this->db->insert($this->table, $data);
		$id_componente = $this->db->insert_id();
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return $id_componente;
	}

	function get_all_by_user($id)
	{
		$query = $this->db->select('*');
		$query = $this->db->join('producto', 'producto.producto_id=producto_has_componente.producto_id');
		$query = $this->db->join('componentes', 'componentes.componente_id=producto_has_componente.componente_id');
		$query = $this->db->where('producto_has_componente.producto_id', $id);
		$query = $this->db->get('producto_has_componente');
		return $query->result_array();
	}

	function set($data)
	{
		$nombreExists = $this->get_by(array($this->nombre => $data['componente_nombre'], $this->deleted => NULL));
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;
		if ($validar_nombre < 1) {
			$this->db->trans_start();
			$this->db->insert($this->table, $data);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
				return FALSE;
			else
				return TRUE;
		} else {
			return NOMBRE_EXISTE;
		}
	}

	function update($grupo, $quehago = NULL)
	{
		$nombreExists = $this->get_by(array($this->nombre => $grupo['componente_nombre'], $this->deleted => null));
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;
		if ($validar_nombre < 1 || ($validar_nombre > 0 && ($nombreExists[$this->id] == $grupo['componente_id']))) {
			$this->db->trans_start();
			$this->db->where($this->id, $grupo['componente_id']);
			$this->db->update($this->table, $grupo);
			if ($quehago != NULL) {
				$modificar = array("producto_componente" => NULL);
				$this->db->where('producto_componente', $grupo['componente_id']);
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
