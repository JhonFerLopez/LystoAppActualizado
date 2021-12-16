<?php

class Migration_Add_opcion_grupo extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'grupo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true

                ),
                'Opcion' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'var_opcion_usuario_estado' => array(
                    'type' => 'BOOL',
                    'default' => 1,
                    'null' => false,

                ),

            )
        );

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (grupo) REFERENCES grupos_usuarios(id_grupos_usuarios)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (Opcion) REFERENCES opcion(nOpcion)');



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('opcion_grupo', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('opcion_grupo');
    }
}