<?php

class Migration_Insert_configuraciones7 extends CI_Migration
{
	public function up()
	{


		$this->db->query("
insert  into `configuraciones`(`config_key`,`config_value`) values 
('KEY_RECIBE_NOTIF_CONTROL_AMB','');");
	}

	public function down()
	{

	}
}
