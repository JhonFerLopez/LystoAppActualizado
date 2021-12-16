<?php


class Migration_Insert_into_control_ambiental_hrsnot2 extends CI_Migration
{
	public function up()
	{

		$this->db->query("
INSERT INTO `control_ambiental_hrsnot` (`nombre`, `alias`, `hora`, `minutos`) VALUES
( 'CADENA DE FRÍO AM','cadena_frio_am',null, null)");

		$this->db->query("
INSERT INTO `control_ambiental_hrsnot` (`nombre`, `alias`, `hora`, `minutos`) VALUES
( 'CADENA DE FRÍO PM','cadena_frio_pm',null, null)");

	}

	public function down()
	{

	}
}
