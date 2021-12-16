<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class StatusCajaModel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('venta/venta_model');
    }


    function getAlBy($data)
    {
        $this->db->where($data);

        $query = $this->db->join('caja', 'caja.caja_id=status_caja.caja_id');
        $query = $this->db->join('usuario', 'usuario.nUsuCodigo=status_caja.cajero');
        $query = $this->db->get('status_caja');
        // echo $this->db->last_query();
        return $query->result_array();
    }

    function getBy($data)
    {
        $this->db->select('*');
        $this->db->where($data);
        $query = $this->db->join('caja', 'caja.caja_id=status_caja.caja_id');
        $query = $this->db->join('usuario', 'usuario.nUsuCodigo=status_caja.cajero');
        $query = $this->db->get('status_caja');
        return $query->row_array();
    }


    //INGRESOS DE DINERO A CAJA , SOLO POR VENTAS
    function getTotalsIngresosByMetodoPago($metoto, $fechainicio, $fechafin, $esfectivo, $nombre, $idcaja=false)
    {
        //echo $fechainicio;
        $condicionVenta = array(
            'venta_status =' => COMPLETADO,
            'id_forma_pago =' => $metoto,
        );

        if($idcaja!=false){  // esto lo hace cuando es un cierre caja
            $condicionVenta['caja_id =']=$idcaja;
            $condicionVenta['fecha >='] = date('Y-m-d H:i:s',strtotime($fechainicio));
            $condicionVenta['fecha <='] = date('Y-m-d H:i:s',strtotime($fechafin));
        }else{ // esto o hace cuando es comprobante de ventas diarias
            $condicionVenta['date(fecha) >='] = date('Y-m-d',strtotime($fechainicio));
            $condicionVenta['date(fecha) <='] = date('Y-m-d',strtotime($fechafin));
        }
        $totalventa = $this->venta_model->get_total_by_forma_pago($condicionVenta);

       // var_dump($totalventa);

        $totalregistros=0;
        $descuentos =0 ;
        $totalgravado =0 ;
        $totalexcluido =0 ;
        $totaliva =0 ;
        $total_otros_impuestos =0 ;

        $total = $totalventa['suma']>$totalventa['total']?$totalventa['total']:$totalventa['suma'];
        if(!is_numeric($total)){
            $total=0;
        }
        $desc_valor = $totalventa['descuento_valor'];
        $desc_porc = $totalventa['descuento_porcentaje'];
        $totalregistros = $totalventa['num'];
        $totalgravado = $totalventa['gravado'];
        $totalexcluido = $totalventa['excluido'];
        $totaliva = $totalventa['iva'];
        $venta_id = $totalventa['venta_id'];
        $total_otros_impuestos = $totalventa['otros_impuestos'];

        $descuentos=$desc_porc+$desc_valor;
        $condicionVenta = array(
            'venta_status' => COMPLETADO,

        );
        if($idcaja!=false){
            $condicionVenta['caja_id']=$idcaja;
            $condicionVenta['fecha >='] = date('Y-m-d H:i:s',strtotime($fechainicio));
            $condicionVenta['fecha <='] = date('Y-m-d H:i:s',strtotime($fechafin));
        }
        else{ // esto o hace cuando es comprobante de ventas diarias
            $condicionVenta['date(fecha) >='] = date('Y-m-d',strtotime($fechainicio));
            $condicionVenta['date(fecha) <='] = date('Y-m-d',strtotime($fechafin));
        }
        $efectivo=0;
        //SI EL METODO DE PAGO ES EFECTIVO TENGO QUE BUSCAR LAS VENTAS QUE SOLO SE HICIERON CON EFECTIVO, ES DECIR LAS QUE NO SE HICIERON CON EL MODAL DE METODOS DE PAGO
        if($esfectivo==1 || $nombre=='EFECTIVO' ){
            $efectivo = $this->venta_model->get_total_soloefectivo($condicionVenta);

            if($nombre=='EFECTIVO'){
                //var_dump($efectivo);
                $dscvalor = isset($efectivo['descuento_valor'])?$efectivo['descuento_valor']:0;
                $dscpor= isset($efectivo['descuento_porcentaje'])?$efectivo['descuento_porcentaje']:0;
                $total= $total+$efectivo['suma'];
                $venta_id= $efectivo['venta_id'];
                $descuentos = $descuentos + $dscvalor+ $dscpor;
                $totalregistros=$totalregistros+$efectivo['num'];
                $totalgravado=$totalgravado+$efectivo['gravado'];
                $totalexcluido=$totalexcluido+$efectivo['excluido'];
                $totaliva=$totaliva+$efectivo['iva'];
                $total_otros_impuestos=$total_otros_impuestos+$efectivo['otros_impuestos'];
            }
        }


        return array('total' => $total, 'descuentos' => $descuentos,'totalregistros'=>$totalregistros, 'venta_id'=>$venta_id,
            'gravado'=>$totalgravado,'excluido'=>$totalexcluido,'iva'=>$totaliva, 'otros_impuestos'=>$total_otros_impuestos);
    }


    function set($data)
    {

        $this->db->trans_start();
        if ($this->db->insert('status_caja', $data)) {

            $id = $this->db->insert_id();
            $this->db->trans_complete();

            return $id;
        } else {
            return false;
        }

    }

    function update($data)
    {

        $this->db->trans_start();
        if ($this->db->update('status_caja', $data, array('id' => $data['id']))) {

            $this->db->trans_complete();

            return $data['id'];
        } else {
            return false;
        }

    }
    public
    function traer_by($select = false, $from = false, $join = false, $campos_join = false, $tipo_join, $where = false, $nombre_in, $where_in,
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

                    for ($t = 0; $t < count($tipo_join); $t++) {

                        if ($tipo_join[$t] != "") {

                            $this->db->join($join[$i], $campos_join[$i], $tipo_join[$t]);
                        }

                    }

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

        $re = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        $devolver=null;
        if ($retorno == "RESULT_ARRAY") {

            $devolver = $query->result_array();

            if (count($devolver) > 0) {
                $devolver[0]['total_afectados'] = $re;
            }
        } elseif ($retorno == "RESULT") {
            $devolver = $query->result();
            if (count($devolver) > 0) {
                $devolver[0]->total_afectados = $re;
            }

        } else {
            $devolver = $query->row_array();
            if (sizeof($devolver) > 0) {
                $devolver['total_afectados'] = $re;
            }
        }

        return $devolver;

    }

}
