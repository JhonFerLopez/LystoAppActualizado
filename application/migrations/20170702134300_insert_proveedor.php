<?php

class Migration_Insert_proveedor extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `proveedor` (`id_proveedor`, `proveedor_nombre`, `proveedor_direccion`, `proveedor_email`, `proveedor_telefono1`, `proveedor_telefono2`, `proveedor_tipo`, `proveedor_regimen`, `proveedor_digito_verificacion`, `proveedor_identificacion`, `proveedor_celular`, `proveedor_ciudad`, `longitud`, `latitud`, `deleted_at`) VALUES
(1, 'COOPIDROGAS', 'VALLE', 'COOPODROGAS@GMAIL.COM', '301212', '', 1, NULL , '1', '12313', '121212', 11, '0', '0', NULL),
(2, 'DISTRIBUIDORA NEGOCIEMOS', '', '', '213456', '', 1, 2, '4', '800123584', '54322345', 11, '0', '0', NULL)


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM proveedor");
    }
}