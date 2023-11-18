<?php

class Migration_Update_producto8 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `producto`   
        ADD COLUMN `in_offer` BOOLEAN DEFAULT 0 NULL COMMENT 'defines si el producto está en oferta' AFTER `fe_type_item_identification_id`;
    
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}