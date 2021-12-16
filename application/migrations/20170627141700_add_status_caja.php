<?php

class Migration_Add_status_caja extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'cajero' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'caja_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'apertura' => array(
                    'type' => 'DATETIME',
                    'null' => true,

                ),
                'cierre' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),
                'base' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'monto_cierre' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'observacion_cierre' => array(
                    'type' => 'TEXT',
                    'null' => true,
                ),
                'observacion_apertura' => array(
                    'type' => 'TEXT',
                    'null' => true,
                ),

            )
        );

        $this->dbforge->add_key('id', TRUE);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (cajero) REFERENCES usuario(nUsuCodigo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (caja_id) REFERENCES caja(caja_id)');


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('status_caja', false, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('status_caja');
    }
}