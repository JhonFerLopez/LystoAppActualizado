<?php

class Migration_Insert_into_opcion_27 extends CI_Migration
{
    public function up()
    {

        $reportes = OpcionElo::where('cOpcionNombre','reportes')->first();
        $reporteTransacciones= OpcionElo::create([
                'nOpcionClase' => $reportes->nOpcion,
                'cOpcionNombre' => 'rep_inv_transacciones',
                'cOpcionDescripcion' => 'Reporte de transacciones',
                'is_to_show_some_value' =>0
            ]);

    }

    public function down()
    {

    }
}