<?php

class Migration_Insert_into_opcion_22 extends CI_Migration
{
    public function up()
    {
        $exists = $this->db->query("select * from opcion where cOpcionNombre='facturacionelectronica' ");
        $exists = $exists->row();

        if (!isset($exists->nOpcionClase)) {
            $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 8 , 'facturacionelectronica', 'Facturacion electronica') ;
");
        }
    }

    public function down()
    {

    }
}