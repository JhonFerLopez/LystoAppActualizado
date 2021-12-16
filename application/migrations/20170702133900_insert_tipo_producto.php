<?php

class Migration_Insert_tipo_producto extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `tipo_producto` (`tipo_prod_id`, `tipo_prod_nombre`, `deleted_at`) VALUES

(1, 'COMERCIAL', NULL),
(2, 'GENÉRICO', NULL),
(3, 'CONTROLADO', NULL),
(4, 'NATURAL', NULL),
(5, 'POPULARES', NULL);


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM tipo_producto");
    }
}