<?php

class Migration_Insert_zonas extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `zonas` (`zona_id`, `zona_nombre`, `ciudad_id`, `status`) VALUES
(1, 'ULPIANO LLOREIDA',  11, 1)

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM zonas");
    }
}