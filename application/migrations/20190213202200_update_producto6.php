<?php

class Migration_Update_producto6 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `producto`   
  ADD COLUMN `producto_presentacion` VARCHAR(255) NULL AFTER `producto_ubicacion_fisica`;";
        $this->db->query($query);

    }

    public function down()
    {

    }
}