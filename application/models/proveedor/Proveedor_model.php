<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class proveedor_model extends CI_Model
{

    private $table = 'proveedor';
    private $nombre = 'proveedor_nombre';
    private $id = 'id_proveedor';
    private $codigo = 'proveedor_identificacion';
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

    function get_by($campo, $valor)
    {
        $this->db->where($campo, $valor);
        $this->db->join('ciudades', 'ciudades.ciudad_id=proveedor.proveedor_ciudad','left');
        $this->db->join('estados', 'ciudades.estado_id=estados.estados_id','left');
        $this->db->join('pais', 'pais.id_pais=estados.pais_id','left');
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function getOnlyProveedor($where)
    {
        $this->db->where($where);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    function insertar($proveedor)
    {

        $nombre = $proveedor['proveedor_nombre'];
        $validar_nombre =$this->get_by($this->nombre, $nombre);
        $validar_nombre = $validar_nombre!=NULL? sizeof($validar_nombre):0;

        $codigo = $proveedor['proveedor_identificacion'];

        $validar_codigo = $this->get_by($this->codigo, $codigo);
        $validar_codigo = $validar_codigo!=NULL? sizeof($validar_codigo):0;

        if ($validar_nombre < 1) {
            if ($validar_codigo < 1) {
                $this->db->trans_start();
                $this->db->insert($this->table, $proveedor);
                $id=$this->db->insert_id();
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE)
                    return FALSE;
                else
                    return $id;
            } else {
                return CODIGO_EXISTE;
            }
        } else {
            return NOMBRE_EXISTE;
        }
    }

    function update($proveedor)
    {
        $produc_exite = $this->get_by($this->nombre, $proveedor[$this->nombre]);
        $cod_existe = $this->get_by($this->codigo, $proveedor[$this->codigo]);
        $validar_nombre = sizeof($produc_exite);
        $validar_cod = sizeof($cod_existe);
        if ($validar_nombre < 1 or ($validar_nombre > 0 and ($produc_exite [$this->id] == $proveedor [$this->id]))) {
            if ($validar_cod < 1 || ($validar_cod > 0 && ($cod_existe [$this->id] == $proveedor [$this->id]))) {

                $this->db->trans_start();
                $this->db->where($this->id, $proveedor[$this->id]);
                $this->db->update($this->table, $proveedor);

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

        /* $modificar = array($this->table => NULL);
         $this->db->where($this->table , $data[$this->id]);
         $this->db->update('proveedor', $modificar);
 */

        $this->db->trans_complete();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }
    function select_all_proveedor()
    {
        $this->db->where('deleted_at', null);
        $query = $this->db->get('proveedor');
        return $query->result();
    }

}
