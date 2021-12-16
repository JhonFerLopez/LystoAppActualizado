<?php

class Migration_Update_venta_anular extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE venta_anular   
  ADD COLUMN apertua_caja_id BIGINT(20) UNSIGNED NULL AFTER dat_fecha_registro;";
     $this->db->query($query1);

   $query1 = "ALTER TABLE `venta_anular`  
  ADD FOREIGN KEY (`apertua_caja_id`) REFERENCES `status_caja`(`id`);
";
        $this->db->query($query1);
    }

    public function down()
    {

    }
}