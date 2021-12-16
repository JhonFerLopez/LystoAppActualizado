<?php

class Migration_update_credito3 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `credito`   
  ADD COLUMN `id_cliente` BIGINT(20) NULL AFTER `credito_id`;




";
        $this->db->query($query);

    }

    public function down()
    {

    }
}