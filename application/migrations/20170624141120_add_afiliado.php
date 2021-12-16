<?php

class Migration_Add_afiliado extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'afiliado_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'afiliado_codigo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true
                ),
                'afiliado_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => false,
                ),
                'afiliado_monto_cartera' => array(
                    'type' => 'FLOAT',
                    'null' => false,
                ),
                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),
                'lista_precios' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => true,
                ),

            )
        );


        $this->dbforge->add_key('afiliado_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('afiliado', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('afiliado');
    }
}