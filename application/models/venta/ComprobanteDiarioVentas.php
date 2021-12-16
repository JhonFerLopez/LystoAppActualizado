<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Jhainey
 * Date: 08/10/2017
 * Time: 13:50
 */
class ComprobanteDiarioVentas extends CI_Model
{


    private $table = 'comprobante_diario_ventas';
    private $id = 'id_reporte';

    /**
     * ComprobanteDiarioVentas constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->order_by($this->id, 'asc');
        $this->db->join('usuario', 'usuario.nUsucodigo=usuario_genero_reporte');
        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    function get_where($where)
    {
        $this->db->order_by($this->id, 'asc');
        $this->db->join('usuario', 'usuario.nUsucodigo=usuario_genero_reporte');
        $this->db->where($where);
        $query = $this->db->get($this->table);

        return $query->row_array();
    }

    function insert($data = array())
    {
        $this->db->trans_start();
        $this->db->insert($this->table, $data);
        $id=$this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return $id;
        }
    }
}