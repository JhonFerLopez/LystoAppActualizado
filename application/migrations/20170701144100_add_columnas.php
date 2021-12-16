<?php

class Migration_Add_columnas extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_columna' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),

                'nombre_columna' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'nombre_join' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'nombre_mostrar' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'tabla' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'mostrar' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'activo' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'orden' => array(
                    'type' => 'INT',
                    'null' => true,
                    'constraint' => 20,
                ),


            )
        );

        $this->dbforge->add_key('id_columna', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('columnas', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('columnas');
    }
}