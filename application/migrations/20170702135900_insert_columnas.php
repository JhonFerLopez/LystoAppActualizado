<?php

class Migration_Insert_columnas extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `columnas` (`nombre_columna`, `nombre_join`, `nombre_mostrar`, `tabla`, `mostrar`, `activo`, `id_columna`, `orden`) VALUES
('producto_id', 'producto_id', 'ID', 'producto', 1, 0, 35, 1),
('producto_nombre', 'producto_nombre', 'Nombre', 'producto', 1, 0, 37, 3),
('produto_grupo', 'nombre_grupo', 'Grupo', 'producto', 0, 1, 40, 6),
('producto_activo', 'producto_activo', 'Activo', 'producto', 0, 1, 53, 5),
('producto_clasificacion', 'clasificacion_nombre', 'Clasificacion', 'producto', 0, 1, 59, 7),
('producto_tipo', 'tipo_prod_nombre', 'Tipo Producto', 'producto', 0, 1, 60, 8),
('producto_codigo_interno', 'producto_codigo_interno', 'Codigo del Producto', 'producto', 1, 1, 61, 2),
('producto_sustituto', 'producto_sustituto', 'Sustituto', 'producto', 0, 1, 63, 11),
('producto_mensaje', 'producto_mensaje', 'Mensaje', 'producto', 0, 1, 65, 13),
('producto_componente', 'componente_nombre', 'Componente o Droga', 'producto', 1, 1, 66, 9),
('producto_ubicacion_fisica', 'ubicacion_nombre', 'Ubicación Física', 'producto', 0, 1, 67, 10);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM columnas");
    }
}