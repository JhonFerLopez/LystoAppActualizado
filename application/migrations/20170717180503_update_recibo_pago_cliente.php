<?php

class Migration_Update_recibo_pago_cliente extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `recibo_pago_cliente`   
  CHANGE `banco` `banco` BIGINT(20) UNSIGNED NULL,
  CHANGE `metodo` `metodo` BIGINT(20) UNSIGNED NULL;
';
        $this->db->query($query);
    }

    public function down()
    {

    }
}