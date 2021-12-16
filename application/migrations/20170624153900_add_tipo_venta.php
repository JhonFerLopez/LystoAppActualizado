<?php

class Migration_Add_tipo_venta extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'tipo_venta_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'tipo_venta_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),

                'solicita_cod_vendedor' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'genera_datos_cartera' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'admite_datos_cliente' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'datos_adic_clientes' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'genera_control_domicilios' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'maneja_formas_pago' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'liquida_iva' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'maneja_descuentos' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'opciones_call_center' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),

                'aproximar_precio' => array(
                    'type' => 'INT',
                    'null' => true,
                    'constraint' => 11,
                ),
                'numero_copias' => array(
                    'type' => 'INT',
                    'null' => true,
                    'constraint' => 11,
                ),
                'documento_generar' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 255,
                ),
                'condicion_pago' => array(
                    'type' => 'BIGINT',
                    'null' => true,
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),

            )
        );


        $this->dbforge->add_key('tipo_venta_id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (condicion_pago) REFERENCES condiciones_pago(id_condiciones)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('tipo_venta', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('tipo_venta');
    }
}