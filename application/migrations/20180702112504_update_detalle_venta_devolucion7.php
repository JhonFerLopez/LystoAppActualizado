<?php

class Migration_Update_detalle_venta_devolucion7 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `venta_devolucion`   
  CHANGE `subtotal` `subtotal` DECIMAL(18,2) NULL,
  CHANGE `impuesto` `impuesto` DECIMAL(18,2) NULL,
  CHANGE `total` `total` DECIMAL(18,2) NULL,
  CHANGE `descuento` `descuento` DECIMAL(18,2) NULL,
  ADD COLUMN `otros_impuestos` DECIMAL(18,2) NULL AFTER `descuento`;


";
        $this->db->query($query);


    }

    public function down()
    {

    }
}