<?php

class Migration_update_usuario2 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `usuario` 
  ADD COLUMN `celular` VARCHAR(250) NULL AFTER `latitud`;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}