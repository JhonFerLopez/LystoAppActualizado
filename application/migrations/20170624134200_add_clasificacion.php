<?php

class Migration_Add_clasificacion extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'clasificacion_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'clasificacion_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ),

                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),


            )
        );
       

        $this->dbforge->add_key('clasificacion_id', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('clasificacion', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('clasificacion');
    }
}