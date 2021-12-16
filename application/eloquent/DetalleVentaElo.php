<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   DetalleVentaElo extends Eloquent {

    protected $table = "detalle_venta"; // table name
    protected $fillable = [
        'id_detalle',
        'id_venta',
        'id_producto',
        'impuesto',
        'descuento',
        'subtotal',
        'porcentaje_impuesto',
        'total',
        'desc_porcentaje',
        'otro_impuesto',
        'porcentaje_otro_impuesto',
    ];
    public $timestamps = false;
    protected $primaryKey = 'id_detalle';



    public function venta()
    {
        return $this->belongsTo(VentaElo::class,'id_venta','venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(ProductoElo::class,'id_producto');
    }

    public function detalle_venta_unidad()
    {
        return $this->hasMany(DetalleVentaUnidadElo::class,'detalle_venta_id','id_detalle');
    }

}