<?php

class Migration_Insert_impuestos extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `impuestos` (`id_impuesto`, `nombre_impuesto`, `porcentaje_impuesto`, `estatus_impuesto`) VALUES

(1, 'IVA 19', 19, 1);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM impuestos");
    }
}