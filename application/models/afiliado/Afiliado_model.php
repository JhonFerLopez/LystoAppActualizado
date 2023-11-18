<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class afiliado_model extends CI_Model
{

	private $table = 'afiliado';
	private $nombre = 'afiliado_nombre';
	private $id = 'afiliado_id';
	private $afiliado_codigo = 'afiliado_codigo';
	private $afiliado_monto_cartera = 'afiliado_monto_cartera';
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
		$nameExists = $this->get_by(array($this->nombre => $data[$this->nombre], $this->deleted => NULL));
		if (is_array($nameExists)) {
			$validar_nombre = sizeof($nameExists);
		} else {
			$validar_nombre = 0;
		}

		$validarCodigo = $this->get_by(array($this->afiliado_codigo => $data[$this->afiliado_codigo], $this->deleted => NULL));
		if (is_array($validarCodigo)) {
			$validar_codigo = sizeof($validarCodigo);
		} else {
			$validar_codigo = 0;
		}

		if ($validar_nombre < 1) {
			if ($validar_codigo < 1) {
				$this->db->trans_start();
				$this->db->insert($this->table, $data);
				$insert_id = $this->db->insert_id();
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE)
					return FALSE;
				else
					return $insert_id;
			} else {
				return CODIGO_EXISTE;
			}
		} else {
			return NOMBRE_EXISTE;
		}
	}

	function update($grupo)
	{
		$nameExists = $this->get_by(array($this->nombre => $grupo[$this->nombre], $this->deleted => null));
		if (is_array($nameExists)) {
			$validar_nombre = sizeof($nameExists);
		} else {
			$validar_nombre = 0;
		}

		$validarCodigo = $this->get_by(array($this->afiliado_codigo => $grupo[$this->afiliado_codigo], $this->deleted => null));
		if (is_array($validarCodigo)) {
			$validar_codigo = sizeof($validarCodigo);
		} else {
			$validar_codigo = 0;
		}

		if ($validar_nombre < 1 || ($validar_nombre > 0 && ($validar_nombre[$this->id] == $grupo[$this->id]))) {
			if (($validar_codigo < 1 || ($validar_codigo > 0 && ($validarCodigo[$this->id] == $grupo[$this->id])))) {
				$this->db->trans_start();
				$this->db->where($this->id, $grupo[$this->id]);
				$this->db->update($this->table, $grupo);
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE)
					return FALSE;
				else
					return TRUE;
			} else {
				return CODIGO_EXISTE;
			}
		} else {
			return NOMBRE_EXISTE;
		}
	}


	function softDelete($data)
	{
		$this->db->trans_start();
		$this->db->where($this->id, $data[$this->id]);
		$this->db->update($this->table, $data);

		$modificar = array("afiliado" => NULL);
		$this->db->where('afiliado', $data[$this->id]);
		$this->db->update('cliente', $modificar);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return FALSE;
		else
			return TRUE;
	}
}
