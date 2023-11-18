<?php

class Migration_Add_fe_clients_fields extends CI_Migration
{
    public function up()
    {
        $query = "ALTER TABLE `cliente`   
       ADD COLUMN `type_document_identification_id` INT(11) NULL AFTER `fe_regime`;
    ";

        $this->db->query($query);

        $query = "ALTER TABLE `cliente`   
       ADD COLUMN `type_organization_id` INT(11) NULL AFTER `type_document_identification_id`;
     ";

        $this->db->query($query);

        $query = "ALTER TABLE `cliente`   
        ADD COLUMN `tax_detail_id` INT(11) NULL AFTER `type_organization_id`;
      ";

        $this->db->query($query);
    }

    public function down()
    {
    }
}
