<?php

class Migration_Insert_grupos_usuarios2 extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `grupos_usuarios` (`nombre_grupos_usuarios`, `status_grupos_usuarios`) VALUES
( 'DOMICILIARIO', 1)
");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM grupos_usuarios");
    }
}