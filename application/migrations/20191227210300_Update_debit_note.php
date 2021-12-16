<?php

class Migration_Update_debit_note extends CI_Migration
{
    public function up()
    {


        $this->db->query("
ALTER TABLE `debit_note`   
	ADD COLUMN `zipkey` VARCHAR(255) NULL AFTER `venta_id`;

");

        $this->db->query("
ALTER TABLE `debit_note`   
ADD COLUMN `reponseDian` TEXT NULL AFTER `zipkey`;

");





    }

    public function down()
    {

    }
}
