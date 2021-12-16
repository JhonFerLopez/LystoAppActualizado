<?php

class Migration_Update_detalle_ingreso_unidad3 extends CI_Migration
{
    public function up()
    {

        $query="ALTER TABLE `detalle_ingreso_unidad`   
  ADD COLUMN `costoestaunidad_antes` DECIMAL(20,2) NULL  COMMENT 'cuanto costaba esta unidad antes' AFTER `ingreso_id`,
  ADD COLUMN `costo_unitario_antes` DECIMAL(22,2) NULL  COMMENT 'costo de la caja antes de comprar esta unidad' AFTER `costoestaunidad_antes`,
  ADD COLUMN `costo_unitario_despues` DECIMAL(22,2) NULL  COMMENT 'costo de la caja despues de comprar esta unidad' AFTER `costo_unitario_antes`;
";
        $this->db->query($query);


    }

    public function down()
    {

    }
}