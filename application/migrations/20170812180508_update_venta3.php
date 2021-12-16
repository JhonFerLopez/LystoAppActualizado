<?php

class Migration_Update_venta3 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `venta`   
  ADD COLUMN `devuelta` BOOLEAN DEFAULT 0  NULL AFTER `cambio`';
        $this->db->query($query);


        $query='ALTER TABLE `venta_backup`   
  ADD COLUMN `devuelta` BOOLEAN DEFAULT 0  NULL AFTER `cambio`;

';
        $this->db->query($query);
    }

    public function down()
    {

    }
}