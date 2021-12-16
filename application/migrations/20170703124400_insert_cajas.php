<?php

class Migration_Insert_cajas extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `caja` (`caja_id`, `alias`, `status`) VALUES
(1, 'CAJA1', 1)

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM caja");
    }
}