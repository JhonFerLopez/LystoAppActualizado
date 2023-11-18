<?php

class Migration_Update_venta_addnota extends CI_Migration
{
    public function up()
    {
		$this->db->query("
ALTER TABLE `venta`   
	ADD COLUMN `nota` TEXT NULL AFTER `total_otros_impuestos`;

");
    }

    public function down()
    {

    }
}
