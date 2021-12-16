<?php

class Migration_Add_control_ambiental_detalle extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_detalle' => array(
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
                'humedad_relat_am' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'temp_ambiental_am' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'humedad_relat_pm' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'temp_ambiental_pm' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                )

            )
        );


        $this->dbforge->add_key('id_detalle', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (control_id) REFERENCES control_ambiental(control_ambiental_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('control_ambiental_detalle', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('control_ambiental_detalle');
    }
}