<?php

class Migration_Insert_configuraciones3 extends CI_Migration
{
    public function up()
    {

        $this->db->query("
insert  into `configuraciones`(`config_id`,`config_key`,`config_value`) values 
(null,'ID_BACKUP_DRIVE',null);");
    }

    public function down()
    {

    }
}