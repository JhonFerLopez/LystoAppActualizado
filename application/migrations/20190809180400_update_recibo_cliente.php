<?php


class Migration_Update_recibo_cliente extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `recibo_pago_cliente`   
	ADD COLUMN `anulado` BOOLEAN DEFAULT 0 NULL AFTER `cuadre_caja_id`;");


    }

    public function down()
    {

    }
}