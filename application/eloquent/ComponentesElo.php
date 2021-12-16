<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   ComponentesElo extends Eloquent {

    protected $table = "componentes"; // table name
    protected $fillable = [ 'componente_id','componente_nombre','deleted_at'];
    public $timestamps = false;
    protected $primaryKey = 'componente_id';

    public function productos()
    {
        return $this->belongsToMany(ProductoElo::class,'producto_has_componente',
            'componente_id','producto_id');
    }


}