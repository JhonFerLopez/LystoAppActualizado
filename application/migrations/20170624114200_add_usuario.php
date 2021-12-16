<?php

class Migration_Add_usuario extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'nUsuCodigo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'username' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '18',
                    'null' => false,
                ),
                'var_usuario_clave' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => false,
                ),
                'nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => false,
                ),
                'identificacion' => array(
                    'type' => 'INT',
                    'constraint' => '45',
                    'null' => false,
                ),

                'grupo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'null' => true,
                ),

                'sueldo' => array(
                    'type' => 'INT',
                    'constraint' => '11',
                    'null' => true,
                ),

                'genero' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 9,
                ),
                'longitud' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,
                ),
                'latitud' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,
                ),

                'obser' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,
                ),

                'smovil' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'admin' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'activo' => array(
                    'type' => 'BOOL',
                    'null' => true,
                    'default' => 1,
                ),
                'deleted' => array(
                    'type' => 'BOOL',
                    'null' => true,
                    'default' => 0,
                ),
                'fnac' => array(
                    'type' => 'DATE',
                    'null' => true,
                ),
                'fent' => array(
                    'type' => 'DATE',
                    'null' => true,
                ),
            )
        );


        $this->dbforge->add_key('nUsuCodigo', TRUE);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (grupo) REFERENCES grupos_usuarios(id_grupos_usuarios)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('usuario', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('usuario');
    }
}