<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   UbicacionFisicaElo extends Eloquent {

    protected $table = "ubicacion_fisica"; // table name
    protected $fillable = [ 'ubicacion_id','ubicacion_nombre','deleted_at'];
    public $timestamps = false;
    protected $primaryKey = 'ubicacion_id';

    public function product()
    {
        return $this->hasOne(ProductoElo::class,'producto_ubicacion_fisica');
    }

}