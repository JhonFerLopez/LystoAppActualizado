<?php

class Migration_Update_condiciones_pago extends CI_Migration
{
    public function up()
    {


        $this->db->query("
ALTER TABLE `condiciones_pago`   
	ADD COLUMN `fe_payment_form_id` BIGINT(20) NULL AFTER `status_condiciones`;



");


    }

    public function down()
    {
        //$this->dbforge->drop_table('metodos_pago');
    }
}