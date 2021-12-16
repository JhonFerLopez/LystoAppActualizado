<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tipo_producto_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_all($result = 'ARRAY'){

        $this->db->select('tipo_producto.*, tipo_producto.tipo_prod_nombre as text, tipo_producto.tipo_prod_id as id');
        $this->db->from('tipo_producto');
        $query=$this->db->where('deleted_at',null);
        $this->db->order_by('tipo_prod_nombre', 'asc');
        $query=$this->db->get();
        if ($result == 'ARRAY') {
            return $query->result_array();
        }
        if ($result == 'RESULT') {
            return $query->result();
        }

    }

    function get_by($array){
        $this->db->where($array);
        $query=$this->db->get('tipo_producto');
        return $query->row_array();
    }

    function set($data)
    {

        $validar_nombre = sizeof($this->get_by(array('tipo_prod_nombre'=> $data['tipo_prod_nombre'],'deleted_at'=>NULL)));

        if ($validar_nombre < 1) {


        $this->db->trans_start();
        $this->db->insert('tipo_producto', $data);

        $this->db->trans_complete();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }else{
            return NOMBRE_EXISTE;

        }

    }

    function update($grupo, $quehago = NULL)
    {



        $produc_exite=$this->get_by(array('tipo_prod_nombre'=>$grupo['tipo_prod_nombre'], 'deleted_at'=>null) );
        $validar_nombre = sizeof($produc_exite);
        if ($validar_nombre < 1 or( $validar_nombre>0 and ($produc_exite ['tipo_prod_id']==$grupo ['tipo_prod_id']))) {

            $this->db->trans_start();
            $this->db->where('tipo_prod_id', $grupo['tipo_prod_id']);
            $this->db->update('tipo_producto', $grupo);
            if ($quehago != NULL) {
                $modificar = array("producto_tipo" => NULL);
                $this->db->where('producto_tipo', $grupo['tipo_prod_id']);
                $this->db->update('producto', $modificar);
            }
            $this->db->trans_complete();

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return TRUE;
        } else {
            return NOMBRE_EXISTE;

        }
    }


}
