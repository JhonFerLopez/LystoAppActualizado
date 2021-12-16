<?php

class Migration_Alter_table_opcion_1 extends CI_Migration
{
    public function up()
    {
        $this->db->query("alter table opcion ADD COLUMN `is_to_show_some_value` BOOLEAN DEFAULT 0  NULL AFTER `cOpcionDescripcion`");
    }

    public function down()
    {
        $this->db->query("   ALTER TABLE `opcion` DROP COLUMN `is_to_show_some_value`;");

    }
}