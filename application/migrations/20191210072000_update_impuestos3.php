<?php

class Migration_Update_impuestos3 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `impuestos`   
	ADD COLUMN `fe_impuesto` BIGINT(20) NULL AFTER `tipo_calculo`;";
        $this->db->query($query);



    }

    public function down()
    {

    }
}