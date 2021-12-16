<?php

class Migration_Insert_metodos_pago extends CI_Migration
{
    public function up()
    {

        $this->db->query("

INSERT INTO `metodos_pago` (`id_metodo`, `nombre_metodo`, `deleted_at`, `centros_bancos`, `suma_total_ingreso`, `incluye_cuadre_caja`) VALUES
(1, 'EFECTIVO', NULL, 0, 1, 1),
(2, 'TARJETA DEBITO', NULL, 1, 0, 1),
(3, 'TARJETA DE CREDITO', NULL, 1, 0, 1),
(4, 'DEPOSITO BANCARIO', NULL, 1, 0, 1);


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM metodos_pago");
    }
}