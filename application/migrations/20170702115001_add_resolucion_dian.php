<?php

class Migration_Add_resolucion_dian extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'resolucion_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true

                ),
                'resolucion_numero' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,

                ),
                'resolucion_prefijo' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,

                ),
                'resolucion_numero_inicial' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,

                ),

                'resolucion_numero_final' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,

                ),
                'resolucion_fech_aprobacion' => array(
                    'type' => 'DATE',
                    'null' => true,


                ),
                'resolucion_avisar' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,

                ),
                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),


            )
        );

        $this->dbforge->add_key('resolucion_id', TRUE);


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('resolucion_dian', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('resolucion_dian');
    }
}