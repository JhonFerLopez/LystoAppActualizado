<?php

class Migration_Insert_opcion14 extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 74 , 'rep_prom_comp_client', 'Promedio de Compras por Cliente') ;
");

    }

    public function down()
    {

    }
}