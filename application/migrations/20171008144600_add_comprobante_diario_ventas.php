<?php

class Migration_Add_comprobante_diario_ventas extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_reporte' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment'=>true

                ),
                'fecha_reporte' => array(
                    'type' => 'DATETIME',
                    'null' => false,
                ),
                'fecha_generado' => array(
                    'type' => 'DATETIME',
                    'null' => false,
                ),
                'usuario_genero_reporte' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),


            )
        );
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id_reporte', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario_genero_reporte) REFERENCES usuario(nUsuCodigo)');
        $this->dbforge->create_table('comprobante_diario_ventas', true, $attributes);

    }

    public function down()
    {
        $this->dbforge->drop_table('comprobante_diario_ventas');
    }
}