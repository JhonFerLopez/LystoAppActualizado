<?php

class Migration_update_domicilios1 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `domicilios`   
  CHANGE `usuario_id` `usuario_id` BIGINT(20) UNSIGNED NULL;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}