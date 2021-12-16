<?php

class Migration_Update_ajuste_detalle extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE `ajustedetalle`   
  ADD COLUMN `id_ubicacion` BIGINT(20) NULL AFTER `costo`;

";
     $this->db->query($query1);

    }

    public function down()
    {

    }
}