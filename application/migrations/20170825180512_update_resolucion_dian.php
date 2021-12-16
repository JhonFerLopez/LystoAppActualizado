<?php

class Migration_Update_resolucion_dian extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `documento_venta`   
  ADD COLUMN `id_resolucion` BIGINT(20) NULL AFTER `id_venta`;


';


        $this->db->query($query);
    }

    public function down()
    {

    }
}