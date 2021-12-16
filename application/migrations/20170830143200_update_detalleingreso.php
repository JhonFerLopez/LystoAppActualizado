<?php

class Migration_Update_detalleingreso extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `detalleingreso`  CHANGE `status` `status` BOOLEAN DEFAULT 0  NULL';
        $this->db->query($query);

    }

    public function down()
    {

    }
}