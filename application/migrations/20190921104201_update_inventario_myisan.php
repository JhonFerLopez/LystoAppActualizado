<?php


class Migration_Update_inventario_myisan extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `inventario`   DROP FOREIGN KEY `inventario_ibfk_1`;

");
        $this->db->query("ALTER TABLE `inventario`     DROP FOREIGN KEY `inventario_ibfk_2`;

");
        $this->db->query("ALTER TABLE `inventario`      DROP FOREIGN KEY `inventario_ibfk_3`;

");





    }

    public function down()
    {

    }
}