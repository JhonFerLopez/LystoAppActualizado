<?php

class Migration_Add_tipo_anulacion extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'tipo_anulacion_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'tipo_anulacion_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),


            )
        );
       

        $this->dbforge->add_key('tipo_anulacion_id', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('tipo_anulacion', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('tipo_anulacion');
    }
}