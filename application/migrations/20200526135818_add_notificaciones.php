<?php

class Migration_Add_notificaciones extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'fecha' => array(
                    'type' => 'DATETIME',
                    'null' => false,
                ),
                'topic' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                    'null' => false,
                ),
                'aplicacion' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                    'null' => false,
                ),
                'titulo' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                    'null' => false,
                ),
                'mensaje' => array(
                    'type' => 'TEXT',
                    'null' => true,
                ),
            )
        );

        $this->dbforge->add_key('id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('notificaciones', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('notificaciones');
    }
}