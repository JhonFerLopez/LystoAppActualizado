<?php

class Migration_update_cliente2 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `cliente`   
  ADD COLUMN `permitir_deuda_vencida` BOOLEAN DEFAULT 0  NULL AFTER `string_promedio`;

";
        $this->db->query($query);

    }

    public function down()
    {

    }
}