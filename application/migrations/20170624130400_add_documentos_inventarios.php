<?php

class Migration_Add_documentos_inventarios extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'documento_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'documento_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'documento_tipo' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => false,
                ),

                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => false,
                ),

            )
        );


        $this->dbforge->add_key('documento_id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('documentos_inventarios', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('documentos_inventarios');
    }
}