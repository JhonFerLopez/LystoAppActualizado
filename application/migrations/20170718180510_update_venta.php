<?php

class Migration_Update_venta extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE  `venta`   
  CHANGE `id_cliente` `id_cliente` BIGINT(20) UNSIGNED NULL;

';
        $this->db->query($query);


        $query='ALTER TABLE `venta_backup`   
  CHANGE `id_cliente` `id_cliente` BIGINT(20) UNSIGNED NULL;

';
        $this->db->query($query);
    }

    public function down()
    {

    }
}