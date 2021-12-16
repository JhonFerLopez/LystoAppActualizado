<?php

class Migration_Insert_into_opcion_23 extends CI_Migration
{
    public function up()
    {
        $exists = $this->db->query("select * from opcion where cOpcionNombre='params_facturacion' ");
        $exists = $exists->row();

        if (!isset($exists->nOpcionClase)) {

            $exists2 = $this->db->query("select * from opcion where cOpcionNombre='venta_columas_productos' ");
            $exists2 = $exists2->row();

            if (!isset($exists2->nOpcionClase)) {
            $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 12 , 'venta_columas_productos', 'Datos del Producto') ;
");
            }

        }
    }

    public function down()
    {

    }
}