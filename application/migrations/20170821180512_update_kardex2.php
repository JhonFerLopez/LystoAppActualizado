<?php

class Migration_Update_kardex2 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `kardex`   
  DROP COLUMN `cKardexEstado`;



';

        $this->db->query($query);
    }

    public function down()
    {

    }
}