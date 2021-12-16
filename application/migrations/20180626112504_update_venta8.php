<?php

class Migration_Update_venta8 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `venta`   
  ADD COLUMN `total_otros_impuestos` DECIMAL(18,2) NULL AFTER `desc_global`;";
        $this->db->query($query);

        $query="ALTER TABLE `venta_backup`   
  ADD COLUMN `total_otros_impuestos` DECIMAL(18,2) NULL AFTER `desc_global`;";
        $this->db->query($query);
    }

    public function down()
    {

    }
}