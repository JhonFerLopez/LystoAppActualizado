<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class detalle_ingreso_model extends CI_Model
{

    private $table = 'detalleingreso';

    function __construct()
    {
        parent::__construct();
        //$this->load->database();

    }


    function get_by($select = false, $join = false, $campos_join = false, $where = false, $group = false, $filas = false)
    {
//si filas es igual a false entonces es un resutl que trae varios resultados
        //sino es una sola fila

        if ($select != false) {
            $this->db->select($select);
            $this->db->from($this->table);


        }
        if ($join != false and $campos_join != false) {

            for ($i = 0; $i < count($join); $i++) {
                $this->db->join($join[$i], $campos_join[$i]);
            }
        }
        if ($where != false) {
            $this->db->where($where);

        }
        if ($group != false) {
            $this->db->group_by($group);
        }
        if ($group != false) {
            $this->db->group_by($group);
        }

        $query = $this->db->get();

        if ($filas != false) {
            return $query->row_array();

        } else {

            return $query->result();

        }

    }


    function get_by_result($campo, $valor)
    {

        //busco el detalle con sus unidades
        $this->load->model('detalle_ingreso_unidad/detalle_ingreso_unidad_model');
        $this->load->model('unidades/unidades_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');

        $this->db->select('producto.producto_id,producto.producto_codigo_interno, producto.costo_unitario,
        producto.producto_nombre,producto.producto_tipo,
        producto.producto_impuesto, producto.producto_ubicacion_fisica,
        producto.produto_grupo, producto.is_prepack, producto.is_obsequio,detalleingreso.*');
        $this->db->from('detalleingreso');
        // $this->db->join('ingreso', 'ingreso.id_ingreso=detalleingreso.id_ingreso');
        $this->db->join('producto', 'producto.producto_id=detalleingreso.id_producto');
        $this->db->where($campo, $valor);
        $query = $this->db->get();
        $query->result();

        $detalle=array();
        foreach ($query->result() as $i=> $value){

            $detalle[$i]=$value;

            $detalle[$i]->detalle_unidad = $this->detalle_ingreso_unidad_model->getDetalleUnidad(array('detalle_ingreso_id'=>
                $value->id_detalle_ingreso));
            $detalle[$i]->consulta_unidades=$this->unidades_model->solo_unidades_xprod(array('producto_id'=>$value->producto_id));
            $detalle[$i]->precios=$this->unidades_has_precio_model->get_all_where(array('id_producto'=>$value->producto_id));

            $where=array(
                'id_producto'=>$value->producto_id
            );
            $detalle[$i]->stock= $this->inventario_model->get_all_by($where);

        }

        return $detalle;
    }

    function get_by_result_array($where)
    {
        $this->db->select('*');
        $this->db->from('detalleingreso');
        //$this->db->join('ingreso', 'ingreso.id_ingreso=detalleingreso.id_ingreso');
        $this->db->join('producto', 'producto.producto_id=detalleingreso.id_producto');
        $this->db->join('unidades', 'unidades.id_unidad=detalleingreso.unidad_medida');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    function insertar($datos)
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

    //elimina de la tabla detalle_ingreso_unidad
    function eliminarDetalle($where)
    {

        $this->db->trans_start();

        $this->db->where($where);
        $this->db->delete($this->table);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    function getSoloDetalle($where)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }


}
