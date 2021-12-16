<?php

class Migration_Update_traslado extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE  `traslado`   
  ADD COLUMN `cant_productos` INT(10) NULL AFTER `fecha`;
';
        $this->db->query($query);

    }

    public function down()
    {

    }
}