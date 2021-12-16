<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class clasificacion_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_all(){
        $this->db->select('clasificacion.*, clasificacion.clasificacion_nombre as text, clasificacion.clasificacion_id as id');
        $this->db->from('clasificacion');
        $query=$this->db->where('deleted_at',null);
        $this->db->order_by('clasificacion_nombre', 'asc');
        $query=$this->db->get();
        return $query->result_array();
    }

    function get_by($array){
        $this->db->where($array);
        $query=$this->db->get('clasificacion');
        return $query->row_array();
    }

    function insert($data)
    {

        $this->db->trans_start();
        $this->db->insert('clasificacion', $data);
        $id_clasificacion = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return $id_clasificacion;

    }

    function set($data)
    {

        $validar_nombre = sizeof($this->get_by(array('clasificacion_nombre'=> $data['clasificacion_nombre'],'deleted_at'=>NULL)));

        if ($validar_nombre < 1) {


        $this->db->trans_start();
        $this->db->insert('clasificacion', $data);

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



        $produc_exite=$this->get_by(array('clasificacion_nombre'=>$grupo['clasificacion_nombre'], 'deleted_at'=>null) );
        $validar_nombre = sizeof($produc_exite);
        if ($validar_nombre < 1 or( $validar_nombre>0 and ($produc_exite ['clasificacion_id']==$grupo ['clasificacion_id']))) {

            $this->db->trans_start();
            $this->db->where('clasificacion_id', $grupo['clasificacion_id']);
            $this->db->update('clasificacion', $grupo);
            if ($quehago != NULL) {
                $modificar = array("producto_clasificacion" => NULL);
                $this->db->where('producto_clasificacion', $grupo['clasificacion_id']);
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
