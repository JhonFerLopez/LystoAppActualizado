<?php

class Migration_Alter_tipo_venta extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `tipo_venta`   
  DROP COLUMN `genera_datos_cartera`;


';
        $this->db->query($query);

    }

    public function down()
    {

    }
}