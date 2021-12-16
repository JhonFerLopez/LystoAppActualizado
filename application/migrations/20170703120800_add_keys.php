<?php

class Migration_Add_keys extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true,

                ),
                'user_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,


                ),

                'key' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 40,
                ),
                'level' => array(
                    'type' => 'INT',
                    'null' => true,
                    'constraint' => 2,
                ),
                'ignore_limits' => array(
                    'type' => 'BOOL',
                    'null' => false,
                    'default' => 0,

                ),
                'is_private_key' => array(
                    'type' => 'BOOL',
                    'null' => false,
                    'default' => 0,

                ),
                'ip_addresses' => array(
                    'type' => 'TEXT',
                    'null' => false,


                ),
                'ip_addresses' => array(
                    'type' => 'INT',
                    'null' => false,
                    'constraint' => 11,

                ),


            )
        );

        $this->dbforge->add_key('id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('keys', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('keys');
    }
}