<?php

class Migration_Insert_pais extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `pais` (`id_pais`, `nombre_pais`) VALUES
(3, 'Colombia');

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM pais");
    }
}