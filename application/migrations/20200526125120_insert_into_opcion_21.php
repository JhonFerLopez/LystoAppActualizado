<?php

class Migration_Insert_into_opcion_21 extends CI_Migration
{
    public function up()
    {
        $exists = $this->db->query("select * from opcion where cOpcionNombre='notificaciones' ");
        $exists = $exists->row();

        if (!isset($exists->nOpcionClase)) {
            $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 0 , 'notificaciones', 'Notificaciones') ;
");
        }
    }

    public function down()
    {

    }
}