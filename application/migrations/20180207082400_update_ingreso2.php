<?php

class Migration_Update_ingreso2 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `ingreso`    
  CHANGE `int_Proveedor_id` `int_Proveedor_id` bigint(20) unsigned NULL; ';
        $this->db->query($query);

        $query = 'ALTER TABLE `ingreso`    
  CHANGE `condicion_pago` `condicion_pago` bigint(20) unsigned NULL; ';
        $this->db->query($query);

    }

    public function down()
    {

    }
}