<?php

class Migration_Update_detalle_venta_devolucion5 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `detalle_venta_devolucion`   
  CHANGE `precio` `precio` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);

        $query = 'ALTER TABLE `detalle_venta_devolucion`   
  CHANGE `precio_sin_iva` `precio_sin_iva` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);

        $query = 'ALTER TABLE `detalle_venta_devolucion`   
  CHANGE `subtotal` `subtotal` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);

        $query = 'ALTER TABLE `detalle_venta_devolucion`   
  CHANGE `porcentaje_impuesto` `porcentaje_impuesto` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);

        $query = 'ALTER TABLE `detalle_venta_devolucion`   
   CHANGE `total` `total` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);


        $query = 'ALTER TABLE `detalle_venta_devolucion`   
  CHANGE `impuesto` `impuesto` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);

    }

    public function down()
    {

    }
}