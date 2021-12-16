<?php

class Migration_Update_venta6 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `venta`   
  ADD COLUMN `regimen_contributivo` BIGINT UNSIGNED NULL AFTER `devuelta`,
  ADD FOREIGN KEY (`regimen_contributivo`) REFERENCES `regimen`(`regimen_id`);

';
        $this->db->query($query);


        $query = 'ALTER TABLE `venta_backup`   
  ADD COLUMN `regimen_contributivo` BIGINT UNSIGNED NULL AFTER `devuelta`,
  ADD FOREIGN KEY (`regimen_contributivo`) REFERENCES `regimen`(`regimen_id`);

';
        $this->db->query($query);

    }

    public function down()
    {

    }
}