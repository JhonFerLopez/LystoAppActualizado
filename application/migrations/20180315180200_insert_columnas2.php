<?php

class Migration_Insert_columnas2 extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `columnas` (`nombre_columna`, `nombre_join`, `nombre_mostrar`, `tabla`, `mostrar`, `activo`, `orden`) VALUES
('costo_unitario', 'costo_unitario', 'Costo Unitario', 'producto', 0, 0, 14),
('producto_impuesto', 'porcentaje_impuesto', 'IVA %', 'producto', 0, 0, 15);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM columnas where nombre_columna='costo_unitario' ");
        $this->db->query(" DELETE FROM columnas where nombre_columna='producto_impuesto' ");
    }
}