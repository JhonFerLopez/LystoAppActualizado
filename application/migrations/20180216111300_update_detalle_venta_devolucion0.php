<?php

class Migration_Update_detalle_venta_devolucion0 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE detalle_venta_devolucion  
  ADD COLUMN `venta_devolucion_id` BIGINT(20) NULL AFTER `total`;
';
        $this->db->query($query);



    }

    public function down()
    {

    }
}