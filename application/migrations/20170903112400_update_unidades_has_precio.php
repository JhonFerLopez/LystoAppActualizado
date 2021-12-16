<?php

class Migration_Update_unidades_has_precio extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `unidades_has_precio`   
  ADD COLUMN `precio_minimo` DECIMAL(18,2) NULL AFTER `utilidad`,
  ADD COLUMN `precio_maximo` DECIMAL(18,2) NULL AFTER `precio_minimo`;
';
        $this->db->query($query);

    }

    public function down()
    {

    }
}