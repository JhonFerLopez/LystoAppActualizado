<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class DetalleIngresoUnidadElo extends Eloquent {

    protected $table = "detalle_ingreso_unidad"; // table name
    protected $fillable = [
        'detalle_ingreso_unidad_id',
        'unidad_id',
        'detalle_ingreso_id',
        'costo',
        'costo_con_descuento',
        'cantidad',
        'impuesto',
        'costo_total',
        'total_final',
        'actualizado',
        'ingreso_id',
        'costoestaunidad_antes',
        'costo_unitario_antes',
        'costo_unitario_despues'

    ];
    public $timestamps = false;
    protected $primaryKey = 'detalle_ingreso_unidad_id';


    public function detalleingreso()
    {
        return $this->belongsTo(DetalleIngresoElo::class,'detalle_ingreso_id','id_detalle_ingreso');
    }

    public function unidad()
    {
        return $this->belongsTo(UnidadesElo::class,'unidad_id','id_unidad');
    }
}