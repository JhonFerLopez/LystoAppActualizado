<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   ImpuestoElo extends Eloquent {

    protected $table = "impuestos"; // table name
    protected $fillable = [ 'id_impuesto','nombre_impuesto','porcentaje_impuesto',
        'estatus_impuesto','tipo_calculo','fe_impuesto'];
    public $timestamps = false;
    protected $primaryKey = 'id_impuesto';


    public function product()
    {
        return $this->hasOne(ProductoElo::class,'producto_impuesto');
    }
}