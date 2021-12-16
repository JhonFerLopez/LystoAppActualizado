<?php


class Migration_Update_ajustedetalle_myisan extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `ajustedetalle`  
  DROP FOREIGN KEY `ajustedetalle_ibfk_1`;
 


");

        $this->db->query("ALTER TABLE `ajustedetalle`  

  DROP FOREIGN KEY `ajustedetalle_ibfk_2`;


");


    }

    public function down()
    {

    }
}