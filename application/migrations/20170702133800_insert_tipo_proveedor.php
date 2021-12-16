<?php

class Migration_Insert_tipo_proveedor extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `tipo_proveedor` (`tipo_proveedor_id`, `tipo_proveedor_nombre`, `deleted_at`) VALUES
(1, 'MAYORISTAS DE MEDICAMENTOS', NULL);


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM tipo_proveedor");
    }
}