<?php

class Migration_Add_zonas extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'zona_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'zona_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'ciudad_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'status' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),


            )
        );
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (ciudad_id) REFERENCES ciudades(ciudad_id)');

        $this->dbforge->add_key('zona_id', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('zonas', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('zonas');
    }
}