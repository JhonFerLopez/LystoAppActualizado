<?php use Mike42\Escpos\Printer;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class inventario_model extends CI_Model
{

    private $table = 'inventario';

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('unidades/unidades_model');
    }

    function sumar_inventario($id_producto, $local_id, $unidad_medida, $cantidad)
    {
        return $this->prosesar_inventario($id_producto, $local_id, $unidad_medida, $cantidad, 'suma');
    }

    function prosesar_inventario($id_producto, $local_id, $unidad_medida, $cantidad, $operacion)
    {
        //Busco las unidades de medida del producto
        $error = "";
        $return = array();
        $unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $id_producto));

        // aqui busco el objeto de la uidad que compre
        foreach ($unidades_producto as $row) {
            if ($row['id_unidad'] == $unidad_medida) {
                $unidad_comprada = $row;
            }
        }
        //aqui valido que exista la unidad para este producto en unidades_has_producto
        if (!isset($unidad_comprada)) {
            $error = "La unidad ingresada no est&aacute; configurada para este producto ";
            //$return['error'] = $error;
            //return $return;
        } else {


            //declaro el objeto de cada unidad
            $caja = $unidades_producto[0];
            $sizeof = sizeof($unidades_producto);

            if ($sizeof == 3) {
                $blister = $unidades_producto[1];
                $unidad = $unidades_producto[2];
            } else if ($sizeof == 2) {
                $unidad = $unidades_producto[1];
            }

            //declaro catidad vieja y id inventrio para cada unidad
            $cantidad_vieja_caja = 0;
            $cantidad_vieja_blister = 0;
            $cantidad_vieja_unidad = 0;
            $id_inventario_caja = null;
            $id_inventario_blister = null;
            $id_inventario_unidad = null;

            //busco el inventario actual para esta unidad de este producto en este local
            $inventario_actual_porunidad = $this->get_all_by(array(
                'id_producto' => $id_producto,
                'id_local' => $local_id,
            ));
            $total_unidades_minimas_viejas = 0;

            //recorro inventario actual para buscar el de la unidad que compre

            if (sizeof($inventario_actual_porunidad) > 0) {
                foreach ($inventario_actual_porunidad as $inv) {


                    if ($inv['id_unidad'] == $caja['id_unidad']) {
                        $id_inventario_caja = $inv['id_inventario'];
                        $cantidad_vieja_caja = $inv['cantidad'];
                        $total_unidades_minimas_viejas += $inv['cantidad'] * $caja['unidades'];


                    }
                    if (isset($blister) && $inv['id_unidad'] == $blister['id_unidad']) {
                        $id_inventario_blister = $inv['id_inventario'];
                        $cantidad_vieja_blister = $inv['cantidad'];
                        $total_unidades_minimas_viejas += $inv['cantidad'] * $unidad['unidades'];


                    }
                    if (isset($unidad) && $inv['id_unidad'] == $unidad['id_unidad']) {
                        $id_inventario_unidad = $inv['id_inventario'];
                        $cantidad_vieja_unidad = $inv['cantidad'];
                        $total_unidades_minimas_viejas += $inv['cantidad'];

                    }


                }
            }

            $total_unidades_minimas_compradas = 0;

            if ($unidad_comprada['id_unidad'] == $caja['id_unidad']) {

                $total_unidades_minimas_compradas = $cantidad * $caja['unidades'];

            }

            if (isset($blister) && $unidad_comprada['id_unidad'] == $blister['id_unidad']) {

                $total_unidades_minimas_compradas = $cantidad * $unidad['unidades'];
            }

            if (isset($unidad) && $unidad_comprada['id_unidad'] == $unidad['id_unidad']) {


                $total_unidades_minimas_compradas = $cantidad;
            }


            $cantidad_caja = 0;
            $cantidad_blister = 0;
            $cantidad_unidad = 0;

            if ($operacion == 'suma')

                $total_unidades_minimas = $total_unidades_minimas_viejas + $total_unidades_minimas_compradas;
            else
                $total_unidades_minimas = $total_unidades_minimas_viejas - $total_unidades_minimas_compradas;


            if ($total_unidades_minimas >= $caja['unidades']) {

                $cantidad_caja = intval($total_unidades_minimas / $caja['unidades']);
            } else {
                $cantidad_caja = 0;

                if ($total_unidades_minimas < -9 && $total_unidades_minimas) {

                    $cantidad_caja = "-" . (intval(abs($total_unidades_minimas) / $caja['unidades']));
                }
            }

            $modulo = abs($total_unidades_minimas) % $caja['unidades'];

            if (isset($unidad['unidades']) and $modulo >= $unidad['unidades']) {

                $cantidad_blister = intval(($modulo) / $unidad['unidades']);

                $cantidad_unidad = ($modulo) % $unidad['unidades'];

                if ($unidad['unidades'] == '1') {

                    $cantidad_unidad = $modulo;
                }

                if ($total_unidades_minimas < 0) {

                    $cantidad_blister = "-" . $cantidad_blister;
                    $cantidad_unidad = "-" . $cantidad_unidad;
                }

            } else {
                $cantidad_unidad = $total_unidades_minimas % $caja['unidades'];

            }

            $this->updateOrInsertInventario($id_producto, $caja['id_unidad'], $local_id, $id_inventario_caja,
                $cantidad_caja);


            if (isset($blister)) {
                $this->updateOrInsertInventario($id_producto, $blister['id_unidad'], $local_id, $id_inventario_blister,
                    $cantidad_blister);
            }
            if (isset($unidad)) {
                $this->updateOrInsertInventario($id_producto, $unidad['id_unidad'], $local_id, $id_inventario_unidad,
                    $cantidad_unidad);
            }


            (isset($caja['id_unidad'])) ? $return[$caja['id_unidad']] = array('cantidad_vieja' => $cantidad_vieja_caja, 'stock_actual' => $cantidad_caja) : '';
            (isset($blister['id_unidad'])) ? $return[$blister['id_unidad']] = array('cantidad_vieja' => $cantidad_vieja_blister, 'stock_actual' => $cantidad_blister) : '';
            (isset($unidad['id_unidad'])) ? $return[$unidad['id_unidad']] = array('cantidad_vieja' => $cantidad_vieja_unidad, 'stock_actual' => $cantidad_unidad) : '';

            if (isset($caja['id_unidad'])) {
                $return['stockviejo_array'][$caja['id_unidad']] = array('nombre' => 'CAJA', 'cantidad' => $cantidad_vieja_caja);
                $return['stocknuevo_array'][$caja['id_unidad']] = array('nombre' => 'CAJA', 'cantidad' => $cantidad_caja);
            }
            if (isset($blister['id_unidad'])) {
                $return['stockviejo_array'][$blister['id_unidad']] = array('nombre' => 'BLISTER', 'cantidad' => $cantidad_vieja_blister);
                $return['stocknuevo_array'][$blister['id_unidad']] = array('nombre' => 'BLISTER', 'cantidad' => $cantidad_blister);
            }
            if (isset($unidad['id_unidad'])) {
                $return['stockviejo_array'][$unidad['id_unidad']] = array('nombre' => 'UNIDAD', 'cantidad' => $cantidad_vieja_unidad);
                $return['stocknuevo_array'][$unidad['id_unidad']] = array('nombre' => 'UNIDAD', 'cantidad' => $cantidad_unidad);
            }

            $return['minima_viejas'] = $total_unidades_minimas_viejas;
            $return['minima_nuevas'] = $total_unidades_minimas;
            return $return;
        }

    }


    function get_stock_array($id_producto, $local_id, $unidad_medida)
    {
        //Busco las unidades de medida del producto
        $error = "";
        $return = array();
        $unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $id_producto));

        // aqui busco el objeto de la uidad que compre
        foreach ($unidades_producto as $row) {
            if ($row['id_unidad'] == $unidad_medida) {
                $unidad_comprada = $row;
            }
        }
        //aqui valido que exista la unidad para este producto en unidades_has_producto
        if (!isset($unidad_comprada)) {
            $error = "La unidad ingresada no est&aacute; configurada para este producto ";
            $return['error'] = $error;
            return $return;
        }


        //declaro el objeto de cada unidad
        $caja = $unidades_producto[0];
        $sizeof = sizeof($unidades_producto);

        if ($sizeof == 3) {
            $blister = $unidades_producto[1];
            $unidad = $unidades_producto[2];
        } else if ($sizeof == 2) {
            $unidad = $unidades_producto[1];
        }

        //declaro catidad vieja y id inventrio para cada unidad
        $cantidad_vieja_caja = 0;
        $cantidad_vieja_blister = 0;
        $cantidad_vieja_unidad = 0;
        $id_inventario_caja = null;
        $id_inventario_blister = null;
        $id_inventario_unidad = null;

        //busco el inventario actual para esta unidad de este producto en este local
        $inventario_actual_porunidad = $this->get_all_by(array(
            'id_producto' => $id_producto,
            'id_local' => $local_id,
        ));
        $total_unidades_minimas_viejas = 0;

        //recorro inventario actual para buscar el de la unidad que compre

        if (sizeof($inventario_actual_porunidad) > 0) {
            foreach ($inventario_actual_porunidad as $inv) {


                if ($inv['id_unidad'] == $caja['id_unidad']) {
                    $id_inventario_caja = $inv['id_inventario'];
                    $cantidad_vieja_caja = $inv['cantidad'];
                    $total_unidades_minimas_viejas += $inv['cantidad'] * $caja['unidades'];


                }
                if (isset($blister) && $inv['id_unidad'] == $blister['id_unidad']) {
                    $id_inventario_blister = $inv['id_inventario'];
                    $cantidad_vieja_blister = $inv['cantidad'];
                    $total_unidades_minimas_viejas += $inv['cantidad'] * $unidad['unidades'];


                }
                if (isset($unidad) && $inv['id_unidad'] == $unidad['id_unidad']) {
                    $id_inventario_unidad = $inv['id_inventario'];
                    $cantidad_vieja_unidad = $inv['cantidad'];
                    $total_unidades_minimas_viejas += $inv['cantidad'];

                }


            }
        }


        $cantidad_caja = 0;
        $cantidad_blister = 0;
        $cantidad_unidad = 0;


        $total_unidades_minimas = $total_unidades_minimas_viejas;


        if ($total_unidades_minimas >= $caja['unidades']) {

            $cantidad_caja = intval($total_unidades_minimas / $caja['unidades']);
        } else {
            $cantidad_caja = 0;

            if ($total_unidades_minimas < -9 && $total_unidades_minimas) {

                $cantidad_caja = "-" . (intval(abs($total_unidades_minimas) / $caja['unidades']));
            }
        }

        $modulo = abs($total_unidades_minimas) % $caja['unidades'];

        if (isset($unidad['unidades']) and $modulo >= $unidad['unidades']) {

            $cantidad_blister = intval(($modulo) / $unidad['unidades']);

            $cantidad_unidad = ($modulo) % $unidad['unidades'];

            if ($unidad['unidades'] == '1') {

                $cantidad_unidad = $modulo;
            }

            if ($total_unidades_minimas < 0) {

                $cantidad_blister = "-" . $cantidad_blister;
                $cantidad_unidad = "-" . $cantidad_unidad;
            }

        } else {
            $cantidad_unidad = $total_unidades_minimas % $caja['unidades'];

        }


        (isset($caja['id_unidad'])) ? $return[$caja['id_unidad']] = array('cantidad_vieja' => $cantidad_vieja_caja, 'stock_actual' => $cantidad_caja) : '';
        (isset($blister['id_unidad'])) ? $return[$blister['id_unidad']] = array('cantidad_vieja' => $cantidad_vieja_blister, 'stock_actual' => $cantidad_blister) : '';
        (isset($unidad['id_unidad'])) ? $return[$unidad['id_unidad']] = array('cantidad_vieja' => $cantidad_vieja_unidad, 'stock_actual' => $cantidad_unidad) : '';

        if (isset($caja['id_unidad'])) {
            $return['stockviejo_array'][$caja['id_unidad']] = array('nombre' => 'CAJA', 'cantidad' => $cantidad_vieja_caja);
            $return['stocknuevo_array'][$caja['id_unidad']] = array('nombre' => 'CAJA', 'cantidad' => $cantidad_vieja_caja);
        }
        if (isset($blister['id_unidad'])) {
            $return['stockviejo_array'][$blister['id_unidad']] = array('nombre' => 'BLISTER', 'cantidad' => $cantidad_vieja_blister);
            $return['stocknuevo_array'][$blister['id_unidad']] = array('nombre' => 'BLISTER', 'cantidad' => $cantidad_vieja_blister);
        }
        if (isset($unidad['id_unidad'])) {
            $return['stockviejo_array'][$unidad['id_unidad']] = array('nombre' => 'UNIDAD', 'cantidad' => $cantidad_vieja_unidad);
            $return['stocknuevo_array'][$unidad['id_unidad']] = array('nombre' => 'UNIDAD', 'cantidad' => $cantidad_vieja_unidad);
        }

        $return['minima_viejas'] = $total_unidades_minimas_viejas;
        $return['minima_nuevas'] = $total_unidades_minimas;
        return $return;

    }


    function updateOrInsertInventario($id_producto, $unidad_medida, $local_id, $id_inventario, $cantidad)
    {
        $inventario_nuevo = array(
            'id_producto' => $id_producto,
            'id_unidad' => $unidad_medida,
            'id_local' => $local_id
        );

        $inventario_nuevo['cantidad'] = $cantidad;

        if ($id_inventario != null) {
            $where = array('id_inventario' => $id_inventario);
            $this->update_inventario($inventario_nuevo, $where);

        } else {
            $this->set_inventario($inventario_nuevo);
        }

    }

    function updateInventarioforProduct($id_producto, $cantidad)
    {
        $inventario_nuevo = array(
            'cantidad' => $cantidad,
        );


        $where = array('id_producto' => $id_producto);
        $this->update_inventario($inventario_nuevo, $where);


    }

    function restar_inventario($id_producto, $local_id, $unidad_medida, $cantidad)
    {


        return $this->prosesar_inventario($id_producto, $local_id, $unidad_medida, $cantidad, 'resta');
    }

    function get_by($campos)
    {
        $this->db->where($campos);
        $query = $this->db->get('inventario');
        return $query->row_array();
    }

    function get_all_by($campos)
    {

        $this->db->where($campos);

        $query = $this->db->get('inventario');

        return $query->result_array();
    }

    function get_all_by_array($campos)
    {


        $query = $this->db->query($campos);
        return $query->result();
    }

    function get_inventario_by_producto_and_unidad($producto, $local, $id_unidad,$select='*')
    {

        $sql = ("SELECT ".$select." FROM inventario LEFT  JOIN unidades_has_producto ON unidades_has_producto.producto_id=inventario.id_producto
      WHERE inventario.id_producto='$producto' AND id_local='$local' and inventario.id_unidad='$id_unidad' and unidades_has_producto.id_unidad=inventario.id_unidad GROUP BY inventario.id_unidad  ORDER by id_inventario DESC");
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function get_inventario_by_producto($producto, $local, $order = false, $orde_type='DESC')
    {

        $this->db->select('inventario.*, unidades_has_producto.*, fe_unidad,unidades_has_precio.precio, 
        unidades_has_precio.utilidad,
        unidades.abreviatura, unidades.nombre_unidad,
         unidades.orden, producto.control_inven, producto.producto_id, producto.costo_unitario');
        $this->db->from('inventario');
        $this->db->join('unidades_has_producto', 'unidades_has_producto.producto_id=inventario.id_producto', 'left');
        $this->db->join('unidades_has_precio', 'unidades_has_precio.id_producto=unidades_has_producto.producto_id and
         unidades_has_precio.id_unidad=unidades_has_producto.id_unidad', 'left');
        $this->db->join('unidades', 'unidades.id_unidad=unidades_has_producto.id_unidad');
        $this->db->join('producto', 'producto.producto_id=inventario.id_producto');
        $this->db->where('inventario.id_producto', $producto);
        $this->db->where('inventario.id_local', $local);
        $this->db->where('unidades_has_producto.id_unidad=inventario.id_unidad');
        $this->db->group_by('inventario.id_unidad');
        if ($order != false) {
            $this->db->order_by($order,$orde_type);
        } else {
            $this->db->order_by('id_inventario', 'DESC');
        }
        $query = $this->db->get();

        //echo $this->db->last_query();

        return $query->result_array();
    }


    function get_inventario_group_by_producto( $local, $order = false, $orde_type='DESC')
    {

        $this->db->select('inventario.*, unidades_has_producto.*, impuestos.porcentaje_impuesto as porcentaje_impuesto_producto, 
        unidades_has_precio.precio, unidades.abreviatura, 
         unidades.orden, producto.*');
        $this->db->from('inventario');
        $this->db->join('unidades_has_producto', 'unidades_has_producto.producto_id=inventario.id_producto', 'left');
        $this->db->join('unidades_has_precio', 'unidades_has_precio.id_producto=unidades_has_producto.producto_id and
         unidades_has_precio.id_unidad=unidades_has_producto.id_unidad', 'left');
        $this->db->join('unidades', 'unidades.id_unidad=unidades_has_producto.id_unidad');
        $this->db->join('producto', 'producto.producto_id=inventario.id_producto');
        $this->db->join('impuestos', 'impuestos.id_impuesto=producto.producto_impuesto');

        $this->db->where('inventario.id_local', $local);
        $this->db->where('unidades_has_producto.id_unidad=inventario.id_unidad');
        $this->db->group_by('producto.producto_id, inventario.id_unidad');
        if ($order != false) {
            $this->db->order_by($order,$orde_type);
        } else {
            $this->db->order_by('id_inventario', 'DESC');
        }
        $query = $this->db->get();


        //echo $this->db->last_query();
        return $query->result_array();
    }



    function set_inventario($campos)
    {


        $this->db->trans_start();
        $this->db->insert('inventario', $campos);
        $ultimo_id = $this->db->insert_id();
        $this->db->trans_complete();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return $ultimo_id;
    }

    function update_inventario($campos, $wheres)
    {

        $this->db->trans_start();
        $this->db->where($wheres);
        $this->db->update('inventario', $campos);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            return false;
        } else {

            return true;
        }

    }

    /*
     * Actualiza el costo de la caja del producto
     * @costo  costo de la unidad de medida de la transaccion
     * @produto prodcto
     * @unidad_id unidad de medida de la transaccion
     * @cantidad cantidad que esta entrando o saliendo
     * */
    function update_costo_unitario($costo, $producto, $unidad_id, $cantidad)
    {

        //ESTO TRAE ES EL COSTO DE LA CAJA
        $costo_caja = $this->unidades_model->costo_unitario($producto, $unidad_id,
            $costo > 0 && $cantidad>0 ? $costo / $cantidad : 0);

        /******************************/
        if ($costo_caja != false) {
            $this->producto_model->solo_update(array('producto_id' => $producto),
                array('costo_unitario' => $costo_caja));

            //Actualizo el costo de cada unidad, calculado segun el costo unitario de la caja
            $this->producto_model->updateCostosByProduct($producto, $costo_caja);
            //fin del parche
        }

    }

    function getIventarioProducto($wheres)
    {

        $this->db->select('*');
        $this->db->from('producto');
        $this->db->join('inventario', 'producto.producto_id=inventario.id_producto', 'left');
        $this->db->join('unidades_has_producto', 'unidades_has_producto.producto_id=producto.producto_id', 'left');
        $this->db->join('unidades', 'unidades.id_unidad=unidades_has_producto.id_unidad', 'left');
        $this->db->where($wheres);
        $this->db->order_by('producto.producto_id', 'asc');
        $query = $this->db->get();

        return $query->result_array();
    }


    function covertUnidadMinima($unidades_producto, $unidad_id, $cantidad)
    {
        $total_unidades_minimas_viejas = 0;
        //$unidades_producto las unidades del producto
        //$unidad_id la unidad que estoy buscando, que se supone estoy recorriendo<- vease traslado_model metodo procesarTraslado
        //la cantidad que estoy ingresando
        foreach ($unidades_producto as $row) {

            if ($unidad_id == $row['id_unidad'] && $row['orden'] == 1) {
                $total_unidades_minimas_viejas += $cantidad * $row['unidades'];
            }

            if ($unidad_id == $row['id_unidad'] && $row['orden'] == 2) {
                $total_unidades_minimas_viejas += $cantidad * $unidades_producto[2]['unidades'];
            }

            if ($unidad_id == $row['id_unidad'] && $row['orden'] == 3) {
                $total_unidades_minimas_viejas += $cantidad;

            }

        }

        return $total_unidades_minimas_viejas;

    }

    function stockUnidadesMinimas($producto, $local)
    {  //devuelve el stock de un producto en unidades minimas
        $total_unidades_minimas_viejas = 0;

        $unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $producto));

        if (count($unidades_producto) > 0) {
            //busco el inventario actual para este producto en este local
            $condicion = array(
                'id_producto' => $producto,
                'id_local' => $local,
            );

            $inventario_actual_porunidad = $this->get_all_by($condicion);


            if (sizeof($inventario_actual_porunidad) > 0) {

                //recorro las unidades que tiene configurada este producto
                foreach ($unidades_producto as $row) {

                    //recorro inventario actual y voy sumando segun la unidad que tenga configurada el producto, busco el stock
                    foreach ($inventario_actual_porunidad as $inv) {

                        //cajas
                        if ($inv['id_unidad'] == $row['id_unidad'] && $row['orden'] == 1) {
                            $total_unidades_minimas_viejas += $inv['cantidad'] * $row['unidades'];
                        }
                        //blister
                        if ($inv['id_unidad'] == $row['id_unidad'] && $row['orden'] == 2) {
                            $total_unidades_minimas_viejas += $inv['cantidad'] * $unidades_producto[2]['unidades'];
                        }
                        //unidad
                        if ($inv['id_unidad'] == $row['id_unidad'] && $row['orden'] == 3) {
                            $total_unidades_minimas_viejas += $inv['cantidad'];

                        }
                    }
                }

            }
        }

        return $total_unidades_minimas_viejas;

    }

    function delete($where)
    {

        $this->db->trans_start();
        $this->db->where($where);
        $this->db->delete($this->table);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;

    }


    public function traer_by($select = false, $from = false, $join = false, $campos_join = false, $tipo_join, $where = false, $nombre_in, $where_in,
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

        // echo $this->db->last_query();
        if ($retorno == "RESULT_ARRAY") {

            return $query->result_array();
        } elseif ($retorno == "RESULT") {
            return $query->result();

        } else {
            return $query->row_array();
        }

    }



    function procesarinventarioDesdeXCantidad($id_producto, $local_id, $unidad_medida, $cantidad,
                                              $operacion,$inventario_actual_porunidad)
    {
        //Busco las unidades de medida del producto
        $error = "";
        $return = array();
        $unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $id_producto));

        // aqui busco el objeto de la uidad que compre
        foreach ($unidades_producto as $row) {
            if ($row['id_unidad'] == $unidad_medida) {
                $unidad_comprada = $row;
            }
        }
        //aqui valido que exista la unidad para este producto en unidades_has_producto
        if (!isset($unidad_comprada)) {
            $error = "La unidad ingresada no est&aacute; configurada para este producto ";
            //$return['error'] = $error;
            //return $return;
        } else {


            //declaro el objeto de cada unidad
            $caja = $unidades_producto[0];
            $sizeof = sizeof($unidades_producto);

            if ($sizeof == 3) {
                $blister = $unidades_producto[1];
                $unidad = $unidades_producto[2];
            } else if ($sizeof == 2) {
                $unidad = $unidades_producto[1];
            }

            //declaro catidad vieja y id inventrio para cada unidad
            $cantidad_vieja_caja = 0;
            $cantidad_vieja_blister = 0;
            $cantidad_vieja_unidad = 0;
            $id_inventario_caja = null;
            $id_inventario_blister = null;
            $id_inventario_unidad = null;


            $total_unidades_minimas_viejas = 0;

            //recorro inventario actual para buscar el de la unidad que compre

            if (sizeof($inventario_actual_porunidad) > 0) {
                foreach ($inventario_actual_porunidad as $inv) {


                    if ($inv['id_unidad'] == $caja['id_unidad']) {
                        $id_inventario_caja = $inv['id_inventario'];
                        $cantidad_vieja_caja = $inv['cantidad'];
                        $total_unidades_minimas_viejas += $inv['cantidad'] * $caja['unidades'];


                    }
                    if (isset($blister) && $inv['id_unidad'] == $blister['id_unidad']) {
                        $id_inventario_blister = $inv['id_inventario'];
                        $cantidad_vieja_blister = $inv['cantidad'];
                        $total_unidades_minimas_viejas += $inv['cantidad'] * $unidad['unidades'];


                    }
                    if (isset($unidad) && $inv['id_unidad'] == $unidad['id_unidad']) {
                        $id_inventario_unidad = $inv['id_inventario'];
                        $cantidad_vieja_unidad = $inv['cantidad'];
                        $total_unidades_minimas_viejas += $inv['cantidad'];

                    }


                }
            }

            $total_unidades_minimas_compradas = 0;

            if ($unidad_comprada['id_unidad'] == $caja['id_unidad']) {

                $total_unidades_minimas_compradas = $cantidad * $caja['unidades'];

            }

            if (isset($blister) && $unidad_comprada['id_unidad'] == $blister['id_unidad']) {

                $total_unidades_minimas_compradas = $cantidad * $unidad['unidades'];
            }

            if (isset($unidad) && $unidad_comprada['id_unidad'] == $unidad['id_unidad']) {


                $total_unidades_minimas_compradas = $cantidad;
            }


            $cantidad_caja = 0;
            $cantidad_blister = 0;
            $cantidad_unidad = 0;

            if ($operacion == 'suma')

                $total_unidades_minimas = $total_unidades_minimas_viejas + $total_unidades_minimas_compradas;
            else
                $total_unidades_minimas = $total_unidades_minimas_viejas - $total_unidades_minimas_compradas;


            if ($total_unidades_minimas >= $caja['unidades']) {

                $cantidad_caja = intval($total_unidades_minimas / $caja['unidades']);
            } else {
                $cantidad_caja = 0;

                if ($total_unidades_minimas < -9 && $total_unidades_minimas) {

                    $cantidad_caja = "-" . (intval(abs($total_unidades_minimas) / $caja['unidades']));
                }
            }

            $modulo = abs($total_unidades_minimas) % $caja['unidades'];

            if (isset($unidad['unidades']) and $modulo >= $unidad['unidades']) {

                $cantidad_blister = intval(($modulo) / $unidad['unidades']);

                $cantidad_unidad = ($modulo) % $unidad['unidades'];

                if ($unidad['unidades'] == '1') {

                    $cantidad_unidad = $modulo;
                }

                if ($total_unidades_minimas < 0) {

                    $cantidad_blister = "-" . $cantidad_blister;
                    $cantidad_unidad = "-" . $cantidad_unidad;
                }

            } else {
                $cantidad_unidad = $total_unidades_minimas % $caja['unidades'];

            }

            $this->updateOrInsertInventario($id_producto, $caja['id_unidad'], $local_id, $id_inventario_caja,
                $cantidad_caja);


            if (isset($blister)) {
                $this->updateOrInsertInventario($id_producto, $blister['id_unidad'], $local_id, $id_inventario_blister,
                    $cantidad_blister);
            }
            if (isset($unidad)) {
                $this->updateOrInsertInventario($id_producto, $unidad['id_unidad'], $local_id, $id_inventario_unidad,
                    $cantidad_unidad);
            }


            (isset($caja['id_unidad'])) ? $return[$caja['id_unidad']] = array('cantidad_vieja' => $cantidad_vieja_caja, 'stock_actual' => $cantidad_caja) : '';
            (isset($blister['id_unidad'])) ? $return[$blister['id_unidad']] = array('cantidad_vieja' => $cantidad_vieja_blister, 'stock_actual' => $cantidad_blister) : '';
            (isset($unidad['id_unidad'])) ? $return[$unidad['id_unidad']] = array('cantidad_vieja' => $cantidad_vieja_unidad, 'stock_actual' => $cantidad_unidad) : '';

            if (isset($caja['id_unidad'])) {
                $return['stockviejo_array'][$caja['id_unidad']] = array('nombre' => 'CAJA', 'cantidad' => $cantidad_vieja_caja);
                $return['stocknuevo_array'][$caja['id_unidad']] = array('nombre' => 'CAJA', 'cantidad' => $cantidad_caja);
            }
            if (isset($blister['id_unidad'])) {
                $return['stockviejo_array'][$blister['id_unidad']] = array('nombre' => 'BLISTER', 'cantidad' => $cantidad_vieja_blister);
                $return['stocknuevo_array'][$blister['id_unidad']] = array('nombre' => 'BLISTER', 'cantidad' => $cantidad_blister);
            }
            if (isset($unidad['id_unidad'])) {
                $return['stockviejo_array'][$unidad['id_unidad']] = array('nombre' => 'UNIDAD', 'cantidad' => $cantidad_vieja_unidad);
                $return['stocknuevo_array'][$unidad['id_unidad']] = array('nombre' => 'UNIDAD', 'cantidad' => $cantidad_unidad);
            }

            $return['minima_viejas'] = $total_unidades_minimas_viejas;
            $return['minima_nuevas'] = $total_unidades_minimas;
            return $return;
        }

    }


}
