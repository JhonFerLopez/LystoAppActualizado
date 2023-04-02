<?php

class Migration_Update_venta13 extends CI_Migration
{
	public function up()
	{
		$this->db->query("
			ALTER TABLE `venta`   
			ADD COLUMN `fe_issue_date` DATE NULL AFTER `fe_XmlFileName`;
		");

		$this->db->query("
			ALTER TABLE `venta_backup`   
			ADD COLUMN `fe_issue_date` DATE NULL AFTER `fe_XmlFileName`;
		");
	}

	public function down()
	{

	}
}
