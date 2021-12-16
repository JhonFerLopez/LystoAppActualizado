<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class afiliado_model extends CI_Model
{

    private $table = 'afiliado';
    private $nombre = 'afiliado_nombre';
    private $id = 'afiliado_id';
    private $afiliado_codigo = 'afiliado_codigo';
    private $afiliado_monto_cartera = 'afiliado_monto_cartera';
    private $deleted = 'deleted_at';

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_all()
    {
        $query = $this->db->where($this->deleted, null);
        $this->db->order_by($this->nombre, 'asc');
        $query = $this->db->get($this->table);
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
        $validar_codigo = sizeof($this->get_by(array($this->afiliado_codigo => $data[$this->afiliado_codigo], $this->deleted => NULL)));

        if ($validar_nombre < 1) {

            if ($validar_codigo < 1) {

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
        } else {
            return NOMBRE_EXISTE;

        }

    }

    function update($grupo)
    {


        $produc_exite = $this->get_by(array($this->nombre => $grupo[$this->nombre], $this->deleted => null));
        $validar_nombre = sizeof($produc_exite);
       
            $codigo_existe = $this->get_by(array($this->afiliado_codigo => $grupo[$this->afiliado_codigo], $this->deleted => null));

            $validar_codigo = sizeof($codigo_existe);
       
        if ($validar_nombre < 1 or ($validar_nombre > 0 and ($produc_exite [$this->id] == $grupo [$this->id]))) {

            
            if (($validar_codigo < 1 or ($validar_codigo > 0 and ($codigo_existe [$this->id] == $grupo [$this->id]))) ) {
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

        } else {
            return NOMBRE_EXISTE;

        }
    }


    function softDelete($data)
    {
                $this->db->trans_start();
                $this->db->where($this->id, $data[$this->id]);
                $this->db->update($this->table, $data);
                
                    $modificar = array("afiliado" => NULL);
                    $this->db->where('afiliado', $data[$this->id]);
                    $this->db->update('cliente', $modificar);


                $this->db->trans_complete();

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                if ($this->db->trans_status() === FALSE)
                    return FALSE;
                else
                    return TRUE;
    }

}
