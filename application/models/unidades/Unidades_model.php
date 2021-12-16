<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class unidades_model extends CI_Model
{

    private $table = 'unidades';

    function __construct()
    {
        parent::__construct();
        $this->load->database();

    }

    function get_unidades()
    {
        $this->db->where('estatus_unidad', 1);
        $this->db->order_by('orden', 'asc');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    function get_by($campo, $valor)
    {
        $this->db->where($campo, $valor);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function set_unidades($grupo)
    {
        $nombre = $grupo['nombre_unidad'];
        $validar_nombre = sizeof($this->get_by('nombre_unidad', $nombre));

        if ($validar_nombre < 1) {
            $this->db->trans_start();
            $this->db->insert($this->table, $grupo);

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

    function update_unidades($grupo)
    {


        $produc_exite = $this->get_by('nombre_unidad', $grupo['nombre_unidad']);
        $validar_nombre = sizeof($produc_exite);
        if ($validar_nombre < 1 or ($validar_nombre > 0 and ($produc_exite ['id_unidad'] == $grupo ['id_unidad']))) {

            $this->db->trans_start();
            $this->db->where('id_unidad', $grupo['id_unidad']);
            $this->db->update($this->table, $grupo);

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

    function get_by_producto($producto)
    {
        $this->db->select('*');
        $this->db->from('unidades_has_producto');
        $this->db->where('unidades_has_producto.producto_id', $producto);
        //aqui coloque esta validacion para la pestana de productos
        $this->db->where('unidades.estatus_unidad', 1);
        $this->db->join('unidades', 'unidades_has_producto.id_unidad=unidades.id_unidad');
        $this->db->join('producto', 'producto.producto_id=unidades_has_producto.producto_id');
        $this->db->order_by('unidades.orden', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    function solo_unidades_xprod($where)
    {
        $this->db->select('*');
        $this->db->from('unidades_has_producto');
        $this->db->where($where);
        //aqui coloque esta validacion para la pestana de productos
        $this->db->where('unidades.estatus_unidad', 1);
        $this->db->join('unidades', 'unidades_has_producto.id_unidad=unidades.id_unidad');
        $this->db->order_by('orden', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    function update_unidades_producto($where, $datos)
    {
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->update('unidades_has_producto', $datos);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    function insert_unidades_producto($datos)
    {
        $this->db->trans_start();
        $this->db->insert('unidades_has_producto', $datos);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    function delete_unidades_producto($where)
    {

        $this->db->trans_start();
        $this->db->where($where);
        $this->db->delete('unidades_has_producto');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;

    }

    public function count_by_producto_id($id)
    {
        return $this->db->from('unidades_has_producto')
            ->where('producto_id', $id)
            ->count_all_results();
    }




    /*
     *
     * calcula el costo DE LA CAJA segun el costo de OTRA UNIDAD
     * @producto_id id del producto
     * @um_id id de la unidad de medida
     * @costo costo de la uniad de medida sobre la cual se quiere calcular el costo de la caja
     * */
    public function costo_unitario($producto_id, $um_id, $costo)
    {
        //si solo existe una unidad, solo debe tener CAJA
        if ($this->count_by_producto_id($producto_id) == 1) return $costo;

        //busco los datos de la unidad que estoy pasando
        $unidad=$this->solo_unidades_xprod(array('producto_id'=>$producto_id,'unidades_has_producto.id_unidad'=>$um_id));
        if(sizeof($unidad)<1) return false;
        //si el orden es==2 es blister, multiplico su cantidad de blister que tiene la caja por el costo
        if ($unidad[0]['orden'] == 2) return $unidad[0]['unidades'] * $costo;

        //si orden==3 es unidad
        if ($unidad[0]['orden'] == 3){


            //busco los datos de la caja
            $caja=$this->solo_unidades_xprod(array('producto_id'=>$producto_id,'orden'=>1));

            //si en unidad tengo 1, quiere decir que no tengo blister, por lo tanto unidad siempre es 1
            //retorna el costo de la unidad * la cantidad de unidades que tiene la caja
            if($unidad[0]['unidades']=="1") return $costo*$caja[0]['unidades'];

            //busco los datos de blister
            $blister=$this->solo_unidades_xprod(array('producto_id'=>$producto_id,'orden'=>2));

            //si llega aqui, blister tiene datos, multiplico el costo de la unidad * la cantidad de unidades que tiene el blister
            //multiplicado por la cantidad de blister que tiene la caja
            return (($costo*$unidad[0]['unidades'])*$blister[0]['unidades']);

        }
        //si llega aqui ingrese caja, y solo retorno el costo
        return $costo;
    }

}
