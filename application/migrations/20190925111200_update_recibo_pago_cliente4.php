<?php

class Migration_Update_recibo_pago_cliente4 extends CI_Migration
{
	public function up()
	{


		$this->db->query("
ALTER TABLE `recibo_pago_cliente`   
	ADD COLUMN `cuadre_caja_id_anulado` BIGINT(20) NULL AFTER `usu_anulado`;
");
	}

	public function down()
	{

	}
}
