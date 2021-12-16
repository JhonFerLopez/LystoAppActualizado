<?php

class Migration_Insert_ciudades extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `ciudades` (`ciudad_id`, `ciudad_nombre`, `estado_id`) VALUES
(11, 'Cali', 5);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM ciudades");
    }
}