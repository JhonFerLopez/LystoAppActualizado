<?php

class Migration_Add_proveedor extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_proveedor' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'proveedor_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => false,
                ),
                'proveedor_identificacion' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => false,
                ),
                'proveedor_celular' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => false,
                ),
                'proveedor_direccion' => array(
                    'type' => 'TEXT',
                    'null' => false,
                ),
                'proveedor_email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'proveedor_telefono1' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => false,
                ),
                'proveedor_telefono1' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'proveedor_telefono2' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'proveedor_telefono2' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'proveedor_tipo' => array(
                    'type' => 'BIGINT',
                    'constraint' => '20',
                    'unsigned' => true,
                    'null' => true,
                ),
                'proveedor_regimen' => array(
                    'type' => 'BIGINT',
                    'constraint' => '20',
                    'unsigned' => true,
                    'null' => true,
                ),
                'proveedor_ciudad' => array(
                    'type' => 'BIGINT',
                    'constraint' => '20',
                    'unsigned' => true,
                    'null' => true,
                ),

                'proveedor_digito_verificacion' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 2,
                ),
                'longitud' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,
                ),
                'latitud' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,
                ),

                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),
            )
        );
       

        $this->dbforge->add_key('id_proveedor', TRUE);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (proveedor_tipo) REFERENCES tipo_proveedor(tipo_proveedor_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (proveedor_regimen) REFERENCES regimen(regimen_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (proveedor_ciudad) REFERENCES ciudades(ciudad_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('proveedor', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('proveedor');
    }
}