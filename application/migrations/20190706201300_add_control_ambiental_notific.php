<?php

class Migration_Add_control_ambiental_notific extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_notific' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'control_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'dia' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 2,
                    'null' => true,
                ),
                'item' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                ),
                'fecha' => array(
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ),
                'usuario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                )

            )
        );


        $this->dbforge->add_key('id_notific', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (control_id) REFERENCES control_ambiental(control_ambiental_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario) REFERENCES usuario(nUsuCodigo)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('control_ambiental_notific', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('control_ambiental_notific');
    }
}