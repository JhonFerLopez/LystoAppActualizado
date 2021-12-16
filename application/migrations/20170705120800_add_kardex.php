<?php

class Migration_Add_kardex extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'nkardex_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true,

                ),
                'dkardexFecha' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),

                'ckardexReferencia' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 100,
                ),
                'cKardexProducto' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'nKardexCantidad' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '9,2',

                ),
                'nKardexPrecioUnitario' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '9,2',

                ),
                'nKardexPrecioTotal' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '9,2',

                ),

                'cKardexUsuario' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'cKardexUnidadMedida' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'cKardexAlmacen' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'cKardexTipo' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,

                ),
                'cKardexIdOperacion' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                ),

                'cKardexTipoDocumento' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,

                ),
                'cKardexNumeroDocumento' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,

                ),
                'stockUManterior' => array(
                    'type' => 'FLOAT',
                    'null' => true,


                ),
                'cKardexEstado' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,

                ),
                'stockUMactual' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'cKardexCliente' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'cKardexProveedor' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                ),
            )
        );

        $this->dbforge->add_key('nkardex_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('kardex', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('kardex');
    }
}