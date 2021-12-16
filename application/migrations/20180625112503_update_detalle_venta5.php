<?php

class Migration_Update_detalle_venta5 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `detalle_venta`   
  ADD COLUMN `otro_impuesto` DECIMAL(18,2) NULL AFTER `desc_porcentaje`,
  ADD COLUMN `porcentaje_otro_impuesto` DECIMAL(18,2) NULL AFTER `otro_impuesto`;


";
        $this->db->query($query);


        $query="ALTER TABLE `detalle_venta_backup`   
  ADD COLUMN `otro_impuesto` DECIMAL(18,2) NULL AFTER `desc_porcentaje`,
  ADD COLUMN `porcentaje_otro_impuesto` DECIMAL(18,2) NULL AFTER `otro_impuesto`;


";
        $this->db->query($query);

    }

    public function down()
    {

    }
}