<?php

class Migration_Insert_into_opcion_25 extends CI_Migration
{
    public function up()
    {
        $historialventas = OpcionElo::where('cOpcionNombre','historialventas')->first();
        $historialventas_vertotalesventas = OpcionElo::where('cOpcionNombre','historialventas_vertotalesventas')->first();

        if($historialventas_vertotalesventas==NULL){
            $historialventas_vertotalesventas= OpcionElo::create([
                'nOpcionClase' => $historialventas->nOpcion,
                'cOpcionNombre' => 'historialventas_vertotalesventas',
                'cOpcionDescripcion' => 'Ver Totales de Ventas',
                'is_to_show_some_value' =>1
            ]);
        }


        $rep_part_ventas_vendedor = OpcionElo::where('cOpcionNombre','rep_part_ventas_vendedor')->first();
        $rep_part_ventas_vendedor_vertotalesventas = OpcionElo::where('cOpcionNombre','rep_part_ventas_vendedor_vertotalesventas')->first();

        if($rep_part_ventas_vendedor_vertotalesventas==NULL){
            $rep_part_ventas_vendedor_vertotalesventas= OpcionElo::create([
                'nOpcionClase' => $rep_part_ventas_vendedor->nOpcion,
                'cOpcionNombre' => 'rep_part_ventas_vendedor_vertotalesventas',
                'cOpcionDescripcion' => 'Ver Totales de Ventas',
                'is_to_show_some_value' =>1
            ]);
        }



        $rep_comparar_vendedores = OpcionElo::where('cOpcionNombre','rep_comparar_vendedores')->first();
        $rep_comparar_vendedores_vertotalesventas = OpcionElo::where('cOpcionNombre','rep_comparar_vendedores_vertotalesventas')->first();

        if($rep_comparar_vendedores_vertotalesventas==NULL){
            $rep_comparar_vendedores_vertotalesventas= OpcionElo::create([
                'nOpcionClase' => $rep_comparar_vendedores->nOpcion,
                'cOpcionNombre' => 'rep_comparar_vendedores_vertotalesventas',
                'cOpcionDescripcion' => 'Ver Totales de Ventas',
                'is_to_show_some_value' =>1
            ]);
        }

    }

    public function down()
    {

    }
}