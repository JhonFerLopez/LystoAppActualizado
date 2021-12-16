<?php

class Migration_Update_status_caja extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE status_caja 
  ADD COLUMN `valor_inventario_apertura` DECIMAL(18,2) NULL AFTER `observacion_apertura`,
  ADD COLUMN `valor_inventario_cierre` DECIMAL(18,2) NULL AFTER `valor_inventario_apertura`;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}