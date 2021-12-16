<?php

class Migration_Insert_grupos extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `grupos` (`id_grupo`, `nombre_grupo`, `estatus_grupo`) VALUES
(1, 'LECHES', 1),
(2, 'PAñALES', 1),
(3, 'DESODORANTES', 1),
(4, 'DESODORANTES EN SOBRE', 1),
(5, 'CHAMPU', 1),
(6, 'CHAMPU SOBRE', 1),
(7, 'CUIDADO DEL BEBE', 1),
(8, 'jabones1478033277', 0),
(9, 'JABONES', 1),
(10, 'PAñAL', 1),
(11, 'CREMA', 1),
(12, 'TOALLAS Y PROTECTORES', 1);


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM grupos");
    }
}