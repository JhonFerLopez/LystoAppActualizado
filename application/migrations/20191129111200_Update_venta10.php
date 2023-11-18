<?php

class Migration_Update_venta10 extends CI_Migration
{
    public function up()
    {
		$this->db->query("
ALTER TABLE `venta`   
	ADD COLUMN `zipkey` VARCHAR(255) NULL AFTER `uuid`;

	



");

        $this->db->query("
ALTER TABLE `venta_backup`   
	ADD COLUMN `zipkey` VARCHAR(255) NULL AFTER `uuid`;



");
    }

    public function down()
    {

    }
}
