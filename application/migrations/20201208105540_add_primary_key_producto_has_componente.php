<?php

class Migration_Add_primary_key_producto_has_componente extends CI_Migration
{
    public function up()
    {
        $query = "ALTER TABLE producto_has_componente ADD producto_has_componente_id bigint PRIMARY KEY AUTO_INCREMENT;";

        $this->db->query($query);
    }

    public function down()
    {
        $query = "ALTER TABLE `producto_has_componente` DROP COLUMN `producto_has_componente_id`;";
        $this->db->query($query);
    }
}