<?php

class Migration_Alter_table_gastos extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `gastos`   
        ADD COLUMN `user_id` BIGINT NULL AFTER `status_gastos`;
    ");
        $this->db->query("ALTER TABLE `gastos`   
     
        ADD COLUMN `caja_id` BIGINT NULL AFTER `user_id`;
    
    ");
        $this->db->query("ALTER TABLE `gastos`   
     
     ADD COLUMN `created_at` DATETIME NULL AFTER `caja_id`;

    
    ");
        $this->db->query("ALTER TABLE `gastos`   
     
     CHANGE `total` `total` DECIMAL(18,2) NULL;

    
    ");
    }

    public function down()
    {
       // $this->db->query("   ALTER TABLE `opcion` DROP COLUMN `user_id`;");
    }
}
