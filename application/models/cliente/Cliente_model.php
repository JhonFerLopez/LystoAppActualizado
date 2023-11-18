<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class cliente_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function count_all($filter = null)
	{
		// Filter
		$this->db->where('cliente_status', 1);
		$query = $this->db->get('cliente');
		// Total Count
		return $query->num_rows();
	}

	function get_all($page = 0, $limit = 0)
	{
		$this->db->select('distinct(cliente.id_cliente),cliente.*, ciudades.*,estados.*,pais.*, grupos_cliente.*,  zonas.* ');
		$this->db->from('cliente');
		$this->db->join('ciudades', 'ciudades.ciudad_id=cliente.ciudad_id');
		$this->db->join('estados', 'ciudades.estado_id=estados.estados_id');
		$this->db->join('pais', 'pais.id_pais=estados.pais_id');
		$this->db->join('grupos_cliente', 'grupos_cliente.id_grupos_cliente=cliente.grupo_id');
		$this->db->join('zonas', 'zonas.zona_id=cliente.id_zona', 'left');

		// Status
		$this->db->where('cliente.cliente_status', 1);

		// Pagination
		if ($page >= 0 && $limit > 0) {
			$start = $page * $limit;
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	function get_by($campo, $valor)
	{
		$this->db->select('cliente.*, estados.estados_id');
		$this->db->where($campo, $valor);
		$this->db->join('ciudades', 'ciudades.ciudad_id=cliente.ciudad_id');
		$this->db->join('estados', 'ciudades.estado_id=estados.estados_id');
		$this->db->join('pais', 'pais.id_pais=estados.pais_id');
		$query = $this->db->get('cliente');
		return $query->row_array();
	}

	function getOnlyClient($where = array(''))
	{
		$this->db->where($where);
		$query = $this->db->get('cliente');
		return $query->row_array();
	}

	function insertar($cliente)
	{
		$identificacion = $cliente['identificacion'];
		$codigo = $cliente['codigo_interno'];
		$validar_nombre = $this->getOnlyClient(
			array(
				'identificacion' => $identificacion,
				'cliente_status' => 1
			)
		);

		$validar_codigo = $this->getOnlyClient(
			array(
				'codigo_interno' => $codigo,
				'cliente_status' => 1
			)
		);

		if (!isset($validar_nombre['id_cliente'])) {
			if (!isset($validar_codigo['id_cliente'])) {
				$fech = date('Y-m-d');
				$this->db->trans_start();
				$this->db->insert('cliente', $cliente);
				$id_usu = $this->db->insert_id();

				try {
					$this->db->trans_complete();
				} catch (Exception $e) {
					return $this->db->_error_message();
				}
				if ($this->db->trans_status() === FALSE) {
					return $this->db->_error_message();
				} else {
					return $id_usu;
				}
			} else {
				return CODIGO_EXISTE;
			}
		} else {
			return CEDULA_EXISTE;
		}
	}

	function soloinsertar($cliente)
	{
		$this->db->trans_start();
		$this->db->insert('cliente', $cliente);
		$id_usu = $this->db->insert_id();
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return $this->db->_error_message();
		} else {
			return $id_usu;
		}
	}

	function update($cliente)
	{
		$identificacionExists = $this->get_by('identificacion', $cliente['identificacion']);
		$validar_nombre = is_array($identificacionExists) ? sizeof($identificacionExists) : 0;

		$validarCodigo = $this->get_by('codigo_interno', $cliente['codigo_interno']);
		$validar_cod = is_array($validarCodigo) ? sizeof($validarCodigo) : 0;
	
		$validar_nombre = $this->getOnlyClient(
			array(
				'identificacion' => $cliente['identificacion'],
				'cliente_status' => 1
			)
		);

		$validar_cod = $this->getOnlyClient(
			array(
				'codigo_interno' => $cliente['codigo_interno'],
				'cliente_status' => 1
			)
		);
		if (
			!isset($validar_nombre['id_cliente'])
			||
			(isset($validar_nombre['id_cliente'])
				&&
				($validar_nombre['id_cliente'] == $cliente['id_cliente']
				)
			)
		) {
			if (
				!isset($validar_cod['id_cliente'])
				||
				(isset($validar_cod['id_cliente'])
					&&
					($validar_cod['id_cliente'] == $cliente['id_cliente']
					)
				)
			) {
				$this->db->trans_start();
				$fech = date('Y-m-d');
				$this->db->where('id_cliente', $cliente['id_cliente']);
				$this->db->update('cliente', $cliente);
				$this->db->where('cliente_id', $cliente['id_cliente']);
				$this->db->order_by('direccion_id', 'DESC');
				$this->db->trans_complete();

				if ($this->db->trans_status() == FALSE) {
					return $this->db->last_query();
				} else {
					return TRUE;
				}
			} else {
				return CODIGO_EXISTE;
			}
		} else {
			return CEDULA_EXISTE;
		}
	}

	function softeDelete($cliente)
	{
		$this->db->trans_start();
		$this->db->where('id_cliente', $cliente['id_cliente']);
		$this->db->update('cliente', $cliente);
		$vendedr = $this->input->post('vendedor', true);
		$fech = date('y-m-d');
		$this->db->trans_complete();

		if ($this->db->trans_status() == FALSE) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_total_cuentas_por_cobrar()
	{
		$sql = "SELECT SUM(dec_cronpago_pagocuota) as suma FROM `cronogramapago` WHERE dec_cronpago_pagorecibido = 0.00";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function traer_by(
		$select = false,
		$from = false,
		$join = false,
		$campos_join = false,
		$tipo_join,
		$where = false,
		$nombre_in,
		$where_in,
		$nombre_or,
		$where_or,
		$group = false,
		$order = false,
		$retorno = false,
		$limit = false,
		$start = 0,
		$order_dir = false,
		$like = false,
		$where_custom
	) {
		if ($select != false) {
			$this->db->select($select);
			$this->db->from($from);
		}
		if ($join != false and $campos_join != false) {
			for ($i = 0; $i < count($join); $i++) {
				if ($tipo_join != false) {
					for ($t = 0; $t < count($tipo_join); $t++) {
						if ($tipo_join[$t] != "") {
							$this->db->join($join[$i], $campos_join[$i], $tipo_join[$t]);
						}
					}
				} else {
					$this->db->join($join[$i], $campos_join[$i]);
				}
			}
		}
		if ($where != false) {
			$this->db->where($where);
		}
		if ($like != false) {
			$this->db->like($like);
		}
		if ($where_custom != false) {
			$this->db->where($where_custom);
		}

		if ($nombre_in != false) {
			for ($i = 0; $i < count($nombre_in); $i++) {
				$this->db->where_in($nombre_in[$i], $where_in[$i]);
			}
		}

		if ($nombre_or != false) {
			for ($i = 0; $i < count($nombre_or); $i++) {
				$this->db->or_where($where_or);
			}
		}

		if ($limit != false) {
			$this->db->limit($limit, $start);
		}
		if ($group != false) {
			$this->db->group_by($group);
		}
		if ($order != false) {
			$this->db->order_by($order, $order_dir);
		}
		$query = $this->db->get();

		// echo $this->db->last_query();
		if ($retorno == "RESULT_ARRAY") {

			return $query->result_array();
		} elseif ($retorno == "RESULT") {
			return $query->result();
		} else {
			return $query->row_array();
		}
	}

	function onlyUpdate($where, $datos)
	{
		$this->db->trans_start();
		$this->db->where($where);
		$this->db->update('cliente', $datos);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function getClientes($where, $datos)
	{
		$this->db->trans_start();
		$this->db->where($where);
		$this->db->update('cliente', $datos);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	/*trae los clientes por busquedas, especialmente usado para el select2 ajax
	notese que el id_cliente esta con un alias, al ifual que el apellido y nombre concatenado con un alias*/
	public function getClientesSelect2($buscar)
	{
		$query = $this->db->select('cliente.id_cliente as id, CONCAT(nombres, " ", apellidos) AS text,zonas.zona_nombre, cliente.*');
		$query = $this->db->from('cliente');
		$query = $this->db->join('zonas', 'zonas.zona_id=cliente.id_zona', 'left');

		$query = $this->db->where("cliente_status =1 and ( 
			CONCAT(nombres, ' ', apellidos) like  '%" . $buscar . "%'   
			or identificacion like  '%" . $buscar . "%'
			or codigo_interno like  '%" . $buscar . "%'
			or telefono like  '%" . $buscar . "%'
			or celular like  '%" . $buscar . "%' ) ");
		$this->db->limit('200');
		$query = $this->db->get();

		return $query->result();
	}

	//retorna la cantidad de clientes que han sido registrados enj los ultimos 7 dias
	public function getCountLastClients()
	{
		$querystring = "SELECT COUNT(id_cliente) AS cont_clientes FROM cliente 
			WHERE create_at>= DATE(NOW()) - INTERVAL 7 DAY";
		$query = $this->db->query($querystring);
		return $query->row_array();
	}
}
