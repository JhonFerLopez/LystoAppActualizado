<?php

class Migration_Insert_into_columnas4 extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `columnas` (`nombre_columna`, `nombre_join`, `nombre_mostrar`, `tabla`, `mostrar`, `activo`, `orden`) VALUES
('producto_precios', 'producto_precios', 'Precios', 'producto', 0, 0, 17);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM columnas where nombre_columna='producto_precios' ");
    }
}