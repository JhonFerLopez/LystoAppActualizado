<?php

class Migration_Insert_opcion11 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` ( `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 5, 'clientesdeuda', 'Clientes deuda');
");
    }

    public function down()
    {

    }
}