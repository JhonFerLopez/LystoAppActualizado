<?php

class Migration_Add_primary_key_detalle_venta_unidad extends CI_Migration
{
    public function up()
    {
        $query = "ALTER TABLE detalle_venta_unidad ADD detalle_venta_unidad_id bigint PRIMARY KEY AUTO_INCREMENT;";

        $this->db->query($query);
    }

    public function down()
    {
        $query = "ALTER TABLE `detalle_venta_unidad` DROP COLUMN `detalle_venta_unidad_id`;";
        $this->db->query($query);
    }
}