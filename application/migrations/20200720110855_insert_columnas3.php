<?php

class Migration_Insert_columnas3 extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `columnas` (`nombre_columna`, `nombre_join`, `nombre_mostrar`, `tabla`, `mostrar`, `activo`, `orden`) VALUES
('producto_codigo_barra', 'codigo_barra', 'Códigos de barra', 'producto', 0, 0, 16);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM columnas where nombre_columna='producto_codigo_barra' ");
    }
}