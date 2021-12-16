<?php

class Migration_Update_venta_anular2 extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE `venta_anular`   
  CHANGE `tipo_anulación` `tipo_anulacion` BIGINT(20) UNSIGNED NOT NULL;
";
     $this->db->query($query1);

    }

    public function down()
    {

    }
}