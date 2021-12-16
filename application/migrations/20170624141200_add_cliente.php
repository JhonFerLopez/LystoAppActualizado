<?php

class Migration_Add_cliente extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_cliente' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'ciudad_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => true,
                    'unsigned' => true,
                ),
                'direccion' => array(
                    'type' => 'TEXT',
                    'null' => true,
                ),
                'email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ),
                'grupo_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => false,
                    'unsigned' => true,
                ),

                'apellidos' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ),

                'nombres' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ),

                'identificacion' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 45,
                ),
                'telefono' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 45,
                ),
                'celular' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 45,
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
                'cliente_status' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'id_zona' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'digito_verificacion' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                ),
                'sexo' => array(
                    'type' => 'CHAR',
                    'null' => true,
                    'constraint' => 1,
                ),
                'fnac' => array(
                    'type' => 'DATE',
                    'null' => true,
                ),
                'fecha_nacimiento' => array(
                    'type' => 'DATE',
                    'null' => true,
                ),
                'facturacion_maximo' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'valida_fact_maximo' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'valida_venta_credito' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'dias_credito' => array(
                    'type' => 'INT',
                    'null' => true,
                    'constraint' => 11,
                ),
                'codigo_interno' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,
                ),
                'afiliado' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                    'null'=>true
                ),
            )
        );


        $this->dbforge->add_key('id_cliente', TRUE);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (ciudad_id) REFERENCES ciudades(ciudad_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_zona) REFERENCES zonas(zona_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (grupo_id) REFERENCES grupos_cliente(id_grupos_cliente)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (afiliado) REFERENCES afiliado(afiliado_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('cliente', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('cliente');
    }
}