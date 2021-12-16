<?php

class Migration_Update_detalle_venta_unidad3 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `detalle_venta_unidad`   
  DROP COLUMN `impuesto`, 
  DROP COLUMN `descuento`, 
  DROP COLUMN `subtotal`, 
  DROP COLUMN `porcentaje_impuesto`, 
  DROP COLUMN `total`;




';
        $this->db->query($query);


        $query='ALTER TABLE `detalle_venta_unidad_backup`   
  DROP COLUMN `impuesto`, 
  DROP COLUMN `descuento`, 
  DROP COLUMN `subtotal`, 
  DROP COLUMN `porcentaje_impuesto`, 
  DROP COLUMN `total`;

';
        $this->db->query($query);
    }

    public function down()
    {

    }
}