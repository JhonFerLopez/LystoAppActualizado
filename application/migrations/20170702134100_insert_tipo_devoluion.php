<?php

class Migration_Insert_tipo_devoluion extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `tipo_devolucion` (`tipo_devolucion_id`, `tipo_devolucion_nombre`, `deleted_at`) VALUES
(1, 'EL PRODUCTO ESTABA MALO', NULL),
(2, 'DESPACHO EQUIVOCADO', NULL),
(3, 'EL CLIENTE NO QUIERE EL PRODUCTO', NULL),
(4, 'CLIENTE SE EQUIVOCO DE PRODCUTO', NULL),
(5, 'ERROR DEL VENDEDOR', NULL);



");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM tipo_devolucion");
    }
}