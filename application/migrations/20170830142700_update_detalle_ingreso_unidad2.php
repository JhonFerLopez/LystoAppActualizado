<?php

class Migration_Update_detalle_ingreso_unidad2 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `detalle_ingreso_unidad`   
  ADD COLUMN `actualizado` BOOLEAN DEFAULT 0  NULL AFTER `costo_total`;';
        $this->db->query($query);

        $query='ALTER TABLE `detalle_ingreso_unidad`   
  ADD COLUMN `ingreso_id` BIGINT(20) DEFAULT NULL  NULL AFTER `actualizado`; ';
        $this->db->query($query);


        $query='ALTER TABLE `detalle_ingreso_unidad`   
  CHANGE `ingreso_id` `ingreso_id` BIGINT(20) UNSIGNED NULL,
  ADD FOREIGN KEY (`ingreso_id`) REFERENCES `ingreso`(`id_ingreso`);';
        $this->db->query($query);


    }

    public function down()
    {

    }
}