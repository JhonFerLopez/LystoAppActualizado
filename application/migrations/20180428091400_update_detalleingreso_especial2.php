<?php

class Migration_Update_detalleingreso_especial2 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `detalleingreso_especial`   
  ADD COLUMN `costo_unitario_antes` DECIMAL(18,2) NULL AFTER `costo_total`;
';
        $this->db->query($query);



    }

    public function down()
    {

    }
}