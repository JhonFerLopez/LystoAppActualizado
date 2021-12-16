<?php

class Migration_Update_detalle_ingreso_unidad4 extends CI_Migration
{
    public function up()
    {

        $query="ALTER TABLE `detalle_ingreso_unidad`   
  ADD COLUMN `costo_con_descuento` FLOAT NULL  COMMENT 'el costo de la unidad con el descuento, si es que tuvo descuento' AFTER `costo`;
";
        $this->db->query($query);

        $query="ALTER TABLE `detalleingreso`   
  ADD COLUMN `total_con_descuento` FLOAT NULL  COMMENT 'el costo total con descuento, sin iva' AFTER `porcentaje_descuento`;";
        $this->db->query($query);

        $query="  ALTER TABLE `detalle_ingreso_unidad`   
  ADD COLUMN `total_final` FLOAT NULL  COMMENT 'el costo total de esta unidad, con descuento e iva, si es que tuvo' AFTER `costo_total`;";
        $this->db->query($query);

    }

    public function down()
    {

    }
}