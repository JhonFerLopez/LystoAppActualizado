<?php

class Migration_Update_detalle_venta extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `detalle_venta`   
  DROP COLUMN `detalle_importe`;


';
        $this->db->query($query);


        $query='ALTER TABLE `detalle_venta_backup`   
  DROP COLUMN `detalle_importe`;


';
        $this->db->query($query);
    }

    public function down()
    {

    }
}