<?php

class Migration_Update_venta9 extends CI_Migration
{
    public function up()
    {
		$this->db->query("
ALTER TABLE `venta`   
	ADD COLUMN `uuid` VARCHAR(255) NULL COMMENT 'id que retorna la dian en la facturacion electronica' AFTER `nota`;
	



");

        $this->db->query("
ALTER TABLE `venta_backup`   
	ADD COLUMN `uuid` VARCHAR(255) NULL COMMENT 'id que retorna la dian en la facturacion electronica' AFTER `nota`;



");
    }

    public function down()
    {

    }
}
