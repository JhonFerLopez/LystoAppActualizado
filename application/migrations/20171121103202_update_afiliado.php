<?php

class Migration_Update_afiliado extends CI_Migration
{

    public function up()
    {


        $query1 = "ALTER TABLE `afiliado`   
  DROP COLUMN `lista_precios`;


";
        $this->db->query($query1);



    }

    public function down()
    {

    }
}