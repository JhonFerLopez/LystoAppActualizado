<?php

class Migration_Update_detalle_venta_unidad4 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `detalle_venta_unidad`   
  ADD COLUMN `costo_promedio` FLOAT NULL AFTER `costo`;


';
        $this->db->query($query);


        $query = 'ALTER TABLE `detalle_venta_unidad_backup`   
  ADD COLUMN `costo_promedio` FLOAT NULL AFTER `costo`;


';
        $this->db->query($query);

    }

    public function down()
    {

    }
}