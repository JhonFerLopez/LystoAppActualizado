<?php

class Migration_Update_venta_dian2 extends CI_Migration
{
    public function up()
    {


        $query = " ALTER TABLE `venta`   
        CHANGE `fe_reponseDian` `fe_reponseDian` LONGTEXT NULL;
    
    
";
        $this->db->query($query);


        $query = " ALTER TABLE `venta_backup`   
        CHANGE `fe_reponseDian` `fe_reponseDian` LONGTEXT NULL;
    
    
";
    }

    public function down()
    {
    }
}
