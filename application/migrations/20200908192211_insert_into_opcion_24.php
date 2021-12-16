<?php

class Migration_Insert_into_opcion_24 extends CI_Migration
{
    public function up()
    {

        $rep_clientes = OpcionElo::where('cOpcionNombre','rep_clientes')->first();

        if($rep_clientes==NULL){
            $rep_clientes= OpcionElo::create([
                'nOpcionClase' => 7,
                'cOpcionNombre' => 'rep_clientes',
                'cOpcionDescripcion' => 'Clientes',
            ]);
        }

        $compras_por_cliente = OpcionElo::where('cOpcionNombre','compras_por_cliente')->first();

        if($compras_por_cliente==NULL){
            $compras_por_cliente= OpcionElo::create([
                'nOpcionClase' => $rep_clientes->nOpcion,
                'cOpcionNombre' => 'compras_por_cliente',
                'cOpcionDescripcion' => 'Compras por Cliente',
            ]);
        }

        $unidades_por_cliente = OpcionElo::where('cOpcionNombre','unidades_por_cliente')->first();

        if($unidades_por_cliente==NULL){
            $unidades_por_cliente= OpcionElo::create([
                'nOpcionClase' => $rep_clientes->nOpcion,
                'cOpcionNombre' => 'unidades_por_cliente',
                'cOpcionDescripcion' => 'Unidades por Cliente',
            ]);
        }
    }

    public function down()
    {

    }
}