<?php

class Migration_Update_detalle_venta_unidad2 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `detalle_venta_unidad`   
  ADD COLUMN `porcentaje_impuesto` FLOAT NULL AFTER `subtotal`,
  ADD COLUMN `total` FLOAT NULL AFTER `porcentaje_impuesto`;




';
        $this->db->query($query);


        $query='ALTER TABLE `detalle_venta_unidad_backup`   
  ADD COLUMN `porcentaje_impuesto` FLOAT NULL AFTER `subtotal`,
  ADD COLUMN `total` FLOAT NULL AFTER `porcentaje_impuesto`;


';
        $this->db->query($query);
    }

    public function down()
    {

    }
}