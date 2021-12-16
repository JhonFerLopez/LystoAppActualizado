<?php

class Migration_Insert_configuraciones2 extends CI_Migration
{
    public function up()
    {

        $fecha_inicio = date('Y-m-d H:i:s');

        $this->db->query("
insert  into `configuraciones`(`config_id`,`config_key`,`config_value`) values 
(null,'FECHA_INICIO','$fecha_inicio');");
    }

    public function down()
    {

    }
}