<?php

class Migration_Insert_ubicacion_fisica extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `ubicacion_fisica` (`ubicacion_id`, `ubicacion_nombre`, `deleted_at`) VALUES

(1, 'ESTANTE 1', NULL),
(2, 'ESTANTE 2', NULL),
(3, 'ESTANTE 3', NULL)


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM ubicacion_fisica ");
    }
}