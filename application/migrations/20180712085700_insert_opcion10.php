<?php

class Migration_Insert_opcion10 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(85, 2, 'control_domicilios', 'Control de Domicilios');
");
    }

    public function down()
    {

    }
}