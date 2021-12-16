<?php

class Migration_add_system_logs extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(
            array(
                'log_id' => array(
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
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ),
                'tabla' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ),
                'tipo' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ),
                'data_before' => array(
                    'type' => 'TEXT',
                    'null' => true,
                    'comment'=>'data antes de tocar ese registro'
                ),
                'data_after' => array(
                    'type' => 'TEXT',
                    'null' => true,
                    'comment'=>'data despues de tocar ese registro'
                ),


            )
        );


        $this->dbforge->add_key('log_id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario) REFERENCES usuario(nUsuCodigo)');



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('system_logs', true, $attributes);

    }

    public function down()
    {
        $this->dbforge->drop_table('system_logs');
    }
}