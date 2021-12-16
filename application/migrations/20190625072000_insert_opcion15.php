<?php

class Migration_Insert_opcion15 extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 1 , 'control_ambiental', 'Control Ambiental') ;
");

    }

    public function down()
    {

    }
}