<?php

class Migration_Insert_opcion9 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(84, 9, 'niveles_grupos', 'Niveles de Grupos');
");
    }

    public function down()
    {

    }
}