<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class traslado_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('kardex/kardex_model');
        $this->load->model('ingreso/ingreso_model');
    }

    function buscarTraslados($where)
    {

        $this->db->select('DATE_FORMAT(fecha, "%d-%m-%Y %H:%i:%s") as fecha_formateada,
         traslado.*, usuario.username, usuario.nombre');
        $this->db->from('traslado');
        $this->db->join('traslado_detalle', 'traslado_detalle.traslado_id=traslado.id_traslado');
        $this->db->join('usuario', 'usuario.nUsuCodigo=traslado.usuario');
        $this->db->where($where);
        $this->db->group_by('traslado.id_traslado');
        $query = $this->db->get();

        return $query->result();
    }

    function procesarTraslado($lst_producto){

        $unidades= $this->unidades_model->get_unidades();

        $traslado_id=$this->guardar($lst_producto);


        $local_desde=$this->input->post('localdesde');
        $local_destino=$this->input->post('localhasta');

        $datas = array();
        $nombresProducto=$this->input->post('nombre_producto');
        $nombreprod="";
        $list_pa=array();
        $error=array();

        foreach ($lst_producto as $row) {

            $datos_producto=$this->producto_model->get_by('producto_id',$row->producto_id);
            $total_stock_minimas = $this->inventario_model->stockUnidadesMinimas($row->producto_id, $local_desde);
            $unidades_producto = $this->unidades_model->solo_unidades_xprod(array('producto_id' => $row->producto_id));
            $nombreprod=$nombresProducto[$row->producto_id];
            $cantidades=$this->input->post('cantidad_'.$row->producto_id);


            $totalentabla=0;
            foreach ($unidades as $unidad) {

                if(isset($cantidades[$unidad['id_unidad']]) and $cantidades[$unidad['id_unidad']]!=""
                && $cantidades[$unidad['id_unidad']]!=false){

                    $totalentabla+= $this->inventario_model->covertUnidadMinima($unidades_producto, $unidad['id_unidad'],
                        $cantidades[$unidad['id_unidad']]);

                    $list_pa = array(
                        'traslado_id' => $traslado_id,
                        'id_producto' => $row->producto_id,
                        'unidad_id' => $unidad['id_unidad'],
                        'cantidad' => $cantidades[$unidad['id_unidad']],
                        'local_salida'=>$local_desde,
                        'local_destino'=>$local_destino
                    );

                    array_push($datas, $list_pa);

                    $this->ingreso_model->setInventario($row->producto_id,$unidad['id_unidad'],$cantidades[$unidad['id_unidad']],
                        TRASLADO_MERCANCIA,'0',$traslado_id,NULL,NULL,SALIDA,"RESTA",$stockviejo = array(), $stocknuevo = array(),
                        null,$local_desde,$datos_producto['control_inven'],$datos_producto['porcentaje_impuesto'],
                        $datos_producto['costo_unitario']);


                    $this->ingreso_model->setInventario($row->producto_id,$unidad['id_unidad'],$cantidades[$unidad['id_unidad']],
                        TRASLADO_MERCANCIA,'0',$traslado_id,NULL,NULL,ENTRADA,"SUMA",$stockviejo = array(), $stocknuevo = array(),
                        null,$local_destino,$datos_producto['control_inven'], $datos_producto['porcentaje_impuesto'],
                        $datos_producto['costo_unitario']);

                }

            }

            if($datos_producto['control_inven']==1 and $totalentabla>$total_stock_minimas){

                $this->db->devolverSinError();

                $error['error']="Ha ingresado una cantidad superior al stock actual, para el producto ".$nombreprod;
                return $error;
                break;
            }

        }

        if(count($list_pa)>0){

            $this->db->insert_batch('traslado_detalle', $datas);

        }else{

            $this->db->trans_complete();
            $error['error']="No ha ingresado al menos una cantidad ";
            return $error;

        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $traslado_id;
        }

    }

    function guardar($lst_producto){

        $this->db->trans_begin();

        $campos=array(
            'usuario'=>$this->session->userdata('nUsuCodigo'),
            'fecha'=>date("Y-m-d", strtotime($this->input->post('fecha_traslado'))) . " " . date('H:i:s'),
            'cant_productos'=>count($lst_producto)
        );
        $this->db->insert('traslado', $campos);
        $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    function getDetalleTraslado($where)
    {

        $this->db->select('traslado.*, traslado_detalle.*,usuario.username, usuario.nombre');
        $this->db->from('traslado');
        $this->db->join('traslado_detalle', 'traslado_detalle.traslado_id=traslado.id_traslado');
        $this->db->join('usuario', 'usuario.nUsuCodigo=traslado.usuario');
        $this->db->where($where);
        $query = $this->db->get();

        return $query->result();
    }

    function productosDetalle($where){  //busca solo los datos de los productos que fueron trasladados

        $this->db->select('producto.producto_id, producto.producto_codigo_interno, producto.producto_nombre');
        $this->db->from('producto');
        $this->db->join('traslado_detalle', 'traslado_detalle.id_producto=producto.producto_id');
        $this->db->where($where);
        $this->db->order_by('traslado_detalle.trasladodetalle_id');
        $this->db->group_by('traslado_detalle.id_producto');
        $query = $this->db->get();

        return $query->result();
    }


}
