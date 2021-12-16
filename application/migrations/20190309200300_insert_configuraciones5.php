<?php

class Migration_Insert_configuraciones5 extends CI_Migration
{
    public function up()
    {


        $this->db->query("
insert  into `configuraciones`(`config_key`,`config_value`) values 
('VENDEDOR_EN_FACTURA','NOMBRE');");
    }

    public function down()
    {

    }
}