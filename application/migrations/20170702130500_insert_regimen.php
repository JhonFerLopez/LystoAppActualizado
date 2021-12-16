<?php

class Migration_Insert_regimen extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `regimen` (`regimen_id`, `regimen_nombre`, `compra_retienen`, `compra_retienen_iva`, `venta_retienen`, `venta_retienen_iva`, `genera_iva`, `autoretenedor`, `gran_contribuyente`, `deleted_at`) VALUES
(1, 'REGIMEN SIMPLIFICADO', 0, 0, 0, 0, 0, 0, 0, NULL),
(2, 'RéGIMEN COMÚN PERSONA JURIDICA', 0, 0, 0, 0, 0, 0, 0, NULL),
(3, 'PERSONA NATURAL NO CONTRIBUYENTE', 0, 0, 0, 0, 0, 0, 0, NULL),
(4, 'GRAN CONTRIBUYENTE', 0, 0, 0, 0, 0, 0, 0, NULL),
(5, 'GRAN CONTRIBUYENTE AUTORETENEDOR', 0, 0, 0, 0, 0, 0, 0, NULL);
");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM regimen");
    }
}