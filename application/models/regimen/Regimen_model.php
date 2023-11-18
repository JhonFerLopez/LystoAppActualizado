<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class regimen_model extends CI_Model
{
	private $table = 'regimen';
	private $nombre = 'regimen_nombre';
	private $id = 'regimen_id';
	private $deleted = 'deleted_at';

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

	function set($data)
	{
		$nombreExists = $this->get_by(array($this->nombre => $data[$this->nombre], $this->deleted => NULL));
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;

		if ($validar_nombre < 1) {
			$this->db->trans_start();
			$this->db->insert($this->table, $data);
			$insert_id = $this->db->insert_id();
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
				return FALSE;
			else
				return $insert_id;
		} else {
			return NOMBRE_EXISTE;
		}
	}

	function update($grupo)
	{
		$nombreExists = $this->get_by(array($this->nombre => $grupo[$this->nombre], $this->deleted => null));
		$validar_nombre = is_array($nombreExists) ? sizeof($nombreExists) : 0;

		if ($validar_nombre < 1 or ($validar_nombre > 0 and ($nombreExists[$this->id] == $grupo[$this->id]))) {
			$this->db->trans_start();
			$this->db->where($this->id, $grupo[$this->id]);
			$this->db->update($this->table, $grupo);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
				return FALSE;
			else
				return TRUE;
		} else {
			return NOMBRE_EXISTE;
		}
	}

	function softDelete($data)
	{
		$this->db->trans_start();
		$this->db->where($this->id, $data[$this->id]);
		$this->db->update($this->table, $data);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return TRUE;
	}
}
