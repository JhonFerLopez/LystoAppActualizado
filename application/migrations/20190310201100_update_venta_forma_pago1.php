<?php

class Migration_Update_venta_forma_pago1 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `venta_forma_pago`   
  ADD COLUMN `nro_recibo` VARCHAR(200) NULL AFTER `monto`;";
        $this->db->query($query);

    }

    public function down()
    {

    }
}