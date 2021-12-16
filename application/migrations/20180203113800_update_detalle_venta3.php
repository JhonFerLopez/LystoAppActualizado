<?php

class Migration_Update_detalle_venta3 extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE `detalle_venta`   
  ADD COLUMN `desc_porcentaje` FLOAT NULL   COMMENT 'porcentaje de descuento aplicado al producto, si no tiene nada quiere decir que el descuento fue valor, sino, el descuento fue por porcentaje' AFTER `total`;
 ";
        $this->db->query($query1);
        $query1 = "ALTER TABLE `detalle_venta_backup`   
  ADD COLUMN `desc_porcentaje` FLOAT NULL   COMMENT 'porcentaje de descuento aplicado al producto, si no tiene nada quiere decir que el descuento fue valor, sino, el descuento fue por porcentaje' AFTER `total`;
 ";
        $this->db->query($query1);

    }

    public function down()
    {
    }
}