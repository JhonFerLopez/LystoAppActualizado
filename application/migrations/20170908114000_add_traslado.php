<?php

class Migration_Add_traslado extends CI_Migration
{
    public function up()
    {

        $this->dbforge->add_field(
            array(
                'id_traslado' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'usuario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

                'fecha' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),

            )
        );


        $this->dbforge->add_key('id_traslado', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario) REFERENCES usuario(nUsuCodigo)');


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('traslado', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('traslado');
    }
}