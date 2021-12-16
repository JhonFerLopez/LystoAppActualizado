<?php

class Migration_Update_kardex3 extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE kardex   
  ADD COLUMN `cKardexIvaPorcentaje` FLOAT(20) NULL AFTER `cKardexProveedor`; ";
        $this->db->query($query1);
    }

    public function down()
    {

    }
}