<?php

class Migration_Insert_configuraciones extends CI_Migration
{
    public function up()
    {


        $fecha_fin_plan = new DateTime();
        date_add($fecha_fin_plan, date_interval_create_from_date_string(1 . ' month'));

        $fecha_fin_plan = date_format($fecha_fin_plan, 'Y-m-d H:i:s');




        $this->db->query("

insert  into `configuraciones`(`config_id`,`config_key`,`config_value`) values (1,'EMPRESA_NOMBRE','EMPRESA_NOMBRE'),
(2,'EMPRESA_DIRECCION','EMPRESA_DIRECCION'),(3,'EMPRESA_TELEFONO','EMPRESA_TELEFONO'),
(5,'MONEDA','PESOS'),
(6,'EMPRESA_PAIS','3'),(7,'MENSAJE_FACTURA','GRACIAS POR SU COMPRA'),(8,'CALCULO_PRECIO_VENTA','MATEMATICO'),
(9,'CORRELATIVO_PRODUCTO','NO'),(10,'REGIMEN_CONTRIBUTIVO','1'),(11,'REPRESENTANTE_LEGAL','REPRESENTATE LEGAL'),(12,'NIT','NIT'),
(13,'CODIGO_COOPIDROGAS','CODIGO_COOPIDROGAS'),(14,'MOSTRAR_SIN_STOCK','0'),(15,'CALCULO_UTILIDAD','COSTO_PROMEDIO'),(16,'BODEGA_PRINCIPAL','1')
,(17,'SYS_EXP_DAT','$fecha_fin_plan'),(18,'IMPRESORA','smb://Principal-PC/Generic'),(19,'PANTALLA_COMPLETA','SI');
");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM configuraciones");
    }
}