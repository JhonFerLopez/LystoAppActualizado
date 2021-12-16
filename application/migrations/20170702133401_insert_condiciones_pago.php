<?php

class Migration_Insert_condiciones_pago extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `condiciones_pago` (`id_condiciones`, `nombre_condiciones`, `status_condiciones`, `dias`) VALUES
(1, 'CONTADO', 1, 0),
(2, 'CREDITO', 1, 30);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM condiciones_pago");
    }
}