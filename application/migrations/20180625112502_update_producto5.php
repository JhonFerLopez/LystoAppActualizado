<?php

class Migration_Update_producto5 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE producto   
  ADD COLUMN `otro_impuesto` BIGINT(20) UNSIGNED NULL AFTER `cantidad_caja`,
  ADD FOREIGN KEY (`otro_impuesto`) REFERENCES `impuestos`(`id_impuesto`);

";
        $this->db->query($query);



    }

    public function down()
    {

    }
}