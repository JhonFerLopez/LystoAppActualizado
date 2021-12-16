<?php

class Migration_Add_ajusteinventario extends CI_Migration
{
    public function up()
    {

        $this->dbforge->add_field(
            array(
                'id_ajusteinventario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'tipo_ajuste' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'local_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
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


        $this->dbforge->add_key('id_ajusteinventario', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (tipo_ajuste) REFERENCES documentos_inventarios(documento_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (local_id) REFERENCES local(int_local_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario) REFERENCES usuario(nUsuCodigo)');


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ajusteinventario', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('ajusteinventario');
    }
}