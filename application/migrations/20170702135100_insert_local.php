<?php

class Migration_Insert_local extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `local` (`int_local_id`, `local_nombre`, `local_status`) VALUES
(1, 'Drogueria Principal', 1),
(2, 'Bodega', 1);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM local");
    }
}