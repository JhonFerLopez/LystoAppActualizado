<?php

class Migration_update_usuario1 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `usuario` 
  ADD COLUMN `texto_posicion` VARCHAR(250) NULL AFTER `latitud`;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}