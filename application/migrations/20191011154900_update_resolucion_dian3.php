<?php

class Migration_Update_resolucion_dian3 extends CI_Migration
{
    public function up()
    {

        $this->db->query("

ALTER TABLE `resolucion_dian`   
	ADD COLUMN `resolucion_fech_vencimiento` DATE NULL AFTER `resolucion_fech_aprobacion`;

");

    }

    public function down()
    {

    }
}