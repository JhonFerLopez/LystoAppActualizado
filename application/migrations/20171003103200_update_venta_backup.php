<?php

class Migration_Update_venta_backup extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `venta_backup` DROP FOREIGN KEY `venta_backup_ibfk_4`;';
        $this->db->query($query);
        $query = 'ALTER TABLE `venta_backup` ADD CONSTRAINT `venta_backup_ibfk_4` FOREIGN KEY (`caja_id`) REFERENCES `status_caja`(`id`);
;';
        $this->db->query($query);

    }

    public function down()
    {

    }
}