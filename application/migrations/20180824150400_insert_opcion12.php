<?php

class Migration_Insert_opcion12 extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` ( `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 4, 'pedidosugerido', 'Pedido Sugerido');
");
    }

    public function down()
    {

    }
}