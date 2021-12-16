<?php

class Migration_Update_kardex5 extends CI_Migration
{
    public function up()
    {
        $this->db->query("
ALTER TABLE `kardex` ADD INDEX `kardex_index_cKardexProducto` (`cKardexProducto`);");

        $this->db->query("
ALTER TABLE `kardex` ADD INDEX `kardex_index_ckardexReferencia` (`ckardexReferencia`);");

        $this->db->query("
ALTER TABLE `kardex` ADD INDEX `kardex_index_dkardexFecha` (`dkardexFecha`);");
    }

    public function down()
    {

    }
}