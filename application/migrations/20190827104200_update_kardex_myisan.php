<?php


class Migration_Update_kardex_myisan extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `kardex`  
  ENGINE=MYISAM;
");



    }

    public function down()
    {

    }
}