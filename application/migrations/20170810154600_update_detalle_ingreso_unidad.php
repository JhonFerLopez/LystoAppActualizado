<?php

class Migration_Update_detalle_ingreso_unidad extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE detalle_ingreso_unidad ADD 
COLUMN detalle_ingreso_unidad_id BIGINT PRIMARY KEY AUTO_INCREMENT UNIQUE FIRST
';
        $this->db->query($query);

    }

    public function down()
    {

    }
}