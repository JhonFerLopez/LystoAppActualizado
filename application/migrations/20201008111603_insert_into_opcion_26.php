<?php

class Migration_Insert_into_opcion_26 extends CI_Migration
{
    public function up()
    {
        $rep_comparar_vendedores = OpcionElo::where('cOpcionNombre','rep_comparar_vendedores')->first();
        $rep_comp_vend_mostrar_filtro_total_vendido = OpcionElo::where('cOpcionNombre','rep_comp_vend_mostrar_filtro_total_vendido')->first();

        if($rep_comp_vend_mostrar_filtro_total_vendido==NULL){
            $rep_comp_vend_mostrar_filtro_total_vendido= OpcionElo::create([
                'nOpcionClase' => $rep_comparar_vendedores->nOpcion,
                'cOpcionNombre' => 'rep_comp_vend_mostrar_filtro_total_vendido',
                'cOpcionDescripcion' => 'Mostrar Filtro Total Vendido',
                'is_to_show_some_value' =>1
            ]);
        }
    }

    public function down()
    {

    }
}