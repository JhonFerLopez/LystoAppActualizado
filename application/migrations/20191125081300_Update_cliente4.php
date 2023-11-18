<?php

class Migration_Update_cliente4 extends CI_Migration
{
    public function up()
    {
		$this->db->query("
ALTER TABLE `cliente`   
	ADD COLUMN `merchant_registration` VARCHAR(255) NULL AFTER `create_at`;


");
    }

    public function down()
    {

    }
}
