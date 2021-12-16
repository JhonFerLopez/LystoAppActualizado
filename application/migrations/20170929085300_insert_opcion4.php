<?php

class Migration_Insert_opcion4 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(69, 1, 'paramrap', 'Parametrización rápida');
");
    }

    public function down()
    {

    }
}