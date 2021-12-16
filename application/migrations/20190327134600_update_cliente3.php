<?php

class Migration_update_cliente3 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `cliente`   
  ADD COLUMN `saldo_inicial` DECIMAL(18,2) NULL AFTER `permitir_deuda_vencida`;


";
        $this->db->query($query);

    }

    public function down()
    {

    }
}