<?php

class Migration_alter_configuraciones1 extends CI_Migration
{
    public function up()
    {

        $this->db->query("ALTER TABLE `configuraciones`   
  CHANGE `config_value` `config_value` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL;");

    }

    public function down()
    {

    }
}