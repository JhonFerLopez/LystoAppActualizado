<?php

class Migration_update_usuario3 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `usuario` 
  ADD COLUMN `emai` VARCHAR(250) NULL AFTER `celular`;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}