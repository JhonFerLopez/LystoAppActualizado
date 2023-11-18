<?php

class Migration_Update_unidades extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `unidades`   
	ADD COLUMN `fe_unidad` BIGINT(20) NULL AFTER `estatus_unidad`;
";
        $this->db->query($query);



    }

    public function down()
    {

    }
}