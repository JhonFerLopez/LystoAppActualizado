<?php

class Migration_Update_venta_7 extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE `venta`   
  ADD COLUMN `desc_global` BOOLEAN DEFAULT 0 NULL AFTER `regimen_contributivo`; ";
        $this->db->query($query1);

        $query1 = "ALTER TABLE `venta_backup`   
  ADD COLUMN `desc_global` BOOLEAN DEFAULT 0 NULL AFTER `regimen_contributivo`; ";
        $this->db->query($query1);

        $query1 = "ALTER TABLE `venta`   
  CHANGE `descuento_porcentaje` `descuento_porcentaje` DECIMAL(18,2) NULL   COMMENT 'esto es el valor una ve calculado el porcentaje';";
        $this->db->query($query1);

        $query1 = "ALTER TABLE `venta_backup`   
  CHANGE `descuento_porcentaje` `descuento_porcentaje` DECIMAL(18,2) NULL   COMMENT 'esto es el valor una ve calculado el porcentaje';";
        $this->db->query($query1);

        $query1 = "ALTER TABLE `venta`   
  ADD COLUMN `porcentaje_desc` DECIMAL(18,2) NULL   COMMENT 'esto es el porcentaje' AFTER `descuento_porcentaje`;
";
        $this->db->query($query1);

        $query1 = "ALTER TABLE `venta_backup`   
  ADD COLUMN `porcentaje_desc` DECIMAL(18,2) NULL   COMMENT 'esto es el porcentaje' AFTER `descuento_porcentaje`;
";
        $this->db->query($query1);
    }

    public function down()
    {
    }
}