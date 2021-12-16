<?php

class Migration_Insert_unidades extends CI_Migration
{
    public function up()
    {



        $this->db->query("

INSERT INTO `unidades` (`id_unidad`, `nombre_unidad`, `estatus_unidad`, `abreviatura`, `orden`) VALUES

(1, 'CAJA', 1, 'CJA', '1'),
(2, 'BLISTER', 1, 'BLIS', '2'),
(3, 'UNIDAD', 1, 'UN', '3')

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM unidades");
    }
}