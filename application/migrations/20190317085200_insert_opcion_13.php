<?php

class Migration_Insert_opcion_13 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` ( `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(7, 'propiedad_productos', 'Productos por Propiedad');
");
    }

    public function down()
    {

    }
}