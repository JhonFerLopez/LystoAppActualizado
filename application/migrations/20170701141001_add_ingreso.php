<?php

class Migration_Add_ingreso extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_ingreso' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'condicion_pago' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'fecha_registro' => array(
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ),


                'int_Proveedor_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'nUsuCodigo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'local_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'tipo_documento' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 45,
                    'null' => true,
                ),
                'documento_numero' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 45,
                    'null' => true,
                ),
                'fecha_emision' => array(
                    'type' => 'TIMESTAMP',
                    'default' => NULL,

                ),

                'ingreso_status' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 45,
                    'null' => true,
                ),
                'impuesto_ingreso' => array(
                    'type' => 'DOUBLE',
                    'null' => true,
                ),
                'sub_total_ingreso' => array(
                    'type' => 'DOUBLE',
                    'null' => true,
                ),
                'total_ingreso' => array(
                    'type' => 'DOUBLE',
                    'null' => true,
                ),
                'total_bonificado' => array(
                    'type' => 'DOUBLE',
                    'null' => true,
                ),
                'total_descuento' => array(
                    'type' => 'DOUBLE',
                    'null' => true,
                ),

            )
        );


        $this->dbforge->add_key('id_ingreso', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (condicion_pago) REFERENCES condiciones_pago(id_condiciones)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (int_Proveedor_id) REFERENCES proveedor(id_proveedor)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (nUsuCodigo) REFERENCES usuario(nUsuCodigo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (local_id) REFERENCES local(int_local_id)');


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ingreso', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('ingreso');
    }
}