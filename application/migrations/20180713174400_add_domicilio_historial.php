<?php

class Migration_Add_domicilio_historial extends CI_Migration
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
                'fecha' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),
                'id_domicilio' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'usuario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'estatus' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                ),
                'comentario' => array(
                    'type' => 'TEXT',
                    'null' => true,
                )

            )
        );
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_domicilio) REFERENCES domicilios(domicilio_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario) REFERENCES usuario(nUsuCodigo)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('domicilio_historial', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('domicilio_historial');
    }
}