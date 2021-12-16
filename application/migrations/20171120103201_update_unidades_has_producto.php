<?php

class Migration_Update_unidades_has_producto extends CI_Migration
{

    public function up()
    {


        $query1 = 'ALTER TABLE `unidades_has_producto`   
  ADD COLUMN `costo` FLOAT NULL AFTER `stock_maximo`;

';
        $this->db->query($query1);


    }

    public function down()
    {

    }
}