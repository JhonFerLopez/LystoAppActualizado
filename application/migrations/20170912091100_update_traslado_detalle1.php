<?php

class Migration_Update_traslado_detalle1 extends CI_Migration
{
    public function up()
    {

        $query = 'ALTER TABLE `traslado_detalle`   
  DROP COLUMN `local_id`, 
  DROP COLUMN `tipo_operacion`, 
  DROP INDEX `local_id`,
  DROP FOREIGN KEY `traslado_detalle_ibfk_4`;
  ';
        $this->db->query($query);

        $query = 'ALTER TABLE `traslado_detalle`   
  ADD COLUMN `local_salida` BIGINT(20) UNSIGNED NOT NULL AFTER `cantidad`,
  ADD COLUMN `local_destino` BIGINT(20) UNSIGNED NOT NULL AFTER `local_salida`,
  ADD FOREIGN KEY (`local_salida`) REFERENCES `local`(`int_local_id`),
  ADD FOREIGN KEY (`local_destino`) REFERENCES `local`(`int_local_id`);
';
        $this->db->query($query);



    }

    public function down()
    {

    }
}