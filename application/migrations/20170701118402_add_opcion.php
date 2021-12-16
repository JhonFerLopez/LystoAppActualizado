<?php

class Migration_Add_opcion extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'nOpcion' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true

                ),
                'nOpcionClase' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'cOpcionNombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,

                ),
                'cOpcionDescripcion' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,

                ),


            )
        );

        $this->dbforge->add_key('nOpcion', TRUE);


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('opcion', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('opcion');
    }
}