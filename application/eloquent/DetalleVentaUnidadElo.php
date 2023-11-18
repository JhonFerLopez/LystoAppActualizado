<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   DetalleVentaUnidadElo extends Eloquent {

    protected $table = "detalle_venta_unidad"; // table name
    protected $fillable = [
        'detalle_venta_unidad_id',
        'detalle_venta_id',
        'unidad_id',
        'precio',
        'precio_sin_iva',
        'cantidad',
        'utilidad',
        'costo',
        'costo_promedio'
    ];
    public $timestamps = false;
    protected $primaryKey = 'detalle_venta_unidad_id';

    public function detalle_venta()
    {
        return $this->belongsTo(DetalleVentaElo::class,'detalle_venta_id','id_detalle');
    }



}