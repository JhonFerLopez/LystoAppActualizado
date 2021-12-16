<?php

class Migration_Update_recibo_pago_proveedor extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `recibo_pago_proveedor`   
  CHANGE `banco` `banco` BIGINT(20) UNSIGNED NULL,
  CHANGE `metodo_pago` `metodo_pago` BIGINT(20) UNSIGNED NULL;
';
        $this->db->query($query);
    }

    public function down()
    {

    }
}