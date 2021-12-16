<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class kardex_model extends CI_Model
{

    private $table = 'kardex';

    function __construct()
    {
        parent::__construct();
        $this->load->database();

    }

    function getKardex($where, $order)
    {

        $this->db->select('unidades.*, kardex.*, producto.*, cliente.*, proveedor.*');
        $this->db->from('kardex');
        $this->db->join('producto', 'producto.producto_id=kardex.cKardexProducto');
        $this->db->join('unidades', 'unidades.id_unidad=kardex.cKardexUnidadMedida', 'left');
        $this->db->join('local', 'local.int_local_id=kardex.cKardexAlmacen');
        $this->db->join('cliente', 'cliente.id_cliente=kardex.cKardexCliente', 'left');
        $this->db->join('proveedor', 'proveedor.id_proveedor=kardex.cKardexProveedor', 'left');
        $this->db->where($where);
        $this->db->order_by($order);

        $query = $this->db->get();
        //  echo $this->db->last_query();
        return $query->result_array();
    }

    function getKardexFiscal($where, $order)
    {
        $this->db->select('unidades.*, kardex_fiscal.*, producto.*, cliente.*');
        $this->db->from('kardex_fiscal');
        $this->db->join('producto', 'producto.producto_id=kardex_fiscal.cKardexProducto');
        $this->db->join('unidades', 'unidades.id_unidad=kardex_fiscal.cKardexUnidadMedida', 'left');
        $this->db->join('local', 'local.int_local_id=kardex_fiscal.cKardexAlmacen');
        $this->db->join('cliente', 'cliente.id_cliente=kardex_fiscal.cKardexCliente', 'left');

        $this->db->where($where);
        $this->db->order_by($order);
        $query = $this->db->get();

        return $query->result_array();
    }

    function set_batch($kardex)
    {
        $result = $this->db->insert_batch('kardex', $kardex);
        if ($result)
            return TRUE;
        else
            return FALSE;
    }

    function set_kardex($id_producto,
                        $local_id,
                        $unidad_medida_venta,
                        $cantidad_venta,
                        $kardexreferencia,
                        $kardex_usuario,
                        $precio,
                        $kardexoperacion_id,
                        $numero,
                        $tipo_documento,
                        $id_cliente,
                        $kardextipo,
                        $cantidad_vieja,
                        $sotkactual,
                        $id_proveedor = NULL,
                        $porcentaje_iva = NULL,
                        $costo_caja=NULL,
                        $fecha=NULL)
    {

        /*******Preparo la data para insertar en el kardex********/
        $item_kardex = array(
            'dkardexFecha' => !empty($fecha)?$fecha:date('Y-m-d H:i:s:u'),
            'ckardexReferencia' => $kardexreferencia,
            'cKardexProducto' => $id_producto,
            'nKardexCantidad' => $cantidad_venta,
            'nKardexPrecioUnitario' => $precio,
            'nKardexPrecioTotal' => $precio * $cantidad_venta,
            'cKardexUsuario' => $kardex_usuario,
            'cKardexUnidadMedida' => $unidad_medida_venta,
            'cKardexAlmacen' => $local_id,
            'cKardexTipo' => $kardextipo,
            'cKardexIdOperacion' => $kardexoperacion_id,
            'cKardexTipoDocumento' => $tipo_documento,
            'cKardexNumeroDocumento' => $numero,
            'stockUManterior' => $cantidad_vieja,
            'stockUMactual' => $sotkactual,
            'cKardexCliente' => $id_cliente,
            'cKardexProveedor' => $id_proveedor,
            'cKardexIvaPorcentaje' => $porcentaje_iva,
            'cKardexCostoCaja' => $costo_caja,
        );


        $result = $this->db->insert('kardex', $item_kardex);
        if ($result)
            return TRUE;
        else
            return FALSE;
    }


//TODO CAMBIAR TODOS LOS DEMAS POR ESTE METODO
    public
    function traer_by_mejorado($select = false, $from = false, $join = false, $campos_join = false, $tipo_join, $where = false, $nombre_in, $where_in,
                               $nombre_or, $where_or,
                               $group = false,
                               $order = false, $retorno = false, $limit = false, $start = 0, $order_dir = false, $like = false, $where_custom)
    {
        if ($select != false) {
            $this->db->select($select);
            $this->db->from($from);
        }
        if ($join != false and $campos_join != false) {

            for ($i = 0; $i < count($join); $i++) {

                if ($tipo_join != false) {

                    // for ($t = 0; $t < count($tipo_join); $t++) {

                    // if ($tipo_join[$t] != "") {

                    $this->db->join($join[$i], $campos_join[$i], $tipo_join[$i]);
                    //}

                    //}

                } else {

                    $this->db->join($join[$i], $campos_join[$i]);
                }

            }
        }
        if ($where != false) {
            $this->db->where($where);
        }
        if ($like != false) {
            $this->db->like($like);
        }
        if ($where_custom != false) {
            $this->db->where($where_custom);
        }

        if ($nombre_in != false) {
            for ($i = 0; $i < count($nombre_in); $i++) {
                $this->db->where_in($nombre_in[$i], $where_in[$i]);
            }
        }

        if ($nombre_or != false) {
            for ($i = 0; $i < count($nombre_or); $i++) {
                $this->db->or_where($where_or);
            }
        }

        if ($limit != false) {
            $this->db->limit($limit, $start);
        }
        if ($group != false) {
            $this->db->group_by($group);
        }

        if ($order != false) {
            $this->db->order_by($order, $order_dir);
        }

        $query = $this->db->get();

        //echo $this->db->last_query();

        if ($retorno == "RESULT_ARRAY") {

            return $query->result_array();
        } elseif ($retorno == "RESULT") {
            return $query->result();

        } else {
            return $query->row_array();
        }

    }

    function getKardexRow($where)
    {
        $this->db->select('*');
        $this->db->from('kardex');
        $this->db->where($where);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {

            return false;
        }
    }

}
