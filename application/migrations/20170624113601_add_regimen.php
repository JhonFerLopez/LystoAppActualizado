<?php

class Migration_Add_regimen extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'regimen_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'regimen_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'compra_retienen' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'compra_retienen_iva' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'venta_retienen' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'venta_retienen_iva' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'genera_iva' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'autoretenedor' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'gran_contribuyente' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),

                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),


            )
        );


        $this->dbforge->add_key('regimen_id', TRUE);


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('regimen', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('regimen');
    }
}