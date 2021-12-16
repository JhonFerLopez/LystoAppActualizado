<?php

class Migration_Updategrupo3 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `grupos`   
  CHANGE `nivel` `nivel_id` BIGINT(20) UNSIGNED NULL;
';
        $this->db->query($query);



    }

    public function down()
    {

    }
}

