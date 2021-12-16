<?php

class Migration_Update_venta5 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `venta`   
  ADD COLUMN `gravado` DECIMAL(18,2) NULL AFTER `subtotal`,
  ADD COLUMN `excluido` DECIMAL(18,2) NULL AFTER `gravado`;

';
        $this->db->query($query);


        $query='ALTER TABLE `venta_backup`   
  ADD COLUMN `gravado` DECIMAL(18,2) NULL AFTER `subtotal`,
  ADD COLUMN `excluido` DECIMAL(18,2) NULL AFTER `gravado`;


';
        $this->db->query($query);
    }

    public function down()
    {

    }
}