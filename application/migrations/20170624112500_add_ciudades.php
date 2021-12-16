<?php

class Migration_Add_ciudades extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'ciudad_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'ciudad_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'estado_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

            )
        );
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (estado_id) REFERENCES estados(estados_id)');

        $this->dbforge->add_key('ciudad_id', TRUE);


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ciudades', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('ciudades');
    }
}