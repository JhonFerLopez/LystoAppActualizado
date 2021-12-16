<?php

class Migration_Insert_into_venta_columas_productos1 extends CI_Migration
{
    public function up()
    {
        $this->db->query("
INSERT INTO `venta_columas_productos` (`nombre_columna`, `nombre_mostrar`, `mostrar`, `orden`) VALUES
( 'codigo' , 'Código', 1,2),
('nombre', 'Nombre',1,3),
('ubicacion_fisica','Ubicación',1,4),
('impuesto','Iva',1,5),
( 'principio_activo','Principio Activo',1,6),
( 'stock','Stock',1,7),
( 'precios','Precios',1,8),
('porcent_utilidad','Porcentaje de Utilidad',1,9),
('porcent_comision','% Comisión',0,10);
");

    }

    public function down()
    {

    }
}