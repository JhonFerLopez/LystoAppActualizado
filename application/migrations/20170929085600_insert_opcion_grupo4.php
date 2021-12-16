<?php

class Migration_Insert_opcion_grupo4 extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `opcion_grupo` (`grupo`, `Opcion`, `var_opcion_usuario_estado`) VALUES
(1, 69, 1);
");
    }

    public function down()
    {

    }
}