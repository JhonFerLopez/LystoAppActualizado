<?php

class Migration_Update_detalle_venta_unidad extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `detalle_venta_unidad`   
  ADD COLUMN `descuento` FLOAT NULL AFTER `impuesto`,
  ADD COLUMN `subtotal` FLOAT NULL AFTER `descuento`;
;

';
        $this->db->query($query);


        $query='ALTER TABLE `detalle_venta_unidad_backup`   
  ADD COLUMN `descuento` FLOAT NULL AFTER `impuesto`,
  ADD COLUMN `subtotal` FLOAT NULL AFTER `descuento`;

';
        $this->db->query($query);
    }

    public function down()
    {

    }
}