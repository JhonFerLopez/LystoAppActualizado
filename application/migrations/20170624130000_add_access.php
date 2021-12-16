<?php

class Migration_Add_access extends CI_Migration
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
                'key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '40',
                    'null' => false,
                ),
                'all_access' => array(
                    'type' => 'BOOL',
                    'null' => false,
                ),
                'controller' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => false,
                ),
                'date_created' => array(
                    'type' => 'datetime',
                    'null' => true,
                ),

                'date_modified' => array(
                    'type' => 'TIMESTAMP',
                    'null' => false,
                ),

            )
        );


        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('access', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('access');
    }
}