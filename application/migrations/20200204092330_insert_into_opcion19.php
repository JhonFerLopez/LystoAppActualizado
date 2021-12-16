<?php

class Migration_Insert_into_opcion19 extends CI_Migration
{
    public function up()
    {

        $exists=$this->db->query("select * from opcion where cOpcionNombre='herramientas' ");
        $exists=$exists->row();

        if(!isset($exists->nOpcionClase)){
            $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 0 , 'herramientas', 'Herramientas') ;
");
        }

    }

    public function down()
    {

    }
}