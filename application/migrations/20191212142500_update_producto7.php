<?php

class Migration_Update_producto7 extends CI_Migration
{
    public function up()
    {


        $query="ALTER TABLE `producto`   
	ADD COLUMN `fe_type_item_identification_id` BIGINT(20) NULL AFTER `otro_impuesto`;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}