<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class detalle_ingreso_unidad_model extends CI_Model
{

    private $table = 'detalle_ingreso_unidad';

    function __construct()
    {
        parent::__construct();
        $this->load->database();

    }

    function insert($datos)
    {
        $this->db->trans_start();
        $this->db->insert($this->table, $datos);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    function delete($where){

        $this->db->trans_start();
        $this->db->where($where);
        $this->db->delete($this->table);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;

    }

    function update($where,$datos)
    {
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->update($this->table, $datos);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }


    function costoPromedio($where)
    {
        $this->db->select_avg('costo_total');
        $this->db->from($this->table);
        $this->db->join('detalleingreso','detalleingreso.id_detalle_ingreso=detalle_ingreso_unidad.detalle_ingreso_id');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row_array();

    }

    function getDetalleUnidad($where)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();

    }

    function getDetalleUnidadRow($where)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row();

    }


    function getDetalleUnidadDetalle($where)
    {
        $this->db->select($this->table.'.*, detalleingreso.*, producto.is_prepack, producto.is_obsequio,producto.costo_unitario,
        producto.producto_impuesto');
        $this->db->from($this->table);
        $this->db->join('detalleingreso','detalleingreso.id_detalle_ingreso=detalle_ingreso_unidad.detalle_ingreso_id');
        $this->db->join('producto','detalleingreso.id_producto=producto.producto_id');
        $this->db->where($where);
        $this->db->order_by('id_ingreso', 'asc');
        $this->db->order_by('detalle_ingreso_unidad_id', 'asc');
        $query = $this->db->get();
        return $query->result();

    }


    //elimina de la tabla detalle_ingreso_unidad
    function eliminarDetalleyUnidades($ingreso_id){

        $this->db->trans_start();

        $this->db->query('DELETE detalle_ingreso_unidad
FROM detalle_ingreso_unidad
INNER JOIN detalleingreso ON detalle_ingreso_unidad.detalle_ingreso_id=detalleingreso.id_detalle_ingreso
WHERE detalle_ingreso_unidad.`detalle_ingreso_id` IS NOT NULL AND detalleingreso.id_ingreso='.$ingreso_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}