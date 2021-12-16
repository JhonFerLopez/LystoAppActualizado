<?php

class Migration_Update_ajusteinventario extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `ajusteinventario`   
  CHANGE `tipo_ajuste` `tipo_ajuste` BIGINT(20) UNSIGNED NULL;';
        $this->db->query($query);
    }

    public function down()
    {

    }
}