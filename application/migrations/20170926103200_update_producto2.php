<?php

class Migration_Update_producto2 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `producto`   
  ADD COLUMN `ultima_fecha_compra` DATETIME NULL AFTER `is_obsequio`,
  ADD COLUMN `precio_corriente` DECIMAL(25,2) NULL AFTER `ultima_fecha_compra`;';
        $this->db->query($query);

    }

    public function down()
    {

    }
}