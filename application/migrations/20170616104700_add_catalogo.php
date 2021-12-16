<?php

class Migration_Add_catalogo extends CI_Migration
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
                'producto_codigo_interno' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => true,
                ),
                'producto_codigo_barra' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'producto_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => true,
                ),
                'presentacion' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => true,
                ),
                'costo_corriente' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'costo_real' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'iva' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'null' => true,
                ),
                'nombre_laboratorio' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => true,
                ),
                'codigo_laboratorio' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '25',
                    'null' => true,
                ),
                'bonificacion' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
            )
        );

        $this->dbforge->add_key('id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('catalogo', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('catalogo');
    }
}