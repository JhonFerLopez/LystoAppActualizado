<?php

class Migration_Add_historial_pagos_clientes extends CI_Migration
{
    public function up()
    {

        $this->dbforge->add_field(
            array(
                'historial_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true,

                ),
                'credito_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

                'historial_monto' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                    'constraint' => '20,2',
                ),
                'monto_restante' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                    'constraint' => '20,2',
                ),
                'recibo_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
            )
        );

        $this->dbforge->add_key('historial_id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (credito_id) REFERENCES venta(venta_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (recibo_id) REFERENCES recibo_pago_cliente(recibo_id)');
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('historial_pagos_clientes', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('historial_pagos_clientes');
    }
}