<?php

class Migration_Update_resolucion_dian2 extends CI_Migration
{

    public function up()
    {


        $query = 'ALTER TABLE `resolucion_dian`   
  CHANGE `resolucion_numero` `resolucion_numero` VARCHAR(255) NOT NULL;
';
        $this->db->query($query);


    }

    public function down()
    {

    }
}