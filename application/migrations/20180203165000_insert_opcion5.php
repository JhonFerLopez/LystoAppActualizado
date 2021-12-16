<?php

class Migration_Insert_opcion5 extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(70, 30, 'system_logs', 'Logs del sistema');
");

    }

    public function down()
    {

    }
}