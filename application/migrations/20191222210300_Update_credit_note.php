<?php

class Migration_Update_credit_note extends CI_Migration
{
    public function up()
    {

        $this->db->query("
ALTER TABLE `credit_note`   
	ADD COLUMN `type` ENUM('ANULACION','DEVOLUCION') NULL AFTER `venta_id`;

");

        $this->db->query("
ALTER TABLE `credit_note`   
	ADD COLUMN `zipkey` VARCHAR(255) NULL AFTER `type`;

");

        $this->db->query("
ALTER TABLE `credit_note`   
ADD COLUMN `reponseDian` TEXT NULL AFTER `zipkey`;

");





    }

    public function down()
    {

    }
}
