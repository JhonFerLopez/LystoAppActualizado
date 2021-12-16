<?php

class Migration_Update_detalle_venta_devolucion3 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `detalle_venta_devolucion`   
  ADD COLUMN `porcentaje_impuesto` FLOAT NULL AFTER `venta_devolucion_id`; ';
        $this->db->query($query);


        $query = 'ALTER TABLE `detalle_venta_devolucion`   
  ADD COLUMN `impuesto` FLOAT NULL AFTER `porcentaje_impuesto`; ';
        $this->db->query($query);


        $query = 'ALTER TABLE `detalle_venta_devolucion`   
  ADD COLUMN `descuento` FLOAT NULL AFTER `impuesto`;';
        $this->db->query($query);

    }

    public function down()
    {

    }
}