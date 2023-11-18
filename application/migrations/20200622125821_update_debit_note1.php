<?php

class Migration_Update_debit_note1 extends CI_Migration
{
    public function up()
    {

        $query = " ALTER TABLE `credit_note`   
        ADD COLUMN `prefijo` VARCHAR(255) NULL AFTER `uuid`;   ";
        $this->db->query($query);


        
        $query = " ALTER TABLE `credit_note`   
      ADD COLUMN `status` VARCHAR(255) NULL AFTER `reponseDian`;  ";
        $this->db->query($query);


        $query = " ALTER TABLE `debit_note`   
        ADD COLUMN `prefijo` VARCHAR(255) NULL AFTER `uuid`;   ";
        $this->db->query($query);


        
        $query = " ALTER TABLE `debit_note`   
      ADD COLUMN `status` VARCHAR(255) NULL AFTER `reponseDian`;  ";
        $this->db->query($query);


        $query = " ALTER TABLE `debit_note`   
    CHANGE `reponseDian` `reponseDian` LONGTEXT NULL; ";
        $this->db->query($query);

        
        $query = " ALTER TABLE `credit_note`   
    CHANGE `reponseDian` `reponseDian` LONGTEXT NULL; ";
        $this->db->query($query);
    }

    public function down()
    {
    }
}
