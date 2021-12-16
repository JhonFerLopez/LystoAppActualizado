<?php

class Migration_Add_banco extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'banco_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'banco_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'banco_numero_cuenta' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'banco_saldo' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'banco_cuenta_contable' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'banco_titular' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'banco_status' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
            )
        );

        $this->dbforge->add_key('banco_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('banco',false,$attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('banco');
    }
}