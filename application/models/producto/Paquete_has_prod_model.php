<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class paquete_has_prod_model extends CI_Model
{

    private $table = 'paquete_has_prod';


    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('unidades/unidades_model');
    }


    function get_where($where)
    {
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    function get_all_by_prod_grouped($id)
    {
        $this->db->select('prod_id, producto.producto_nombre, producto.control_inven, costo_unitario, producto.producto_impuesto');
        $this->db->join('producto', 'producto.producto_id=paquete_has_prod.prod_id');
        $this->db->where('paquete_id', $id);
        $this->db->group_by('prod_id');
        $query = $this->db->get($this->table);
        $prods = $query->result_array();
        $paquetes = array();
        foreach ($prods as $prod) {
            $where = array(
                'producto_id' => $prod['prod_id']
            );
            //aqui busco las unidades de medida que tienen configurados los productos que componen el paquete
            $prod['unidades_medida'] = $this->unidades_model->solo_unidades_xprod($where); // etsas son todas las unidades del proucto

            $paq = $this->get_where(array('paquete_id' => $id, 'prod_id' => $prod['prod_id']));

            $prod['unidades'] = $paq;
            array_push($paquetes, $prod);
        }


        return $paquetes;
    }

    /***saber si un producto es parte de un prepack , osea si esta dentro de un prepack
     * le paso por parametro el id del producto
     */
    function is_part_of_prepack($id)
    {
        $this->db->select('prod_id, producto.producto_nombre, producto.control_inven, costo_unitario, producto.producto_impuesto');
        $this->db->join('producto', 'producto.producto_id=paquete_has_prod.prod_id');
        $this->db->where('prod_id', $id);
        $this->db->group_by('prod_id');
        $query = $this->db->get($this->table);
        $prods = $query->result_array();

        //echo $this->db->last_query();

        return count($prods);
    }

    function guardar($codigos, $producto_id)
    {
        if (count($codigos) > 0) {


            for ($i = 0; $i < count($codigos); $i++) {

                if ($codigos[$i] != "") {
                    $arreglo = $codigos[$i];
                    $arreglo['paquete_id'] = $producto_id;
                    $this->db->insert($this->table, $arreglo);
                }
            }
        }
    }

    function delete($where)
    {

        $this->db->where($where);
        $this->db->delete($this->table);
    }
}
