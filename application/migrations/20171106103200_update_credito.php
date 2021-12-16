<?php

class Migration_Update_credito extends CI_Migration
{

    public function up()
    {


        $query = 'ALTER TABLE `credito`   
  ADD COLUMN `credito_dias` INT NULL AFTER `dec_credito_montodebito`;
';
        $this->db->query($query);


    }

    public function down()
    {

    }
}