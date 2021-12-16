<?php

class Migration_Update_recibo_pago_proveedor2 extends CI_Migration
{
    public function up()
    {


        $query1 = "ALTER TABLE recibo_pago_proveedor
  ADD COLUMN `cuadre_caja_id` BIGINT(20) NULL AFTER `fecha`;

";
        $this->db->query($query1);



    }

    public function down()
    {

    }
}