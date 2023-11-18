<?php

class Migration_Update_metodos_pago extends CI_Migration
{
    public function up()
    {


        $this->db->query("
ALTER TABLE `metodos_pago`   
	ADD COLUMN `fe_method_id` BIGINT(20) NULL AFTER `deleted_at`;


");


    }

    public function down()
    {
        //$this->dbforge->drop_table('metodos_pago');
    }
}