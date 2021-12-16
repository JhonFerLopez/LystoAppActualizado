<?php

class Migration_update_usuario4 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE usuario   
  CHANGE `emai` `imei` VARCHAR(250) CHARSET utf8 COLLATE utf8_general_ci NULL;

";
        $this->db->query($query);

    }

    public function down()
    {

    }
}