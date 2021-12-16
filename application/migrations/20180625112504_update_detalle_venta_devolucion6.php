<?php

class Migration_Update_detalle_venta_devolucion6 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `detalle_venta_devolucion`   
  ADD COLUMN `otro_impuesto` DECIMAL(18,2) NULL AFTER `impuesto`,
  ADD COLUMN `porcentaje_otro_impuesto` DECIMAL(18,2) NULL AFTER `otro_impuesto`;

";
        $this->db->query($query);


    }

    public function down()
    {

    }
}