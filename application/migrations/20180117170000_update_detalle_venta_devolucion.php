<?php

class Migration_Update_detalle_venta_devolucion extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE `detalle_venta_devolucion`   
  CHANGE `id_detalle_venta` `id_detalle_venta` BIGINT(20) UNSIGNED NULL;
";
     $this->db->query($query1);

    }

    public function down()
    {

    }
}