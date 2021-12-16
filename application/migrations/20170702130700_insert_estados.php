<?php

class Migration_Insert_estados extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `estados` (`estados_id`, `estados_nombre`, `pais_id`) VALUES
(5, 'Valle del cauca', 3);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM estados");
    }
}