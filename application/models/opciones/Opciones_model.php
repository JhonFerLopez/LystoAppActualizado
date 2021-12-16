<?php

/**
 * Created by IntelliJ IDEA.
 * User: Jhainey
 * Date: 18/03/2015
 * Time: 11:56 AM
 */
class opciones_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function guardar_configuracion($configuraciones)
    {


        $this->db->trans_start();


        foreach ($configuraciones as $conf) {
            if (!empty($conf)) {
                $query = $this->db->where('config_key', $conf['config_key']);
                $query = $this->db->select('*');
                $query = $this->db->from('configuraciones');
                $query = $this->db->get();
                $existe = $query->result_array();

                if (sizeof($existe) >= 1) {
                    $this->db->where('config_key', $conf['config_key']);
                    $this->db->update('configuraciones', $conf);
                } else {
                    $this->db->insert('configuraciones', $conf);
                }

            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;

    }

    /**
     * carga las configuraciones en sesion
     */
    function loadConfigsInSession(){
        $data = array();
        $configuraciones = $this->get_opciones();

        if ($configuraciones == TRUE) {
            foreach ($configuraciones as $configuracion) {

                $clave = $configuracion['config_key'];

                $data[$clave] = $configuracion['config_value'];
                //if ($configuracion['config_key'] == 'BODEGA_PRINCIPAL') {
                $this->session->set_userdata($clave, $configuracion['config_value']);
                //}
            }
        }

        $this->session->set_userdata($data);

    }

    public function getByKey($key){
        $query=$this->db->where('config_key', $key);
        $query = $this->db->get('configuraciones');
        return $query->row_array();
    }
    public function get_opciones()
    {
        $this->db->select('*');
        $this->db->from('configuraciones');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function delete_all_catalogo()
    {

        $this->db->trans_start();

        $this->db->query("TRUNCATE catalogo");

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    public function insert_bach_catalogo($data)
    {
        $this->db->trans_start();

        $this->db->insert_batch('catalogo', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }


    public function update($where,$data)
    {
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->update('configuraciones', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    function restablecerBD(){

        ini_set('max_execution_time', 6200);
        $this->db->trans_start();

        $query = $this->db->query("SET FOREIGN_KEY_CHECKS = 0;");
        $query = $this->db->query("DELETE FROM venta_forma_pago");
        $query = $this->db->query("TRUNCATE TABLE venta_forma_pago");
        $query = $this->db->query("DELETE FROM venta_estatus");
        $query = $this->db->query("TRUNCATE TABLE venta_estatus");
        $query = $this->db->query("DELETE FROM venta_devolucion");
        $query = $this->db->query("TRUNCATE TABLE venta_devolucion");
        $query = $this->db->query("DELETE FROM venta_backup");
        $query = $this->db->query("TRUNCATE TABLE venta_backup");
        $query = $this->db->query("DELETE FROM venta_anular");
        $query = $this->db->query("TRUNCATE TABLE venta_anular");
        $query = $this->db->query("DELETE FROM traslado_detalle");
        $query = $this->db->query("TRUNCATE TABLE traslado_detalle");
        $query = $this->db->query("DELETE FROM traslado");
        $query = $this->db->query("TRUNCATE TABLE traslado");
        $query = $this->db->query("DELETE FROM system_logs");
        $query = $this->db->query("TRUNCATE TABLE system_logs");

        $query = $this->db->query("DELETE FROM status_caja");
        $query = $this->db->query("TRUNCATE TABLE status_caja");
        $query = $this->db->query("DELETE FROM recibo_pago_proveedor");
        $query = $this->db->query("TRUNCATE TABLE recibo_pago_proveedor");
        $query = $this->db->query("DELETE FROM recibo_pago_cliente");
        $query = $this->db->query("TRUNCATE TABLE recibo_pago_cliente");
        $query = $this->db->query("DELETE FROM pagos_ingreso");
        $query = $this->db->query("TRUNCATE TABLE pagos_ingreso");
        $query = $this->db->query("DELETE FROM kardex");
        $query = $this->db->query("TRUNCATE TABLE kardex");
        $query = $this->db->query("DELETE FROM historial_pagos_clientes");
        $query = $this->db->query("TRUNCATE TABLE historial_pagos_clientes");
        $query = $this->db->query("DELETE FROM documento_venta");
        $query = $this->db->query("TRUNCATE TABLE documento_venta");
        $query = $this->db->query("DELETE FROM detalleingreso_especial");
        $query = $this->db->query("TRUNCATE TABLE detalleingreso_especial");
        $query = $this->db->query("DELETE FROM detalle_ingreso_unidad");
        $query = $this->db->query("TRUNCATE TABLE detalle_ingreso_unidad");
        $query = $this->db->query("DELETE FROM detalleingreso");
        $query = $this->db->query("TRUNCATE TABLE detalleingreso");

        $query = $this->db->query("DELETE FROM detalle_venta_unidad_backup");
        $query = $this->db->query("TRUNCATE TABLE detalle_venta_unidad_backup");
        $query = $this->db->query("DELETE FROM detalle_venta_unidad");
        $query = $this->db->query("TRUNCATE TABLE detalle_venta_unidad");
        $query = $this->db->query("DELETE FROM detalle_venta_devolucion");
        $query = $this->db->query("TRUNCATE TABLE detalle_venta_devolucion");
        $query = $this->db->query("DELETE FROM detalle_venta_backup");
        $query = $this->db->query("TRUNCATE TABLE detalle_venta_backup");
        $query = $this->db->query("DELETE FROM detalle_venta");
        $query = $this->db->query("TRUNCATE TABLE detalle_venta");


        $query = $this->db->query("DELETE FROM credito");
        $query = $this->db->query("TRUNCATE TABLE credito");
        $query = $this->db->query("DELETE FROM comprobante_diario_ventas");
        $query = $this->db->query("TRUNCATE TABLE comprobante_diario_ventas");
        $query = $this->db->query("DELETE FROM comision_vendedor");
        $query = $this->db->query("TRUNCATE TABLE comision_vendedor");
        $query = $this->db->query("DELETE FROM ajustedetalle");
        $query = $this->db->query("TRUNCATE TABLE ajustedetalle");
        $query = $this->db->query("DELETE FROM ajusteinventario");
        $query = $this->db->query("TRUNCATE TABLE ajusteinventario");

        $query = $this->db->query("DELETE FROM ingreso");
        $query = $this->db->query("TRUNCATE TABLE ingreso");

        $query = $this->db->query("DELETE FROM venta");
        $query = $this->db->query("TRUNCATE TABLE venta");

        $query = $this->db->query("DELETE FROM inventario");
        $query = $this->db->query("TRUNCATE TABLE inventario");

        $query = $this->db->query("DELETE FROM resolucion_dian");
        $query = $this->db->query("TRUNCATE TABLE resolucion_dian");

        $query = $this->db->query("DELETE FROM domicilios");
        $query = $this->db->query("TRUNCATE TABLE domicilios");

        $query = $this->db->query("DELETE FROM domicilio_historial");
        $query = $this->db->query("TRUNCATE TABLE domicilio_historial");

		$query = $this->db->query("DELETE FROM control_ambiental_hrsnot");
		$query = $this->db->query("TRUNCATE TABLE control_ambiental_hrsnot");

		$query = $this->db->query("DELETE FROM control_ambiental_notific");
		$query = $this->db->query("TRUNCATE TABLE control_ambiental_notific");

		$query = $this->db->query("DELETE FROM control_ambiental_detalle");
		$query = $this->db->query("TRUNCATE TABLE control_ambiental_detalle");

		$query = $this->db->query("DELETE FROM control_ambiental");
		$query = $this->db->query("TRUNCATE TABLE control_ambiental");


        /**
         * datos de producto
         */

        /*
        $query = $this->db->query("DELETE FROM unidades_has_precio");
        $query = $this->db->query("TRUNCATE TABLE unidades_has_precio");

        $query = $this->db->query("DELETE FROM unidades_has_producto");
        $query = $this->db->query("TRUNCATE TABLE unidades_has_producto");

        $query = $this->db->query("DELETE FROM producto_codigo_barra");
        $query = $this->db->query("TRUNCATE TABLE producto_codigo_barra");

        $query = $this->db->query("DELETE FROM paquete_has_prod");
        $query = $this->db->query("TRUNCATE TABLE paquete_has_prod");

        $query = $this->db->query("DELETE FROM producto_has_componente");
        $query = $this->db->query("TRUNCATE TABLE producto_has_componente");

        $query = $this->db->query("DELETE FROM producto");
        $query = $this->db->query("TRUNCATE TABLE producto");
        */

        $query = $this->db->query("SET FOREIGN_KEY_CHECKS = 1;");

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;

    }

    public function getDatabaseVersion(){
        $this->db->select('*');
        $this->db->from('migrations');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }


}

?>
