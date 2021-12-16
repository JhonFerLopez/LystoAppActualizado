<?php


class Migration_Insert_contro_ambiental_hrsnot extends CI_Migration
{
    public function up()
    {
        $this->db->query("
INSERT INTO `control_ambiental_hrsnot` (`nombre`, `alias`, `hora`, `minutos`) VALUES
( 'HUMEDAD RELATIVA AM','humedad_relat_am',null, null)");

        $this->db->query("
INSERT INTO `control_ambiental_hrsnot` (`nombre`, `alias`, `hora`, `minutos`) VALUES
( 'TEMPERATURA °C AMBIENTAL AM','temp_ambiental_am',null, null)");

        $this->db->query("
INSERT INTO `control_ambiental_hrsnot` (`nombre`, `alias`, `hora`, `minutos`) VALUES
( 'HUMEDAD RELATIVA PM','humedad_relat_pm',null, null)");

        $this->db->query("
INSERT INTO `control_ambiental_hrsnot` (`nombre`, `alias`, `hora`, `minutos`) VALUES
( 'TEMPERATURA °C AMBIENTAL PM','temp_ambiental_pm',null, null)");

    }

    public function down()
    {

    }
}