<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ubicacion_fisica_model extends CI_Model
{

    private $table = 'ubicacion_fisica';
    private $nombre = 'ubicacion_nombre';
    private $id = 'ubicacion_id';
    private $deleted = 'deleted_at';

    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->select($this->table.'.*, '.$this->table.'.'.$this->nombre.' as text, '.$this->table.'.'.$this->id.' as id');
        $this->db->from($this->table);
        $query = $this->db->where($this->deleted, null);
        $this->db->order_by($this->nombre, 'asc');
        $query = $this->db->get();

        return $query->result_array();
    }

    function get_by($array)
    {
        $this->db->where($array);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function set($data)
    {

        $validar_nombre = sizeof($this->get_by(array($this->nombre => $data[$this->nombre], $this->deleted => NULL)));

        if ($validar_nombre < 1) {


            $this->db->trans_start();
            $this->db->insert($this->table, $data);

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

    function insert($data)
    {

        $this->db->trans_start();
        $this->db->insert($this->table, $data);
        $id_ubicacion = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return $id_ubicacion;

    }

    function update($grupo, $quehago = NULL)
    {


        $produc_exite = $this->get_by(array($this->nombre => $grupo[$this->nombre], $this->deleted => null));
        $validar_nombre = sizeof($produc_exite);
        if ($validar_nombre < 1 or ($validar_nombre > 0 and ($produc_exite [$this->id] == $grupo [$this->id]))) {

            $this->db->trans_start();
            $this->db->where($this->id, $grupo[$this->id]);
            $this->db->update($this->table, $grupo);
            if ($quehago != NULL) {
                $modificar = array("producto_ubicacion_fisica" => NULL);
                $this->db->where('producto_ubicacion_fisica', $grupo[$this->id]);
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
