<?php

class Migration_Insert_grupos_usuarios extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `grupos_usuarios` (`id_grupos_usuarios`, `nombre_grupos_usuarios`, `status_grupos_usuarios`) VALUES
(1, 'ADMINISTRADOR', 1),
(2, 'VENDEDOR', 1),
(3, 'CAJERO', 1)
");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM grupos_usuarios");
    }
}