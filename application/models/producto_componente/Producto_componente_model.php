<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 02/11/2016
 * Time: 15:26
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class producto_componente_model extends CI_Model
{

    private $table="producto_has_componente";
    function __construct()
    {
        parent::__construct();
    }


    function get($where){

        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();

    }

    function guardar($codigos,$producto_id){


        if($codigos!=NULL && count($codigos)>0) {

            for ($i = 0; $i < count($codigos); $i++) {


                    if ($codigos[$i] != "") {

                        $arreglo = array(
                            'producto_id' => $producto_id,
                            'componente_id' => $codigos[$i]
                        );
                        $this->db->insert($this->table, $arreglo);
                    }

            }
        }

    }

    function delete($where){

        $this->db->where($where);
        $this->db->delete($this->table);

    }

    function insert($data)
    {

        $this->db->trans_start();
        $this->db->insert($this->table, $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;

    }

    /*trae los productos que pertenece a x componente*/
    function getProductoByComponent($where=array(''),$order_colum=false , $oder_desc =false)
    {
        $this->db->select('producto.*');
        $this->db->from('producto_has_componente');
        $this->db->join('producto', 'producto.producto_id=producto_has_componente.producto_id');
        $this->db->join('componentes', 'componentes.componente_id=producto_has_componente.componente_id');
        $this->db->where($where);
        if($order_colum!=false){
            $this->db->order_by($order_colum, $oder_desc);
        }
        $query= $this->db->get();
        return $query->result_array();
    }


}
