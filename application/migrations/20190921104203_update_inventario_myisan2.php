<?php


class Migration_Update_inventario_myisan2 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `inventario`  
  ENGINE=MYISAM;");


        $this->db->query("OPTIMIZE TABLE inventario;
");

    }

    public function down()
    {

    }
}