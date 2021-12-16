<?php

class Migration_Add_estados extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'estados_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'estados_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ),
                'pais_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

            )
        );

        $this->dbforge->add_key('estados_id', TRUE);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (pais_id) REFERENCES pais(id_pais)');



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('estados', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('estados');
    }
}