<?php

class Migration_Insert_clasificacion extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `clasificacion` (`clasificacion_id`, `clasificacion_nombre`, `deleted_at`) VALUES

(1, 'ANALGESICO', NULL),
(2, 'VACUNAS', NULL),
(3, 'ANTISEPTICO', NULL),
(4, 'ANTIBIOTICO', NULL),
(5, 'ANTINFLAMATORIO', NULL),
(6, 'ANTIHISTAMINICO', NULL),
(7, 'ANESTESICO', NULL),
(8, 'ANTIDEPRESIVO', NULL),
(9, 'DIURETICO', NULL),
(10, 'LAXANTE', NULL),
(11, 'BRONCODILATADOR', NULL),
(12, 'ANTIPIRETICO', NULL),
(13, 'ANTIFUNGICO', NULL);


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM clasificacion");
    }
}