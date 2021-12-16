<?php

class Migration_Update_ingreso extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `ingreso` ADD COLUMN tipo_carga VARCHAR(10) DEFAULT "MANUAL"';
        $this->db->query($query);

    }

    public function down()
    {

    }
}