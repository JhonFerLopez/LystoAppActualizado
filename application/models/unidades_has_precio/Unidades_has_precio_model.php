<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class unidades_has_precio_model extends CI_Model
{
    private $table = 'unidades_has_precio';

    function __construct() {

        $this->load->model('condicionespago/condiciones_pago_model');

        parent::__construct();
        $this->load->database();
    }

    function get_all_by($id_unidad, $id_producto)
    {
        $this->db->where('id_unidad', $id_unidad);
        $this->db->where('id_producto', $id_producto);
        $this->db->order_by('id_precio', 'ASC');

        $query = $this->db->get('unidades_has_precio');

        return $query->result_array();
    }

    function get_all_where($where)
    {
        $this->db->where($where);
        $query = $this->db->get('unidades_has_precio');

        return $query->result_array();
    }

    function insert($datos)
    {
        $this->db->trans_start();
        $this->db->insert('unidades_has_precio', $datos);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    function delete($where){

        $this->db->trans_start();
        $this->db->where($where);
        $this->db->delete('unidades_has_precio');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;

    }

    function solo_update($where, $datos)
    {

        $this->db->trans_start();
        $this->db->where($where);
        $this->db->update($this->table, $datos);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return false;
        else
            return true;
    }

    function get_precio_has_producto_list($condicion)
    {
        $sql=$this->db->query("SELECT precios.nombre_precio,precios.id_precio,
 unidades_has_precio.*, producto.producto_nombre, unidades.nombre_unidad, orden,grupos.id_grupo, grupos.nombre_grupo FROM precios JOIN unidades_has_precio
ON unidades_has_precio.`id_precio`= precios.`id_precio`
JOIN producto ON producto.`producto_id`=unidades_has_precio.`id_producto`
left JOIN grupos ON grupos.`id_grupo`=producto.`produto_grupo`
JOIN unidades ON unidades.`id_unidad`=unidades_has_precio.`id_unidad`
 JOIN unidades_has_producto ON  unidades_has_producto.`id_unidad`=unidades_has_precio.`id_unidad` AND
unidades_has_producto.`producto_id`=unidades_has_precio.`id_producto`
WHERE mostrar_precio=1 AND estatus_precio=1 AND producto.producto_estatus=1 AND producto.producto_activo=1 ".$condicion."
GROUP BY id_producto, precios.id_precio,unidades_has_precio.`id_unidad` ORDER BY orden asc, grupos.nombre_grupo asc");
        return $sql->result_array();
    }

    function get_precio_has_producto()
    {
        $sql=$this->db->query("SELECT precios.nombre_precio,precios.id_precio,
 unidades_has_precio.*, producto.producto_nombre, unidades.nombre_unidad, orden,grupos.id_grupo, grupos.nombre_grupo FROM precios JOIN unidades_has_precio
ON unidades_has_precio.`id_precio`= precios.`id_precio`
JOIN producto ON producto.`producto_id`=unidades_has_precio.`id_producto`
left JOIN grupos ON grupos.`id_grupo`=producto.`produto_grupo`
JOIN unidades ON unidades.`id_unidad`=unidades_has_precio.`id_unidad`
 JOIN unidades_has_producto ON  unidades_has_producto.`id_unidad`=unidades_has_precio.`id_unidad` AND
unidades_has_producto.`producto_id`=unidades_has_precio.`id_producto`
WHERE mostrar_precio=1 AND estatus_precio=1 AND producto.producto_estatus=1  AND producto.producto_activo=1
GROUP BY id_producto, precios.id_precio,unidades_has_precio.`id_unidad` ORDER BY orden asc, grupos.nombre_grupo asc");
        return $sql->result_array();
    }



    public function traer_by($select = false, $from = false, $join = false, $campos_join = false, $tipo_join, $where = false, $nombre_in, $where_in,
                             $nombre_or, $where_or,
                             $group = false,
                             $order = false, $retorno = false)
    {
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

        if ($group != false) {
            $this->db->group_by($group);
        }

        if ($order != false) {
            $this->db->order_by($order);
        }

        $query = $this->db->get();

        if ($retorno == "RESULT_ARRAY") {

            return $query->result_array();
        } elseif ($retorno == "RESULT") {
            return $query->result();

        } else {
            return $query->row_array();
        }

    }


    ///no se por que este metodo devuelve un resut array en vez de un row array , revisar
    function get_by_unidad_and_producto($producto, $unidad,$condicion = false){
        $this->db->select('*');
        $this->db->from('unidades_has_precio');
        $this->db->join('condiciones_pago', 'condiciones_pago.id_condiciones=unidades_has_precio.id_condiciones_pago');
        $this->db->join('unidades', 'unidades_has_precio.id_unidad=unidades.id_unidad');
        $this->db->where('unidades_has_precio.id_producto', $producto);
        $this->db->where('unidades_has_precio.id_unidad', $unidad);
        if($condicion!=false) {
            $this->db->where('unidades_has_precio.id_condiciones_pago', $condicion);
        }
        $this->db->where('condiciones_pago.status_condiciones', 1);
        $query =$this->db->get();
        return $query->result_array();
    }

    function get_all_by_producto($producto){

        $condiciones = $this->condiciones_pago_model->get_all();
        $precioscond = array();
        foreach($condiciones as $cond) {
            $condicion = $cond;
            $this->db->select('*');
            $this->db->from('unidades_has_precio');
            $this->db->join('producto', 'unidades_has_precio.id_producto=producto.producto_id');
            $this->db->join('impuestos', 'producto.producto_impuesto=impuestos.id_impuesto', 'left');
            $this->db->join('tipo_venta', 'tipo_venta.condicion_pago=unidades_has_precio.id_condiciones_pago');
            $this->db->join('unidades', 'unidades_has_precio.id_unidad=unidades.id_unidad');
            $this->db->join('unidades_has_producto', 'unidades_has_precio.id_unidad=unidades_has_producto.id_unidad and unidades_has_precio.id_producto=unidades_has_producto.producto_id ');
            $this->db->where('unidades_has_precio.id_producto', $producto);
            $this->db->where('tipo_venta.tipo_venta_id', $cond['id_condiciones']);
            $this->db->order_by('unidades.orden', 'asc');

            $query = $this->db->get();
            $precios = $query->result_array();
            $condicion['precios']=$precios;
            array_push($precioscond, $condicion);
        }
        return $precioscond;
    }

    function getPreciosByProdAndCondicionPago($producto, $condicionpago){
        $this->db->select('unidades_has_precio.*,impuestos.*,producto.*,fe_unidad, unidades_has_producto.unidades, unidades.abreviatura, unidades.nombre_unidad');
        $this->db->from('unidades_has_precio');
        $this->db->join('producto', 'unidades_has_precio.id_producto=producto.producto_id');
        $this->db->join('impuestos', 'producto.producto_impuesto=impuestos.id_impuesto', 'left');
        $this->db->join('condiciones_pago', 'condiciones_pago.id_condiciones=unidades_has_precio.id_condiciones_pago');
        $this->db->join('unidades', 'unidades_has_precio.id_unidad=unidades.id_unidad');
        $this->db->join('unidades_has_producto', 'unidades_has_precio.id_unidad=unidades_has_producto.id_unidad and unidades_has_precio.id_producto=unidades_has_producto.producto_id');

        $this->db->where('condiciones_pago.id_condiciones', $condicionpago);
        $this->db->where('unidades_has_precio.id_producto', $producto);

        $this->db->group_by('unidades.id_unidad');
        $this->db->order_by('unidades.orden', 'asc');
        $query =$this->db->get();

       // echo $this->db->last_query();
        return $query->result_array();
    }
}
