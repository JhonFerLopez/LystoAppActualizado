<?php

class Migration_Insert_opcion16 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` ( `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7, 'compras', 'Compras');
");

    $this->db->query("


INSERT INTO `opcion` ( `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 91, 'informe_detallado', 'Informe detallado de compras');
");
    }

    public function down()
    {

    }
}