<?php

class Migration_Insert_opcion6 extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 2, 'cajas_abiertas', 'Cajas abiertas');
");

    }

    public function down()
    {

    }
}