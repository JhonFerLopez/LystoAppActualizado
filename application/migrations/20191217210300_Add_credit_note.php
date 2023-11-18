<?php

class Migration_Add_credit_note extends CI_Migration
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
                'uuid' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'number' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => false,
                    'unsigned' => true,
                ),
                'resolution_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => false,
                    'unsigned' => true,
                ),
                'issued_date' => array(
                    'type' => 'DATE',
                    'null' => false,
                ),
                'user_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => false,
                    'unsigned' => true,
                ),
                'caja_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => false,
                    'unsigned' => true,
                ),
                'venta_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => false,
                    'unsigned' => true,
                ),


            )
        );


        $this->dbforge->add_key('id', TRUE);




        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('credit_note', true, $attributes);


    }

    public function down()
    {

    }
}
