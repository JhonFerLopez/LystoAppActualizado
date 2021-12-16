<?php

class Migration_Update_detalleingreso_especial extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `detalleingreso_especial`   
  ADD COLUMN `actualizado` BOOLEAN DEFAULT 0  NULL AFTER `tipo`;';
        $this->db->query($query);

        $query='ALTER TABLE `detalleingreso_especial`   
  ADD COLUMN `ingreso` BIGINT(20) UNSIGNED DEFAULT NULL  NULL AFTER `actualizado`; ';
        $this->db->query($query);

        $query='ALTER TABLE `detalleingreso_especial`  
  ADD FOREIGN KEY (`ingreso`) REFERENCES `ingreso`(`id_ingreso`); ';
        $this->db->query($query);



    }

    public function down()
    {

    }
}