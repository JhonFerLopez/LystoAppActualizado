<?php

class Migration_Update_producto extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `producto`   
  DROP COLUMN `precio_minimo`, 
  DROP COLUMN `precio_maximo`;

';
        $this->db->query($query);

    }

    public function down()
    {

    }
}