<?php


class Migration_Update_recibo_cliente2 extends CI_Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `recibo_pago_cliente`   
	ADD COLUMN `fecha_anulado` DATETIME NULL AFTER `anulado`");


        $this->db->query("
	ALTER TABLE `recibo_pago_cliente`   
	ADD COLUMN `usu_anulado` BIGINT(20) UNSIGNED NULL AFTER `fecha_anulado`;

");


    }

    public function down()
    {

    }
}