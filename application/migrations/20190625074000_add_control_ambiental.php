<?php

class Migration_Add_control_ambiental extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'control_ambiental_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'periodo' => array(
                    'type' => 'DATETIME',
                ),
                'usuario_crea' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                )

            )
        );


        $this->dbforge->add_key('control_ambiental_id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario_crea) REFERENCES usuario(nUsuCodigo)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('control_ambiental', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('control_ambiental');
    }
}