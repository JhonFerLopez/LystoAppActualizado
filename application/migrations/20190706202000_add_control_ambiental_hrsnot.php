<?php

class Migration_Add_control_ambiental_hrsnot extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_hrsnot' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ),
                'alias' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                ),
                'hora' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 2,
                    'null' => true,
                ),
                'minutos' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 2,
                    'null' => true,
                ),

            )
        );

        //esta tabla almacena los 4 tipos de datos que se guardan en control ambiental,
        //para notificar la hora en la que se va a mostrar el aviso

        $this->dbforge->add_key('id_hrsnot', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('control_ambiental_hrsnot', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('control_ambiental_hrsnot');
    }
}