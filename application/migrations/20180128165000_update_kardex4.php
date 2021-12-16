<?php

class Migration_Update_kardex4 extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE kardex 
  ADD COLUMN `cKardexCostoCaja` FLOAT NULL AFTER `cKardexIvaPorcentaje`; ";
     $this->db->query($query1);

    }

    public function down()
    {}
}