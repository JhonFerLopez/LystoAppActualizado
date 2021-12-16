<?php

class Migration_Update_impuestos extends CI_Migration
{
    public function up()
    {

        $query="ALTER TABLE `impuestos`   
  ADD COLUMN `tipo_calculo` ENUM('PORCETAJE','FIJO') NULL AFTER `estatus_impuesto`;
";
        $this->db->query($query);

        $query="ALTER TABLE `impuestos`   
  CHANGE `tipo_calculo` `tipo_calculo` ENUM('PORCENTAJE','FIJO') CHARSET utf8 COLLATE utf8_general_ci DEFAULT 'PORCENTAJE'  NULL;

";
        $this->db->query($query);





    }

    public function down()
    {

    }
}