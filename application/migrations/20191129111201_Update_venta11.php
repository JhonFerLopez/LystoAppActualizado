<?php

class Migration_Update_venta11 extends CI_Migration
{
    public function up()
    {
        $this->db->query("
ALTER TABLE `venta`   
	ADD COLUMN `fe_resolution_id` BIGINT(20) NULL AFTER `uuid`;


");

        $this->db->query("
ALTER TABLE `venta_backup`   
	ADD COLUMN `fe_resolution_id` BIGINT(20) NULL AFTER `uuid`;
");


        $this->db->query("
ALTER TABLE `venta`   
	ADD COLUMN `fe_numero` BIGINT(20) NULL AFTER `fe_resolution_id`;


");

        $this->db->query("
ALTER TABLE `venta_backup`   
	ADD COLUMN `fe_numero` BIGINT(20) NULL AFTER `fe_resolution_id`;
");


        $this->db->query("
ALTER TABLE `venta`   
		ADD COLUMN `reponseDian` TEXT NULL AFTER `fe_numero`;

");

        $this->db->query("
ALTER TABLE `venta_backup`   
	ADD COLUMN `reponseDian` TEXT NULL AFTER `fe_numero`;
");

    }

    public function down()
    {

    }
}
