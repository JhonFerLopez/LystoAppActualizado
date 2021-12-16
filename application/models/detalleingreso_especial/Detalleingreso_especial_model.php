<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class detalleingreso_especial_model extends CI_Model
{

    private $table = 'detalleingreso_especial';

    function __construct()
    {
        parent::__construct();
        //$this->load->database();

    }


    function insert($datos)
    {
        $this->db->trans_start();
        $this->db->insert($this->table, $datos);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return $insert_id;
        }
    }


    function update($datos, $where)
    {
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->update($this->table, $datos);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return true;
        }
    }

    function getByidIngreso($where)
    {
        $this->db->select('detalleingreso_especial.*, producto.producto_id, producto.producto_codigo_interno,
        producto.producto_nombre, unidades.*');
        $this->db->from($this->table);
        $this->db->join('detalleingreso', 'detalleingreso.`id_detalle_ingreso`=detalleingreso_especial.`detalle_ingreso_id`');
        $this->db->join('ingreso', 'ingreso.`id_ingreso`= detalleingreso.`id_ingreso`');
        $this->db->join('unidades', 'unidades.id_unidad=detalleingreso_especial.unidad_id');
        $this->db->join('producto', 'producto.producto_id=detalleingreso_especial.producto_id_especial', 'left');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getSoloDetalle($where)
    {
        $this->db->select('detalleingreso_especial.*, producto.producto_impuesto, producto.costo_unitario,
        detalleingreso.impuesto_porcentaje');
        $this->db->from($this->table);
        $this->db->join('producto','producto.producto_id=detalleingreso_especial.producto_id');
        $this->db->join('detalleingreso','detalleingreso.id_detalle_ingreso=detalleingreso_especial.detalle_ingreso_id');

        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }


    function deleteByIngreso($id_ingreso)
    {
        $this->db->query("DELETE detalleingreso_especial FROM detalleingreso_especial JOIN detalleingreso  
        ON detalleingreso.`id_detalle_ingreso`=detalleingreso_especial.`detalle_ingreso_id` 
        WHERE detalleingreso.`id_ingreso`=" . $id_ingreso . " ");

        return $this->db->affected_rows();
    }



    function getDetalleEspecialDetalle($where)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join('detalleingreso','detalleingreso.id_detalle_ingreso=detalleingreso_especial.detalle_ingreso_id');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();

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


}
