<?php

class Migration_Update_customer_dian extends CI_Migration
{
    public function up()
    {


        $query = "  ALTER TABLE `cliente`   
        ADD COLUMN `fe_type_liability` INT NULL AFTER `merchant_registration`;
    
";
        $this->db->query($query);
    }

    public function down()
    {
        $this->dbforge->drop_table('notificaciones');
    }
}
