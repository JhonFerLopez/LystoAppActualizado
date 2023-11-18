<?php

class Migration_Update_venta_dian extends CI_Migration
{
    public function up()
    {


        $query = " ALTER TABLE `venta`   
        ADD COLUMN `fe_prefijo` VARCHAR(255) NULL AFTER `fe_resolution_id`;
    
";
        $this->db->query($query);

        
        $query = " ALTER TABLE `venta_backup`   
        ADD COLUMN `fe_prefijo` VARCHAR(255) NULL AFTER `fe_resolution_id`;
    
";

        $this->db->query($query);

        $query = " ALTER TABLE `venta`   
        ADD COLUMN `fe_type_document` INT NULL AFTER `fe_prefijo`;

  
";

      $this->db->query($query);
      
        $query = " ALTER TABLE `venta_backup`   
      	ADD COLUMN `fe_type_document` INT NULL AFTER `fe_prefijo`;

    
";

        $this->db->query($query);
    }

    public function down()
    {
        $this->dbforge->drop_table('notificaciones');
    }
}
