<?php

class Migration_Insert_opcion2 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(67, 8, 'importar', 'Importar');
");
    }

    public function down()
    {

    }
}