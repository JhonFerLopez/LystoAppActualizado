<?php

class Migration_Insert_opcion3 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(68, 3, 'traslado', 'Traslado');
");
    }

    public function down()
    {

    }
}