<?php

class Migration_Insert_tipo_venta extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `tipo_venta` (`tipo_venta_id`, `tipo_venta_nombre`, `solicita_cod_vendedor`, `genera_datos_cartera`, `admite_datos_cliente`, `datos_adic_clientes`, `genera_control_domicilios`, `maneja_formas_pago`, `liquida_iva`, `maneja_descuentos`, `aproximar_precio`, `documento_generar`, `numero_copias`, `opciones_call_center`, `deleted_at`, `condicion_pago`) VALUES
(1, 'MOSTRADOR PERSONALIZADA', 1, 0, 1, 1, 0, 0, 1, 1, 50, 'FACTURA', 1, 0, NULL, 1),
(2, 'MOSTRADOR', 1, 0, 0, 0, 0, 1, 1, 0, 50, 'FACTURA', 1, 0, NULL, 1),
(3, 'VENTA A CREDITO', 1, 0, 1, 0, 0, 0, 1, 0, 50, 'FACTURA', 2, 0, NULL, 2),
(4, 'DOMICILIOS', 1, 0, 1, 0, 0, 0, 1, 1, 50, 'FACTURA', 1, 0, NULL, NULL);


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM tipo_venta");
    }
}