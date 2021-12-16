<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   TipoProductoElo extends Eloquent {

    protected $table = "tipo_producto"; // table name
    protected $fillable = [ 'tipo_prod_id','tipo_prod_nombre','deleted_at'];
    public $timestamps = false;
    protected $primaryKey = 'tipo_prod_id';

    public function product()
    {
        return $this->hasOne(ProductoElo::class,'producto_tipo');
    }
}