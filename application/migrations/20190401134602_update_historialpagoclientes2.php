<?php

class Migration_update_historialpagoclientes2 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `historial_pagos_clientes`   
  CHANGE `venta_id` `venta_id` BIGINT(20) UNSIGNED NULL;
 ";
        $this->db->query($query);



    }

    public function down()
    {

    }
}