<?php

class Migration_Update_venta12 extends CI_Migration
{
    public function up()
    {
        $this->db->query("
ALTER TABLE `venta`   
	CHANGE `reponseDian` `fe_reponseDian` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL;


");

        $this->db->query("
ALTER TABLE `venta_backup`   
	CHANGE `reponseDian` `fe_reponseDian` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL;
");


        $this->db->query("
ALTER TABLE `venta`   
	CHANGE `zipkey` `fe_zipkey` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL;


");

        $this->db->query("
ALTER TABLE `venta_backup`   
	CHANGE `zipkey` `fe_zipkey` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL;
");


        $this->db->query("
ALTER TABLE `venta`   
	ADD COLUMN `fe_XmlFileName` VARCHAR(255) NULL AFTER `fe_zipkey`;

");

        $this->db->query("
ALTER TABLE `venta_backup`   
	ADD COLUMN `fe_XmlFileName` VARCHAR(255) NULL AFTER `fe_zipkey`;
");

    }

    public function down()
    {

    }
}
