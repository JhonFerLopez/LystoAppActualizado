<?php

class Migration_Insert_opcion_grupo3 extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `opcion_grupo` (`grupo`, `Opcion`, `var_opcion_usuario_estado`) VALUES
(1, 68, 1);
");
    }

    public function down()
    {

    }
}