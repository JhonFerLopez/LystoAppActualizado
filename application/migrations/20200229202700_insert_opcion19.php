<?php

class Migration_Insert_opcion19 extends CI_Migration
{
    public function up()
    {

        $exists=$this->db->query("select * from opcion where cOpcionNombre='rep_prod_sin_rotacion' ");
        $exists=$exists->row();

        if(!isset($exists->nOpcionClase)){
            $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7 , 'rep_prod_sin_rotacion', 'Productos con menor Rotación') ;
");
        }


    }

    public function down()
    {

    }
}