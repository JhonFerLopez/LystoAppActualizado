<?php

class Migration_Insert_configuraciones6 extends CI_Migration
{
    public function up()
    {


        $this->db->query("
insert  into `configuraciones`(`config_key`,`config_value`) values 
('PEDIR_VALOR_CIERRE_CAJA','SI');");
    }

    public function down()
    {

    }
}