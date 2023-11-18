<?php

class Migration_Update_venta_dian4 extends CI_Migration
{
    public function up()
    {


       

        $query = " ALTER TABLE `venta_backup`   
        ADD COLUMN `fe_status` VARCHAR(255) NULL AFTER `fe_issue_date`    
    
";


$this->db->query($query);
    }

    public function down()
    {
    }
}
