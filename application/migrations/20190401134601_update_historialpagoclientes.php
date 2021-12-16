<?php

class Migration_update_historialpagoclientes extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `historial_pagos_clientes`   
  CHANGE `credito_id` `venta_id` BIGINT(20) UNSIGNED NOT NULL,
 ADD COLUMN `id_credito` BIGINT(20) UNSIGNED NULL AFTER `recibo_id`; ";
        $this->db->query($query);

        $query = "ALTER TABLE `historial_pagos_clientes`  
  ADD FOREIGN KEY (`id_credito`) REFERENCES `credito`(`credito_id`);
 ";
        $this->db->query($query);


    }

    public function down()
    {

    }
}