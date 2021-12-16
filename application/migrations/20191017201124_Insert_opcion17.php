<?php

class Migration_Insert_opcion17 extends CI_Migration
{
    public function up()
    {
		$this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7 , 'productos_mas_vendidos', 'Productos Mas Vendidos') ;
");
    }

    public function down()
    {

    }
}
