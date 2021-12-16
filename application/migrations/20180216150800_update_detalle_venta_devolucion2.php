<?php

class Migration_Update_detalle_venta_devolucion2 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `detalle_venta_devolucion`  
  DROP FOREIGN KEY `detalle_venta_devolucion_ibfk_4`;

';
        $this->db->query($query);



    }

    public function down()
    {

    }
}