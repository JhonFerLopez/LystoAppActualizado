<?php

class Migration_Update_kardex extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `kardex`   
  CHANGE `stockUManterior` `stockUManterior` TEXT NULL,
  CHANGE `stockUMactual` `stockUMactual` TEXT NULL;
';
        $this->db->query($query);

    }

    public function down()
    {

    }
}