<?php

class Migration_Add_primary_keys_some_tables extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE unidades_has_precio ADD unidades_has_precio_id bigint PRIMARY KEY AUTO_INCREMENT;";

        $this->db->query($query);

        $query = "ALTER TABLE unidades_has_producto ADD unidades_has_producto_id  bigint PRIMARY KEY AUTO_INCREMENT;";
        $this->db->query($query);

        $query = "ALTER TABLE producto_codigo_barra ADD producto_codigo_barra_id  bigint PRIMARY KEY AUTO_INCREMENT;";
        $this->db->query($query);

        $query = "ALTER TABLE paquete_has_prod ADD paquete_has_prod_id  bigint PRIMARY KEY AUTO_INCREMENT;";
        $this->db->query($query);
    }

    public function down()
    {

        $query = "ALTER TABLE `unidades_has_precio` DROP COLUMN `unidades_has_precio_id`;";
        $this->db->query($query);

        $query = "ALTER TABLE `unidades_has_producto` DROP COLUMN `unidades_has_producto_id`;";
        $this->db->query($query);

        $query = "ALTER TABLE `producto_codigo_barra` DROP COLUMN `producto_codigo_barra_id`;";
        $this->db->query($query);

        $query = "ALTER TABLE `paquete_has_prod` DROP COLUMN `paquete_has_prod_id`;";
        $this->db->query($query);

    }
}