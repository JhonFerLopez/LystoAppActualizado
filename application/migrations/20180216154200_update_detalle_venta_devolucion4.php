<?php

class Migration_Update_detalle_venta_devolucion4 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `detalle_venta_devolucion`   
  DROP COLUMN `descuento`;
 ';
        $this->db->query($query);


        $query = 'ALTER TABLE `venta_devolucion`   
  ADD COLUMN `subtotal` FLOAT NULL AFTER `fecha_devolucion`;
';
        $this->db->query($query);


        $query = 'ALTER TABLE `venta_devolucion`   
    ADD COLUMN `impuesto` FLOAT NULL AFTER `subtotal`;
';
        $this->db->query($query);

        $query = 'ALTER TABLE `venta_devolucion`   
   ADD COLUMN `total` FLOAT NULL AFTER `impuesto`;
';
        $this->db->query($query);


        $query = 'ALTER TABLE `venta_devolucion`   
    ADD COLUMN `descuento` FLOAT NULL AFTER `total`;
';
        $this->db->query($query);
    }

    public function down()
    {

    }
}