<?php

class Migration_Insert_grupos_cliente extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `grupos_cliente` (`id_grupos_cliente`, `nombre_grupos_cliente`, `status_grupos_cliente`) VALUES

(1, 'EMPRESA', 1),
(2, 'PARTICULAR', 1),
(3, 'CLIENTE MINORISTA', 1);


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM grupos_cliente");
    }
}