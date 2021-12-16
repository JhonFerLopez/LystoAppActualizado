<?php

class Migration_Update_detalle_venta_unidad_backup extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE `detalle_venta_unidad_backup`   
  ADD COLUMN `precio_sin_iva` FLOAT NULL AFTER `precio`;";
        $this->db->query($query1);
    }

    public function down()
    {

    }
}