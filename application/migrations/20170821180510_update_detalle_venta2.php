<?php

class Migration_Update_detalle_venta2 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `detalle_venta`   
  ADD COLUMN `impuesto` FLOAT NULL AFTER `id_producto`,
  ADD COLUMN `descuento` FLOAT NULL AFTER `impuesto`,
  ADD COLUMN `subtotal` FLOAT NULL AFTER `descuento`,
  ADD COLUMN `porcentaje_impuesto` FLOAT NULL AFTER `subtotal`,
  ADD COLUMN `total` FLOAT NULL AFTER `porcentaje_impuesto`;


';
        $this->db->query($query);


        $query='ALTER TABLE `detalle_venta_backup`   
  ADD COLUMN `impuesto` FLOAT NULL AFTER `id_producto`,
  ADD COLUMN `descuento` FLOAT NULL AFTER `impuesto`,
  ADD COLUMN `subtotal` FLOAT NULL AFTER `descuento`,
  ADD COLUMN `porcentaje_impuesto` FLOAT NULL AFTER `subtotal`,
  ADD COLUMN `total` FLOAT NULL AFTER `porcentaje_impuesto`;


';
        $this->db->query($query);
    }

    public function down()
    {

    }
}