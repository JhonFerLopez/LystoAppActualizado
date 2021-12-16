<?php

class Migration_Insert_tipo_anulacion extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `tipo_anulacion` (`tipo_anulacion_id`, `tipo_anulacion_nombre`, `deleted_at`) VALUES
(1, 'ANULACION NORMAL', NULL),
(2, 'NO EXISTE LA DIRECCIóN ', NULL),
(3, 'EL CLIENTE NO QUISO LLEVAR EL PRODUCTO', NULL);


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM tipo_anulacion");
    }
}