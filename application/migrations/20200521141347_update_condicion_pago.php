<?php

class Migration_Update_condicion_pago extends CI_Migration
{
    public function up()
    {
        $this->db->query("
        ALTER TABLE `condiciones_pago`   
	ADD COLUMN `is_offer` BOOLEAN DEFAULT 0 NULL COMMENT 'Se usa para saber si este precio se toma como precio en oferta' AFTER `fe_payment_form_id`;
");

    }
    public function down()
    {

    }
}