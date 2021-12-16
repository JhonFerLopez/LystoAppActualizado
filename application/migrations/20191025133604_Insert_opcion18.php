<?php

class Migration_Insert_opcion18 extends CI_Migration
{
    public function up()
    {
		$this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7 , 'rep_ventas_por_hora', 'Ventas por Hora') ;
");
    }

    public function down()
    {

    }
}
