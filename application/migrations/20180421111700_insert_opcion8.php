<?php

class Migration_Insert_opcion8 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(83, 7, 'productos_comisionan', 'Productos que comisionan');
");
    }

    public function down()
    {

    }
}