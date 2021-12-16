<?php

class Migration_Add_local extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'int_local_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'local_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'local_status' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),


            )
        );
       

        $this->dbforge->add_key('int_local_id', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('local', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('local');
    }
}