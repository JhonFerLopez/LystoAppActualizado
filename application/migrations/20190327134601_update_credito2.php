<?php

class Migration_update_credito2 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `credito`   
  CHANGE `id_venta` `id_venta` BIGINT(20) UNSIGNED NULL,
  ADD COLUMN `credito_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT AFTER `credito_dias`, 
  DROP PRIMARY KEY,
  ADD PRIMARY KEY (`credito_id`);



";
        $this->db->query($query);

    }

    public function down()
    {

    }
}