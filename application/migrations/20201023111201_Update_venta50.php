<?php

class Migration_Update_venta50 extends CI_Migration
{
        public function up()
        {
                
                $this->db->query("
        ALTER TABLE `venta`   
	ADD COLUMN `fe_type_enviroment` INT NULL AFTER `fe_status`; ");

                $this->db->query("
                ALTER TABLE `venta_backup`   
                ADD COLUMN `fe_type_enviroment` INT NULL AFTER `fe_status`; 
        ");


                $this->db->query("
                ALTER TABLE `credit_note`   
                ADD COLUMN `fe_type_enviroment` INT NULL AFTER `reponseDian`;
        


        ");

                $this->db->query("
                ALTER TABLE `debit_note`   
                ADD COLUMN `fe_type_enviroment` INT NULL AFTER `reponseDian`;
        
        ");
        }

        public function down()
        {
        }
}
