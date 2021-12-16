<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class resolucion_model extends CI_Model
{

    private $table = 'resolucion_dian';
    private $numero = 'resolucion_numero';
    private $id = 'resolucion_id';

    private $deleted = 'deleted_at';

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_all()
    {
        $query = $this->db->where($this->deleted, null);
        $this->db->order_by($this->numero, 'asc');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    function get_by($array)
    {
        $this->db->where($array);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function get_last()
    {

        $this->db->select('*');
        $this->db->order_by('resolucion_id','desc');
        $query = $this->db->limit(1);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function set($data)
    {

        $validar_nombre = sizeof($this->get_by(array($this->numero => $data[$this->numero], $this->deleted => NULL)));

        if ($validar_nombre < 1) {


            $this->db->trans_start();
            $this->db->insert($this->table, $data);
            $insert_id = $this->db->insert_id();

            $this->db->trans_complete();

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return $insert_id;

        } else {
            return CODIGO_EXISTE;

        }

    }

    function update($grupo)
    {


        $produc_exite = $this->get_by(array($this->numero => $grupo[$this->numero], $this->deleted => null));
        $validar_nombre = sizeof($produc_exite);


        if ($validar_nombre < 1 or ($validar_nombre > 0 and ($produc_exite [$this->id] == $grupo [$this->id]))) {


            $this->db->trans_start();
            $this->db->where($this->id, $grupo[$this->id]);
            $this->db->update($this->table, $grupo);

            $this->db->trans_complete();

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return TRUE;


        } else {
            return CODIGO_EXISTE;

        }
    }


    function softDelete($data)
    {


        $this->db->trans_start();
        $this->db->where($this->id, $data[$this->id]);
        $this->db->update($this->table, $data);

        /* $modificar = array($this->table => NULL);
         $this->db->where($this->table , $data[$this->id]);
         $this->db->update('venta', $modificar);
 */

        $this->db->trans_complete();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

}
