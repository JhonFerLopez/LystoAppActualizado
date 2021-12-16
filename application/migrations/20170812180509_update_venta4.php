<?php

class Migration_Update_venta4 extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `venta`  
  ADD FOREIGN KEY (`venta_tipo`) REFERENCES `tipo_venta`(`tipo_venta_id`);
';
        $this->db->query($query);


        $query='ALTER TABLE `venta_backup`  
  ADD FOREIGN KEY (`venta_tipo`) REFERENCES `tipo_venta`(`tipo_venta_id`);

';
        $this->db->query($query);
    }

    public function down()
    {

    }
}