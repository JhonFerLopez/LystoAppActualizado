<?php

class Migration_Updategrupo2 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `grupos`   
  ADD COLUMN `nivel` INT(2) NULL AFTER `estatus_grupo`,
  ADD COLUMN `codigo` VARCHAR(20) NULL AFTER `nivel`;
';
        $this->db->query($query);

    }

    public function down()
    {

    }
}