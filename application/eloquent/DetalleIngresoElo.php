<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class DetalleIngresoElo extends Eloquent {

    protected $table = "detalleingreso"; // table name
    protected $fillable = [
        'id_detalle_ingreso',
        'id_ingreso',
        'id_producto',
        'status',
        'total_detalle',
        'porcentaje_descuento',
        'total_con_descuento',
        'impuesto_porcentaje',
        'total_impuesto',
        'porcentaje_bonificacion'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id_detalle_ingreso';

    public function ingreso()
    {
        return $this->belongsTo(IngresoElo::class,'id_ingreso','id_ingreso');
    }

    /**
     * Método que deine la relacion de uno a muchos
     */
    public function detalleingresounidad()
    {
        return $this->hasMany(DetalleIngresoUnidadElo::class,'detalle_ingreso_id','id_detalle_ingreso');
    }


    public function producto()
    {
        return $this->belongsTo(ProductoElo::class,'id_producto','producto_id');
    }



}