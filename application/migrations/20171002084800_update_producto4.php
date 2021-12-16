<?php

class Migration_Update_producto4 extends CI_Migration
{
    public function up()
    {

        $query = 'UPDATE producto SET `is_paquete`=0 WHERE producto.`is_paquete` IS NULL;';
        $this->db->query($query);

    }

    public function down()
    {

    }
}