<?php

class Migration_Alter_tipo_venta2 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `tipo_venta`   
  ADD COLUMN `maneja_impresion` BOOLEAN DEFAULT 0  NULL AFTER `liquida_iva`;



';
        $this->db->query($query);

    }

    public function down()
    {

    }
}