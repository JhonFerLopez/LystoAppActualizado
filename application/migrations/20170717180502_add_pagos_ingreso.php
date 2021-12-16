<?php

class Migration_Add_pagos_ingreso extends CI_Migration
{
    public function up()
    {

        $this->dbforge->add_field(
            array(
                'pagoingreso_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true,

                ),
                'pagoingreso_ingreso_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

                'pagoingreso_monto' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                    'constraint' => '20,2',
                ),
                'pagoingreso_restante' => array(
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

        $this->dbforge->add_key('pagoingreso_id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (pagoingreso_ingreso_id) REFERENCES ingreso(id_ingreso)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (recibo_id) REFERENCES recibo_pago_proveedor(recibo_id)');
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('pagos_ingreso', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('pagos_ingreso');
    }
}