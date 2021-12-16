<?php

class Migration_Insert_componentes extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `componentes` (`componente_id`, `componente_nombre`, `deleted_at`) VALUES

(1, 'ACETAMINOFEN', NULL),
(2, 'IBUPROFENO', NULL),
(3, 'AZITROMICINA', NULL),
(4, 'AMPICILINA', NULL),
(5, 'ATORVASTATINA', NULL),
(6, 'ACICLOVIR', NULL),
(7, 'ACIDO FUSIDICO', NULL),
(8, 'ACIDO VALPROICO', NULL),
(9, 'ACIDO ACETIL SALICILICO', NULL),
(10, 'ALIZAPRIDA', NULL),
(11, 'AMITRIPTILINA', NULL),
(12, 'ALENDRONATO', NULL),
(13, 'AMLODIPINO', NULL),
(14, 'AMIKACINA', NULL),
(15, 'ALBENDAZOL', NULL),
(16, 'AMOXICILINA', NULL),
(17, 'ACIDO FOLICO', NULL),
(18, 'SALBUTAMOL', NULL),
(19, 'NAPROXENO', NULL);

");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM componentes");
    }
}