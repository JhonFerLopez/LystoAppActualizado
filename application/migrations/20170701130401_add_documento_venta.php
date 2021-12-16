<?php

class Migration_Add_documento_venta extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_tipo_documento' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_tipo_documento' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'documento_Numero' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => false,
                ),

                'id_venta' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

            )
        );


        $this->dbforge->add_key('id_tipo_documento', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_venta) REFERENCES venta(venta_id)');



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('documento_venta', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('documento_venta');
    }
}