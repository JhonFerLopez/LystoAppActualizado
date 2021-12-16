<?php

class Migration_Add_logs extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true

                ),
                'uri' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false,

                ),
                'method' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 6,
                    'null' => false,

                ),
                'params' => array(
                    'type' => 'TEXT',
                    'null' => true,

                ),
                'api_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 40,
                    'null' => false,

                ),
                'api_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 45,
                    'null' => false,

                ),
                'time' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false,

                ),
                'rtime' => array(
                    'type' => 'FLOAT',
                    'null' => true,

                ),
                'authorized' => array(
                    'type' => 'BOOL',
                    'null' => false,

                ),
                'response_code' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,

                ),
            )
        );

        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('logs', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('logs');
    }
}