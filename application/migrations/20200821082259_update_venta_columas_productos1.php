<?php

class Migration_Update_venta_columas_productos1 extends CI_Migration
{
    public function up()
    {

        $query = "update  venta_columas_productos set nombre_mostrar = 'Cant', nombre_columna='cant' where id=6; ";
        $this->db->query($query);

        $query = "update  venta_columas_productos set nombre_mostrar = 'Precio', nombre_columna='precio' where id=7; ";
        $this->db->query($query);

        $query = "update  venta_columas_productos set nombre_mostrar = '% Utilidad' where id=8; ";
        $this->db->query($query);
    }

    public function down()
    {
    }
}