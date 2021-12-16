<?php

class Migration_Insert_into_opcion20 extends CI_Migration
{
    public function up()
    {

        $exists=$this->db->query("select * from opcion where cOpcionNombre='modif_fecha_ventas' ");
        $exists=$exists->row();

        if(!isset($exists->nOpcionClase)){
            $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 95 , 'modif_fecha_ventas', 'Modificar Fechas a Ventas') ;
");
        }
    }

    public function down()
    {

    }
}