<?php

class Migration_Insert_usuario extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `usuario` (`nUsuCodigo`, `username`, `var_usuario_clave`, `activo`, `nombre`, `grupo`, `deleted`, `identificacion`, `latitud`, `longitud`, `genero`, `sueldo`, `smovil`, `admin`, `obser`, `fnac`, `fent`) VALUES
(1, 'ADMINISTRADOR', '25d55ad283aa400af464c76d713c07ad', 1, 'Usuario', 1, 0, 46598773, '', '', 'masculino', 0, 1, 1, NULL, '2016-02-16', '2016-02-16'),(2, 'CAJERO', '25d55ad283aa400af464c76d713c07ad', 1, 'CAJERO 1', 3, 0, 46598773, '', '', 'masculino', 0, 1, 1, NULL, '2016-02-16', '2016-02-16')

");

    }

    public function down()
    {
        $this->db->query(" DELETE FROM usuario");
    }
}