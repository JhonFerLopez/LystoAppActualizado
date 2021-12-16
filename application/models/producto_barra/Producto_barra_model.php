<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 02/11/2016
 * Time: 15:26
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class producto_barra_model extends CI_Model
{

    private $table="producto_codigo_barra";
    function __construct()
    {
        parent::__construct();
    }


    function get_codigo_barra($where){

        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();

    }

    //este funciona solo para el menu de productos
    function guardar($codigos,$producto_id){

        if(count($codigos)>0) {
            /*borro el primer item, porque el primer item debe anadirse con el boon anadir, primero*/
            unset($codigos[0]);
            for ($i = 1; $i < count($codigos)+1; $i++) {

                /*aqui valido si existe el codigo de barra, ya que puede ser que hayan ingresado en dos campos de texo difeentes, dos valores iguales*/
                if (sizeof($this->get_codigo_barra(array('codigo_barra' => $codigos[$i]))) < 1) {
                    if ($codigos[$i] != "") {

                        $arreglo = array(
                            'producto_id' => $producto_id,
                            'codigo_barra' => $codigos[$i]
                        );
                        $this->db->insert($this->table, $arreglo);
                    }
                }
            }
        }

    }

    //guarda los codigos de barra en compra;
    function guardarEnCompra($producto_id,$codigos){

        $arreglo = array(
            'codigo_barra' => $codigos
        );

        if( count($this->get_codigo_barra($arreglo))<1) {
            $arreglo['producto_id'] = $producto_id;
            $this->db->insert($this->table, $arreglo);
        }

    }

    function delete($where){

        $this->db->where($where);
        $this->db->delete($this->table);

    }

    function insert($data){

        $this->db->insert($this->table, $data);
        $ultimo_id = $this->db->insert_id();
        return $ultimo_id;

    }


}
