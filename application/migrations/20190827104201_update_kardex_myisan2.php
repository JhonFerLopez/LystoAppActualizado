<?php


class Migration_Update_kardex_myisan2 extends CI_Migration
{
    public function up()
    {
        $this->db->query("OPTIMIZE TABLE kardex;
");



    }

    public function down()
    {

    }
}