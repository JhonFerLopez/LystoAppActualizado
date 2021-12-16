<?php

class Migration_Update_detalle_venta4 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `detalle_venta`   
  CHANGE `impuesto` `impuesto` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);

        $query = 'ALTER TABLE `detalle_venta`   
  CHANGE `descuento` `descuento` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);

        $query = 'ALTER TABLE `detalle_venta`   
  CHANGE `subtotal` `subtotal` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);

        $query = 'ALTER TABLE `detalle_venta`   
  CHANGE `porcentaje_impuesto` `porcentaje_impuesto` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);

        $query = 'ALTER TABLE `detalle_venta`   
   CHANGE `total` `total` DECIMAL(18,2) NULL;
 ';
        $this->db->query($query);


        $query = 'ALTER TABLE `detalle_venta`   
  CHANGE `desc_porcentaje` `desc_porcentaje` DECIMAL(18,2) NULL   COMMENT \'porcentaje de descuento aplicado al producto, si no tiene nada quiere decir que el descuento fue valor, sino, el descuento fue por porcentaje\';

 ';
        $this->db->query($query);

    }

    public function down()
    {

    }
}