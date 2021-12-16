<?php

class Migration_update_domicilio_historial1 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `domicilio_historial`   
  ADD COLUMN `latitud_pos` VARCHAR(100) NULL AFTER `comentario`,
  ADD COLUMN `longitud_pos` VARCHAR(100) NULL AFTER `latitud_pos`,
  ADD COLUMN `texto_pos` TEXT NULL AFTER `longitud_pos`;
";
        $this->db->query($query);

        $query = "ALTER TABLE `domicilio_historial`   
  CHANGE `id_domicilio` `id_domicilio` BIGINT(20) UNSIGNED NULL,
  CHANGE `estatus` `estatus` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci NULL;
;
";
        $this->db->query($query);


        $query = "ALTER TABLE `domicilio_historial`   
  ADD COLUMN `segundos_tarda` INT(25) NULL  COMMENT 'segundos que tarda desde el anterior historial hasta ahora' AFTER `texto_pos`;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}