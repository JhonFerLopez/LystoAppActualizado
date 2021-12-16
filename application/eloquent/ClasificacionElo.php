<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   ClasificacionElo extends Eloquent {

    protected $table = "clasificacion"; // table name
    protected $fillable = [ 'clasificacion_id','clasificacion_nombre','deleted_at'];
    public $timestamps = false;
    protected $primaryKey = 'clasificacion_id';

    public function product()
    {
        return $this->hasOne(ProductoElo::class,'producto_clasificacion');
    }
}