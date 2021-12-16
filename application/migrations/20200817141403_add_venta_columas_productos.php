<?php

class Migration_Add_venta_columas_productos extends CI_Migration
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
                'nombre_columna' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'nombre_mostrar' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'mostrar' => array(
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

        $this->dbforge->add_key('id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('venta_columas_productos', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('venta_columas_productos');
    }
}