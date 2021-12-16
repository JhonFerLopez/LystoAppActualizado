<?php

class Migration_update_credito4 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `credito`   
  ADD COLUMN `credito_fecha` DATETIME NULL AFTER `credito_dias`;

 ";
        $this->db->query($query);



    }

    public function down()
    {

    }
}