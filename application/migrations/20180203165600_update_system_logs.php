<?php

class Migration_Update_system_logs extends CI_Migration
{
    public function up()
    {


        $query1 = "ALTER TABLE `system_logs`   
  ADD COLUMN `ip` VARCHAR(255) NULL AFTER `data_after`;
";
        $this->db->query($query1);
    }

    public function down()
    {

    }
}