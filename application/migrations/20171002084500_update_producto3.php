<?php

class Migration_Update_producto3 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `producto`   
  CHANGE `is_paquete` `is_paquete` TINYINT(1) DEFAULT 0  NULL;';
        $this->db->query($query);

    }

    public function down()
    {

    }
}